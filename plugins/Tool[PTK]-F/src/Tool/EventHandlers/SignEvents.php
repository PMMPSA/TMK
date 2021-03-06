<?php

namespace Tool\EventHandlers;

use Tool\BaseFiles\BaseEventHandler;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class SignEvents extends BaseEventHandler{
    /**
     * @param PlayerInteractEvent $event
     */
    public function onSignTap(PlayerInteractEvent $event){
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            // Free sign
            if(TextFormat::clean($tile->getText()[0], true) === "[Free]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.free")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    if($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                        return;
                    }

                    $item_name = $tile->getText()[1];
                    $damage = $tile->getText()[2];

                    $item = $this->getAPI()->getItem($item_name . ":" . $damage);

                    $event->getPlayer()->getInventory()->addItem($item);
                    $event->getPlayer()->sendMessage(TextFormat::YELLOW . "Giving " . TextFormat::RED . $item->getCount() . TextFormat::YELLOW . " of " . TextFormat::RED . ($item->getName() === "Unknown" ? $item_name : $item->getName()));
                }
            }

            // Gamemode sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Gamemode]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.gamemode")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    $v = strtolower($tile->getText()[1]);
                    if($v === "survival"){
                        $event->getPlayer()->setGamemode(0);
                    }elseif($v === "creative"){
                        $event->getPlayer()->setGamemode(1);
                    }elseif($v === "adventure"){
                        $event->getPlayer()->setGamemode(2);
                    }elseif($v === "spectator"){
                        $event->getPlayer()->setGamemode(3);
                    }
                }
            }

            // Heal sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Heal]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.heal")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
               }else{
                    $event->getPlayer()->heal($event->getPlayer()->getMaxHealth(), new EntityRegainHealthEvent($event->getPlayer(), $event->getPlayer()->getMaxHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "You have been healed!");
                }
            }
            
            // Kit2 sign
            // TODO: Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Kit2]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.kit2")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
                }else{
                    if(!($kit2 = $this->getAPI()->getKit2($tile->getText()[1]))){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Kit2 doesn't exists");
                        return;
                    }elseif(!$event->getPlayer()->hasPermission("tool.kit22." . $kit2->getName())){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You don't have permissions to get this kit2");
                        return;
                    }else{
                        $kit2->giveToPlayer($event->getPlayer());
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Getting kit2 " . TextFormat::AQUA . $kit2->getName() . "...");
                    }
                }
            }

            // Repair sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Repair]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.repair")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return;
               }else{
                    if(($v = $tile->getText()[1]) === "Hand"){
                        if($this->getAPI()->isRepairable($item = $event->getPlayer()->getInventory()->getItemInHand())){
                            $item->setDamage(0);
                            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Item successfully repaired!");
                        }
                    }elseif($v === "All"){
                        foreach ($event->getPlayer()->getInventory()->getContents() as $item){
                            if($this->getAPI()->isRepairable($item)){
                                $item->setDamage(0);
                            }
                        }
                        foreach ($event->getPlayer()->getInventory()->getArmorContents() as $item){
                            if($this->getAPI()->isRepairable($item)){
                                $item->setDamage(0);
                            }
                        }
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "All the tools on your inventory were repaired!" . TextFormat::AQUA . "\n(including the equipped Armor)");
                    }
                }
            }

            // Time sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Time]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.time")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    if(($v = $tile->getText()[1]) === "Day"){
                        $event->getPlayer()->getLevel()->setTime(0);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Day\"");
                    }elseif($v === "Night"){
                        $event->getPlayer()->getLevel()->setTime(12500);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Night\"");
                    }
                }
            }

            // Teleport sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Teleport]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.teleport")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    $event->getPlayer()->teleport(new Vector3($x = $tile->getText()[1], $y = $tile->getText()[2], $z = $tile->getText()[3]));
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleporting to " . TextFormat::AQUA . $x . TextFormat::GREEN . ", " . TextFormat::AQUA . $y . TextFormat::GREEN . ", " . TextFormat::AQUA . $z);
                }
            }

            // Warp sign
            // TODO Implement costs
            elseif(TextFormat::clean($tile->getText()[0], true) === "[Warp]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("tool.sign.use.warp")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
               }else{
                    $warp = $this->getAPI()->getWarp($tile->getText()[1]);
                    if(!$warp){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Warp doesn't exists");
                        return;
                    }
                    if(!$event->getPlayer()->hasPermission("tool.warps.*") && !$event->getPlayer()->hasPermission("tool.warps." . $tile->getText()[1])){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You can't teleport to that warp");
                        return;
                    }
                    $event->getPlayer()->teleport($warp);
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warping to " . $tile->getText()[1] . "...");
                }
            }

            /**
             * Economy signs
             */

            // Balance sign
            /**elseif(TextFormat::clean($tile->getText()[0) === "[Balance]"){
             * $event->setCancelled(true);
             * if(!$event->getPlayer()->hasPermission("tool.sign.use.balance")){
             * $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
             * }else{
             * $event->getPlayer()->sendMessage(TextFormat::AQUA . "Your current balance is " . TextFormat::YELLOW . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($event->getPlayer()));
             * }
             * }*/

            /**
             * TODO Implement:
             * - Buy sign
             * - Sell sign
             */
        }
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @priority HIGH
     */
    public function onBlockBreak(BlockBreakEvent $event){
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            $key = ["Free", "Gamemode", "Heal", "Kit2", "Repair", "Time", "Teleport", "Warp"];
            foreach($key as $k){
                if(TextFormat::clean($tile->getText()[0], true) === "[" . $k . "]" && !$event->getPlayer()->hasPermission("tool.sign.break." . strtolower($k))){
                    $event->setCancelled(true);
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
                    break;
                }
            }
        }
    }

    /**
     * @param SignChangeEvent $event
     */
    public function onSignChange(SignChangeEvent $event){
        // Special Signs
        // Free sign
        if(strtolower(TextFormat::clean($event->getLine(0), true)) === "[free]" && $event->getPlayer()->hasPermission("tool.sign.create.free")){
            if(trim($event->getLine(1)) !== "" || $event->getLine(1) !== null){
                $item_name = $event->getLine(1);

                if(trim($event->getLine(2)) !== "" || $event->getLine(2) !== null){
                    $damage = $event->getLine(2);
                }else{
                    $damage = 0;
                }

                $item = $this->getAPI()->getItem($item_name . ":" . $damage);

                if($item->getId() === 0 || $item->getName() === "Air"){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid item name/ID");
                    $event->setCancelled(true);
                }else{
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Free sign successfully created!");
                    $event->setLine(0, TextFormat::AQUA . "[Free]");
                    $event->setLine(1, ($item->getName() === "Unknown" ? $item->getId() : $item->getName()));
                    $event->setLine(2, $damage);
                }
            }else{
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] You should provide an item name/ID");
                $event->setCancelled(true);
            }
        }

        // Gamemode sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[gamemode]" && $event->getPlayer()->hasPermission("tool.sign.create.gamemode")){
            switch(strtolower($event->getLine(1))){
                case "survival":
                case "0":
                    $event->setLine(1, "Survival");
                    break;
                case "creative":
                case "1":
                    $event->setLine(1, "Creative");
                    break;
                case "adventure":
                case "2":
                    $event->setLine(1, "Adventure");
                    break;
                case "spectator":
                case "view":
                case "3":
                    $event->setLine(1, "Spectator");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Unknown Gamemode, you should use \"Survival\", \"Creative\", \"Adventure\" or \"Spectator\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Gamemode sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Gamemode]");
        }

        // Heal sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[heal]" && $event->getPlayer()->hasPermission("tool.sign.create.heal")){
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Heal sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Heal]");
        }

        // Kit2 sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[kit2]" && $event->getPlayer()->hasPermission("tool.sign.create.kit2")){
            if(!$this->getAPI()->kit2Exists($event->getLine(1))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Kit2 doesn't exist");
                return;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Kit2 sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Kit2]");
        }

        // Repair sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[repair]" && $event->getPlayer()->hasPermission("tool.sign.create.repair")){
            switch(strtolower($event->getLine(1))){
                case "hand":
                    $event->setLine(1, "Hand");
                    break;
                case "all":
                    $event->setLine(1, "All");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid argument, you should use \"Hand\" or \"All\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Repair sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Repair]");
        }

        // Time sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[time]" && $event->getPlayer()->hasPermission("tool.sign.create.time")){
            switch(strtolower($event->getLine(1))){
                case "day":
                    $event->setLine(1, "Day");
                    break;
                case "night";
                    $event->setLine(1, "Night");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid time, you should use \"Day\" or \"Night\"");
                    $event->setCancelled(true);
                    return;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time sign successfully created!");
            $event->setLine(0, TextFormat::AQUA . "[Time]");
        }

        // Teleport sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[teleport]" && $event->getPlayer()->hasPermission("tool.sign.create.teleport")){
            if(!is_numeric($event->getLine(1))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid X position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(2))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid Y position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(3))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Invalid Z position, Teleport sign will not work");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleport sign successfully created!");
                $event->setLine(0, TextFormat::AQUA . "[Teleport]");
                $event->setLine(1, $event->getLine(1));
                $event->setLine(2, $event->getLine(2));
                $event->setLine(3, $event->getLine(3));
            }
        }

        // Warp sign
        elseif(strtolower(TextFormat::clean($event->getLine(0), true)) === "[warp]" && $event->getPlayer()->hasPermission("tool.sign.create.warp")){
            $warp = $event->getLine(1);
            if(!$this->getAPI()->warpExists($warp)){
                $event->getPlayer()->sendMessage(TextFormat::RED . "§c[Lỗi] Warp doesn't exists");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warp sign successfully created!");
                $event->setLine(0, TextFormat::AQUA . "[Warp]");
            }
        }

        // Colored Sign
        elseif($event->getPlayer()->hasPermission("tool.sign.color")){
            for($i = 0 ; $i < 4 ; $i++){
                $event->setLine($i, $this->getAPI()->colorMessage($event->getLine($i)));
            }
        }
    }
}