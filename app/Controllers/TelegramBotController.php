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
        $headers = getallheaders();
        $secret = $headers['X-Telegram-Bot-Api-Secret-Token'];

        if ($secret == env('BOT_SECRET')) {
            $update = $this->readUpdate();
            $text = $update['message']['text'];
            $chat_id = $update['message']['chat']['id'];

            $commandMethod = $this->commands[$text] ?? null;

            if ($commandMethod) {
                return $this->$commandMethod($update);
            }

            $this->sendMessage($chat_id, "_I don't understand the command_");
        }
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
            'parse_mode' => $parse_mode
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }


    /**
     * Bot Command Handler
     */

    public function startCommand($update)
    {
        /* /start - Start the bot*/
        $this->sendMessage($update['message']['chat']['id'], "🔥 *Welcome to AkuOnline Bot* 🔥");
    }

    public function randomCommand($update)
    {
        /* /random - Generate 6 digit random numbers*/
        $random = "Random Code  *";
        for ($i = 0; $i < 6; $i++) {
            $random .= rand(0, 9) . " ";
        }
        $random .= '*';

        $this->sendMessage($update['message']['chat']['id'], $random);
    }
}
