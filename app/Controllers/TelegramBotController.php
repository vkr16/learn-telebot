<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotController extends BaseController
{
    public function index()
    {
        $update = $this->readUpdate();
        $chat_id = $update['message']['chat']['id'];
        $firstname = $update['message']['from']['first_name'];
        $message = $update['message']['text'];

        $text = "Hi " . $firstname . ", " . $message;
        $this->sendMessage($chat_id, $text);
    }

    private function readUpdate()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    private function sendMessage($chat_id, $text)
    {
        $url = env('BOT_URL') . env('BOT_TOKEN') . "/sendMessage?";

        $params = [
            'chat_id' => $chat_id,
            'text' => $text
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
