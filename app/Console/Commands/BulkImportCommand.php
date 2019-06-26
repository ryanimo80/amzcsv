<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\KeywordModel;
use App\ProfileModel;
use App\CSVDataModel;
use Validator;
use App\Rules\ValidBannedKeyword;

class BulkImportCommand extends Command
{
    protected $delay_time = 1;//1 seconds
    protected $default_profile = 15; // black mug
    protected $default_keyword = 19; // mug keyword
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bulk {--profile=PROFILE_ID} {--keyword=KEYWORD_ID} {--mpath=} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk Import Folder PNG to csvdata table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature = str_replace('PROFILE', $this->default_profile, $this->signature);
        $this->signature = str_replace('KEYWORD', $this->default_keyword, $this->signature);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $mpath = $this->option('mpath');

// print_r( config('database.connections.mysql'));

        $path = $this->argument('path');
        $path = str_replace("\\", "/", trim($path));
        $path = str_replace("\"", "", $path);
        // die($path);

        $filelist = array();
        if ($handle = opendir($path)) {
            while ($entry = readdir($handle)) {
                if (strpos(strtolower($entry), ".png") > 0) {
                    $filelist[] = $path."/".$entry;
                }
            }
            closedir($handle);
        }

        foreach ($filelist as $key => $value) {
            # code...
            // echo $value."\n";
            $csvdata = $this->import_to_csv($value);
            if($csvdata->id>0){
                echo $this->info("Imported ".basename($value)." successful! CSV ID: ". $csvdata->id);
                $this->imported_marker($value);
                sleep($this->delay_time);
           }else{
                dd("Error: $value");             
            }
        }
        rename($path, dirname($path).'/T'.$mpath);
 }

    protected function imported_marker($value='')
    {
        # code...
        $newname = dirname($value)."/".str_replace('.png', '.p_n_g', basename($value));
        rename($value, $newname);
    }

    protected function import_to_csv($filepng)
    {
        # code...
        // print_r(explode('_', str_replace('.png', '', basename($filepng))));die();
        list($design_id, $title, $keyword_0, $keyword_1, $keyword_2) = explode('_', str_replace('.png', '', basename($filepng)));

        $profile_id = $this->option('profile');
        $keyword_id = $this->option('keyword');
        $mpath = $this->option('mpath');
        $profile = ProfileModel::find($profile_id);
        $keyword = KeywordModel::find($keyword_id);

        $csvdata = new CSVDataModel();
        $csvdata->design_id = $design_id;
        $csvdata->brand_name = brand_name();
        $csvdata->profile_id = $profile->id;
        $csvdata->design_month = $mpath;
        $csvdata->item_sku = gen_item_sku(date('y'), $csvdata->design_month, $design_id);
        $csvdata->item_name = replace_keyword($title, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->bulletpoint_1 = replace_keyword($keyword->bulletpoint_1, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->bulletpoint_2 = replace_keyword($keyword->bulletpoint_2, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->bulletpoint_3 = replace_keyword($keyword->bulletpoint_3, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->bulletpoint_4 = replace_keyword($keyword->bulletpoint_4, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->bulletpoint_5 = replace_keyword($keyword->bulletpoint_5, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->searchterm_1 = replace_keyword($keyword->searchterm_1, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->searchterm_2 = replace_keyword($keyword->searchterm_2, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->searchterm_3 = replace_keyword($keyword->searchterm_3, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->searchterm_4 = replace_keyword($keyword->searchterm_4, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->searchterm_5 = replace_keyword($keyword->searchterm_5, $keyword_0, $keyword_1, $keyword_2);
        $csvdata->description = replace_keyword($keyword->description, $keyword_0, $keyword_1, $keyword_2);
        
        $path = storage_png_path().'/files/'.time();
        mkdir($path);
        copy($filepng, $path.'/'.basename($filepng));
        $csvdata->filepng = $path.'/'.basename($filepng);

        $validator = Validator::make($csvdata->toArray(), [
            'item_name' => [new ValidBannedKeyword],
            'bulletpoint_1' => [new ValidBannedKeyword],
            'bulletpoint_2' => [new ValidBannedKeyword],
            'bulletpoint_3' => [new ValidBannedKeyword],
            'bulletpoint_4' => [new ValidBannedKeyword],
            'bulletpoint_5' => [new ValidBannedKeyword],
            'searchterm_1' => [new ValidBannedKeyword],
            'searchterm_2' => [new ValidBannedKeyword],
            'searchterm_3' => [new ValidBannedKeyword],
            'searchterm_4' => [new ValidBannedKeyword],
            'searchterm_5' => [new ValidBannedKeyword],
            'description' => [new ValidBannedKeyword],
        ]);
        $errors = $validator->errors()->all();
        foreach ($errors as $message) {
            dd($filepng." -> ".$message);   
        }

        $mockup = generate_png_mockup($filepng, $profile, $title);
        $csvdata->mockup = json_encode($mockup);

        $csvdata->save();//save to database        
        return $csvdata;
    }
}
