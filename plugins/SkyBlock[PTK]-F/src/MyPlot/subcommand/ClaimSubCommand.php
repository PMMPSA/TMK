<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\NBT;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\Position;
use MyPlot\MyPlot;

class ClaimSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.claim");
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) > 1) {
            return false;
        }
        $name = "";
        if (isset($args[0])) {
            $name = $args[0];
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner != "") {
            if ($plot->owner === $sender->getName()) {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.yourplot"));
            } else {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.alreadyclaimed", [$plot->owner]));
            }
            return true;
        }

        $maxPlots = $this->getPlugin()->getMaxPlotsOfPlayer($player);
        $plotsOfPlayer = count($this->getPlugin()->getProvider()->getPlotsByOwner($player->getName()));
        if ($plotsOfPlayer >= $maxPlots) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.maxplots", [$maxPlots]));
            return true;
        }

        $plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
        $economy = $this->getPlugin()->getEconomyProvider();
        if ($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.nomoney"));
            return true;
        }
		

        $plot->owner = $sender->getName();
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            $sender->sendMessage($this->translateString("claim.success"));
			//Tạo Chest
			$block = Block::get(54);
			$player->getLevel()->setBlock(new Vector3($player->x - 3, $player->y, $player->z + 1), $block, true, true);
			$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", floor($player->x - 3)),
			new IntTag("y", floor($player->y)),
			new IntTag("z", floor($player->z + 1))
			]);
			$nbt->Items->setTagType(NBT::TAG_Compound);
			$ie1 = Item::get(278, 0, 1);
			$ie1->setCustomName("§r§eCúp SkyBlock");
			$ie2 = Item::get(279, 0, 1);
			$ie2->setCustomName("§r§eRìu SkyBlock");
			$ie3 = Item::get(277, 0, 1);
			$ie3->setCustomName("§r§eXẻng SkyBlock");
			$ie1->addEnchantment(Enchantment::getEnchantment(15)->setLevel(1));
			$ie1->addEnchantment(Enchantment::getEnchantment(17)->setLevel(1));
			$ie2->addEnchantment(Enchantment::getEnchantment(15)->setLevel(1));
			$ie2->addEnchantment(Enchantment::getEnchantment(17)->setLevel(1));
			$ie3->addEnchantment(Enchantment::getEnchantment(15)->setLevel(1));
			$ie3->addEnchantment(Enchantment::getEnchantment(17)->setLevel(1));
			$tile = Tile::createTile("Chest", $player->getLevel()->getChunk($player->getX() >> 4, $player->getZ() >> 4), $nbt);
			$i = $tile->getInventory();
			$ip = $player->getInventory();
			$i->addItem(Item::get(2, 0, 16));
			$i->addItem(Item::get(8, 0, 2));
			$i->addItem(Item::get(Item::FENCE, 0, 2));
			$ip->addItem(Item::get(276, 0, 1));
			$ip->addItem($ie1);
			$ip->addItem($ie2);
			$ip->addItem($ie3);
			$i->addItem(Item::get(6, 0, 2));
			$i->addItem(Item::get(81, 0, 3));
			$i->addItem(Item::get(295, 0, 5));
			$i->addItem(Item::get(338, 0, 3));
			$i->addItem(Item::get(352, 0, 10));
			$i->addItem(Item::get(361, 0, 3));
			$i->addItem(Item::get(362, 0, 3));
			$i->addItem(Item::get(391, 0, 3));
			$i->addItem(Item::get(392, 0, 3));
			$i->addItem(Item::get(54, 0, 2));
			$i->addItem(Item::get(364, 0, 5));
			$player->addWindow($tile->getInventory());
            $sender->sendMessage("§b[§e§cT§aᗰ§6K§b-§aSkyBlock§b] §aĐã Thêm§b Nước§a Và§6 Hàng Rào§a Vào Rương Bên Cạnh Bạn!");
            $sender->sendMessage("§b[§e§cT§aᗰ§6K§b-§aSkyBlock§b] §bHãy Sử Dụng Hàng Rào Thay Cho Dung Nham Để Làm Máy Farm Đá Ghi /khu farm để xem cách làm nếu bạn không biết!");
			} else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }
}