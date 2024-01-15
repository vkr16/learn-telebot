<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class TelebotController extends BaseController
{
    public function index()
    {
        //
    }

    public function getUpdates()
    {
        $url = env('BOT_URL') . env('BOT_TOKEN') . "/getUpdates";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        dd(json_decode($response, true));
    }

    public function webhookFeedback()
    {
        $input = file_get_contents('php://input');
        try {
            $updates = json_decode($input, true);
        } catch (Exception $e) {
            die();
        }

        // $url = env('BOT_URL') . env('BOT_TOKEN') . "/getUpdates";

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);

        // $updates = json_decode($response, true);

        $text = "Hello ";
        if ($updates['ok'] == true) {
            foreach ($updates['result'] as $key => $update) {
                $chat_id = $update['message']['chat']['id'];
                $firstname = $update['message']['from']['first_name'];
                echo $update['update_id'];
                // echo " - ";
                echo $update['message']['text'];
                // echo "<br><br>";
                // $this->sendMessage("chat_id=" . $chat_id . "&text=local-" . $text . $firstname);
            }
        }
    }

    public function sendMessage($params)
    {
        $url = env('BOT_URL') . env('BOT_TOKEN') . "/sendMessage?" . $params;

        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute request
        $result = curl_exec($ch);

        // Close cURL  
        curl_close($ch);

        // Print response
        return $result;
    }
}
