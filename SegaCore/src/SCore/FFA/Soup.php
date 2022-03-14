<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Soup {
	
	public function Soup($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Soup"];
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
		$protection = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION));
		$unbreak->setLevel(3);
		$protection->setLevel(3);
		
		$sword = Item::get(Item::IRON_SWORD);
		$sword->addEnchantment($unbreak);
		
		$hel = Item::get(Item::IRON_HELMET);
		$hel->addEnchantment($unbreak);
		$hel->addEnchantment($protection);
		
		$che = Item::get(Item::IRON_CHESTPLATE);
		$che->addEnchantment($unbreak);
		$che->addEnchantment($protection);
		
		$leg = Item::get(Item::IRON_LEGGINGS);
		$leg->addEnchantment($unbreak);
		$leg->addEnchantment($protection);
		
		$boo = Item::get(Item::IRON_BOOTS);
		$boo->addEnchantment($unbreak);
		$boo->addEnchantment($protection);

		$player->getInventory()->addItem($sword);
		$player->getInventory()->addItem(Item::get(282, 0, 35));
		
		$player->getArmorInventory()->setHelmet($hel);
		$player->getArmorInventory()->setChestplate($che);
		$player->getArmorInventory()->setLeggings($leg);
		$player->getArmorInventory()->setBoots($boo);
	}
}