<?php

namespace SCore\Commands;

use SCore\Core;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use SCore\Menus;

class RanksCmd extends PluginCommand {

	private $core;

	public function __construct($core, $name) {
		parent::__construct($name, $core);
		$this->setDescription("Server Ranks");
		$this->setUsage("/ranks");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			$menu = new Menus();
			$menu->RanksTab($sender);
		} else $sender->sendMessage("Only In-Game Command");
	}
}