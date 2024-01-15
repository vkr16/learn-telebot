<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TelegramBotCommandController extends BaseController
{
    public function index()
    {
        //
    }

    public function pubgGaBang()
    {
        TelegramBotController::sendMessage();
    }
}
