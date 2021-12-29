<?php
namespace PTK\Crates\Commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\inventory\Inventory;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

class binhthuong extends PluginBase implements CommandExecutor{
	      public function __construct($plugin){
	           $this->plugin = $plugin;
		}
		public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		     $cmd = strtolower($command->getName());
		     switch($cmd){
		        case "binhthuong":
		              if($sender instanceof Player){
			              $inv = $sender->getInventory();
			              if($inv->contains(Item::get(388,0,1))){
				              $level = $sender->getLevel();
				              $x = $sender->getX();
				              $y = $sender->getY();
				              $z = $sender->getZ();
				              $pos = new Vector3($x, $y + 2, $z);
				              $pos1 = new Vector3($x, $y, $z);
				              $level->addSound(new EndermanTeleportSound($pos1));
				              $level->addParticle(new LavaParticle($pos));
				              $inv->removeItem(Item::get(388,0,1));
				              $result = rand(1,3);
					          switch($result){
			                case 1:
				                $inv->addItem(Item::get(265,0,15));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 15 Thỏi Sắt!");
				             break;
				             case 2:
				                $inv->addItem(Item::get(264,0,1));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Viên Kim Cương!");
				             break;
				             case 3:
				                $inv->addItem(Item::get(17,0,20));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 20 Khối Gỗ Sồi!");
				             break;
				    }
				}else{
				               $sender->sendMessage("§b-•[§aCrates§b-§cError§b]•-§6 Bạn Cần 1 Chìa Khóa Bình Thường Để Mở Crate Này.");
			    	}
				}else{
				               $sender->sendMessage("§cRun this command on Game!");
				}
			}
		}
	}
