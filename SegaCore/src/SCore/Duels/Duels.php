<?php

namespace SCore\Duels;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use SCore\Task\DuelTask;
use SCore\Duels\DuelKits;
use SCore\Core;

class Duels {

	public static $instance;
	public static $configMode = [];
	private static $playing = [];
	public static $kits = [
		"Fist",
		"Gapple",
		"Classic",
		"Boxing",
		"Combo",
		"Nodebuff",
		"BuildUHC",
		"Soup",
		"Sumo"
	];


	public function startTask() {
		$plugin = $this;
		Core::getInstance()->getScheduler()->scheduleRepeatingTask(new DuelTask($plugin), 20);
	}


	public static function availKits() : array {
		return self::$kits;
	}


	public static function isAvailKit($kit) {
		$kits = self::$kits;
		if (in_array($kit, $kits)) {
			return true;
		} else false;
	}


	public static function isConfigMode(Player $player) {
		if (isset(self::$configMode[$player->getName()])) {
			return true;
		}
		return false;
	}


	public static function setConfigMode(Player $player, string $arenaName) {
		if (!isset(self::$configMode[$player->getName()])) {
			self::$configMode[$player->getName()] = $arenaName;
		}
	}


	public static function unsetConfigMode(Player $player) {
		if (isset(self::$configMode[$player->getName()])) {
			unset(self::$configMode[$player->getName()]);
		}
	}


	public static function arenaExist(string $arenaName) {
		if (file_exists(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arenaName" . ".yml")) {
			return true;
		} else return false;
	}

	//setup
	public static function createArena(string $name, string $kit, bool $isRanked) {
		$list = new Config(Core::getInstance()->getDataFolder() . "duels/arena.yml", Config::YAML);
		$arenas = $list->get("arenas");
		$arenas[] = $name;
		$list->set("arenas", $arenas);
		$list->save();
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "{$name}.yml", Config::YAML);
		$file->set($name, [
			"Name" => $name, //var string Arena Name/World Name
			"Kit" => $kit, //var string Nodebuff, Fist, Gapple
			"Status" => "Offline", //var string Offline, Running, Waiting
			"IsRanked" => $isRanked, //var bool true, false
			"GameTime" => 300, //var int seconds
			"Spawns" => [] //var array sapawn points (WaitPoint, Player1, Player2)
		]);
		$file->save();
	}


	public static function setSpawn(Player $player, int $slot) {
		$arena = self::$configMode[$player->getName()];
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		$xyz = [$player->getX(),
			$player->getY(),
			$player->getZ()];
		$arenaData["Spawns"]["spawn-{$slot}"] = $xyz;
		$file->set($arena, $arenaData);
		$file->save();
	}


	public static function setWaitPoint(Player $player) {
		$arena = self::$configMode[$player->getName()];
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		$xyz = [$player->getX(),
			$player->getY(),
			$player->getZ()];
		$arenaData["Spawns"]["waitPoint"] = $xyz;
		$file->set($arena, $arenaData);
		$file->save();
	}


