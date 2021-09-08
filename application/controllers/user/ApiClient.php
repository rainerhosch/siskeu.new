<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : ApiClient.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : dd/mm/yyyy
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */

use \GuzzleHttp;

class ApiClient extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $token = 'semogabahagia';
        $type = 'count';
        $url = "https://api.wastu.digital/resources/RegUjian";
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
            'Authorization: someAuthorization',
            'token: ' . $token,
            'type: ' . $type,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        $data = json_decode($response, true);
        var_dump($data);
    }

    public function get_data()
    {
        $token = 'semogabahagia';
        $type = 'count';
        $url = "https://api.wastu.digital/resources/RegUjian";
        // Create a PSR-7 request object to send
        $headers = [
            'token' => $token,
            'type' => $type,
            'Content-Type' => 'application/json'
        ];
        $body = 'Hello!';
        $request = new Request('HEAD', 'https://api.wastu.digital/resources/RegUjian', $headers, $body);
        $promise = $client->sendAsync($request);
    }
}
