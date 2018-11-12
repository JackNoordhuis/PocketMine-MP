<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\utils;


class EmbeddedClassLoader extends \Threaded implements \ClassLoader {

	/** @var string[] */
	private $paths;

	/** @var bool */
	private static $registered = false;

	public function __construct() {
		$this->paths = new \Threaded;
	}

	/**
	 * Adds a path to the lookup list
	 *
	 * @param string $path
	 * @param bool   $prepend
	 */
	public function addPath($path, $prepend = false){
		foreach($this->paths as $p){
			if($p === $path){
				return;
			}
		}

		$this->paths[] = $path;

		//we need to automatically register the path to the runtime once the loader has been registered
		if(static::$registered){
			global $loader; //the require()d composer autoloader
			$this->registerPath($loader, $path);
		}
	}

	/**
	 * Attaches the ClassLoader to the PHP runtime.
	 *
	 * @param bool $prepend
	 */
	public function register($prepend = false){
		if(static::$registered){
			return;
		}

		static::$registered = true;

		global $loader; //the require()d composer autoloader
		if($loader !== null){
			foreach($this->paths as $path) {
				$this->registerPath($loader, $path);
			}
		}
	}

	/**
	 * Attach a single path to the PHP runtime.
	 *
	 * @param $loader
	 * @param string $path
	 */
	protected function registerPath($loader, string $path) : void{
		Utils::embedComposerPath($loader, $path);
	}
}