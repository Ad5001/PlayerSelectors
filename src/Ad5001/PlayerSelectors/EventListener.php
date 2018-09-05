<?php

declare(strict_types=1);

namespace Ad5001\PlayerSelectors;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\server\CommandEvent;

/**
 * Class EventListener
 * @package Ad5001\PlayerSelectors
 */
class EventListener implements Listener {

	/** @var Main */
	private $main;

	public function __construct(Main $plugin) {
		$this->main = $plugin;
	}

	/**
	 * When a command is executed, check for selectors
	 * @priority HIGHEST
	 * @param PlayerCommandPreProcessEvent $event
	 * @return void
	 */
	public function onPlayerCommand(PlayerCommandPreprocessEvent $event): void{
		$m = substr($event->getMessage(), 1);
		if(substr($event->getMessage(), 0, 1) == "/" && $this->main->execSelectors($m, $event->getPlayer())) {
			$event->setCancelled();
		}
	}

	/**
	 * When a command is executed, check for selectors
	 * @priority HIGHEST
	 * @param CommandEvent $event
	 * @return void
	 */
	public function onConsoleCommand(CommandEvent $event): void {
		$m = $event->getCommand();
		if ($this->main->execSelectors($m, $event->getSender())) {
			$event->setCancelled();
		}
	}
}