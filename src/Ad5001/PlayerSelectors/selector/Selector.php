<?php
declare(strict_types=1);

namespace Ad5001\PlayerSelectors\selector;

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\math\Vector3;

abstract class Selector {
    /**
     * Default minecraft selectors params.
     * Can be used in selectors that supports them.
     */
    const DEFAULT_PARAMS = [
        // Coordinates of the center, will be modified if player in command
        "x" => 0, 
        "y" => 0, 
        "z" => 0, 
        "lvl" => "", // The world to search in. Defaults to the current world in case of console, current world in the case of a player
        // Search by coordinates
        "dx" => 0, // Distance from the center in x (0 = no limit)
        "dy" => 0, // Distance from the center in y (0 = no limit)
        "dz" => 0, // Distance from the center in z (0 = no limit)
        "r" => 0, // Radius max (0 = no limit)
        "rm" => 0, // Radius min
        // Search by pitch and yaw
        "rx" => 180, // Pitch max
        "rxm" => 0, // Pitch min
        "ry" => 360, // Yaw max
        "rym" => 0, // Yaw min
        // Count searching
        "c" => 0,
        // Player parameters searching
        "m" => -1, // Gamemode
        "l" => PHP_INT_MAX, // Maximum xp level
        "lm" => 0, // Min xp level
        "name" => "", // Displayed name of the entity (display name for the player, name tag rename for entity)
        "type" => "all", // Type of the entity (only for entity selector). "all" means all entities
        "tag" => "Health", // Check if the tag exists in the entity/player.
    ];

    protected $name;
    protected $selectorChar;
    protected $acceptModifiers;
    
    /**
     * Defines base for a selector
     *
     * @param string $name  The name of the selector. E.g: "All players", "Entities", ...
     * @param string $selectorChar  What should be after the "@" to use this selector
     * @param bool $acceptModifiers Should the selector accept modifiers? If false, arguments used in commands will be ignored. Can save alot of performances.
     */
    public function __construct(string $name, string $selectorChar, bool $acceptModifiers){
        $this->name = $name;
        $this->selectorChar = $selectorChar;
        $this->acceptModifiers = $acceptModifiers;
    }
    
    /**
     * Returns the name of the selector. E.g: "All players", "Entities", ...
     *
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }
        
    /**
     * Returns the character of the selector. What should be after the "@" to use this selector
     *
     * @return string
     */
    public function getSelectorChar(): string{
        return $this->selectorChar;
    }
    
    /**
     * Should the selector accept modifiers? If false, arguments used in commands will be ignored. Can save alot of performances.
     *
     * @return bool
     */
    public function acceptsModifiers(): bool{
        return $this->acceptModifiers;
    }

    /**
     * Gets all the possible values for a selector depending on the sender and the parameters.
     * Return should be an array of all the possible strings that match up to this selector.
     *
     * @param CommandSender $sender
     * @param String[] $parameters
     * @return String[]
     */
    abstract public function applySelector(CommandSender $sender, array $parameters = []): array;

    /**
     * Check if an entity $et matches default params provided in $params.
     *
     * @param Entity $et
     * @param array $params
     * @return bool
     */
    public function checkDefaultParams(Entity $et, array $params): bool{ 
        $dist = sqrt($et->distanceSquared(new Vector3($params["x"], $params["y"], $params["z"])));
        if(($params["r"] !== 0 && $dist > $params["r"]) || $dist < $params["rm"]) return false; // Not in range
        if($params["dx"] !== 0 && abs($et->x - $params["x"]) > $params["dx"]); // Not in x range
        if($params["dy"] !== 0 && abs($et->y - $params["y"]) > $params["dy"]); // Not in y range
        if($params["dz"] !== 0 && abs($et->z - $params["z"]) > $params["dz"]); // Not in z range
        if($params["m"] !== -1 && $et instanceof Player && $et->getGamemode() !== $params["m"]) return false; // Not in the right mode.
        if($params["rx"] < $et->getPitch() || $et->getPitch() < $params["rxm"]) return false; // Not in range pitch
        if($params["ry"] < $et->getYaw() || $et->getYaw() < $params["rym"]) return false; // Not in range yaw
        if($et instanceof Player && ($et->getXpLevel() > $params["l"] || $et->getXpLevel() < $params["lm"])) return false; // Not in range XP
        if($params["name"] !== "" && (($et instanceof Player && $et->getDisplayName() !== $params["name"]) || 
            (!($et instanceof Player) && $et->getNameTag() !== $params["name"]))) return false; // Not selected name
        // Entity type
        $etClassName = explode("\\", get_class($et))[count(explode("\\", get_class($et))) - 1];
        if(substr($params["type"], 0, 1) == "!" && $etClassName == substr($params["type"], 1)) return false; // Should not be this kind of entity
        if($params["type"] !== "all" && $etClassName !== $params["type"]) return false; // Not the required kind of entity
        return true; // All checks passed!
    }
}