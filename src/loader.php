<?php

namespace GameParrot\PingView;

use GameParrot\PingView\command\ping;
use pocketmine\plugin\PluginBase;

class loader extends PluginBase {

	public function onEnable(): void {
		$this->getServer()->getCommandMap()->register('ping', new ping($this));
		$this->getLogger()->info("Plugin Enabled.");
	}
}
