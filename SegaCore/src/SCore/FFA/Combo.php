<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Combo {
	
	public function Combo($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Combo"];
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
		
		$sword = Item::get(Item::DIAMOND_SWORD);
		$sword->addEnchantment($unbreak);
		
		$hel = Item::get(Item::DIAMOND_HELMET);
		$hel->addEnchantment($unbreak);
		$hel->addEnchantment($protection);
		
		$che = Item::get(Item::DIAMOND_CHESTPLATE);
		$che->addEnchantment($unbreak);
		$che->addEnchantment($protection);
		
		$leg = Item::get(Item::DIAMOND_LEGGINGS);
		$leg->addEnchantment($unbreak);
		$leg->addEnchantment($protection);
		
		$boo = Item::get(Item::DIAMOND_BOOTS);
		$boo->addEnchantment($unbreak);
		$boo->addEnchantment($protection);
		
		$player->getInventory()->addItem($sword);
		$player->getInventory()->addItem(Item::get(Item:: ENCHANTED_GOLDEN_APPLE, 0, 32));
		$player->getArmorInventory()->setHelmet($hel);
		$player->getArmorInventory()->setChestplate($che);
		$player->getArmorInventory()->setLeggings($leg);
		$player->getArmorInventory()->setBoots($boo);
	}
}