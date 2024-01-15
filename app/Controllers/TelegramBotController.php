<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotController extends BaseController
{
    // private $commandList = ["perang", "gas"];

    public function index()
    {
        $update = $this->readUpdate();
        $chat_id = $update['message']['chat']['id'];
        $firstname = $update['message']['from']['first_name'];
        $message = $update['message']['text'];

        if ($message == '/start') {
            $this->sendMessage($chat_id, "Try to send command \".perang\" without quotes");
        } else {
            if ($this->is_cmd($message)) {
                switch (substr($message, 1)) {
                    case 'perang':
                        $this->sendMessage($chat_id, "War invitation sent!");
                        for ($i = 0; $i < 5; $i++) {
                            $this->sendMessage(env('FM_ID'), "You are invited to a war by " . $firstname . "!");
                        }

                        break;

                    default:
                        $this->sendMessage($chat_id, "Unknown command!");
                        break;
                }
            } else {
                $this->sendMessage($chat_id, "Unknown command!");
            };
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
}
