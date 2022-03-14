<?php

namespace SCore\Entity\Leaderboard;

use SCore\Core;
use pocketmine\utils\Config;
use pocketmine\entity\Monster;
use pocketmine\entity\EntityIds;

class Deaths extends Monster {

	const NETWORK_ID = EntityIds::CHICKEN;

	public $height = 0.7;
	public $width = 0.4;
	public $gravity = 0;

	public function getName(): string {
		return "Deaths";
	}

	public function initEntity(): void {
		parent::initEntity();
		$this->setImmobile(true);
		$this->setHealth(1);
		$this->setNameTagAlwaysVisible(true);
		$this->setScale(0.001);
	}

	public function onUpdate(int $currentTick): bool {
		$data = new Config(Core::getInstance()->getDataFolder() . "data/score/Deaths.yml", Config::YAML);
		$Deaths = $data->getAll();
		arsort($Deaths);
		$top = 1;
		$nametag = "§l§c>> TOP-DEATHS LEADERBOARD <<\n \n";
		foreach ($Deaths as $name => $value) {
			if ($top > 10)break;
			if ($top <= 3) {
				$nametag .= "§c§l»§r§l {$name} §c- §r{$value}§c§l«§r\n";
			} else {
				$nametag .= "§c[{$top}]§r {$name} §c- §7{$value}§r\n";
			}
			$top++;
		}
		$this->setNameTag($nametag);
		return parent::onUpdate($currentTick);
	}
}