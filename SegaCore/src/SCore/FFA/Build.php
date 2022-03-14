<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class Build {

	public function Build($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Build"];
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
		$efficiency = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY));
		$unbreak->setLevel(3);
		$protection->setLevel(3);
		$efficiency->setLevel(4);
		
		$sword = Item::get(Item::IRON_SWORD);
		$sword->addEnchantment($unbreak);
		
		$pick = Item::get(Item::IRON_PICKAXE);
		$pick->addEnchantment($unbreak);
		$pick->addEnchantment($efficiency);
		
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
		
		
		$player->getInventory()->setItem(0, $sword);
		$player->getInventory()->setItem(1, $pick);
		$player->getInventory()->addItem(Item::get(Item::COBBLESTONE, 0, 64));
		$player->getInventory()->setItem(3, Item::get(Item::GOLDEN_APPLE, 0, 5));
		$player->getInventory()->setItem(8, Item::get(Item::ENDER_PEARL, 0, 2));
		
		$player->getArmorInventory()->setHelmet($hel);
		$player->getArmorInventory()->setChestplate($che);
		$player->getArmorInventory()->setLeggings($leg);
		$player->getArmorInventory()->setBoots($boo);
	}
}