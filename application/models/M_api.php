<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : M_api.php
 *  File Type             : Model
 *  File Package          : CI_Models
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 22/06/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class M_api extends CI_Model
{
    public function getGetDataTerbaru($data)
    {
        $url = "https://api.wastu.digital/resources/MahasiswaForSiskeu?token=semogabahagia&offset=" . $data;
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, "$url");
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        $data = json_decode($response, true);
        return $data;
    }
}
