<?php

namespace SCore\Events;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\scheduler\Task;

use SCore\Task\BuildFFATask;
use SCore\Core;

class BuildListener implements Listener {
	
	public function onBlockPlace(BlockPlaceEvent $event) {
		$player = $event->getPlayer();
		if ($player->isCreative()) return;
		$block = $event->getBlock();
		$level = $block->getLevel();
		$loc = $block->add(0, 0, 0);
		if ($level->getName() == Core::getInstance()->config->get("FFAWorlds")["Build"]) {
			if ($block->getId() == Block::COBBLESTONE) {
				$player->getInventory()->setItem(2, Item::get(Item::COBBLESTONE, 0, 32));
				Core::getInstance()->getScheduler()->scheduleDelayedTask(new BuildFFATask($this, $loc, $block), 7 * 20);
			} else {
				$event->setCancelled();
			}
		}
	}
	public function onBlockBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		if ($player->isCreative()) return;
		$block = $event->getBlock();
		$level = $block->getLevel();
		if ($level->getName() == Core::getInstance()->config->get("FFAWorlds")["Build"]) {
			if ($block->getId() != Block::COBBLESTONE) {
				$event->setCancelled();
			}
		}
	}
}