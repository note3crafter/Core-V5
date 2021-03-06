<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone;

use pocketmine\Player;
use pocketmine\item\Item;

use TheNote\core\utils\Facing;

class RedstoneRepeaterUnpowered extends RedstoneDiode {

    protected $id = self::UNPOWERED_REPEATER;
    protected $itemId = Item::REPEATER;

    public function getName() : string {
        return "Unpowered Repeater";
    }
    
    public function onActivate(Item $item, Player $player = null) : bool {
        if ($this->getDamage() >= 12) {
            $this->setDamage($this->getDamage() - 12);
        } else {
            $this->setDamage($this->getDamage() + 4);
        }
        $this->level->setBlock($this, $this);
        $this->updateAroundDiodeRedstone($this);
        return true;
    }

    public function onScheduledUpdate() : void {
        $this->getLevel()->setBlock($this, new RedstoneRepeaterPowered($this->getDamage()));
        $this->updateAroundDiodeRedstone($this);
        $this->getLevel()->getBlock($this)->onRedstoneUpdate();
    }
    
    public function isLocked() : bool {
        $face = Facing::rotate($this->getInputFace(), Facing::AXIS_Y, false);
        $block = $this->getSide($face);
        if ($block instanceof RedstoneDiode && $this->getRedstonePower($block, $face)) {
            return true;
        }

        $face = Facing::opposite($face);
        $block = $this->getSide($face);
        if ($block instanceof RedstoneDiode && $this->getRedstonePower($block, $face)) {
            return true;
        }
        return false;
    }

    public function getDelayTime() : int {
        return ($this->getDamage() / 4 + 1) * 2;
    }

    public function onRedstoneUpdate() : void {
        if ($this->isLocked()) {
            return;
        }
        if ($this->isSidePowered($this->asVector3(), $this->getInputFace())) {
            $this->level->scheduleDelayedBlockUpdate($this, $this->getDelayTime());
        }
    }
}