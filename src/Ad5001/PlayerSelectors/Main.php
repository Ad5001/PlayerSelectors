<?php

declare(strict_types=1);

namespace Ad5001\PlayerSelectors;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use Ad5001\PlayerSelectors\selector\Selector;
use Ad5001\PlayerSelectors\selector\ClosestPlayer;
use Ad5001\PlayerSelectors\selector\AllPlayers;
use Ad5001\PlayerSelectors\selector\RandomPlayer;
use Ad5001\PlayerSelectors\selector\WorldPlayers;
use Ad5001\PlayerSelectors\selector\Entities;
use Ad5001\PlayerSelectors\selector\SelfSelector;

/**
 * Class Main
 * @package Ad5001\PlayerSelectors
 */
class Main extends PluginBase {

	/** @var Selector[] */
    protected static $selectors = [];

    public function onEnable(): void{
    	$listener = new EventListener($this);
        $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        // Registering the default selectors
		self::registerSelectors([
			new AllPlayers(),
			new ClosestPlayer(),
			new Entities(),
			new RandomPlayer(),
			new SelfSelector(),
			new WorldPlayers()
		]);
    }

    /**
     * Parses selectors and executes the commands
     * @param string $m The command
     * @param CommandSender $sender
     * @return bool - If selectors were found or not.
     */
    public function execSelectors(string $m, CommandSender $sender): bool{
        preg_match_all($this->buildRegex(), $m, $matches);
        $commandsToExecute = [$m];
        foreach($matches[0] as $index => $match){
            if(isset(self::$selectors[$matches[1][$index]])){ // Does the selector exist?
                // Search for the parameters
                $params = self::$selectors[$matches[1][$index]]->acceptsModifiers() ? $this->checkArgParams($matches, $index): [];
                // Applying the selector
                $newCommandsToExecute = [];
                foreach($commandsToExecute as $index => $cmd){
                    // Foreaching the returning commands to push them to the new commands to be executed at the next run.
                    foreach(self::$selectors[$matches[1][$index]]->applySelector($sender, $params) as $selectorStr){
                        if(strpos($selectorStr, " ") !== -1) $selectorStr = explode(" ", $selectorStr)[count(explode(" ", $selectorStr)) - 1]; // Name w/ spaces. Match the nearest name in the player. Not perfect :/
                        $newCommandsToExecute[] = substr_replace($cmd, " " . $selectorStr . " ", strpos($cmd, $match), strlen($match));
                    }
                    if(count($newCommandsToExecute) == 0) {
                        $sender->sendMessage("§cYour selector $match (" . self::$selectors[$matches[1][$index]]->getName() . ") did not mactch any player/entity.");
                        return true;
                    }
                }
                $commandsToExecute = $newCommandsToExecute;
            }
        }
        if(!isset($matches[0][0])) return false;
        // Then we have all the commands here and we can execute them
        foreach($commandsToExecute as $cmd){
            $this->getServer()->dispatchCommand($sender, $cmd);
        }
        return true;
    }

    /**
     * Return all the params in an array form in a match.
     * @param array $match
	 * @param int $index
     * @return array
     */
    public function checkArgParams(array $match, int $index): array{
        $params = [];
        if(strlen($match[2][$index]) !== 0){ // Is there any command parameter?
            if(strpos($match[3][$index], ",") !== -1){ // Is there multiple arguments
                foreach(explode(",", $match[3][$index]) as $param){
                    // Param here is in form argName=argProperty.
                    // Parsing it to put it into the $params
                    $parts = explode("=", $param);
                    $params[$parts[0]] = $parts[1];
                }
            } else { // There is one argument
                $parts = explode("=", $match[3][$index]);
                $params[$parts[0]] = $parts[1];
            }
        }
        return $params;
    }

    /**
     * Build the regex for parsing selectors in commands
     * $1 is the selector character(s)
     * $2 is "Is there any arguments to the command?"
     * $3 is the list of arguments
     * @return string
     */
    public function buildRegex(): string {
        $regex = "/ @("; // Space is to check that it's an argument on it's own and not a part of one
        // Adding the selectors
        $regex .= preg_replace("/(\\$|\\(|\\)|\\^|\\[|\\])/", "\\\\$1", // Always parse input we don't trust!
            implode("|", array_keys(self::$selectors))
        ); 
        // Adding the arguments
        $regex .= ")(\\[(((\w+)?=(.)+(,)?){1,})\\])?";
        // Closing the regex
        $regex .= "( |$)/"; // Space is to check that it's an argument on it's own and not a part of one (cf twitter accounts would we used with @+some letters)
        return $regex;
    }

	/**
	 * Register some selectors
	 * @param array $sels
	 * @return void
	 */
    public static function registerSelectors(array $sels): void {
    	foreach ($sels as $sel) {
    		self::registerSelector($sel);
		}
	}
    
    /**
     * Registers a selector
     * @param Selector $sel
     * @return void
     */
    public static function registerSelector(Selector $sel): void {
        self::$selectors[$sel->getSelectorChar()] = $sel;
    }
        
    /**
     * Unregisters a selector
     * @param string $selChar The selector character
     * @return void
     */
    public static function unregisterSelector(string $selChar = ""): void {
    	if (!empty($selChar)) {
    		if (isset(self::$selectors[$selChar])) {
    			unset(self::$selectors[$selChar]);
			}else {
    			throw new \InvalidArgumentException($selChar." was not registered.");
			}
		}else {
			self::$selectors = [];
		}
    }

    /**
     * Returns a selector
     * @param string $selChar The selector character
     * @return null|Selector
     */
    public static function getSelector(string $selChar): ?Selector {
        return self::$selectors[$selChar] ?? null;
    }
}
