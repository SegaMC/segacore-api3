<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Nodebuff {
	
	public function Nodebuff($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Nodebuff"];
			$player->teleport(Core::getInstance()->getServer()->getLevelByName($world)->getSafeSpawn());
			Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedKitTask($this, $player), 5);
		}
	}
	
	public function Kit($player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		
		$unbreak = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING));
		$unbreak->setLevel(3);
		
		$sword = Item::get(Item::DIAMOND_SWORD);
		$sword->addEnchantment($unbreak);
		
		$hel = Item::get(Item::DIAMOND_HELMET);
		$hel->addEnchantment($unbreak);
		
		$che = Item::get(Item::DIAMOND_CHESTPLATE);
		$che->addEnchantment($unbreak);
		
		$leg = Item::get(Item::DIAMOND_LEGGINGS);
		$leg->addEnchantment($unbreak);
		
		$boo = Item::get(Item::DIAMOND_BOOTS);
		$boo->addEnchantment($unbreak);
		
		$player->getInventory()->addItem($sword);
		$player->getInventory()->addItem(Item::get(Item::ENDER_PEARL, 0, 16));
		$player->getInventory()->addItem(Item::get(438, 22, 34));
		$player->getArmorInventory()->setHelmet($hel);
		$player->getArmorInventory()->setChestplate($che);
		$player->getArmorInventory()->setLeggings($leg);
		$player->getArmorInventory()->setBoots($boo);
	}
}