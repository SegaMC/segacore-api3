<?php

namespace SCore\Commands;

use SCore\Core;
use SCore\Menus;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\Position;
use pocketmine\entity\Entity;

class LeaderboardCmd extends PluginCommand {

	private $core;

	public function __construct($core, $name) {
		parent::__construct($name, $core);
		$this->setDescription("Summon Leaderboard");
		$this->setUsage("/leaderboard <kills/deaths/kdr/elo/killstreak>");
		$this->setPermission("PCore.adm.cmd");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($this->testPermission($sender)) {
			if ($sender instanceof Player) {
				if (!isset($args[0])) return $sender->sendMessage("usage: /leaderboard <kills/deaths/kdr/elo/killstreak>");
				if (strtolower($args[0]) == "kills") {
					$position = new Position($sender->x, $sender->y+1.0, $sender->z, $sender->level);
					$nbt = Entity::createBaseNBT($position, null, 1.0, 1.0);
					$leaderboard = Entity::createEntity("Kills", $sender->level, $nbt);
					$leaderboard->spawnToAll();
				} elseif (strtolower($args[0]) == "deaths") {
					$position = new Position($sender->x, $sender->y+1.0, $sender->z, $sender->level);
					$nbt = Entity::createBaseNBT($position, null, 1.0, 1.0);
					$leaderboard = Entity::createEntity("Deaths", $sender->level, $nbt);
					$leaderboard->spawnToAll();
				} elseif (strtolower($args[0]) == "kdr") {
					$position = new Position($sender->x, $sender->y+1.0, $sender->z, $sender->level);
					$nbt = Entity::createBaseNBT($position, null, 1.0, 1.0);
					$leaderboard = Entity::createEntity("Kdr", $sender->level, $nbt);
					$leaderboard->spawnToAll();
				} elseif (strtolower($args[0]) == "killstreak") {
					$position = new Position($sender->x, $sender->y+1.0, $sender->z, $sender->level);
					$nbt = Entity::createBaseNBT($position, null, 1.0, 1.0);
					$leaderboard = Entity::createEntity("KillStreak", $sender->level, $nbt);
					$leaderboard->spawnToAll();
				} elseif (strtolower($args[0]) == "elo") {
					$position = new Position($sender->x, $sender->y+1.0, $sender->z, $sender->level);
					$nbt = Entity::createBaseNBT($position, null, 1.0, 1.0);
					$leaderboard = Entity::createEntity("Elo", $sender->level, $nbt);
					$leaderboard->spawnToAll();
				} else {
					$sender->sendMessage("usage: /leaderboard <kills/deaths/kdr>");
				}
			} else {
				$sender->sendMessage("Only In-Game Command");
			}
		}
	}
}