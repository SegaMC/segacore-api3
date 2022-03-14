<?php

namespace SCore\Misc;

use pocketmine\Player;

class TimePlayed {

	public static function getPlayTime(Player $player) {
		$time = ((int) floor(microtime(true) * 1000)) - $player->getFirstPlayed() ?? microtime();
		$seconds = floor($time % 60);
		$minutes = null;
		$hours = null;
		$days = null;
		if ($time >= 60) {
			$minutes = floor(($time % 3600) / 60);
			if ($time >= 3600) {
				$hours = floor(($time % (3600 * 24)) / 3600);
				if ($time >= 3600 * 24) {
					$days = floor($time / (3600 * 24));
				}
			}
		}
		$uptime = ($minutes !== null ?
			($hours !== null ?
				($days !== null ?
					"$days days "
					: "") . "$hours hours "
				: "") . "$minutes minutes "
			: "") . "$seconds seconds";
		return $uptime;
	}
}