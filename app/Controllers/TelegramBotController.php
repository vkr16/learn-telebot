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
        log_message('error', $update);
        $text = $update['message']['text'];

        $commandMethod = $this->commands[$text] ?? null;

        if ($commandMethod) {
            return $this->$commandMethod($update);
        }

        return "I don't understand the command";
    }

    public function readUpdate()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function sendMessage($chat_id, $text, $parse_mode = 'MarkdownV2')
    {
        $url = env('BOT_URL') . env('BOT_TOKEN') . "/sendMessage?";

        $params = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'markdownv2'
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }


    /**
     * Commands
     */

    public function startCommand($update)
    {
        $this->sendMessage($update['message']['chat']['id'], "*Welcome to _AkuOnline_ Bot!*\n\n```By : FM```");
    }
}
