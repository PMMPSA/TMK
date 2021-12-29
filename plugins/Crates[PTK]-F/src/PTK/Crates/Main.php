<?php
namespace PTK\Crates;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat as C;

class Main extends PluginBase{
	public function onEnable(){
	  $this->getLogger()->info(C::GREEN."§aON!");
		$this->getCommand("binhthuong")->setExecutor(new Commands\binhthuong($this));
		$this->getCommand("hiem")->setExecutor(new Commands\hiem($this));
		$this->getCommand("huyenthoai")->setExecutor(new Commands\huyenthoai($this));
		$this->getCommand("thanthoai")->setExecutor(new Commands\thanthoai($this));
		//$this->getServer()->getPluginManager()->registerEvents(new Listeners\Item($this),$this);
		}
	public function onDisable(){
	  $this->getLogger()->info(C::RED."§cOFF");
		}
	}