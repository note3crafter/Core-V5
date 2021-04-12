<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\object;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\BirchTree;
use pocketmine\level\generator\object\JungleTree;
use pocketmine\level\generator\object\OakTree;
use pocketmine\level\generator\object\SpruceTree;
use pocketmine\level\generator\object\Tree as TreeObject;
use pocketmine\utils\Random;

abstract class Tree extends TreeObject {

    public const OAK = 0;
    public const SPRUCE = 1;
    public const BIRCH = 2;
    public const JUNGLE = 3;
    public const ACACIA = 4;
    public const DARK_OAK = 5;
    public const BIG_BIRCH = 6;
    public const SMALL_OAK = 10;
    public const BIG_OAK = 11;
    public const MUSHROOM = 20;

    public static function growTree(ChunkManager $level, int $x, int $y, int $z, Random $random, int $type = 0, bool $vines = false) {
        switch($type){
            case self::SPRUCE:
                $tree = new SpruceTree();
                break;
            case self::BIRCH:
                if($random->nextBoundedInt(39) === 0){
                    $tree = new BirchTree(true);
                }else{
                    $tree = new BirchTree();
                }
                break;
            case self::BIG_BIRCH:
                $tree = new BirchTree(true);
                break;
            case self::JUNGLE:
                $tree = new JungleTree();
                break;
            case self::ACACIA:
                $tree = new AcaciaTree();
                break;
            case self::DARK_OAK:
                $tree = new DarkOakTree();
                break;
            case self::MUSHROOM:
                $tree = new HugeMushroom();
                break;
            default:
                if($vines) {
                    $tree = new SwampTree();
                    goto place;
                }

                if($type !== self::SMALL_OAK && $random->nextRange(0, 9) === 0){
                    $tree = new BigOakTree($random, $level);
                }
                else {
                    $tree = new OakTree();
                }
                break;
        }

        place:
        if($tree->canPlaceObject($level, $x, $y, $z, $random)){
            $tree->placeObject($level, $x, $y, $z, $random);
        }
    }
}