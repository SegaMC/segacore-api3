<?php

namespace sega\Status\API;

use sega\Status\Main;
use sega\Status\API\Discord;

class Webhook {

    const USERNAME = "Sega";

    public static function getStatusWebhook() : string {
        return Main::getInstance()->getDiscordConfig()->get("StatusWebhook");
    }

    public static function getCountWebhook() : string {
        return Main::getInstance()->getDiscordConfig()->get("CountWebhook");
    }

    public static function getMotd() : string {
        return Main::getInstance()->getDiscordConfig()->get("Motd");
    }

    public static function send(string $webhook, Discord $message) : void {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhook);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message->getContent());
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($curl);
    }
}