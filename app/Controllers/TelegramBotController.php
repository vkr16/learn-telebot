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
        $this->sendMessage($chat_id, $text);
    }

    private function readMessage()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    private function sendMessage($chat_id, $text)
    {
        $url = env('BOT_URL') . env('BOT_TOKEN') . "/sendMessage?chat_id=" . $chat_id . "&text=" . $text;
        return file_get_contents($url);
    }
}
