<?php
namespace Tool\BaseFiles;

use pocketmine\level\Level;
use pocketmine\level\Location;

class BaseLocation extends Location{
    /** @var string */
    protected $name;

    /**
     * @param string $name
     * @param int $x
     * @param int $y
     * @param int $z
     * @param Level $level
     * @param float $yaw
     * @param float $pitch
     */
    public function __construct($name, $x, $y, $z, Level $level, $yaw, $pitch){
        parent::__construct($x, $y, $z, $yaw, $pitch, $level);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * @param $name
     * @param Location $pos
     * @return BaseLocation
     */
    public static function fromPosition($name, Location $pos): BaseLocation{
        return new BaseLocation($name, $pos->getX(), $pos->getY(), $pos->getZ(), $pos->getLevel(), $pos->getYaw(), $pos->getPitch());
    }
}