<?php

namespace GameParrot\PingView;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\Server;

class Main extends PluginBase{
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($command->getName() === "ping"){
				$target = $sender;
				if (isset($args[0])) {
					if ($sender instanceof Player && !$sender->hasPermission("pingview.others")) {
						$sender->sendMessage(TextFormat::RED ."You do not have permission to view ping of others.");
						return true;
					}
					if (Server::getInstance()->getPlayerExact($args[0])) {
						$target = Server::getInstance()->getPlayerExact($args[0]);
					} else {
						$sender->sendMessage(TextFormat::RED."Could not find player ".$args[0]);
						return true;
					}
				}
				if ($target instanceof Player) {
					$ping = $target->getNetworkSession()->getPing();
					$color=TextFormat::GREEN;
					if($ping > 170) {
						$color= TextFormat::RED;
					} else if ($ping > 85) {
						$color= TextFormat::YELLOW;
					}
					$sender->sendMessage($target->getName()."'s ping: ".$color . $ping . TextFormat::WHITE ."ms");
				} else {
					$sender->sendMessage(TextFormat::RED."Not a player");
				}
			return true;
		}
		return false;
	}
}
