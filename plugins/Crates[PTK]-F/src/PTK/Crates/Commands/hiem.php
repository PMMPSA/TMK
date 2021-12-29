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
use pocketmine\item\enchantment\Enchantment;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

class hiem extends PluginBase implements CommandExecutor{
	      public function __construct($plugin){
	           $this->plugin = $plugin;
		}
		public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		     $cmd = strtolower($command->getName());
		     switch($cmd){
		        case "hiem":
		              if($sender instanceof Player){
			              $inv = $sender->getInventory();
			              if($inv->contains(Item::get(388,0,5))){
				              $level = $sender->getLevel();
				              $x = $sender->getX();
				              $y = $sender->getY();
				              $z = $sender->getZ();
				              $pos1 = new Vector3($x, $y, $z);
				              $pos = new Vector3($x, $y + 2, $z);
				              $level->addSound(new EndermanTeleportSound($pos1));
				              $level->addParticle(new LavaParticle($pos));
				              $inv->removeItem(Item::get(388,0,5));
				              $result = rand(1,6);
			             switch($result){
			                case 1:
				                $inv->addItem(Item::get(265,0,20));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 20 Thỏi Sắt!");
				             break;
				             case 2:
				                $inv->addItem(Item::get(264,0,5));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 5 Viên Kim Cương!");
				             break;
				             case 3:
				                $inv->addItem(Item::get(322,0,1));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Quả Táo Vàng!");
				             break;
				             case 4:
				                $i = Item::get(267,0,1);
				                $e = Enchantment::getEnchantment(9);
				                $e->setLevel(2);
				                $i->addEnchantment($e);
				                $inv->addItem($i);
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Cây Kiếm Sắt Phù Phép!");
				             break;
				             case 5:
				                $inv->addItem(Item::get(466,0,1));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Quả Táo Vàng Phù Phép!");
				             break;
				             case 6:
				                $inv->addItem(Item::get(17,0,64));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 64 Khối Gỗ Sồi!");
				             break;
				   }
				}else{
				               $sender->sendMessage("§b-•[§aCrates§b-§cError§b]•-§6 Bạn Cần 5 Chìa Khóa Bình Thường Để Mở Crate Này.");
			    	}
				}else{
				               $sender->sendMessage("§cRun this command on Game!");
			     }
		    }
		}
	}