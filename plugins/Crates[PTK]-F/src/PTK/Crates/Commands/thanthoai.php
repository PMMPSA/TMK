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

class thanthoai extends PluginBase implements CommandExecutor{
	      public function __construct($plugin){
	           $this->plugin = $plugin;
		}
		public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		     $cmd = strtolower($command->getName());
		     switch($cmd){
		        case "thanthoai":
		              if($sender instanceof Player){
			              $inv = $sender->getInventory();
			              if($inv->contains(Item::get(131,0,5))){
				              $level = $sender->getLevel();
				              $x = $sender->getX();
				              $y = $sender->getY();
				              $z = $sender->getZ();
				              $pos1 = new Vector3($x, $y, $z);
				              $pos = new Vector3($x, $y + 2, $z);
				              $level->addSound(new EndermanTeleportSound($pos1));
				              $level->addParticle(new LavaParticle($pos));
				              $inv->removeItem(Item::get(131,0,5));
				              $result = rand(1,9);
			             switch($result){
			                case 1:
				                $inv->addItem(Item::get(266,0,20));
				                $inv->addItem(Item::get(265,0,20));
				                $inv->addItem(Item::get(264,0,20));
				                $inv->addItem(Item::get(351,4,20));
				                $inv->addItem(Item::get(263,0,20));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 20 Khối Quặng Các Loiaj!");
				             break;
				             case 2:
				                $inv->addItem(Item::get(264,0,30));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 30 Viên Kim Cương!");
				             break;
				             case 3:
				                $inv->addItem(Item::get(322,0,20));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 20 Quả Táo Vàng!");
				             break;
				             case 4:
				                $i = Item::get(276,0,1);
				                $e = Enchantment::getEnchantment(9);
				                $e->setLevel(3);
				                $e1 = Enchantment::getEnchantment(12);
				                $e1->setLevel(2);
				                $e2 = Enchantment::getEnchantment(13);
				                $e2->setLevel(2);
				                $i->addEnchantment($e);
				                $i->addEnchantment($e1);
				                $i->addEnchantment($e2);
				                $i->setCustomName("§6§lMythic§bSword");
				                $inv->addItem($i);
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Cây Kiếm §l§6Mythic§bSword!");
				             break;
				             case 5:
				                $inv->addItem(Item::get(466,0,15));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 15 Quả Táo Vàng Phù Phép!");
				             break;
				             case 6:
				                $inv->addItem(Item::get(17,5,64));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 64 Gỗ Vân Sam!");
				             break;
				             case 7:
				                $i = Item::get(276,0,1);
				                $e = Enchantment::getEnchantment(9);
				                $e->setLevel(5);
				                $i->addEnchantment($e);
				                $inv->addItem($i);
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Cây Kiếm Kim Cương Phù Phép!");
				             break;
				             case 8:
				                $chest = Item::get(311,0,1);
				                $e = Enchantment::getEnchantment(1);
				                $e->setLevel(4);
				                $chest->addEnchantment($e);
				                $inv->addItem(Item::get(310,0,1));
				                $inv->addItem($chest);
				                $inv->addItem(Item::get(312,0,1));
				                $inv->addItem(Item::get(313,0,1));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 1 Set Kim Cương Với Áo Giáp Phù Phép!!");
				             break;
				             case 9:
				                $inv->addItem(Item::get(388,0,30));
				                $sender->sendMessage("§b-•[§aCrates§b-§eReward§b]•-§2 Bạn Đã Nhận Được 30 Chìa Khóa Bình Thường!");
				             break;
				    }
				}else{
				               $sender->sendMessage("§b-•[§aCrates§b-§cError§b]•-§6 Bạn Cần 5 Chìa Khóa Đắc Biệt Để Mở Crate Này.");
			    	}
				}else{
				               $sender->sendMessage("§cRun this command on Game!");
				} 
			            }
	         		}
			}