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
        $update = $this->readUpdate();
        // $chat_id = $update['message']['chat']['id'];
        // $firstname = $update['message']['from']['first_name'];
        $message = $update['message']['text'];

        $commandMethod = $this->commands[$message] ?? null;

        if ($commandMethod) {
            return $this->$commandMethod($update);
        }
    }

    public function is_cmd($message)
    {

        return substr($message, 0, 1) == "." ? true : false;
    }

    public function readUpdate()
    {
        return json_decode(file_get_contents('php://input'), true);
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
        $this->sendMessage($update['message']['chat']['id'], "Test response");
    }
}
