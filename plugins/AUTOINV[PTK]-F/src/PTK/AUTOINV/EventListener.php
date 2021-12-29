<?php

/**
 * AutoInv EventListener class
 * 
 * Created on Mar 24, 2016 at 10:12:22 PM
 *
 * @author PTK-KienPham
 */

namespace PTK\AUTOINV;

use pocketmine\event\Listener;
use pocketmine\inventory\InventoryHolder;
use pocketmine\block\Block;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityExplodeEvent;

use PTK\AUTOINV\Main;

class EventListener implements Listener {
        
        /** @var $plugin Main */
        private $plugin;
        
        /**
         * Construct a new event listener class
         * 
         * @param Main $plugin
         */
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }
        
        /**
         * Get the owning plugin
         * 
         * @return Main
         */
        public function getPlugin() {
                return $this->plugin;
        }
        
        /**
         * Handles autoinv block breaking
         * 
         * @param BlockBreakEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onBreak(BlockBreakEvent $event) {
                if($event->isCancelled()) {
                        return;
                } else {
                        foreach($event->getDrops() as $drop) {
								$player = $event->getPlayer();
								if($this->isInventoryFull($player) == true) {
								$player->sendMessage("§b•»§cKho Đồ Của Bạn Đã Đầy!!§b«");
								$event->setDrops($drop);
								}else{
                                $event->getPlayer()->getInventory()->addItem($drop);
								$event->setDrops([]);
								}
                        }
                }
        }
		public function isInventoryFull(Player $player){
   for($i = 0; $i < $player->getInventory()->getSize(); $i++){
    	if($player->getInventory()->getItem($i)->getId() === 0){
      	return false;
       }
     }
     return true;
   }
        /**
         * Handles autoinv entity exploding
         * 
         * @param EntityExplodeEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onExplode(EntityExplodeEvent $event) {
                if($event->isCancelled()) {
                        return;
                } else {
                        $explosive = $event->getEntity();
                        $closest = PHP_INT_MAX;
                        $entity = null;
                        foreach($explosive->getLevel()->getNearbyEntities($explosive->getBoundingBox()->grow(24, 24, 24)) as $nearby) {
                                if($explosive->distance($nearby) <= $closest) {
                                        $entity = $nearby;
                                }
                        }
                        $disallowed = [
                            Block::TNT,
                            Block::FIRE,
                            Block::LAVA,
                            Block::WATER,
                            Block::STILL_LAVA,
                            Block::STILL_WATER,
                        ];
                        $blocks = $event->getBlockList();
                        if($nearby instanceof InventoryHolder) {
                                foreach($blocks as $key => $block) {
                                        if(isset($disallowed[$block->getId()]) && $this->isInventoryFull($nearby) == false) {
                                                continue;
                                        }
                                        $nearby->getInventory()->addItem($block);
                                        unset($blocks[$key]);
										if($this->isInventoryFull($nearby) == true) {
											$nearby->sendMessage("§b•»§cKho Đò Của Bạn Đã Đầy!!§b«");
											
                                }
                        }
                        $event->setBlockList($blocks);
                }
                return;
        }
}
}