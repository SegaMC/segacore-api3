<?php

namespace SCore\FFA;

use pocketmine\Player;
use SCore\Task\DelayedKitTask;
use SCore\Core;

use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class Knockback {

	public function Knockback($player) {
		if ($player instanceof Player) {
			$world = Core::getInstance()->config->get("FFAWorlds")["Knockback"];
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
		$knockback = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::KNOCKBACK));
		$knockback->setLevel(3);
		
		$stick = Item::get(Item::STICK);
		$stick->addEnchantment($unbreak);
		
		$eff = new EffectInstance(Effect::getEffect(11), 20 * 9999, 250);
		$eff->setVisible(false);
		
		$player->getInventory()->addItem($stick);
		$player->addEffect($eff);
	}
}