<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
// get data mahasiswa from simak
function getDataMahasiswa($token, $data = null)
{
    if ($data !== null) {
        $url = "https://api.wastu.digital/resources/MahasiswaForSiskeu?token=" . $token . '&' . $data;
    } else {
        $url = "https://api.wastu.digital/resources/MahasiswaForSiskeu?token=" . $token;
    }
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

function getSemesterAktif($token, $data = null)
{
    if ($data !== null) {
        $url = "https://api.wastu.digital/resources/TahunAkademikAktif?token=" . $token . '&' . $data;
    } else {
        $url = "https://api.wastu.digital/resources/TahunAkademikAktif?token=" . $token;
    }
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, "$url");
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_HEADER, false);
    $response = curl_exec($curl_handle);
    curl_close($curl_handle);
    $data = json_decode($response, true);
    foreach ($data['semester_aktif'] as $d) {
        return $d;
    }
}

function getRegMhs($token, $data = null)
{
    if ($data !== null) {
        $url = "https://api.wastu.digital/resources/RegMhs?token=" . $token . '&' . $data;
    } else {
        $url = "https://api.wastu.digital/resources/RegMhs?token=" . $token;
    }
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

function getRegUjian($token, $data = null)
{
    if ($data !== null) {
        $url = "https://api.wastu.digital/resources/RegUjian?token=" . $token . '&' . $data;
    } else {
        $url = "https://api.wastu.digital/resources/RegUjian?token=" . $token;
    }
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
