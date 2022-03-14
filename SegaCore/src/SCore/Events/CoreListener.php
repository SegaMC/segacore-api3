<?php

namespace SCore\Events;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\event\player\ {
	PlayerInteractEvent,
	PlayerDeathEvent,
	PlayerLoginEvent,
	PlayerRespawnEvent,
	PlayerJoinEvent,
	PlayerDropItemEvent,
	PlayerExhaustEvent
};

use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\item\Potion;
use pocketmine\utils\Color;
use pocketmine\utils\Config;

use SCore\FFA\ {
	Gapple,
	Nodebuff,
	Soup,
	Combo,
	Build
};
use SCore\Task\DelayedItemTask;
use SCore\Duels\Duels;
use SCore\ScoreSystem;
use SCore\Menus;
use SCore\Core;

class CoreListener implements Listener {

	public function onPlayerJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$player->getInventory()->clearAll();
		Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedItemTask($this, $player), 10);
		
		$eloval = new Config(Core::getInstance()->getDataFolder() . "data/score/Elo.yml", Config::YAML);
		$score = new ScoreSystem();
		$score->setDefaultElo($player);

		$pdata = new Config(Core::getInstance()->getDataFolder() . "data/players/" . $player->getLowerCaseName() . ".yml", Config::YAML, array(
			"Settings" => ["scorehud" => true, "arena_spawn" => false, "cps_popup" => true, "distance_popup" => true, "combo_popup" => false]
		));
		$pdata->save();


	}

	public function onPlayerInteract(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$item = $event->getItem();

		//FFA
		if ($item->getCustomName() == "§cPractice") {
			$Menu = new Menus();
			$Menu->FFAMenu($player);
		}

		//Duels
		if ($item->getCustomName() == "§cDuels") {
			$menu = new Menus();
			$menu->DuelsMenu($player);
		}

		//Hub_Menu
		if ($item->getCustomName() == "§cMenu") {
			$Menu = new Menus();
			$Menu->HubMenu($player);
		}



		//AutoPot
		$meta = $item->getDamage();
		$id = $item->getId();
		if ($id === Item::SPLASH_POTION) {
			$effects = Potion::getPotionEffectsById($meta);
			foreach ($effects as $effect) $player->addEffect($effect);
			$event->setCancelled(true);
			$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
		}
		if (empty($effects)) {
			$colors = [
				new Color(0x38, 0x5d, 0xc6) //Default colour for splash water bottle and similar with no effects.
			];
			$hasEffects = false;
		} else {
			$colors = [];
			foreach ($effects as $effect) {
				$level = $effect->getEffectLevel();
				for ($j = 0; $j < $level; ++$j) {
					$colors[] = $effect->getColor();
				}
			}
		}
		if ($id === Item::SPLASH_POTION) {
			$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_GLASS);
			$player->getLevel()->broadcastLevelEvent($player, LevelEventPacket::EVENT_PARTICLE_SPLASH, Color::mix(...$colors)->toARGB());
		}

		//SoupFFA
		if ($id == 282) {
			if ($player->getLevel()->getName() == Core::getInstance()->config->get("FFAWorlds")["Soup"]) {
				if ($player->getHealth() < 19.8) {
					$player->setHealth($player->getHealth() + 5);
					$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
				}
			}
		}
	}


	public function onRespawn(PlayerRespawnEvent $event) {
		$player = $event->getPlayer();
		$player->getInventory()->clearAll();
		Core::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedItemTask($this, $player), 10);
	}

	public function onDeath(PlayerDeathEvent $event) {
		if ($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
			$victim = $event->getPlayer();
			$killer = $victim->getLastDamageCause()->getDamager();
			if (!$killer instanceof Player) return;
			$level = $killer->getLevel()->getName();
			if ($victim instanceof Player) {
				$killer->getLevel()->broadcastLevelEvent($killer, LevelEventPacket::EVENT_SOUND_ORB, (int) 1);
				$killername = $killer->getName();
				$victimname = $victim->getName();
				$killerhp = round($killer->getHealth(), 3);
				$victimhp = round($victim->getHealth(), 3);
				$killer->setHealth(20);
				//kill message
				$arraykillmsg = Core::getInstance()->config->get("killMessage");
				$key = array_rand($arraykillmsg);
				$killmsg = $arraykillmsg[$key];
				$killmsg = str_replace("{victim}", $victimname, $killmsg);
				$killmsg = str_replace("{killer}", $killername, $killmsg);
				$killmsg = str_replace("{victim_hp}", $victimhp, $killmsg);
				$killmsg = str_replace("{killer_hp}", $killerhp, $killmsg);
				$duelArenas = Duels::getArenas();
				$nodekits = [];
				foreach ($duelArenas as $arena) {
					if (Duels::getKit($arena) == "Nodebuff") {
						$nodekits[] = $arena;
					}
				}
				if ($level == Core::getInstance()->config->get("FFAWorlds")["Nodebuff"] or in_array($level, $nodekits)) {
					static $vpots = 0;
					static $kpots = 0;
					foreach ($victim->getInventory()->getContents() as $item) {
						if ($item->getId() === 248 and $item->getDamage() === 34) {
							$vpots++;
						}
					}
					foreach ($killer->getInventory()->getContents() as $item) {
						if ($item->getId() === 248 and $item->getDamage() === 34) {
							$kpots++;
						}
					}
					$killmsg = str_replace("{victim_pots}", $vpots, $killmsg);
					$killmsg = str_replace("{killer_pots}", $kpots, $killmsg);
					var_dump($vpots);
				} else {
					$killmsg = str_replace("{victim_pots}", "", $killmsg);
					$killmsg = str_replace("{killer_pots}", "", $killmsg);
				}
				$event->setDeathMessage($killmsg);
				//set kdr & killstreak
				$function = new ScoreSystem;
				$function->addKP($killer);
				$function->addDP($victim);
				$function->setKDR($victim);
				$function->setKDR($killer);
				$function->setKillStreak($victim, $killer);
				//rekit
				if ($level == Core::getInstance()->config->get("FFAWorlds")["Combo"]) {
					$kit = new Combo();
					$kit->Kit($killer);
				} elseif ($level == Core::getInstance()->config->get("FFAWorlds")["Nodebuff"]) {
					$kit = new Nodebuff();
					$kit->Kit($killer);
				} elseif ($level == Core::getInstance()->config->get("FFAWorlds")["Gapple"]) {
					$kit = new Gapple();
					$kit->Kit($killer);
				} elseif ($level == Core::getInstance()->config->get("FFAWorlds")["Soup"]) {
					$kit = new Soup();
					$kit->Kit($killer);
				} elseif ($level == Core::getInstance()->config->get("FFAWorlds")["Build"]) {
					$kit = new Build();
					$kit->Kit($killer);
				}
			}
		}
	}

	public function onDamage(EntityDamageEvent $event) : void {
		$entity = $event->getEntity();
		if (!$entity instanceof Player) return;
		if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
			if (Core::getInstance()->config->get("antiVoid") == false) return;
			$entity->teleport(Core::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
			$event->setCancelled();
		}
		if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
			if (Core::getInstance()->config->get("fallDamage") == false) {
				$event->setCancelled();
			}
		}
	}

	public function onDropItem(PlayerDropItemEvent $event) {
		if (Core::getInstance()->config->get("itemDrop") == false) $event->setCancelled(true);
	}

	public function Hunger(PlayerExhaustEvent $event) {
		if (Core::getInstance()->config->get("Hunger") == false) $event->setCancelled(true);
	}

	public function onPlayerLogin(PlayerLoginEvent $event) {
		if (Core::getInstance()->config->get("alwaysSpawn") == true) {
			$event->getPlayer()->teleport(Core::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
		}
	}
}