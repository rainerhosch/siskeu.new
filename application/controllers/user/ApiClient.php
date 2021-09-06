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
class ApiClient extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $token = 'semogabahagia';
        $url = "https://api.wastu.digital/resources/TahunAkademikAktif";
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
            'Authorization: someAuthorization',
            'token: ' . $token,
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        $data = json_decode($response, true);
        var_dump($data);
    }
}
