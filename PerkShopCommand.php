<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use onebone\economyapi\EconomyAPI;
use TheNote\core\formapi\SimpleForm;

class PerkShopCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("perkshop", $config->get("prefix") . "Kaufe deine Perks", "/perkshop", ["ps"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $name = $sender->getLowerCaseName();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("info") . "Du kannst dir diesen Perk im Perkshop kaufen");
            return true;
        }

        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            $mymoney = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $player = $sender->getName();
            $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
            $preise = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($mymoney->myMoney($sender) < $preise->get("explode")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("explodeperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("explode")) {
                        $mymoney->reduceMoney($sender, $preise->get("explode"));
                        $daten->set("explodeperkpermission");
                        $daten->set("explodeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 1:
                    if ($mymoney->myMoney($sender) < $preise->get("angry")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("angryperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("angry")) {
                        $mymoney->reduceMoney($sender, $preise->get("angry"));
                        $daten->set("angryperkpermission");
                        $daten->set("angryperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 2:
                    if ($mymoney->myMoney($sender) < $preise->get("redstone")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("redstoneperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("redstone")) {
                        $mymoney->reduceMoney($sender, $preise->get("redstone"));
                        $daten->set("redstoneperkpermission");
                        $daten->set("redstoneperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 3:
                    if ($mymoney->myMoney($sender) < $preise->get("smoke")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("smokeperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("smoke")) {
                        $mymoney->reduceMoney($sender, $preise->get("smoke"));
                        $daten->set("smokeperkpermission");
                        $daten->set("smokeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 4:
                    if ($mymoney->myMoney($sender) < $preise->get("lava")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("lavaperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("lava")) {
                        $mymoney->reduceMoney($sender, $preise->get("lava"));
                        $daten->set("lavaperkpermission");
                        $daten->set("lavaperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 5:
                    if ($mymoney->myMoney($sender) < $preise->get("heart")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("heartperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("heart")) {
                        $mymoney->reduceMoney($sender, $preise->get("heart"));
                        $daten->set("heartperkpermission");
                        $daten->set("heartperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 6:
                    if ($mymoney->myMoney($sender) < $preise->get("flame")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("flameperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("flame")) {
                        $mymoney->reduceMoney($sender, $preise->get("flame"));
                        $daten->set("flameperkpermission");
                        $daten->set("flameperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 7:
                    if ($mymoney->myMoney($sender) < $preise->get("portal")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("portalperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("portal")) {
                        $mymoney->reduceMoney($sender, $preise->get("portal"));
                        $daten->set("portalperkpermission");
                        $daten->set("portalperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 8:
                    if ($mymoney->myMoney($sender) < $preise->get("spore")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("sporeperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("spore")) {
                        $mymoney->reduceMoney($sender, $preise->get("spore"));
                        $daten->set("sporeperkpermission");
                        $daten->set("sporeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 9:
                    if ($mymoney->myMoney($sender) < $preise->get("splash")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($daten->get("splashperkpermission") == true) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("splash")) {
                        $mymoney->reduceMoney($sender, $preise->get("splash"));
                        $daten->set("splashperkpermission");
                        $daten->set("splashperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
            }
        });
        $player = $sender->getLowerCaseName();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $perk = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);
        $form->setTitle($config->get("uiname"));
        $form->setContent("§6===========§f[§dPerkShop§f]§6==========\n\n" .
            "§dWillkommen im Perkshop. Hier kannst du verschiedene Perks Kaufen mit IngameGeld die dann dauerhaft aktiviert sind!\n");
        if ($daten->get("explodeperkpermission") == true) {
            $form->addButton("§0ExplodePerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0ExplodePerk\n§cKostet : " . $perk->get("explode"),0);
        }
        if ($daten->get("angryperkpermission") == true) {
            $form->addButton("§0VillagerAngryPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0VillagerAngryPerk\n§cKostet : " . $perk->get("angry"),0);
        }
        if ($daten->get("redstoneperkpermission") == true) {
            $form->addButton("§0RedstonePerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0RedstonePerk\n§cKostet : " . $perk->get("redstone"),0);
        }
        if ($daten->get("smokeperkpermission") == true) {
            $form->addButton("§0RauchPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0RauchPerk\n§cKostet : " . $perk->get("smoke"),0);
        }
        if ($daten->get("lavaperkpermission") == true) {
            $form->addButton("§0LavaPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0LavaPerk\n§cKostet : " . $perk->get("lava"),0);
        }
        if ($daten->get("heartperkpermission") == true) {
            $form->addButton("§0HerzPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0HerzPerk\n§cKostet : " . $perk->get("heart"), 0);
        }
        if ($daten->get("flameperkpermission") == true) {
            $form->addButton("§0FlammenPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0FlammenPerk\n§cKostet : " . $perk->get("flame"),0);
        }
        if ($daten->get("portalperkpermission") == true) {
            $form->addButton("§0PortalPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0PortalPerk\n§cKostet : " . $perk->get("portal"),0);
        }
        if ($daten->get("sporeperkpermission") == true) {
            $form->addButton("§0SporenPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0SporenPerk\n§cKostet : " . $perk->get("spore"),0);
        }
        if ($daten->get("splashperkpermission") == true) {
            $form->addButton("§0WasserPerk\n§aGekauft", 0);
        } else {
            $form->addButton("§0WasserPerk\n§cKostet : " . $perk->get("splash"),0);
        }
        $form->sendToPlayer($sender);
        return true;
    }
}
