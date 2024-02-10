<?php

namespace GameParrot\PingView\command;

use pocketmine\command\Command;
use GameParrot\PingView\loader;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use pocketmine\Server;

class ping extends Command implements PluginOwned {

    private loader $plugin;

    public function __construct(Loader $loader) {
        $this->plugin = $loader;

        parent::__construct("ping", "Show your ping or someone elses ping");
        $this->setPermissions(['pingview.command', 'pingview.others']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!isset($args, $args[0])) {
            if($sender instanceof Player) {
                $sender->sendMessage($this->getFormattedPing($sender));
            } else $sender->sendMessage("Please enter a player to ping.");

            return;
        }

        if($sender instanceof Player && !$sender->hasPermission("pingview.others")) {
            $sender->sendMessage(TextFormat::RED ."You do not have permission to view ping of others.");
            return;
        }

        if(!Server::getInstance()->getPlayerByPrefix($args[0])) {
            $sender->sendMessage(TextFormat::RED."Could not find player " . $args[0]);
            return;
        }

        if(!Server::getInstance()->getPlayerByPrefix($args[0]) instanceof Player) {
            $sender->sendMessage(TextFormat::RED."Not a player");
            return;
        }

        $sender->sendMessage($this->getFormattedPing(Server::getInstance()->getPlayerByPrefix($args[0])));
    }


    private function getFormattedPing(Player $player): string {
        $ping = $player->getNetworkSession()->getPing();

        $color = TextFormat::GREEN;
        if($ping > 170) $color= TextFormat::RED;
        if($ping > 85) $color= TextFormat::YELLOW;

        return $player->getName()."'s ping: " . $color . $ping . TextFormat::WHITE . "ms";
    }

    public function getOwningPlugin(): Loader {
		return $this->plugin;
	}
}