<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\tile;

use pocketmine\nbt\tag\CompoundTag;

use pocketmine\tile\Tile;

class Observer extends Tile {

    private $runtimeId;

    protected function readSaveData(CompoundTag $nbt) : void {
        if ($nbt->hasTag("runtimeId")) {
            $this->runtimeId = $nbt->getInt("runtimeId");
        }
    }

    protected function writeSaveData(CompoundTag $nbt) : void {
        $nbt->setInt("runtimeId", $this->runtimeId);
    }

    public function getSideRuntimeId() : ?int {
        return $this->runtimeId;
    }

    public function setSideRuntimeId(int $runtimeId) : void {
        $this->runtimeId = $runtimeId;
    }
}