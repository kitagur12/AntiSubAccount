<?php

namespace asa;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $playerIp = $player->getNetworkSession()->getIp();

        $this->checkip($playerIp, $player->getName());

        if ($this->issub($playerIp, $player->getName())) {
            $kickMessage = $this->getConfig()->get("Kick-Message", "Don't Allow alt Account.");
            $player->kick($kickMessage);
        }
    }

    private function checkip(string $playerIp, string $playerName): void {
        $data = new Config($this->getDataFolder() . "player_ips.json", Config::JSON);
        if (!$data->exists($playerIp)) {
            $data->set($playerIp, $playerName);
            $data->save();
        }
    }

    private function issub(string $playerIp, string $playerName): bool {
        $data = new Config($this->getDataFolder() . "player_ips.json", Config::JSON);
        return $data->exists($playerIp) && $data->get($playerIp) !== $playerName;
    }
}