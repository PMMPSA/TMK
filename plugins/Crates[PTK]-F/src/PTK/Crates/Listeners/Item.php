<?php
namespace PTK\Crates\Listeners;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\inventory\Inventory;
use pocketmine\event\player\PlayerItemHeldEvent;
class Item extends PluginBase implements Listener{
	 public function __construct($plugin){
	      $this->plugin = $plugin;
		}
	 public function onItemHeld(PlayerItemHeldEvent $event){
	      $item = $event->getItem();
	      $player = $event->getPlayer();
	      $inv = $player->getInventory();
	      if($item->getId() == 388){
		        $player->sendPopup("§6[Chìa Bình Thường]\n§aBình Thường\n§bHiếm§r\n§9Huyền Thoại");
		     }
		   elseif($item->getId() == 399){
				  $player->sendPopup("§6[Chìa Đắc Biệt]\n§4Thần Thoại");
		}
     }
   }