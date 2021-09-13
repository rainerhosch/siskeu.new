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

use \GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class ApiClient extends CI_Controller
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
        echo ($response->getBody()->getContents());
    }

    public function mPost()
    {
        $end_point = 'RegUjian';
        $data_param = [
            'form_params' => [
                'type' => 'count',
                'thn_akademik' => '20212',
                // 'nipd' => '141351059'
            ]
        ];
        $response = $this->_client->request('POST', $end_point, $data_param);
        echo ($response->getBody()->getContents());
    }
}
