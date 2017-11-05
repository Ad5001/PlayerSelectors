<?php
declare(strict_types=1);


namespace Ad5001\PlayerSelectors\selector;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;

use Ad5001\PlayerSelectors\Main;


class Entities extends Selector{
    
    public function __construct(){
        parent::__construct("Entities", "e", true);
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
        $return = [];
        foreach(Server::getInstance()->getLevels() as $lvl){
            foreach($lvl->getEntities() as $e){
                if($params["c"] !== 0 && count($return) == $params["c"]) continue; // Too much players
                if($e->getLevel()->getName() !== $params["lvl"] && $params["lvl"] !== "") continue; // Not in the right level
                if(!$this->checkDefaultParams($e, $params)) continue;
                $return[] = "e" . $e->getId();
            }
        }
        return array_merge($return, Main::getSelector("a")->applySelector($sender, $parameters)); // Merging w/ all players so that it also adds players.
    }
}