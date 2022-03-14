<?php

namespace SCore\Events;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\event\entity\EntityDamageEvent;
use SCore\Core;

class DistanceListener implements Listener {

	public $core;
	public static $dist_arr = array();
	public static $combo_arr = array();

	public function __construct() {
		$this->core = Core::getInstance();
	}

	public function onDamage(EntityDamageEvent $event) {
		global $dist_arr, $combo_arr;
		$player = $event->getEntity();
		$cause = $event->getCause();
		if ($cause == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
			$damager = $event->getDamager();
			if ($player instanceof Player && $damager instanceof Player) {
				$playerlvl = $player->getLevel();
				$damagerlvl = $damager->getLevel();
				$damagername = $damager->getName();
				$playername = $player->getName();
				if ($damagerlvl == $playerlvl) {
					$playerpos = $player->getPosition() ?? new Vector3(0, 0, 0);
					$damagerpos = $damager->getPosition() ?? new Vector3(0, 0, 0);
					$distance = round($damagerpos->distance($playerpos) / 1.5, 2);
					$dist_arr["1"] = 1;
					$combo_arr["1"] = 1;
					$dist_arr[$damagername] = "§a" . $distance . "§r";
					$dist_arr[$playername] = "§4" . $distance . "§r";
					if (!array_key_exists($damagername, $combo_arr)) $combo_arr[$damagername] = 0;
					$combo_arr[$damagername] = $combo_arr[$damagername] + 1;
					$combo_arr[$playername] = 0;
				}
			}
		}
	}

	public function getAtkDistance($player) : string {
		global $dist_arr;
		if ($player instanceof Player) {
			$name = $player->getName();
			if (isset($dist_arr)) {
				if (array_key_exists($name, $dist_arr)) {
					return $dist_arr[$name];
				} else return "§a0";
			}
		}
		return "§a0";
	}
	
	public function getCmb($player) : int {
		global $combo_arr;
		if ($player instanceof Player) {
			$name = $player->getName();
			if (isset($combo_arr)) {
				if (array_key_exists($name, $combo_arr)) {
					return $combo_arr[$name];
				} else return 0;
			}
		}
		return 0;
	}
}