<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;
use pocketmine\Player;

class NetheriteShovel extends Item
{
    const NETHERITE_SHOVEL = 744;

    public function __construct(int $meta = 0)
    {
        parent::__construct(self::NETHERITE_SHOVEL, $meta, "Netherite Shovel");
    }

    public function onUpdate(Player $player): void
    {
        $player->setGenericFlag(Entity::DATA_FLAG_BLOCKING, $player->isSneaking());
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }
}