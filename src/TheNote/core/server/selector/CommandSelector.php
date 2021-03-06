<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\selector;

use pocketmine\command\CommandSender;

use TheNote\core\server\selector\arguments\DistanceArgument;
use TheNote\core\server\selector\arguments\EntityTypeArgument;
use TheNote\core\server\selector\arguments\GamemodeArgument;
use TheNote\core\server\selector\arguments\IArgument;
use TheNote\core\server\selector\arguments\LevelArgument;
use TheNote\core\server\selector\arguments\LimitArgument;
use TheNote\core\server\selector\arguments\NameArgument;
use TheNote\core\server\selector\arguments\WorldArgument;
use TheNote\core\server\selector\arguments\XPositionArgument;
use TheNote\core\server\selector\arguments\XRelativePositionArgument;
use TheNote\core\server\selector\arguments\XRotationArgument;
use TheNote\core\server\selector\arguments\YPositionArgument;
use TheNote\core\server\selector\arguments\YRelativePositionArgument;
use TheNote\core\server\selector\arguments\YRotationArgument;
use TheNote\core\server\selector\arguments\ZPositionArgument;
use TheNote\core\server\selector\arguments\ZRelativePositionArgument;

use TheNote\core\server\selector\variables\AllEntityVariable;
use TheNote\core\server\selector\variables\AllPlayerVariable;
use TheNote\core\server\selector\variables\IVariable;
use TheNote\core\server\selector\variables\NearestPlayerVariable;
use TheNote\core\server\selector\variables\RandomPlayerVariable;
use TheNote\core\server\selector\variables\SenderVariable;

use function array_key_exists;
use function count;
use function explode;
use function preg_replace;
use function substr;

class CommandSelector {

    private $variables = [];
    private $arguments = [];

    public function __construct() {
        $this->initVariables();
        $this->initArgument();
    }

    private function initVariables() : void {
        $this->registerVariable(new AllEntityVariable());
        $this->registerVariable(new AllPlayerVariable());
        $this->registerVariable(new NearestPlayerVariable());
        $this->registerVariable(new RandomPlayerVariable());
        $this->registerVariable(new SenderVariable());
    }

    private function initArgument() : void {
        $this->registerArgument(new DistanceArgument());
        $this->registerArgument(new EntityTypeArgument());
        $this->registerArgument(new GamemodeArgument());
        $this->registerArgument(new LevelArgument());
        $this->registerArgument(new LimitArgument());
        $this->registerArgument(new NameArgument());
        $this->registerArgument(new WorldArgument());
        $this->registerArgument(new XPositionArgument());
        $this->registerArgument(new XRelativePositionArgument());
        $this->registerArgument(new XRotationArgument());
        $this->registerArgument(new YPositionArgument());
        $this->registerArgument(new YRelativePositionArgument());
        $this->registerArgument(new YRotationArgument());
        $this->registerArgument(new ZPositionArgument());
        $this->registerArgument(new ZRelativePositionArgument());
    }

    public function registerVariable(IVariable $variable) : void {
        $this->variables[$variable->getVariable()] = $variable;
    }

    public function existVariable(string $key) : bool {
        return array_key_exists($key, $this->variables);
    }

    public function getVariable(string $key) : ?IVariable {
        if ($this->existVariable($key)) {
            return $this->variables[$key];
        }
        return null;
    }

    public function removeVariable(string $key) : void {
        unset($this->variables[$key]);
    }

    public function registerArgument(IArgument $argument) : void {
        $this->arguments[$argument->getArgument()] = $argument;
    }

    public function existArgument(string $key) : bool {
        return array_key_exists($key, $this->arguments);
    }

    public function getArgument(string $key) : ?IArgument {
        if ($this->existArgument($key)) {
            return $this->arguments[$key];
        }
        return null;
    }

    public function removeArgument(string $key) : void {
        unset($this->arguments[$key]);
    }

    public function getEntities(CommandSender $sender, string $args) : array {
        $key = substr($args, 1, 1);
        if (!array_key_exists($key, $this->variables)) {
            return [];
        }

        $variable = $this->variables[$key];

        $arguments = [];
        if (strlen($args) > 6) { //@p[r=1] > 6
            $str = substr($args, 2, strlen($args) - 2);
            if ($str[0] == "[" && $str[strlen($str) - 1] = "]") {
                $str = substr($str, 1, -1);
                $str = preg_replace("/ /", "", $str);
                $split = explode(",", $str);
                foreach ($split as $s) {
                    $pair = explode("=", $s);
                    if (count($pair) < 2) {
                        continue;
                    }

                    $key = $pair[0];
                    if (substr($key, -1) == "!") {
                        $key = substr($key, 0, -1);
                    }

                    if (!array_key_exists($key, $this->arguments)) {
                        continue;
                    }

                    $arguments[] = $this->arguments[$key];
                }
            }
        }

        $players = $variable->getEntities($sender, $args, $arguments);
        foreach ($arguments as $arg) {
            $players = $arg->selectgetEntities($sender, $args, $arguments, $players);
        }

        return $players;
    }
}