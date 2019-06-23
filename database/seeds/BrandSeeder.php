<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('brandmanager')->insert([
            'brand_name' => Str::random('10'),
        ]);        
    }
}
