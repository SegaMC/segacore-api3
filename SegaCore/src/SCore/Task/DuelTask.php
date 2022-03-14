<?php

namespace SCore\Task;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use SCore\Duels\Duels;
use SCore\Task\DelayedItemTask;
use SCore\Core;
use SCore\ScoreSystem;

class DuelTask extends Task {
	private $duels;
	public static $stime = array();
	public static $gtime = array();
	public static $rtime = array();

	public function __construct($duels) {
		$this->duels = $duels;
	}

	public function onRun(int $currentTick) {
		global $stime, $gtime, $rtime;
		$stime["1"] = 1;
		$gtime["1"] = 1;
		$rtime["1"] = 1;
		$duels = $this->duels;
		if (count(Duels::getArenas()) <= 0) return;
		foreach (Duels::getArenas() as $arena) {
			if (!array_key_exists($arena, $gtime) or $gtime[$arena] < 0) $gtime[$arena] = Duels::getTime($arena);
			if (!array_key_exists($arena, $stime) or $stime[$arena] < 0) $stime[$arena] = 5;
			if (!array_key_exists($arena, $rtime) or $rtime[$arena] < 0) $rtime[$arena] = 3;
			switch (Duels::getStatus($arena)) {

				case "Ready":

					if (count($duels->getPlayers($arena)) >= 2) {
						$stime[$arena] = $stime[$arena] - 1;
						foreach ($duels->getPlayers($arena) as $player) {
							$time = $stime[$arena];
							$player->sendTip("§aGame Starting in§e $time §aseconds");
							$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_NOTE);
							if ($stime[$arena] == 2) $player->addTitle("§eReady!");
						}

						if ($stime[$arena] == 0) {
							$slot = 0;
							Duels::copyMap($arena);
							foreach ($duels->getPlayers($arena) as $player) {
								$slot++;
								$spawn = Duels::getSpawnPoint($arena, $slot);
								$player->teleport(new Vector3($spawn[0], $spawn[1] + 1.0, $spawn[2]));
								if (Duels::getKit($arena) == "BuildUHC") {
									$player->setGamemode(Player::SURVIVAL);
								} else $player->setGamemode(Player::ADVENTURE);
								$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_ARMOR_EQUIP_DIAMOND);
								$player->addTitle("§eFight!");
								$duels->giveKit($player, $arena);
							}
							Duels::setStatus($arena, "Running");
						}

					} else {
						foreach ($duels->getPlayers($arena) as $player) {
							$player->setGamemode(Player::ADVENTURE);
							$player->sendTip("§cWaiting for Opponent");
						}
					}
					break;

				case "Running":
					$gtime[$arena] = $gtime[$arena] - 1;

					foreach ($duels->getPlayers($arena) as $player) {
						$time = $gtime[$arena];
						if ($time >= 100) {
							$time = "§a" . $time;
						} elseif ($time >= 30) {
							$time = "§6" . $time;
						} elseif ($time >= 10) {
							$time = "§c" . $time;
						} elseif ($time < 10) {
							$time = "§4" . $time;
						}
					}

					if (count($duels->getPlayers($arena)) < 2) {
						$gtime[$arena] = 0;
						foreach ($duels->getPlayers($arena) as $player) {
							$player->addTitle("§cVICTORY");
							if (Duels::isRanked($arena)) {
								$score = new ScoreSystem();
								$score->addElo($player, 50);
							}
							$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LAUNCH);
							$player->sendTip("Restarting");
							Duels::setStatus($arena, "Resetting");
							$gtime[$arena] = Duels::getTime($arena);
							break;
						}
					}

					if ($gtime[$arena] == 0) {
						foreach ($duels->getPlayers($arena) as $player) {
							$player->addTitle("§cTime Out");
							$player->sendMessage("§cTime ran out and no one won this match!");
							Duels::setStatus($arena, "Resetting");
						}
					}
					break;

				case "Resetting":
					$rtime[$arena] = $rtime[$arena] - 1;
					
					if ($rtime[$arena] == 0) {
						foreach ($duels->getPlayers($arena) as $player) {
							$player->getInventory()->clearAll();
							$player->getArmorInventory()->clearAll();
							$player->setFood($player->getMaxFood());
							$player->setHealth($player->getMaxHealth());
							$player->setGamemode(Player::SURVIVAL);
							$player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
							Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedItemTask($this, $player), 10);
						}
						Duels::pasteMap($arena);
						Duels::setStatus($arena, "Ready");
					}
			}
		}
	}
}