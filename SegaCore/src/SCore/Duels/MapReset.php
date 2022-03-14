<?php

namespace SCore\Duels;

use pocketmine\Server;
use SCore\Core;

class MapReset {

	public static function extractZip($level) {
		$name = $level->getFolderName();
		if (Server::getInstance()->isLevelLoaded($name)) {
			Server::getInstance()->unloadLevel(Server::getInstance()->getLevelByName($name));
		}

		$zip = new \ZipArchive;
		$zip->open(Core::getInstance()->getDataFolder() . 'backup/' . $name . '.zip');
		$zip->extractTo(Server::getInstance()->getDataPath() . 'worlds');
		$zip->close();

		unset($zip);
		Server::getInstance()->loadLevel($name);
		return true;
	}
}