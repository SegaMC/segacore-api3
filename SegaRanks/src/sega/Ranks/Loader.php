<?php

namespace sega\Ranks;

use sega\Ranks\Commands\RankCommand;
use sega\Ranks\Listeners\Chat;
use sega\Ranks\Listeners\Join;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase
{

    public static Config $rank;
    public static self $instance;
    public $permissions = [];

    public function onEnable()
    {
        self::$instance = $this;
        $this->initListeners();
        $this->initCommands();
        $this->initConfigs();
    }

    public function initListeners()
    {
        foreach ([
                     new Chat(),
                     new Join(),
                 ] as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }

    public function initCommands()
    {
        $this->getServer()->getCommandMap()->registerAll("Sega", [
            new RankCommand($this)
        ]);
    }

    public function initConfigs()
    {
        self::$rank = new Config($this->getDataFolder() . "rank.yml", Config::YAML);
        self::$rank->save();
    }

    public function addRank(Player $player, string $rank): void
    {
        self::$rank->set($player->getAddress(), $rank);
        self::$rank->save();
        self::$rank->reload();
        switch ($rank) {
            case "Owner":
                $player->setNameTag("[Owner] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Executive":
                $player->setNameTag("[Executive] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Manager":
                $player->setNameTag("[Manager] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Administrator":
                $player->setNameTag("[Administrator] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Moderator":
                $player->setNameTag("[Moderator] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Helper":
                $player->setNameTag("[Helper] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Builder":
                $player->setNameTag("[Builder] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Designer":
                $player->setNameTag("[Designer] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Famous":
                $player->setNameTag("[Famous] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Youtube":
                $player->setNameTag("[YouTube] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Media":
                $player->setNameTag("[Media] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Nitro":
                $player->setNameTag("[Nitro] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Duke":
                $player->setNameTag("[Duke] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Knight":
                $player->setNameTag("[Knight] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
            case "Player":
                $player->setNameTag("[Player] " . $player->getName());
                $perms = []; // to add a perm to a rank add it into the array like "sega.ban", "sega.kick"
                break;
        }
        $this->addPermissions($player, $perms);
    }

    public function addPermissions(Player $player, array $perms = []): void{
        if (!isset($this->permissions[$player->getNameTag()])) $this->permissions[$player->getName()] = [];
        $permissions = $this->permissions[$player->getName()];
        $permissions = array_merge($permissions, $perms);
        $this->permissions[$player->getName()] = $permissions;
        foreach ($permissions as $perm) {
            $attachement = $player->addAttachment($this);
            $attachement->setPermission($perm, true);
            $player->addAttachment($this, $perm);
        }
    }

    public function getRank(Player $player){
        return self::$rank->get($player->getAddress(), 'Player');
    }

    public static function getInstance(){
        return self::$instance;
    }
}