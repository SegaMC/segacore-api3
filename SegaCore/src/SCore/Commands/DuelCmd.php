<?php

namespace SCore\Commands;

use pocketmine\ {
	Player,
	Server
};
use pocketmine\command\ {
	PluginCommand,
	CommandSender
};

use SCore\Duels\Duels;
use SCore\Menus;
use SCore\Core;;

class DuelCmd extends PluginCommand {

	private $plugin;

	public function __construct(Core $plugin) {
		parent::__construct("duels", $plugin);
		$this->setDescription("Duels command");
		$this->setUsage("/duels help");
		$this->setPermission("PCore.adm.cmd");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->sendMessage("§a§lCore >>§r " . "§4use /duels help");
				return false;
			}
			switch ($args[0]) {
				case "help":
					if (!$sender->hasPermission("PCore.adm.cmd")) {
						$sender->sendMessage("§a§lCore >>§r " . "§4 Permission Denied");
						return false;
					}
					$sender->sendMessage("§l§aDUELS - SETUP COMMANDS" . "\n§r" .
						"§a use {world/name} {kit} {bool:isRanked} to create a new arena\n" .
						"§a use /duels spawn {1/2} to set the spawn ppoint\n" .
						"§a use /duels wait to set the waiting area of the aaren\n" .
						"§a use /duels done to exit setup mode\n" .
						"§a use /duels join see the input menu\n"
					);
					break;
				case "create":
					if (!$sender->hasPermission("PCore.adm.cmd")) {
						$sender->sendMessage("§a§lCore >>§r " . "§4 Permission Denied");
						return false;
					}

					if (!isset($args[1]) or !isset($args[2]) or !isset($args[3])) {
						$sender->sendMessage("§a§lCore >>§r " . "§ruse /duels create {world/name} {kit} {bool:isRanked (true = ranked, false = unranked)}");
						return false;
					}

					if (!file_exists(Server::getInstance()->getDataPath(). "worlds/" . $args[1])) {
						$sender->sendMessage("§a§lCore >>§r " . "§4The world§r " . $args[1] . " §4does not exist!");
						return false;
					}

					if (Duels::arenaExist($args[1])) {
						$sender->sendMessage("§a§lCore >>§r " . "§4Arena already exist");
						return false;
					}

					if (!Duels::isAvailKit($args[2])) {
						$sender->sendMessage("§a§lCore >>§r " . "§4Kit " . $args[2] . "doesn't exist");
						$sender->sendMessage("§a§lCore >>§r " . "§aAvailable kits:");
						foreach (Duels::availKits() as $kit) {
							$sender->sendMessage("- $kit");
						}
						return false;
					}

					Duels::setConfigMode($sender, $args[1]);
					Duels::createArena($args[1], $args[2], filter_var($args[3], FILTER_VALIDATE_BOOLEAN));
					$sender->sendMessage("§a§lCore >>§r " . " §aArena registered world name " . $args[1] . " using kit " . $args[2] . " Ranked: " . $args[3]);
					$sender->sendMessage("§aUse /duels spawn {1/2} - to set spawn points and /duels set wait - to set queue wait point");
					break;


				case "spawn":
					if (!$sender->hasPermission("PCore.adm.cmd")) {
						$sender->sendMessage("§a§lCore >>§r " . "§4Permission Denied");
						return false;
					}
					if (!isset($args[1])) {
						$sender->sendMessage("§a§lCore >>§r " . "§4use /duels spawn {1/2} to set the spawn points");
						return false;
					}
					if (!Duels::isConfigMode($sender)) {
						$sender->sendMessage("§a§lCore >>§r " . "§4you are not in configuration mode, use /duels create (arena) (type) - to create a new arena!");
						return false;
					}
					if (!is_numeric($args[1])) {
						$sender->sendMessage("§a§lCore >>§r " . "§4use a numeric value in the slots!");
						return false;
					}
					if ((int)$args[1] > 2) {
						$sender->sendMessage("§a§lCore >>§r " . "there are only 2 slots availables!");
						return false;
					}
					Duels::setSpawn($sender, $args[1]);
					$sender->sendMessage("§a§lCore >>§r " . "§aSpawn §b" . $args[1] . "§a set successfully!");
					break;


				case "wait":
					if (!$sender->hasPermission("PCore.adm.cmd")) {
						$sender->sendMessage("§a§lCore >>§r " . "§4Permission Denied");
						return false;
					}
					if (!Duels::isConfigMode($sender)) {
						$sender->sendMessage("§a§lCore >>§r " . "§4You are not in configuration mode, use /duels create to create arena");
						return false;
					}
					Duels::setWaitPoint($sender);
					$sender->sendMessage("§a§lCore >>§r " . "§aWait Point set successfully");
					break;


				case "done":
					if (!$sender->hasPermission("PCore.adm.cmd")) {
						$sender->sendMessage("§a§lCore >>§r " . "§4Permission Denied");
						return false;
					}
					if (!Duels::isConfigMode($sender)) {
						$sender->sendMessage("§a§lCore >>§r " . "§4You are not in configuration mode, use /duels create to create arena");
						return false;
					}
					Duels::configDone($sender);
					Duels::unsetConfigMode($sender);
					$sender->sendMessage("§a§lCore >>§r " . "You exited Configuration Mode");
					break;
					
				case "join":
					$menu = new Menus();
					$menu->DuelsMenu($sender);
					break;

				default:
					$sender->sendMessage("§a§lCore >>§r " . "use /duels help");
					break;
			}
		} else $sender->sendMessage("Only In-Game Command");
	}
}