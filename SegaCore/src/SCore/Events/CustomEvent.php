<?php

namespace SCore\Events;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\math\Vector3;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;

use SCore\Core;

class CustomEvent implements Listener {

	public function onShootBow(EntityShootBowEvent $event) {
		$bow = $event->getBow();
		$projectile = $event->getProjectile();
		if ($bow->getCustomName() == "§l§eBomb Bow§r") {
			if ($bow->getNamedTag()->hasTag("bow")) {
				if ($bow->getNamedTag()->getString("bow") == "bomb") {
					$projectile->namedtag->setString("projectile", "bomb");
				}
			}
		}
	}

	public function onProjectileHit(ProjectileHitBlockEvent $event) {
		$entity = $event->getEntity();
		$block = $event->getBlockHit();
		$level = $block->getLevel();
		if ($entity->namedtag->hasTag("projectile")) {
			if ($entity->namedtag->getString("projectile") == "bomb") {
				$entity->flagForDespawn();
				$explosion = new Explosion(new Position($entity->getX(), $entity->getY(), $entity->getZ(), $level), 3);
				//$explosion->explodeA();
				$explosion->explodeB();
			}
		}
	}

	public function onInteract(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$item = $event->getItem();
		$targetblock = $player->getTargetBlock(100);


		if ($item->getId() == Item::TNT) {
			if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) $event->setCancelled();
			if ($item->getCustomName() == "§l§eThrowable TNT§r") {
				if ($item->getNamedTag()->hasTag("tnt")) {
					if ($item->getNamedTag()->getString("tnt") == "throwable") {
						$nbt = new CompoundTag("", [
							"Pos" => new ListTag("Pos", [
								new DoubleTag("", $player->x),
								new DoubleTag("", $player->y + $player->getEyeHeight()),
								new DoubleTag("", $player->z)
							]),
							"Motion" => new ListTag("Motion", [
								new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
								new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
								new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
							]),
							"Rotation" => new ListTag("Rotation", [
								new FloatTag("", $player->yaw),
								new FloatTag("", $player->pitch)
							]),
						]);
						$force = 1;
						$tnt = Entity::createEntity("PrimedTNT", $player->getLevel(), $nbt, true);
						$tnt->setMotion($tnt->getMotion()->multiply($force));
						$tnt->spawnToAll($player);
						$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
					}
				}
			}
		}

		if ($item->getId() == Item::BLAZE_ROD) {
			$level = $player->getLevel();
			if ($item->getCustomName() == "§l§bLightning §eRod§r") {
				if ($item->getNamedTag()->hasTag("rod")) {
					if ($item->getNamedTag()->getString("rod") == "lightning") {
						
						$light = new AddActorPacket();
						$light->type = "minecraft:lightning_bolt";
						$light->entityRuntimeId = Entity::$entityCount++;
						$light->metadata = [];
						$light->motion = null;
						$light->position = new Vector3($targetblock->getX(), $targetblock->getY(), $targetblock->getZ());
						Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $light);
						
						$sound = new PlaySoundPacket();
						$sound->soundName = "ambient.weather.thunder";
						$sound->x = $targetblock->getX();
						$sound->y = $targetblock->getY();
						$sound->z = $targetblock->getZ();
						$sound->volume = 1;
						$sound->pitch = 1;
						Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $sound);
					}
				}
			}
		}
	}
}
	