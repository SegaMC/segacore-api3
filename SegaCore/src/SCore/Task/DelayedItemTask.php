<?php

namespace SCore\Task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use SCore\Menus;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\nbt\tag\StringTag;

class DelayedItemTask extends Task {

	public $plugin;
	public $player;

	public function __construct($plugin, $player) {
		$this->plugin = $plugin;
		$this->player = $player;
	}

	public function onRun(int $currentTick) {
		$player = $this->player;
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		
		$enchant_power = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::POWER));
		$enchant_power->setLevel(1);
		
		$ffa = Item::get(Item::DIAMOND_SWORD)->setCustomName("§cPractice");
        $ffa->setLore(["Play FFA Arenas"]);
		$duels = Item::get(Item::IRON_SWORD)->setCustomName("§cDuels");
        $duels->setLore(["Play Duels"]);
		$menu = Item::get(Item::CLOCK)->setCustomName("§cMenu");
        $menu->setLore(["Configure Settings"]);
		
		$player->getInventory()->setItem(0, $duels);
		$player->getInventory()->setItem(1, $ffa);
		$player->getInventory()->setItem(8, $menu);
		
		/**
		$settings = Item::get(Item::CLOCK)->setCustomName("§l§fSettings§r");
		$settings->getNamedTag()->setString("hub", "interactable");
		
		$report = Item::get(Item::ANVIL)->setCustomName("§l§4Report§r");
		$report->getNamedTag()->setString("hub", "interactable");
		*/
	}
}