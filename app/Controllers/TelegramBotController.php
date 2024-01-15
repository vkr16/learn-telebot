<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotController extends BaseController
{
    public function index()
    {
        $update = $this->readMessage();
        $chat_id = $update['message']['chat']['id'];
        $firstname = $update['message']['from']['first_name'];
        $message = $update['message']['text'];

        $text = "Hi " . $firstname . ", " . $message;
        $this->sendMessage(urlencode("chat_id=" . $chat_id . "&text=" . $message));
    }

    private function readMessage()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    private function sendMessage($params)
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
