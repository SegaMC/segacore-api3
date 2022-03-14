<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;


class Fist {
	
	public function Fist($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Fist"];
			$player->teleport(Core::getInstance()->getServer()->getLevelByName($world)->getSafeSpawn());
			Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedKitTask($this, $player), 5);
		}
	}
	
	public function Kit($player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		
		$player->getInventory()->addItem(Item::get(Item::STEAK));
	}
}