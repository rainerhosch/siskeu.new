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

use \GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class M_api extends CI_Model
{
    private $_client;
    public function __construct()
    {
        parent::__construct();
        $this->_client = new Client([
            'base_uri' => 'https://api.wastu.digital/resources/',
            'headers' => ['token' => 'semogabahagia']
        ]);
    }

    public function mGet($end_point, $data_param)
    {
        // $end_point = 'RegUjian';
        // $data_param = [
        //     'query' => [
        //         'type' => 'count',
        //         'thn_akademik' => '20212',
        //         // 'nipd' => '141351059'
        //     ]
        // ];
        $response = $this->_client->request('GET', $end_point, $data_param);
        $result =  json_decode($response->getBody()->getContents(), true);
        return $result;
    }

    public function mPost($end_point, $data_param)
    {
        // $end_point = 'RegUjian';
        // $data_param = [
        //     'form_params' => [
        //         'type' => 'count',
        //         'thn_akademik' => '20212',
        //     ]
        // ];
        $response = $this->_client->request('POST', $end_point, $data_param);
        $result =  json_decode($response->getBody()->getContents(), true);
        return $result;
    }
}
