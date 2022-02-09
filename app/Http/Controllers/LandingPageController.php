<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    private static $client = null;

    public function __construct()
    {
        $this->publik_api_host = env('PORTAL_PUBLIK_HOST_API');
        $this->publik_sso = env('PORTAL_PUBLIK_KODE_SSO');
        $this->publik_host = env('PORTAL_PUBLIK_HOST');
    }    

    public function getClient()
    {
        if (is_null(self::$client))
            self::$client = new \GuzzleHttp\Client();
        return self::$client;
    }

    public function index(){
        $client = self::getClient();
        $response = $client->get($this->publik_api_host.$this->publik_sso.'/service-to-tjsl');
        $data = json_decode($response->getBody(), true);

        return view('landing_page.index-dynamic',['data'=>$data['data'], 'publik_host'=>$this->publik_host]);
    }

}
