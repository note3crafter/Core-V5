<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

declare(strict_types = 1);
namespace TheNote\core\tile;

use pocketmine\block\{BlockFactory, Block, Reserved6};
use pocketmine\nbt\tag\{CompoundTag, ByteTag, ShortTag};
use TheNote\core\blocks\Placeholder;

trait PlaceholderTrait{
    protected $block = null;
    protected function loadBlock(CompoundTag $nbt): void{
        $block = $nbt->getCompoundTag("Block");
        if($block !== null){
            $this->block = BlockFactory::get($block->getShort("id"), $block->getByte("meta"));
            if($this->block instanceof Placeholder){
                $this->block = new Reserved6(Block::RESERVED6, 0, 'reserved6');
            }
        }
        $this->getBlock(true)->position($this);
    }
    protected function saveBlock(CompoundTag $nbt): void{
        $block = $this->getBlock(true);
        if($block->isValid()){
            $nbt->setTag(new CompoundTag("Block", [
                new ShortTag("id", $block->getId()),
                new ByteTag("meta", $block->getDamage())
            ]));
        }
    }
    public function getCleanedNBT() : ?CompoundTag{
        $tag = parent::getCleanedNBT();
        if($tag !== null){
            $tag->removeTag("Block");
        }
        return $tag;
    }
    public function getBlock(bool $extended = false): Block{
        if(!$extended){
            return parent::getBlock();
        }
        return $this->block = $this->block ?? new Reserved6(Block::RESERVED6, 0, 'reserved6');
    }
}
