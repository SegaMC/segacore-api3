<?php

namespace SCore\Events;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\Item;
use SCore\Task\DelayedItemTask;
use pocketmine\inventory\ArmorInventory;
use SCore\Duels\Duels;
use SCore\Core;

class DuelListener implements Listener {

	public function onChange(EntityLevelChangeEvent $event) {
		$player = $event->getEntity();
		$duels = new Duels();
		if (!$player instanceof Player) return;
		if ($duels->isPlaying($player)) {
			$duels->quitGame($player);
		}
	}

	public function onQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$duels = new Duels();
		if ($duels->isplaying($player)) {
			$duels->quitGame($player);
		}
	}

	public function onDeath(PlayerDeathEvent $event) {
		$player = $event->getPlayer();
		$duels = new Duels();
		if ($duels->isplaying($player)) {
			$duels->quitGame($player);
			$event->setDrops([]);
			$player->teleport(Core::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
			Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedItemTask($this, $player), 10);
		}
	}

	public function onBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$Duels = new Duels();
		$arena = $Duels->getPlayerArena($player);
		$block = $event->getBlock();
		if ($Duels->isPlaying($player) and is_string($arena)) {
			if (Duels::getKit($arena) != "BuildUHC") {
				$event->setCancelled(true);
			}
		}
	}
}