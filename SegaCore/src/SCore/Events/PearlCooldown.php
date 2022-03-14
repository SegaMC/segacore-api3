<?php

namespace SCore\Events;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\EnderPearl;
use SCore\Core;

class PearlCooldown implements Listener {

	private $pcd;

	public function onPearl(PlayerInteractEvent $event) {
		$item = $event->getItem();
		$player = $event->getPlayer();
		$level = $player->getLevel();
		$level = $level->getName();
		if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
			$activelevel = Core::getInstance()->config->get("pearlCooldownWorlds");
			if (in_array($level, $activelevel)) {
				if ($item instanceof EnderPearl) {
					$cooldown = Core::getInstance()->config->get("pearlCooldownTime");
					if (isset($this->pcd[$player->getName()]) and time() - $this->pcd[$player->getName()] < $cooldown) {
						$event->setCancelled(true);
						$time = time() - $this->pcd[$player->getName()];
						$message = Core::getInstance()->config->get("pearlCooldownMessage");
						$message = str_replace("{time}", ($cooldown - $time), $message);
						$player->sendMessage($message);
					} else {
						$this->pcd[$player->getName()] = time();
					}
				}
			}
		}
	}
}