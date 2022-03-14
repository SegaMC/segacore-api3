<?php

namespace SCore\Commands;

use pocketmine\Player;
use SCore\Core;
use SCore\Menus;
use SCore\KDRSystem;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class ArenaStatusCmd extends PluginCommand {

	private $core;

	public function __construct($core, $name) {
		parent::__construct($name, $core);
		$this->setDescription("Set Arena Status");
		$this->setUsage("/ArenaStatus");
		$this->setPermission("PCore.adm.cmd");
	}
	
	public function execute(CommandSender $sender,string $commandLabel, array $args) {
		if ($this->testPermission($sender)) {
			if ($sender instanceof Player) {
				$menu = new Menus();
				$menu->ArenaStatus($sender);
			} else {
				$sender->sendMessage("Only In-Game Command");
			}
		}
	}
}