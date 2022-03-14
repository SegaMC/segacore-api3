<?php

namespace SCore;

use pocketmine\Player;
use pocketmine\utils\Config;
use SCore\Core;

class ScoreSystem {

	public function addDP(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Deaths.yml", Config::YAML);
		$config->set($name, $config->get($name) + 1);
		$config->save();
	}
	
	public function getDP(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Deaths.yml", Config::YAML);
		$value = $config->get($name);
		if (!is_int($name)) {
			return $value;
		} else return 0;
	}

	public function addKP(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Kills.yml", Config::YAML);
		$config->set($name, $config->get($name) + 1);
		$config->save();
	}
	
	public function getKP(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Kills.yml", Config::YAML);
		$value = $config->get($name);
		if (!is_int($name)) {
			return $value;
		} else return 0;
	}


	public function setKDR(Player $player) {
		$name = $player->getName();
		$kdr = new Config(Core::getInstance()->getDataFolder() . "data/score/Kdr.yml", Config::YAML);
		$kills = new Config(Core::getInstance()->getDataFolder() . "data/score/Kills.yml", Config::YAML);
		$deaths = new Config(Core::getInstance()->getDataFolder() . "data/score/Deaths.yml", Config::YAML);
		if ($deaths->get($name) == 0) {
			$kdr->set($name, $kills->get($name));
		} elseif ($kills->get($name) == 0) {
			$kdr->set($name, 0);
		} elseif ($deaths->get($name) != 0) {
			$ratio = $kills->get($name) / $deaths->get($name);
			$ratio = round($ratio, 3);
			$kdr->set($name, $ratio);
		}
		$kdr->save();
	}
	
	public function getKDR(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Kdr.yml", Config::YAML);
		$value = $config->get($name);
		if (!is_int($name)) {
			return $value;
		} else return 0;
	}

	
	public function setKillStreak(Player $victim,Player $killer) {
		$killername = $killer->getName();
		$victimname = $victim->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/KillStreak.yml", Config::YAML);
		$config->set($killername, $config->get($killername) + 1);
		$config->set($victimname, 0);
		$config->save();
	}
	
	public function getKillStreak(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/KillStreak.yml", Config::YAML);
		$value = $config->get($name);
		if (!is_int($name)) {
			return $value;
		} else return 0;
	}
	
	public function setDefaultElo(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Elo.yml", Config::YAML, array());
		$list = $config->getAll();
		if (!in_array($name, $list)) {
			$config->set($name, 1000);
			$config->save();
		}
	}
	
	public function addElo(Player $player, int $value) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Elo.yml", Config::YAML);
		$config->set($name, $config->get($name) + $value);
		$config->save();
	}
	
	public function removeElo(Player $player, int $value) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Elo.yml", Config::YAML);
		$config->set($name, $config->get($name) - $value);
		$config->save();
	}
	
	public function getElo(Player $player) {
		$name = $player->getName();
		$config = new Config(Core::getInstance()->getDataFolder() . "data/score/Elo.yml", Config::YAML);
		$value = $config->get($name);
		if (!is_int($name)) {
			return $value;
		} else return 0;
	}
}