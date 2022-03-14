<?php

namespace sega\Status;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use sega\Status\API\Discord;
use sega\Status\API\Embed;
use sega\Status\API\Message;
use sega\Status\API\Webhook;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{

    public static Config $discord;

    private static Main $instance;

    public function onEnable()
    {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!file_exists($this->getDataFolder() . "discord.yml")) {
            $this->saveResource('discord.yml', true);
        }
        self::$discord = new Config($this->getDataFolder() . 'discord.yml', Config::YAML);

        $embed = new Embed();
        $embed->setTitle("**Sega Network**");
        $embed->setDescription("**Server is starting..\nConnect via sega.mcpe.lol**");
        $embed->setFooter("Enjoy!");
        $embed->setColor(Embed::GREEN);
        Webhook::send(Webhook::getStatusWebhook(), $embed);
        $this->getServer()->getNetwork()->setName(Webhook::getMotd());
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $online = count($this->getServer()->getOnlinePlayers());
        $embed = new Embed();
        $embed->setTitle("**Sega Network**");
        $embed->setDescription("**Player Joined:** " . $player->getName() . "\n**Online Players:** " . $online);
        $embed->setFooter(date('l jS \of F Y h:i:s A'));
        $embed->setColor(Embed::GREEN);
        Webhook::send(Webhook::getCountWebhook(), $embed);
        $event->setJoinMessage(TextFormat::GREEN . "[+] " . $player->getName());

    }

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $online = count($this->getServer()->getOnlinePlayers());
        $embed = new Embed();
        $embed->setTitle("**Sega Network**");
        $embed->setDescription("**Player Left:** " . $player->getName() . "\n**Online Players:** " . $online - 1);
        $embed->setFooter(date('l jS \of F Y h:i:s A'));
        $embed->setColor(Embed::RED);
        Webhook::send(Webhook::getCountWebhook(), $embed);
        $event->setQuitMessage(TextFormat::RED . "[-] " . $player->getName());
    }

    public function onDisable(){
        $embed = new Embed();
        $embed->setTitle("**Sega Network**");
        $embed->setDescription("**Server is stopping..\nWill be back soon**");
        $embed->setFooter("Sorry For DownTime!");
        $embed->setColor(Embed::RED);
        Webhook::send(Webhook::getStatusWebhook(), $embed);
    }

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public function getDiscordConfig(): Config
    {
        return self::$discord;
    }
}
