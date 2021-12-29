<?php

namespace PTK\AWL;

use pocketmine\event\level\LevelLoadEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;


class Main extends PluginBase{

	public function onEnable() {
	$this->getServer()->getLogger()->info(TextFormat::BLUE . "Plugin AWL By PTK-KienPham Version 1.0.");
	$this->getServer()->getLogger()->info(TextFormat::YELLOW . "Tất Cả Các World Đã Được Load.");
	$this->getServer()->loadLevel(sb);
	$this->getServer()->loadLevel(Crate);
	$this->getServer()->loadLevel(Build);
	$this->getServer()->loadLevel(sw);
	}
	}
