<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Cookie\CookieJar;

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
        // $cmd = $this->argument('cmd');
        // if($cmd == 'scan'){
            // $csvexport = new \App\Exports\CSVUploadFacebookExport();
            // $links = explode("\n", \Storage::disk('local')->get( $this->teezily_list_file ));

            // $this->line("Total links: ".count($links));
            // if(!$this->option('slow')){
            //     $this->line('Delay: '.$this->sleep_time.' seconds');
            // }else{
            //     $this->line('Delay: random');                
            // }
            // $i = 1;
            // foreach ($links as $key => $value) {
            //     # code...
            //     if($value){
            //         $data = $this->perform_scan($value, $i);
            //         $csvexport->push($data);
            //         if($this->option('slow')){
            //             sleep(rand($this->sleep_time, $this->sleep_time+3));
            //         }else{
            //             sleep($this->sleep_time);
            //         }
            //         $i++;
            //     }
            // }

            // // if($this->option('filter')) return;

            // $this->line('Export csv file:'. storage_path().'/'.$this->time.'/upload_page.csv');
            // \Excel::store($csvexport, $this->time.'/upload_page.csv', 'local');

            $this->check_affiliate('https%3A%2F%2Fwww.teezily.com%2Fde-de%2Fmkt%2Fmein-herz-wurde-arbeitsvermittlerg%3Fprop%255Bcolor%255D%3Dpink_azalea%26product%3D7%26side%3Dfront%26tag%3D4VYii5TY');

        // }else{
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

    public function check_affiliate($link='')
    {
        # code...
        $cookieJar = CookieJar::fromArray([
            '_teezr_session' => 'MFluZCtDTDBWdHIwNzcvTTBKWlg2d0E3SFJGS2lKTnE1bHQxRVBqSitCWTd6aWZqcm96L2U3N2M0OVp2SGVuK1JLS3luMXplUFVTUXpXK25WdUo5N2tRNkJ1T0pON0xQVTZNMGV5bzFKbzZ1MjVvOGZSMlJTZFdFUStBVEpaUkpiaklBcDhkTWZIa2Z2TGg4TnM2TFBIbmxhQkFmYlBvbGo1eVRtcUZhellIRFQxVVJvYUs4K1JrWVFMUDBCem53OUVVT0hERGRLQ0t6TjRIdUE1aHhMQzl0YzRpYjhGTzJ6d2ZBcDcrOGhmL01xWTU0d0V3VnBmZjU2cFRlTGR1RTRWVVNRaDlIeFFsbnI4VFpMQUIwK0l2aHJVcVROQ0RrVk53RThYeXh2TjBzcjloaFkyWG4zdXovWXdINWtKWUhHeS9zZGdXNWZQNmtwMmQ2cFVOaENpMnlwemFkaEpEck0rN3dweG5BTC91ejFVZUJKU3BHa0RvR1ljU1l3b2wxUmdJZ1MwQWlzWTBrZ0ZYWC9TV2FOQWVxVTdqTmdqckpNL1ZlRG45QXRwS3BJbkdyRjM1WHZ0ZTNmeWRSekwyWUJCWGZyRE5aMk5QRmN5UnozWUdwR3R0UVlBS0NsbjBaVGUxTXdwSzRaTlFnbDFMR3h3L3VDcHFqc3ZEb0ZjREJxVlNKRXFEYlE2MVlDUHpra2xaUCtOQTlnOEtwTWVNTmlITlFzUHdkMXlmWmdnYW9QSjBBT012TWR4aXFQODNRY2tiTjBEWElwYjhSc0h0dERpOVIzNE9pSVdaeEFXRXlaMktIem5Xc3RoN29Yc1VJS1pMdXNEOWJoWTBYNjQ0eVpEUFEyaVk5RU1KTlpMZ0R3TGFwdk5ucEtYcVU2VTNXVzRFWFBWVERLb29DckwvVkhwa1BVSDM1TndNaWZ1TGx5VVFYUEVVMEtaYklOL0pmZTgxeGdjZk0xRVBuRXltL0xCc2lXMTFmN1AwYW1IZGh1TmZBTzBOd01ySklZUGxkUXNydjd4bXQ4RTNDaml0dkxYMVVWTnlkNWdxdTd1aTRsZ2pnL2xNN0FSSGgrbVNJS3YyYVhVYVRiSVQxa05IdCtaaFV3RCtqb1ZROExwSjlwVkJZR1I2NzdncE5VVkFJeVVWa29mc3hhbFBKREVNNFQyUjhCeWZ1MlBmMDNSTXg3WmNsVHBUcUxRWkFtVG83SjhMMjdRQ2hFUnZERDBwTUgxdmo5L0V4L1RSTmdmMitOUjZHSHZhcVY2VzZCcVg0RmlJb2pRRVpvcjZSelgzN0VsM0ZSQ1lpZmFxcDRHK3pEd3pEeEhnSWU4L1NnV21PNC93WDEyVnA1VHRISm5QSFkwQiswUlN1YWZYanQ4blhCc3hucmc9PS0td00yTGhHVllmTDd2dUI0akozQnNzUT09--f3abc25d619a97572e285696a162eb12caf0fa7e'
        ], 'teezily.com');

        $url = 'https://www.teezily.com/api/1/my/sale_affiliation/check_affiliate_link?url='.urlencode($link);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, ['Cookie' => '_teezr_session=MDNMRXdNTWc3WDlnZmd4bkpXMlE1R2JSQnFQclZzNFZhZ1JKaDNxS0VDeWRaRVI3YUZGbG1wUkcxVFI1Z3U5RGFOQStKZnZheDIvcE53ZFQrZGkvNkQ4WmVTRUlVdkJhOW9GSG5mMEtHMTgwelVNUTZWWE5Fd0xidzNZb3B1eWZ4ZHpqczZ3amlzWHpVV2FtQ0hjWHM4ZmZ2QzhzdUFYWFcycVJaNnhqcmNxekJYVHpRYUpmTXF1cnJ2MjE0WnN0VDhqTVlVblNVVnhLT3UzUTk1RUdCMEw2cGRIeWJQc25HR3dDRlFlSHZjamhEVGZ2dTd1MTIwc2pnNzl2Qzl4d0VaM1FIUk1ySE9sZG9FT08zV1c5b3BDMnZSTlR1dVRaQ3gyaGpYQXZhWkxrMjZ3VkhRMlEva29CQ3h0MkdXa3I5TFZiNm9BOU0xWUUvYS9CSkVUVXF2QzNzYm9MSTJ3emtBd0E0TlhuMEFSZFprcTRLcVJCenoxTHU4dVNDS1h5eklSRlptRVlmQkQxQTNYcVhzdHJncy9ZSytPK2tpakI3dGlZN1VKRnNWZ2FTR05vWWVnR0xreWo3TlFaSEZJUlFtTmNLS0Fnb2pJUm5tOFdjbVlHWHIrVTFMY1RyRGNGVk5LUmNpVkgyVFI3Q2NyQW00WkF4djlIKzhFODJQclUybS81RFdJUGFWWEZCbWdlNVhTMkVLV0t6WkQ5L1Y0RU9UZDB5QmJlOEdXam1DTG1yeTF2K2lvQndkV2hScCtpSkR5NDZENmFlTkw0dVBRSXVGSkdKdkYyQUY5bityMGphVnJIRFV4dTNhOW9FS255amorcWt2Q0pkODNJWHpmQUNYdDBkbmU4K1hKSlVGV1hkQ25FZDJNWXlPYmhsb1EwTFhtVE5sTHlHeCt0NW9nNnJWRFRGQXMxdnlMeERiN3BOL3Q0aHUyUUZQNVBqaytxaEl2Tzg5aXZtN01MQitEL2FkTmZoQVdBdDhCcVhqMVh6a1NobllhQjlMMi8wdnZCYXE3QUJzT2NvL0swZElZeHlyRWovaGt2UFNtcHlsenlnV1FUVGYzVXc5L0VObnE2ZXl1NkF1SUJzNWd0S3JSdnFxR0VlaTAzNTdib2h5d05oTjU2dDJKS01hOWV0VGFQU3hXY28vcHFjTEtWNXhEbkZzYmRjOWwxL1lIdlAxaTQ2eDFROFl6VU4rQVpzQVZnQ0tyT0JrbG5MdWY1UUExMnI3d2FKQ01aZ3ZDVnNMTGY4anBRM0JwVzhZTHh0bzA2RUxmRjNneXE5QkVvMGJqYTEwWExiVGJuS1dvMzRERHo1Smk2NEk5MG5PSkh4UzByWE82OTFwM0UzcGZJVXZkZDdMY08yaWhZZFlOSVNsek5Ra3M2L3ZMdXZXR0lwY2swSHVManJreGEvK0k9LS1qRGxYVWRVSTZZWDBnU2c5QXlTbjZnPT0%3D--41f8069e48b8d8d713de5b9d941e55ebb4754455; ; _teezr_session=NTlIam8rVy8vaUg2dGV2NDBzRHFtSmJqV2NUeEErY1B4c2J3ai9nRWdxeE1kL1BjU1dUMWIwaFRWTitIb3Y5YUV5bGZ0SFEybGplVlBzNFdEcW44MXZSWDgxL25EZS9XYy9lR0RBZ3pnRXI0NVFsT3ZYRlkwT01tKzU5TFg0UEtQQnhTaXBFMEtIQ0tUTlUrdXVsaXh1SVlzN05CZitZM2tQZnRIMFNncjF2WTVSVjllY3ljTVd6bUZDY0Z2SkEwa3lBek5IRC9WSG9NT3pZQ2EyRHRJWDRWeWIwSXRrQktROEpoaDk5UzZlajhOd044VVhaQ0FqdEYrd1A1ZVY1bTl2UUJuMmN2YXBIc1FGdWE4K1hkNmgvd2pCWkpZNHFJWjJYMU9BRFhVem40L3RtY1Y5ZUxqekUxUm85Nk4vV25Fcjd0Q1V4VFRNc1RhbG5GOEhpV285eFVkUEVZQUR6bnU0S3JRV1llQXg4UUwxbmZGY2FIb3Z4TkhtU3k3Ni9kbWVjVGxOSXE0ZzVOK1NzTUU0RGZVZ1NqUFM5MVkxZFd4T3BNZFVEbVduUFRMb1BseDU0dWVzakdOZENlbGV5UHBDWFphdlFKdU9lMlJ1NEthUFREYnJ1VVk4WmdaOEI3Zm5tQXlhQmdxalFFazVkYitOcDEyL0VRemRORHFzZ3lkZE91dVpUbDZKOTBUSmZrN0s2ck53N2lnL0lEK1BneFVTMTVlNk56cW9BdXBiak1nTmpMZkFDN0Zib1dUWE03eUhacU95Nm5GaVJNMi9UcFVpYWxSeU52bGpqQW1MT1Y4MVNoemtXNDZwQi9rUUdxZ0RLUE5PNEFLRjlSbjUzbTIwcFF1RUc4WmhEMlJ0V2w1dHZDbm5leDZHMEtyakwyNnZzQ1U4MjVuWEhoL2RKMmlEMFVIRFB4YWY2NmtDOVl1VnNBbFd4QklDYURFYkRDaFd0ZzU4Q0FnazVuRlpXbkVwRkYzVGc2bTJUakgwbDBDRm0zazVZWGFwcmhIVHhIWVhmb1JLWFliSWo4R0tTam5NT0FCQ3lON2FQbkVaRTZwdy95dzNYYXhHN2hKdXdHSWxJNjlFcWs5eWtrQ0N4aVhKSzhlYTN3endWMk16OGpjb1RuNkN5TXBHeHJwQi8wZ3VMQnZoOHFwa3BqRU9QMktvYjVpSDgyYWlnWW0yL2ljcmU4MXNaU09aRTV2Y3RoanJCWE9mdExtZ0ZPVFpDTU43UTZJN05aMXVyT3JMY2dGSEI0ajFxdE50ZElYTEQ3enV3NUI4N1MyRC9aeEZSOUNIN2x1dGNIZk44UEVDU0R2V3BadmhQK1FBRnJxS0RBeHJrRC9hK09LbFIwQTdjS0JtdEtYK1VjL1M5dXg2TGF1Y1hpdWowRlBzTXE4K01JbnVyOXhNL1JNeWs9LS1nZm9obkJTbnllYnlFcmd0ZnBzR1NBPT0%3D--ea40b5f353dccdbc44b59a70972841a6a35733fb; v2_serializers=false','Cache-Control'=>'no-cache']);
        $response = $response->getBody()->getContents();
        echo $response;
    }
}
