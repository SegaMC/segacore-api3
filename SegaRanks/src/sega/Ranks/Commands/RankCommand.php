<?php

namespace sega\Ranks\Commands;

use sega\Ranks\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class RankCommand extends Command
{

    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        parent::__construct("setrank", "Sega Rank Command", "/setrank (player)");
        $this->setPermission("sega.rank");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->testPermission($sender)) {
            if (isset($args[0]) AND $this->loader->getServer()->getPlayer($args[0]) instanceof Player) {
                if (isset($args[1])) {
                    switch ($args[1]) {
                        case "Owner":
                        case "Executive":
                        case "Manager":
                        case "Administrator":
                        case "Moderator":
                        case "Helper":
                        case "Builder":
                        case "Designer":
                        case "Famous":
                        case "Youtube":
                        case "Media":
                        case "Nitro":
                        case "Duke":
                        case "Knight":
                        case "Player":
                            $player = Loader::getInstance()->getServer()->getPlayer($args[0]);
                            $this->loader->addRank($player, $args[1]);
                            $sender->sendMessage(TextFormat::GREEN . "{$args[0]} now has the rank: {$args[1]}");
                            $player->sendMessage(TextFormat::GREEN . "{$sender->getName()} has given you the rank: {$args[1]}");
                            return;
                        default:
                            break;
                    }
                }
                $sender->sendMessage($this->getUsage());
            }
        }
    }
}
