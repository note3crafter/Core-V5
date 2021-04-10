<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CommandBlockUpdatePacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;

use TheNote\core\Main;
use TheNote\core\tile\CommandBlock;
use TheNote\core\blocks\redstone\NoteBlockR;

class EventListener implements Listener {

    public function onDataPacketReceive(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if ($packet instanceof PlayerActionPacket) {
            if ($packet->action == PlayerActionPacket::ACTION_START_BREAK) {
                $block = $event->getPlayer()->getLevel()->getBlock(new Vector3($packet->x, $packet->y, $packet->z));
                if ($block instanceof NoteBlockR) {
                    $block->playSound();
                }
            }
        } elseif ($packet instanceof CommandBlockUpdatePacket) {
            $player = $event->getPlayer();

            if (!$player->isOp() || !$player->isCreative()) {
                return;
            }

            $tile = $player->getLevel()->getTileAt($packet->x, $packet->y, $packet->z);
            if (!($tile instanceof CommandBlock)) {
                return;
            }

            $tile->setName($packet->name);
            $tile->setCommandBlockMode($packet->commandBlockMode);
            $tile->setCommand($packet->command);
            $tile->setLastOutput($packet->lastOutput);
            $tile->setAuto(!$packet->isRedstoneMode);
            $tile->setConditionalMode($packet->isConditional);

            $player->removeWindow($tile->getInventory());
        }
    }
}