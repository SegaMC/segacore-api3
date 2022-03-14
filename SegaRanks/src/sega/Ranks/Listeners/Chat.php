<?php

namespace sega\Ranks\Listeners;

use sega\Ranks\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;

class Chat implements Listener{

    public function onChat(PlayerChatEvent $event){
        Loader::getInstance()->getServer()->broadcastMessage($event->getPlayer()->getNameTag() . " | " . $event->getMessage());
        $event->setCancelled();
    }
}