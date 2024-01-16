<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotController extends BaseController
{
    protected $commands = [
        '/start' => 'startCommand',
        '/random' => 'randomCommand'
    ];

    public function index()
    {
        $update = json_decode(file_get_contents('php://input'), true);
        $chat_id = $update['message']['chat']['id'];
        $firstname = $update['message']['from']['first_name'];
        $message = $update['message']['text'];

        $commandMethod = $this->commands[$message] ?? null;

        if ($commandMethod) {
            return $this->$commandMethod($update);
        } else {
            $this->sendMessage($chat_id, "I'm sorry, I don't understand the command \"$message\"");
        }
    }

    public function sendMessage($chat_id, $text)
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

    public function startCommand($update)
    {
        $this->sendMessage($update['message']['chat']['id'], "Selamat datang di bot AkuOnline by Fikri Miftah\n
        Coba balas dengan \"/random\" tanpa tanda kutip");

    }
}
