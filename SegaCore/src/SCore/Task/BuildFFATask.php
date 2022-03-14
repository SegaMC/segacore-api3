<?php

namespace SCore\Task;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\scheduler\Task;


class BuildFFATask extends Task {
	
	public $main;
	public $loc;
	public $block;
	
	public function __construct($main, $loc, $block) {
		$this->core = $main;
		$this->loc = $loc;
		$this->block = $block;
	}
	
	 public function onRun(int $currentTick) {
	 	$this->block->getLevel()->setBlock($this->loc, Block::get(Block::AIR, 0, null));
	 	$this->block->getLevel()->addParticle(new DestroyBlockParticle($this->loc, $this->block));
	 }
}