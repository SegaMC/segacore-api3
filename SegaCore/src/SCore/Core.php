<?php

namespace SCore;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase as PB;
use pocketmine\utils\Config;
use pocketmine\scheduler\ClosureTask;
use pocketmine\entity\Entity;

use SCore\Entity\Leaderboard\ {
	Kills,
	Deaths,
	Kdr,
	KillStreak,
	Elo
};
use SCore\Events\ {
	CoreListener,
	BuildListener,
	PearlCooldown,
	CustomEvent,
	DistanceListener,
	DuelListener
};
use SCore\Commands\ {
	ArenaStatusCmd,
	LeaderboardCmd,
	CustomItemCmd,
    RanksCmd,
	SpawnCmd,
	ChangelogCmd,
	DuelCmd
};

use SCore\Task\ActionBarTask;
use SCore\Task\DuelTask;
use SCore\Duels\Duels;

use SCore\Duels\Arena;

class Core extends PB implements Listener {

	public static $instance;
	public $config;

	public function onEnable() {
		$server = $this->getServer();
		$server->getLogger()->info("§aEnabled Polux Core");
		self::$instance = $this;

		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		
		if (!file_exists($this->getDataFolder() . "duels/arenas")) mkdir($this->getDataFolder() . "duels/arenas", 755, true);
		if (!file_exists($this->getDataFolder() . "duels/backup")) mkdir($this->getDataFolder() . "duels/backup", 755, true);
		new Config($this->getDataFolder() . "duels/arena.yml", Config::YAML);
		if (!file_exists($this->getDataFolder() . "data/players")) mkdir($this->getDataFolder() . "data/players", 755, true);
		if (!file_exists($this->getDataFolder() . "data/score")) mkdir($this->getDataFolder() . "data/score", 755, true);
		new Config($this->getDataFolder() . "data/score/Kdr.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/Kills.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/Deaths.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/KillStreak.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/Elo.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/Unranked.yml", Config::YAML);
		new Config($this->getDataFolder() . "data/score/Ranked.yml", Config::YAML);
		
		Duels::loadWorlds();
		
		$server->getPluginManager()->registerEvents($this, $this);
		$server->getPluginManager()->registerEvents(new CoreListener($this), $this);
		$server->getPluginManager()->registerEvents(new BuildListener($this), $this);
		$server->getPluginManager()->registerEvents(new CustomEvent($this), $this);
		$server->getPluginManager()->registerEvents(new PearlCooldown($this), $this);
		$server->getPluginManager()->registerEvents(new DistanceListener($this), $this);
		$server->getPluginManager()->registerEvents(new DuelListener($this), $this);
		
		$this->getScheduler()->scheduleRepeatingTask(new ActionBarTask($this), 1);
		$duel = new Duels();
		$duel->startTask();

		$this->getLogger()->info("§aLoading FFA WorlgetCommandMapds");
		$server->loadLevel($this->config->get("FFAWorlds")["Fist"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Resistance"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Resistance2"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Sumo"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Build"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Gapple"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Nodebuff"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Soup"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Combo"]);
		$server->loadLevel($this->config->get("FFAWorlds")["Knockback"]);

		$server->getCommandMap()->register("leaderboard", new LeaderboardCmd($this, "leaderboard"));
		$server->getCommandMap()->register("customitem", new CustomItemCmd($this, "customitem"));
		$server->getCommandMap()->register("ranks", new RanksCmd($this, "ranks"));
		$server->getCommandMap()->register("spawn", new SpawnCmd($this, "spawn"));
		$server->getCommandMap()->register("changelogs", new ChangelogCmd($this, "changelogs"));
		$server->getCommandMap()->register("duels", new DuelCmd($this));

		Entity::registerEntity(Kills::class, true);
		Entity::registerEntity(Deaths::class, true);
		Entity::registerEntity(Kdr::class, true);
		Entity::registerEntity(KillStreak::class, true);
		Entity::registerEntity(Elo::class, true);
	}
	
	public static function getInstance() {
		return self::$instance;
	}
}