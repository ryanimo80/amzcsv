<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomcatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = array(
            "shipping_first_name"=> "Joe",
            "shipping_last_name"=> "Testing",
            "shipping_address1"=> "1300 Rosa Parks Blvd ",
            "shipping_address2"=> "",
            "shipping_city"=> "Detroit",
            "shipping_state"=> "MI",
            "shipping_zip"=> "48216",
            "shipping_country"=> "US",
            "shipping_email"=> "no-email@customcat.com",
            "shipping_phone"=> "555-555-5555",
            "shipping_method"=> "Economy",
            "items"=> array(
                array(
                    "catalog_sku"=> "39515",
                    "design_url"=> "https=>//myimage-url.com/front_design_order.png",
                    "design_url_back"=> "https=>//myimage-url.com/back_design_order.png",
                    "quantity"=> 1
                ),
                array(
                    "catalog_sku"=> "48301",
                    "design_url"=> "https=>//myimage-url.com/front_design_order.png",
                    "quantity"=> 3
                )
            ),
            "sandbox"=> "1",
            "api_key"=> '24A7DCD3-9225-2E21-73CA9E0FBEBE8542'
        );
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://customcat-beta.mylocker.net/order/', [
            'form_params' => $data
        ]);
        $response = $response->getBody()->getContents();
        echo $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
