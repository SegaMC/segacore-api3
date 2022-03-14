<?php

namespace SCore\Duels;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;


class DuelKits {

	public function Fist(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		$player->getInventory()->addItem(Item::get(Item::STEAK));
	}

	public function Gapple(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		$player->getInventory()->addItem(Item::get(Item::IRON_SWORD));
		$player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 12));
		$player->getArmorInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
		$player->getArmorInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
		$player->getArmorInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
		$player->getArmorInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
	}

	public function Boxing(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
	}

	public function Classic(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);

		$sword = Item::get(Item::IRON_SWORD);
		$hel = Item::get(Item::IRON_HELMET);
		$che = Item::get(Item::IRON_CHESTPLATE);
		$leg = Item::get(Item::IRON_LEGGINGS);
		$boo = Item::get(Item::IRON_BOOTS);

        $player->getInventory()->addItem($sword);
		$player->getArmorInventory()->setHelmet($hel);
		$player->getArmorInventory()->setChestplate($che);
		$player->getArmorInventory()->setLeggings($leg);
		$player->getArmorInventory()->setBoots($boo);
	}

	public function Soup(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
	}

	public function Combo(Player $player) {
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

	public function Nodebuff(Player $player) {
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

	public function BuildUHC(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);
		
		$ench = new EnchantmentInstance(Enchantment::getEnchantment(17), 10);
		
		$sword = Item::get(276, 0, 1);
		$sword->addEnchantment($ench);
		
		$goldenhead = Item::get(322, 0, 3);
		$goldenhead->setCustomName("§eGolden Head");
		
		$hel = Item::get(310, 0, 1);
		$hel->addEnchantment($ench);
		$player->getArmorInventory()->setHelmet($hel);
		
		$che = Item::get(311, 0, 1);
		$che->addEnchantment($ench);
		$player->getArmorInventory()->setChestplate($che);
		
		$leg = Item::get(312, 0, 1);
		$leg->addEnchantment($ench);
		$player->getArmorInventory()->setLeggings($leg);
		
		$boo = Item::get(313, 0, 1);
		$boo->addEnchantment($ench);
		$player->getArmorInventory()->setBoots($boo);
		
		$player->getInventory()->setItem(0, $sword);
		$player->getInventory()->setItem(1, Item::get(346, 0, 1));
		$player->getInventory()->setItem(2, Item::get(261, 0, 1));
		$player->getInventory()->setItem(3, Item::get(322, 0, 6));
		$player->getInventory()->setItem(4, $goldenhead);
		$player->getInventory()->setItem(5, Item::get(278, 0, 1));
		$player->getInventory()->setItem(6, Item::get(279, 0, 1));
		$player->getInventory()->setItem(7, Item::get(5, 0, 64));
		$player->getInventory()->setItem(8, Item::get(4, 0, 64));
		$player->getInventory()->setItem(9, Item::get(262, 0, 64));
		$player->getInventory()->setItem(10, Item::get(325, 8, 1));
		$player->getInventory()->setItem(11, Item::get(325, 8, 1));
		$player->getInventory()->setItem(12, Item::get(325, 10, 1));
		$player->getInventory()->setItem(13, Item::get(325, 10, 1));
	}

	public function Sumo(Player $player) {
		$player->getArmorInventory()->clearAll();
		$player->getInventory()->clearAll();
		$player->removeAllEffects();
		$player->setHealth(20);

		$unbreak = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING));
		$unbreak->setLevel(3);

		$stick = Item::get(Item::STICK);
		$stick->addEnchantment($unbreak);

		$eff = new EffectInstance(Effect::getEffect(11), 20 * 9999, 250);
		$eff->setVisible(false);

		$player->getInventory()->addItem($stick);
		$player->addEffect($eff);
	}
}