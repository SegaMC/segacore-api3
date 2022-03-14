<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class Resistance {
	
	public function Resistance($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Resistance"];
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
		$unbreak->setLevel(10);
		$axe = Item::get(Item::DIAMOND_AXE);
		$axe->addEnchantment($unbreak);
		$player->getInventory()->addItem($axe);
		
		$eff = new EffectInstance(Effect::getEffect(11), 20 * 9999, 250);
		$eff->setVisible(false);
		$player->addEffect($eff);
	}
}