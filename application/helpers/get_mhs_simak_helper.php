<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
// get data mahasiswa from simak
function getDataMahasiswa()
{
    $url = "https://api.wastu.digital/resources/MahasiswaForSiskeu?token=semogabahagia";
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
