<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CorsMiddleware {
    public function handle() {
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: https://siskeu.wastu.digital");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");

        // Cek request OPTIONS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }
}
