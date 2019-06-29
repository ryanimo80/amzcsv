<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\CSVDataModel;

class CSVTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/amz/listing/');
        $response->assertViewHas('title');
    }

    /**
     * @test
     */
    public function view_list($value='')
    {
        # code...
        $csv = CSVDataModel::get()->random();
        $response = $this->get(route('listing', ['id' => $csv->id]));
        $response->assertSuccessful();
// print_r($response->original->csvdata);
// echo $csv->id;
        $returnedCSV = $response->original->csvdata;
        $this->assertEquals($csv->id, $returnedCSV->id, "The returned CSV is different from the one we requested");
    }
}
