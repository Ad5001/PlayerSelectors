<?php
declare(strict_types=1);


namespace Ad5001\PlayerSelectors\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;


class RandomPlayer extends Selector{
    
    public function __construct(){
        parent::__construct("Random player", "r", true);
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
        $defaultParams = Selector::DEFAULT_PARAMS;
        if($sender instanceof Position){
            $defaultParams["x"] = $sender->x;
            $defaultParams["y"] = $sender->y;
            $defaultParams["z"] = $sender->z;
        }
        $params = $parameters + $defaultParams;
        $possible = [];
        foreach(Server::getInstance()->getOnlinePlayers() as $p){
            if($p->getLevel()->getName() !== $params["lvl"] && $params["lvl"] !== "") continue; // Not in the right level
            if(!$this->checkDefaultParams($p, $params)) continue;
            $possible[] = $p;
        }
        if(count($possible) == 0) return [];
        return [$possible[array_rand($possible)]->getName()];
    }
}