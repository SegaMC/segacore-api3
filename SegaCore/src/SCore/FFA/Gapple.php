<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Gapple {
	
	public function Gapple($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Gapple"];
			$player->teleport(Core::getInstance()->getServer()->getLevelByName($world)->getSafeSpawn());
			Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedKitTask($this, $player), 5);
		}
	}
	
	public function Kit($player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		
		$player->getInventory()->addItem(Item::get(Item::IRON_SWORD));
		$player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 32));
		$player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
		$player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
		$player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
		$player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
	}
}