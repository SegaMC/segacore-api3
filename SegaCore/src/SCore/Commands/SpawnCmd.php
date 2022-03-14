<?php

namespace SCore\Commands;

use SCore\Core;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use SCore\Task\DelayedItemTask;

class SpawnCmd extends PluginCommand {

	private $core;

	public function __construct($core, $name) {
		parent::__construct($name, $core);
		$this->setDescription("Teleport to Spawn");
		$this->setAliases(array ("spawn", "sp", "hub", "lobby"));
		$this->setUsage("/spawn");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$spawn = Core::getInstance()->getServer()->getDefaultLevel()->getSpawnLocation();
			$sender->teleport($spawn);
			$sender->sendMessage(Core::getInstance()->config->get("spawnMessage"));
			$sender->getInventory()->clearAll();
			Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedItemTask($this, $sender), 10);
		} else {
			$sender->sendMessage("Only In-Game Command");
		}
	}
}