	public static function setStatus($arena, string $status) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		$arenaData["Status"] = $status;
		$file->set($arena, $arenaData);
		$file->save();
	}

	public static function configDone($player) {
		$arena = self::$configMode[$player->getName()];
		self::setStatus($arena, "Ready");
		if (isset(self::$configMode[$player->getName()])) {
			unset(self::$configMode[$player->getName()]);
		}
	}


	public static function getTime($arena) : int {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["GameTime"];
	}


	public static function getStatus($arena) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["Status"];
	}


	public static function getWaitPoint(string $arena) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["Spawns"]["waitPoint"];
	}


	public static function getSpawnPoint(string $arena, int $slot) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["Spawns"]["spawn-{$slot}"];
	}


	public static function getKit(string $arena) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["Kit"];
	}


	public static function isRanked(string $arena) : bool {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["IsRanked"];
	}


	public static function getArenaName(string $arena) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		return $arenaData["spawns"]["lobby"];
	}

	public function getPlayerArena(Player $player) {
		foreach (self::getArenas() as $arena) {
			foreach ($this->getPlayers($arena) as $target) {
				if ($target->getName() === $player->getName()) {
					return $arena;
				}
			}
		}
		return null;
	}

	public static function getArenas() : array {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arena.yml", Config::YAML);
		$arenas = [];
		if ($file->get("arenas") != null) {
			$arenas = $file->get("arenas");
		} else return [];
		return $arenas;
	}

	public static function loadWorlds() {
		$arenas = self::getArenas();
		foreach ($arenas as $arena) {
			Core::getInstance()->getServer()->loadLevel($arena);
			self::setStatus($arena, "Ready");
		}
	}


	public static function copyMap($levelname) {
		$path = realpath(Server::getInstance()->getDataPath() . 'worlds/' . $levelname);
		$zip = new \ZipArchive;
		@mkdir(Core::getInstance()->getDataFolder() . 'duels/backup', 0755);
		$zip->open(Core::getInstance()->getDataFolder() . 'duels/backup' . $levelname . '.zip', $zip::CREATE | $zip::OVERWRITE);
		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($path),
			\RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ($files as $datos) {
			if (!$datos->isDir()) {
				$relativePath = $levelname . '/' . substr($datos, strlen($path) + 1);
				$zip->addFile($datos, $relativePath);
			}
		}
		$zip->close();
		unset($zip, $path, $files);
	}

	public static function pasteMap($levelname) {
		if (Server::getInstance()->isLevelLoaded($levelname)) {
			Server::getInstance()->unloadLevel(Server::getInstance()->getLevelByName($levelname));
		}
		$zip = new \ZipArchive;
		$zip->open(Core::getInstance()->getDataFolder() . 'duels/backup' . $levelname . '.zip');
		$zip->extractTo(Server::getInstance()->getDataPath() . 'worlds');
		$zip->close();
		unset($zip);
		Server::getInstance()->loadLevel($levelname);
		return true;
	}


	public function isPlaying(Player $player) {
		if (isset(self::$playing[$player->getName()])) {
			return true;
		}
		return false;
	}


	public function getPlayers(string $arena) {
		$players = [];
		$level = Core::getInstance()->getServer()->getLevelByName($arena);
		foreach ($level->getPlayers() as $player) {
			if ($this->isPlaying($player)) {
				$players[] = $player;
			}

		}
		return $players;
	}


	public function joinToArena(Player $player, string $arena) {
		$wPoint = self::getWaitPoint($arena);
		if (count($this->getPlayers($arena)) >= 2) {
			$player->sendMessage("§cThe game you tried to join is Full");
			return false;
		}
		if (self::getStatus($arena) != "Ready") {
			$player->sendMessage("§cThe game you tried to join is not Available, try again later");
			return false;
		}
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$player->setFood($player->getMaxFood());
		$player->setHealth($player->getMaxHealth());
		$player->teleport(Server::getInstance()->getLevelByName($arena)->getSafeSpawn());
		$player->setGamemode(Player::ADVENTURE);
		self::$playing[$player->getName()] = $player;
		echo "player added\n";
	}


	public function quitGame(Player $player) {
		if (isset($this->playing[$player->getName()])) {
			$level = $player->getLevel()->getName();
			$arenas = self::getArenas();
			if (in_array($level, $arenas)) {
				if (self::getStatus($level) == "Running") {
					$score = new ScoreSystem();
					$score->removeElo($player, 25);
					$player->sendMessage("§4 You lost the Match!");
				}
			}
			unset($this->playing[$player->getName()]);
		}
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$player->setGamemode(Player::SURVIVAL);
		$player->setHealth($player->getMaxHealth());

	}

	public function giveKit(Player $player, $arena) {
		$file = new Config(Core::getInstance()->getDataFolder() . "duels/arenas/" . "$arena" . ".yml", Config::YAML);
		$arenaData = $file->get($arena);
		$kit = $arenaData["Kit"];
		$class = new DuelKits();
		switch ($kit) {
			case "BuildUHC":
				$class->BuildUHC($player);
				break;
			case "Soup":
				$class->Soup($player);
				break;
			case "Fist":
				$class->Fist($player);
				break;
			case "Boxing":
				$class->Boxing($player);
				break;
			case "Classic":
				$class->Classic($player);
				break;
			case "Gapple":
				$class->Gapple($player);
				break;
			case "Nodebuff":
				$class->Nodebuff($player);
				break;
			case "Combo":
				$class->Combo($player);
				break;
			case "Sumo":
				$class->Sumo($player);
				break;
			default:
				$player->sendMessage("Invalid Kit given");
				break;
		}
	}
}