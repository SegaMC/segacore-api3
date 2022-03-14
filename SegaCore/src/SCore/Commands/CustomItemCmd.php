<?php

namespace SCore\Commands;

use pocketmine\Player;
use pocketmine\nbt\tag\StringTag;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class CustomItemCmd extends PluginCommand {

	private $core;

	public function __construct($core, $name) {
		parent::__construct($name, $core);
		$this->setDescription("Gives you Custom Item");
		$this->setUsage("/customitem <bowbomb|lightningrod|throwabletnt>");
		$this->setPermission("PCore.adm.cmd");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($this->testPermission($sender)) {
			if ($sender instanceof Player) {
				$usage = "usage: /customitem <bowbomb|lightningrod|throwabletnt>";
				if (!isset($args[0])) return $sender->sendMessage($usage);
				if (strtolower($args[0]) == "bowbomb") {
					$flame = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::FLAME));
					$flame->setLevel(3);
					$bow = Item::get(Item::BOW)->setCustomName("§l§eBomb Bow§r");;
					$bow->addEnchantment($flame);
					$bow->getNamedTag()->setString("bow", "bomb");
					$sender->getInventory()->addItem($bow);
					$sender->sendMessage("given \"bowbomb\"");
				} elseif (strtolower($args[0]) == "lightningrod") {
					$power = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::POWER));
					$power->setLevel(10);
					$rod = Item::get(Item::BLAZE_ROD)->setCustomName("§l§bLightning §eRod§r");;
					$rod->addEnchantment($power);
					$rod->getNamedTag()->setString("rod", "lightning");
					$sender->getInventory()->addItem($rod);
					$sender->sendMessage("given \"lightningrod\"");
				} elseif (strtolower($args[0]) == "throwabletnt") {
					$power = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::POWER));
					$power->setLevel(10);
					$tnt = Item::get(Item::TNT)->setCustomName("§l§eThrowable TNT§r");;
					$tnt->addEnchantment($power);
					$tnt->getNamedTag()->setString("tnt", "throwable");
					$sender->getInventory()->addItem($tnt);
					$sender->sendMessage("given \"throwabletnt\"");
				} else $sender->sendMessage($usage);
			} else {
				$sender->sendMessage("Only In-Game Command");
			}
		}
	}
}