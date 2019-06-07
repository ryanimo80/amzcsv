<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Teezily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teezily:scan {--filter} {--slow}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teezily Scan T-Shirt Affiliate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->time = time();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    protected $teezily_list_file = 'teezily_list_file.txt';
    protected $sleep_time = 1;
    protected $teezily_domain = 'https://www.teezily.com/';
    protected $time = '';
    protected $teezily_aff_tag = '?tag=4VYii5TY';

    public function handle()
    {
        //
        // $cmd = $this->argument('tecmd');
        // if($cmd == 'scan'){
            $csvexport = new \App\Exports\CSVUploadFacebookExport();
            $links = explode("\n", \Storage::disk('local')->get( $this->teezily_list_file ));

            $this->line("Total links: ".count($links));
            if(!$this->option('slow')){
                $this->line('Delay: '.$this->sleep_time.' seconds');
            }else{
                $this->line('Delay: random');                
            }
            $i = 1;
            foreach ($links as $key => $value) {
                # code...
                if($value){
                    $data = $this->perform_scan($value, $i);
                    $csvexport->push($data);
                    if($this->option('slow')){
                        sleep(rand($this->sleep_time, $this->sleep_time+3));
                    }else{
                        sleep($this->sleep_time);
                    }
                    $i++;
                }
            }

            // if($this->option('filter')) return;

            $this->line('Export csv file:'. storage_path().'/'.$this->time.'/upload_page.csv');
            \Excel::store($csvexport, $this->time.'/upload_page.csv', 'local');
        // }
    }

    public function perform_scan($value, $i)
    {
        # code...
        try{
            $value = preg_replace('/product=(.*?)&prop/is', 'product=1&prop', $value);
            $link = $this->teezily_domain.'/'.$value;
            $scan_result = teezily_scan($link);
            $message = $i.($i<10?'.':'').': '.
                substr($value, 0, strpos($value, '?')>0?strpos($value, '?'):strlen($value))."\t\t\t".
                ($scan_result['is_customized']?'[Customized]':'-')."\t\t\t".
                ($scan_result['is_showoff']?'[Sold '.$scan_result['is_showoff'].']':'-')."\t\t\t".
                $scan_result['price'];
            if($scan_result['is_customized']){
                $this->comment($message);
            }else if($scan_result['is_showoff']){
                $this->info($message);
            }else if($scan_result['price']>20){
                $this->line($message);        
            }else{
                if(!$this->option('filter')){
                    $this->line($message);
                    return;
                }
            }

            $filename = $this->download_resize_photo($scan_result['photo']);
            // $filename = $scan_result['photo'];

            if(!$this->option('filter'))
                $short_link = $this->short_link($link);
            return array($filename, $short_link);
        }catch(\GuzzleHttp\Exception\ClientException $ex){
            //$this->error($ex->getMessage());
            return false;
        }catch(\ErrorException $ex){
            //$this->error($ex->getMessage());
            return false;
        }catch(\Exception $ex){ 
            //$this->error($ex->getMessage());
            return false;
        }
    }

    public function download_resize_photo($value='')
    {
        # code...
        $url = substr($value, 0, strpos($value, '?'));
        $contents = file_get_contents($url);
        $ret = substr($url, strrpos($url, '/') + 1);
        $name = $this->time."/".$ret;
        \Storage::disk('local')->put($name, $contents);

        $photo = \Image::make(storage_path().'/app/'.$name)->resize(1080, 1215);
        $photo->save( storage_path().'/app/'.$name );
        return $ret;
    }

    public function short_link($link)
    {
        $link = substr($link, 0, strpos($link, '?'));
        $link .= $this->teezily_aff_tag;
        $bitly_api = "http://api.bit.ly/shorten?longUrl=$link&login=oceanteam&apiKey=R_026376852695476681d145cb25c0ed44&format=txt";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $bitly_api, ['verify' => false]);
        $response = $response->getBody()->getContents();
        return $response;
    }
}
