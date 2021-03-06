<?PHP
namespace PTK\HotBar;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as CLR;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use PTK\HotBar\PL;

class Main extends PluginBase {
	public $hotbar, $conf_provider, $task, $prefix, $no_perm;
	
	public function onEnable() {
		$this->getLogger()->info(CLR::GOLD . 'HotBar will be enabled after the complete server load...');
		$pl = new PL($this);
		$pl->main = $this;
		$task = $this->getServer()->getScheduler()->scheduleRepeatingTask($pl, 1);
		$pl->id = $task->getTaskId();
	}
	
	public function dataLoader($reload = false) {
		if ($reload)
			$this->getServer()->getScheduler()->cancelTask($this->task->getTaskId());
		$ticks = $this->conf_provider->loadData();
		$this->task = $this->getServer()->getScheduler()->scheduleRepeatingTask($this->hotbar, $ticks);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $lbl, array $args){
		if($cmd->getName() == 'hotbar') {
			if (count($args) == 0) {
				$sender->sendMessage(
					$this->prefix . "Version " . $this->getDescription()->getVersion() . "\n" . 
					$this->prefix . 'Commands list: ' . CLR::DARK_GREEN . '/hb help'
				);
			} elseif ($args[0] == 'help') {
				if ($sender->hasPermission('hotbar.help')) {
					$sender->sendMessage(
						$this->prefix . "Commands:\n" .
						CLR::DARK_GREEN . '/hbb reload' . CLR::BLUE . ' - ' . CLR::DARK_AQUA . "reload the hotbar settings"
						//CLR::DARK_GREEN . '/hbb example' . CLR::BLUE . ' - ' . CLR::DARK_AQUA . "somesing"
					);
				} else
					$sender->sendMessage($this->prefix . $this->no_perm);
			} elseif ($args[0] == 'reload') {
				if ($sender->hasPermission('hotbar.reload')) {
					$this->dataLoader(true);
					$sender->sendMessage($this->prefix . 'Successfully reloaded!');
				} else
					$sender->sendMessage($this->prefix . $this->no_perm);
			} elseif ($args[0] == 'addgroup') {
				if ($sender->hasPermission('hotbar.addgroup')) {
					
				} else
					$sender->sendMessage($this->prefix . $this->no_perm);
			} else {
				$sender->sendMessage($this->prefix . CLR::RED . 'Wrong command!' . CLR::DARK_GREEN . ' /hbb help ' . CLR::RED . 'for a list of commands.');
			}
		}
	}
	
	public function getPlug($name) {
		if ($plug = $this->getServer()->getPluginManager()->getPlugin($name)) {
			if ($plug->isEnabled()) {
				return $plug;
			} else return false;
		} else return false;
	}
}
