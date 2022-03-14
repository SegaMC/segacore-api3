<?php

namespace sega\Ranks\Listeners;

use sega\Ranks\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class Join implements Listener
{

    public function onJoin(PlayerJoinEvent $event)
    {
        Loader::getInstance()->addRank($event->getPlayer(), Loader::getInstance()->getRank($event->getPlayer()));
    }
}