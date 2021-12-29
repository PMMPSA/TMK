<?php
namespace MyPlot\subcommand;

use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\command\CommandSender;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Server;

class AutoSubCommand extends SubCommand implements Listener
{
	

    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.auto");
    }
    
    public function pl(Player $player)
    {
        return $player;
    }

    public function execute(CommandSender $sender, array $args) {
        if (!empty($args)) {
            return false;
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $levelName = $player->getLevel()->getName();
		$wsb = "sb";
		$lv = $this->getServer()->getLevelByName($wsb);
        if (!$this->getPlugin()->isLevelLoaded($levelName) || ($plot = $this->getPlugin()->getProvider()->getNextFreePlot($levelName)) !== null){
			//$this->getServer()->loadLevel($wab);
			$player->teleport($lv->getSafeSpawn());
            $this->getPlugin()->teleportPlayerToPlot($player, $plot);
            $sender->sendMessage($this->translateString("auto.success", [$plot->X, $plot->Z]));
			$sender->sendMessage(TextFormat::GREEN . "•§e/is claim §ađể sở hữu đảo");
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("auto.noplots"));
        }
        return true;
        }
    }