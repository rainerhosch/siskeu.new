<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
// get data mahasiswa from simak

function reqData($endpoint, $param = null)
{
    $token = 'semogabahagia';
    if ($param != null) {
        $url = 'https://api.wastu.digital/resources/' . $endpoint . '?' . $param;
    } else {
        $url = 'https://api.wastu.digital/resources/' . $endpoint;
    }
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
    if ($endpoint = 'TahunAkademikAktif') {
        foreach ($data['semester_aktif'] as $d) {
            return $d;
        }
    } else {
        return $data;
    }
}
function getDataMahasiswa($token, $param = null)
{
    if ($param != null) {
        $url = "https://api.wastu.digital/resources/MahasiswaForSiskeu?token=" . $token . '&' . $param;
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

function getSemesterAktif($token, $param = null)
{
    if ($param != null) {
        $url = "https://api.wastu.digital/resources/TahunAkademikAktif?token=" . $token . '&' . $param;
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

function getRegMhs($token, $param = null)
{
    if ($param != null) {
        $url = "https://api.wastu.digital/resources/RegMhs?token=" . $token . '&' . $param;
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

function getRegUjian($token, $param = null)
{
    if ($param != null) {
        $url = "https://api.wastu.digital/resources/RegUjian?token=" . $token . '&' . $param;
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
