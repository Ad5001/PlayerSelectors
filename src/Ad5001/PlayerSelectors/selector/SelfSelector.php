<?php

declare(strict_types=1);

namespace Ad5001\PlayerSelectors\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;

class SelfSelector extends Selector{
    
    public function __construct(){
        parent::__construct("Self", "s", false);
    }

    /**
     * Executes the selector. 
     * Documentation is in the Selector.php file.
     *
     * @param CommandSender $sender
     * @param array $parameters
     * @return array
     */
    public function applySelector(CommandSender $sender, array $parameters = []): array{
        return [$sender->getName()];
    }
}