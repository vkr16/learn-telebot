<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotController extends BaseController
{
    protected $commands = [
        '/start' => 'startCommand',
        '/cmd' => 'cmdCommand',
        '/random' =>  'randomCommand',
        '/appointment' =>  'appointmentCommand'
    ];

    protected $commandDescriptions = [
        '/start' => 'Start the bot',
        '/cmd' => ' Show all bot available commands',
        '/random' => 'Generate 6 digit random numbers',
        '/appointment' => 'Create an appointment at specified hour\. usage\: /appointment 22\:00'
    ];

    public function index()
    {
        $headers = getallheaders();
        $secret = $headers['X-Telegram-Bot-Api-Secret-Token'];

        if ($secret == env('BOT_SECRET')) {
            $update = $this->readUpdate();
            $text = $update['message']['text'];
            $command = explode(" ", $text, 1);
            $chat_id = $update['message']['chat']['id'];

            $commandMethod = $this->commands[$command] ?? null;

            if ($commandMethod) {
                return $this->$commandMethod($update);
            }

            $this->sendMessage($chat_id, "_I don't understand the command\._\n_Try */cmd* to see available commands\._");
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

    public function cmdCommand($update)
    {
        /* /cmd - Show all bot available commands*/
        $message = "🔥 *Command list* 🔥\n\n_";

        foreach ($this->commands as $key => $cmd) {
            $message .= "*" . $key . "*" . "\n" . $this->commandDescriptions[$key] . "\n\n";
        }

        $this->sendMessage($update['message']['chat']['id'], $message . "_");
    }

    public function randomCommand($update)
    {
        /* /random - Generate 6 digit random numbers*/
        $random = "🎲 Random Code  *";
        for ($i = 0; $i < 6; $i++) {
            $random .= rand(0, 9) . " ";
        }
        $random .= '*';

        $this->sendMessage($update['message']['chat']['id'], $random . "🎲");
    }

    public function appointmentCommand($update)
    {
        /* /appointment [time] - Create an appointment at specified hour */
        $params = explode(' ', $update['message']['text']);

        if (isset($params[1])) {
            $this->sendMessage($update['message']['chat']['id'], $params[1]);
        } else {
            $this->sendMessage($update['message']['chat']['id'], "Please specify time in HH\:MM format \(e\.g\: 22\:00\)");
        }
    }
}
