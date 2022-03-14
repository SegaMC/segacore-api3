<?php

namespace SCore;

use pocketmine\Player;
use pocketmine\utils\Config;

use SCore\Core;
use SCore\API\SimpleForm;
use SCore\API\CustomForm;
use SCore\Duels\Duels;
use SCore\ScoreSystem;
use SCore\Misc\TimePlayed;

use SCore\FFA\ {
	Fist,
	Resistance,
	Resistance2,
	Sumo,
	Gapple,
	Nodebuff,
	Combo,
	Soup,
	Build,
	Knockback
};


class Menus {

	public function FFAMenu($player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
			switch ($data) {
				case "fist":
					$game = new Fist();
					$game->Fist($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Fist");                
					break;
				case "resistance":
					$game = new Resistance();
					$game->Resistance($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Resistance");                
					break;
				case "resistance2":
					$game = new Resistance2();
					$game->Resistance2($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Resistance2");                
					break;
				case "sumo":
					$game = new Sumo();
					$game->Sumo($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Sumo");                
					break;
				case "gapple":
					$game = new Gapple();
					$game->Gapple($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Gapple");                
					break;
				case "nodebuff":
					$game = new Nodebuff();
					$game->Nodebuff($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Nodebuff");                
					break;
				case "combo":
					$game = new Combo();
					$game->Combo($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Combo");                
					break;
				case "soup":
					$game = new Soup();
					$game->Soup($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Soup");                
					break;
				case "build":
					$game = new Build();
					$game->Build($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Build");                       
					break;
				case "knockback":
					$game = new Knockback();
					$game->Knockback($player);
                    $player->sendMessage("§cSega §r§8» §7You are now playing at Knockback");                
					break;
				case "offline":
					$player->sendMessage("§4This Arena is Currently Oflline");
					break;
			}
		});
		$config = Core::getInstance()->config;
		$fistWorld = $config->get("FFAWorlds")["Fist"];
		$resistanceWorld = $config->get("FFAWorlds")["Resistance"];
		$resistance2World = $config->get("FFAWorlds")["Resistance2"];
		$sumoWorld = $config->get("FFAWorlds")["Sumo"];
		$gappleWorld = $config->get("FFAWorlds")["Gapple"];
		$nodebuffWorld = $config->get("FFAWorlds")["Nodebuff"];
		$comboWorld = $config->get("FFAWorlds")["Combo"];
		$soupWorld = $config->get("FFAWorlds")["Soup"];
		$buildWorld = $config->get("FFAWorlds")["Build"];
		$knockbackWorld = $config->get("FFAWorlds")["Knockback"];

		$server = Core::getInstance()->getServer();
		$fist = $server->getLevelByName($fistWorld);
		$resistance = $server->getLevelByName($resistanceWorld);
		$resistance2 = $server->getLevelByName($resistance2World);
		$sumo = $server->getLevelByName($sumoWorld);
		$gapple = $server->getLevelByName($gappleWorld);
		$nodebuff = $server->getLevelByName($nodebuffWorld);
		$combo = $server->getLevelByName($comboWorld);
		$soup = $server->getLevelByName($soupWorld);
		$build = $server->getLevelByName($buildWorld);
		$knockback = $server->getLevelByName($knockbackWorld);

		if (!$server->isLevelLoaded($fistWorld)) {
			$pfist = "§4Offline";
			$c0 = "offline";
		} else {
			$pfist = "Playing: " . count($fist->getPlayers());
			$c0 = "fist";
		}
		if (!$server->isLevelLoaded($resistanceWorld)) {
			$presistance = "§4Offline";
			$c1 = "offline";
		} else {
			$presistance = "Playing: " . count($resistance->getPlayers());
			$c1 = "resistance";
		}
		if (!$server->isLevelLoaded($resistance2World)) {
			$presistance2 = "§4Offline";
			$c2 = "offline";
		} else {
			$presistance2 = "Playing: " . count($resistance2->getPlayers());
			$c2 = "resistance2";
		}
		if (!$server->isLevelLoaded($sumoWorld)) {
			$psumo = "§4Offline";
			$c3 = "offline";
		} else {
			$psumo = "Playing: " . count($sumo->getPlayers());
			$c3 = "sumo";
		}
		if (!$server->isLevelLoaded($gappleWorld)) {
			$pgapple = "§4Offline";
			$c4 = "offline";
		} else {
			$pgapple = "Playing: " . count($gapple->getPlayers());
			$c4 = "gapple";
		}
		if (!$server->isLevelLoaded($nodebuffWorld)) {
			$pnodebuff = "§4Offline";
			$c5 = "offline";
		} else {
			$pnodebuff = "Playing: " . count($nodebuff->getPlayers());
			$c5 = "nodebuff";
		}
		if (!$server->isLevelLoaded($comboWorld)) {
			$pcombo = "§4Offline";
			$c6 = "offline";
		} else {
			$pcombo = "Playing: " . count($combo->getPlayers());
			$c6 = "combo";
		}
		if (!$server->isLevelLoaded($soupWorld)) {
			$psoup = "§4Offline";
			$c7 = "offline";
		} else {
			$psoup = "Playing: " . count($soup->getPlayers());
			$c7 = "soup";
		}
		if (!$server->isLevelLoaded($buildWorld)) {
			$pbuild = "§4Offline";
			$c8 = "offline";
		} else {
			$pbuild = "Playing: " . count($build->getPlayers());
			$c8 = "build";
		}
		if (!$server->isLevelLoaded($knockbackWorld)) {
			$pknockback = "§4Offline";
			$c9 = "offline";
		} else {
			$pknockback = "Playing: " . count($knockback->getPlayers());
			$c9 = "knockback";
		}
		$menu->setTitle("§c§lFFA");
		$menu->addButton("§0Build\n§r§f $pbuild", 0, "textures/others/build", $c8);
		$menu->addButton("§0Fist\n§r§f $pfist", 0, "textures/other/fist", $c0);
		$menu->addButton("§0Resistance\n§r§f $presistance", 0, "textures/other/resis", $c1);
//$menu->addButton("§0Resistance 2\n§r§f $presistance2", 0, "textures/ui/resistance_effect", $c2);
		$menu->addButton("§0Sumo\n§r§f $psumo", 0, "textures/other/sumo", $c3);
		$menu->addButton("§0Gapple\n§r§f $pgapple", 0, "textures/other/gapple", $c4);
		$menu->addButton("§0Nodebuff\n§r§f $pnodebuff", 0, "textures/other/node", $c5);
		$menu->addButton("§0Combo\n§r§f $pcombo", 0, "textures/items/fish_pufferfish_raw", $c6);
		$menu->addButton("§0Soup\n§r§f $psoup", 0, "textures/items/mushroom_stew", $c7);
		$menu->addButton("§0Knockback\n§r§f $pknockback", 0, "textures/items/stick", $c9);
		$menu->sendToPlayer($player);
	}


	public function HubMenu($player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
			switch ($data) {
				case "settings":
					$this->SettingsTab($player);
					break;
				case "stats":
					$this->PlayerStats($player);
					break;
                case "cosmetics":
                    $player->getServer()->dispatchCommand($player, "cape");
                    break;
                case "report":
                    $player->getServer()->dispatchCommand($player, "report");
                    break;

			}
		});
		$menu->setTitle("§cMenu");
		$menu->addButton("Settings", 0, "textures/ui/slow_falling_effect", "settings");
		$menu->addButton("Stats", 0, "textures/ui/strength_effect", "stats");
        $menu->addButton("Cosmetics", 0, "textures/items/leather", "cosmetics");
        $menu->addButton("Report", 0, "textures/items/book_writable", "report");
		$menu->sendToPlayer($player);
	}

	public function SettingsTab($player) {
		$menu = new CustomForm(function(Player $player, array $data = null) {
			if ($data === null) return true;
			$setting = new Config(Core::getInstance()->getDataFolder() . "data/players/" . $player->getLowerCaseName() . ".yml", Config::YAML);

			$setting->setNested("Settings.cps_popup", $data["cps_display"]);
			$setting->setNested("Settings.distance_popup", $data["distance_display"]);
			$setting->setNested("Settings.combo_popup", $data["combo_display"]);

			$setting->save();
		});
		$setting = new Config(Core::getInstance()->getDataFolder() . "data/players/" . $player->getLowerCaseName() . ".yml",
			Config::YAML);
		$menu->setTitle("§cSettings");
		
		$menu->addToggle("Cps Display",
			$setting->get("Settings")["cps_popup"],
			"cps_display");
		$menu->addToggle("Reach/Distance Display",
			$setting->get("Settings")["distance_popup"],
			"distance_display");
		$menu->addToggle("Combo Display",
			$setting->get("Settings")["combo_popup"],
			"combo_display");
		$menu->addToggle("ScoreHud",
			true,
			"scorehud");
		$menu->sendToPlayer($player);
	}

	
	public function PlayerStats($player) {
				$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
		});
		$score = new ScoreSystem();
		
		$menu->setTitle("§c" . $player->getName() . "'s§r§ Stats");
		$menu->setContent(
			"Total Time Played: " . TimePlayed::getPlayTime($player) . "\n\n" .
			
			"Elo: " . $score->getElo($player) . "\n" .
			"Kills: " . $score->getKP($player) . "\n" . 
			"Deaths: " . $score->getDP($player) . "\n" . 
			"KDR: " . $score->getKDR($player) . "\n");
			
		$menu->sendToPlayer($player);
	}


	public function ChangelogsTab($player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
		});
		$logs = Core::getInstance()->config->get("Changelogs");
		$menu->setTitle("§cChangelogs");
		$menu->setContent($logs);
		$menu->sendToPlayer($player);
	}


	public function RanksTab($player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
		});
		$menu->setTitle("§cRanks");
		$menu->setContent("test");
		$menu->sendToPlayer($player);
	}


	public function DiscordTab($player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
		});
		$menu->setTitle("§cDiscord");
		$menu->setContent("§adiscord.gg/example");
		$menu->sendToPlayer($player);
	}
	
	public function DuelsMenu(Player $player) {
		$menu = new SimpleForm(function(Player $player, $data = null) {
			if ($data === null) return true;
			switch($data) {
				case "Ranked":
					$this->DuelsRanked($player);
					break;
				case "Unranked":
					$this->DuelsUnRanked($player);
					break;
			}
		});
		$menu->setTitle("§cDuels");
		$menu->addButton("Ranked", 0, "textures/items/diamond_sword", "Ranked");
		$menu->addButton("Unranked", 0, "textures/items/iron_sword", "Unranked");
        $menu->addButton("§c« Back");
		$menu->sendToPlayer($player);
	}

	public function DuelsRanked(Player $player) {
		$menu = new SimpleForm(function (Player $player, $data = null) {
			if ($data === null) return true;
			$duels = new Duels();
			$arena = Duels::getArenas()[$data];
			$duels->joinToArena($player, $arena);
		});
		$duels = new Duels();
		$menu->setTitle("§cRanked Duels");
		$menu->setContent("");
		foreach (Duels::getArenas() as $arena) {
			if ($duels->isRanked($arena)) {
				$inQueue = count($duels->getPlayers($arena));
				$menu->addButton("§0$arena \n In-queue: $inQueue ");
			}
		}
		$menu->sendToPlayer($player);
	}
	
	public function DuelsUnRanked(Player $player) {
		$menu = new SimpleForm(function (Player $player, $data = null) {
			if ($data === null) return true;
			$duels = new Duels();
			$arena = Duels::getArenas()[$data];
			$duels->joinToArena($player, $arena);
		});
		$duels = new Duels();
		$menu->setTitle("§cUnRanked Duels");
		$menu->setContent("");
		foreach (Duels::getArenas() as $arena) {
			if (!$duels->isRanked($arena)) {
				$inQueue = count($duels->getPlayers($arena));
				$menu->addButton("§0$arena \n In-queue: $inQueue ");
			}
		}
		$menu->sendToPlayer($player);
	}
}