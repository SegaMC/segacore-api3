<?php

namespace SCore\Task;

use SCore\Core;
use SCore\Events\DistanceListener;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\scheduler\Task;

class ActionBarTask extends Task {

	public function __construct(Core $core) {
		$this->core = $core;
	}

	public function onRun(int $currentTick) {
		foreach ($this->core->getServer()->getOnlinePlayers() as $player) {
			
			$config = new Config(Core::getInstance()->getDataFolder() . "data/players/" . $player->getLowerCaseName() . ".yml", Config::YAML);
			if ($config->exists("Settings")) {
				$cps_popup = $config->get("Settings")["cps_popup"];
				$distance_popup = $config->get("Settings")["distance_popup"];
				$combo_popup = $config->get("Settings")["combo_popup"];
			} else return;
			$cpsfunc = Server::getInstance()->getPluginManager()->getPlugin("PreciseCpsCounter");
			$cps = $cpsfunc->getCps($player);
			$DistList = new DistanceListener;
			$distance = $DistList->getAtkDistance($player);
			$combo = $DistList->getCmb($player);
			$popup_msg = "";
			if ($cps_popup == true) $popup_msg = "| §cCPS:§r $cps | §f";
			if ($distance_popup == true) $popup_msg = $popup_msg . "| §cReach:§r $distance §r| §f";
			if ($combo_popup == true) $popup_msg = $popup_msg . "| §cCombo:§r $combo | §f";
			if ($popup_msg != "") $player->sendActionbarMessage($popup_msg);
		}
	}
}