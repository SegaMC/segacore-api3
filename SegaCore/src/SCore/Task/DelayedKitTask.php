<?php

namespace SCore\Task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use SCore\FFA\FFAKits;


class DelayedKitTask extends Task {

	public $plugin;
	public $player;

	public function __construct($plugin, $player) {
		$this->plugin = $plugin;
		$this->player = $player;
	}

	public function onRun(int $currentTick) {
		$player = $this->player;
		$this->plugin->Kit($player);
	}
}