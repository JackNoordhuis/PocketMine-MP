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

namespace pocketmine\plugin;

/**
 * Handles different types of plugins
 */
class PharPluginLoader implements PluginLoader{

	/** @var \ClassLoader */
	private $loader;

	/** @var \ClassLoader */
	private $embeddedLoader;

	public function __construct(\ClassLoader $loader, \ClassLoader $embeddedLoader){
		$this->loader = $loader;
		$this->embeddedLoader = $embeddedLoader;
	}

	public function canLoadPlugin(string $path) : bool{
		$ext = ".phar";
		return is_file($path) and substr($path, -strlen($ext)) === $ext;
	}

	/**
	 * Loads the plugin contained in $file
	 *
	 * @param string $file
	 */
	public function loadPlugin(string $file) : void{
		$phar = new \Phar($file);

		//check if a composer.json file exists in the phar, if so treat it as a composer plugin
		if(isset($phar["composer.json"]) and $phar["composer.json"] instanceof \PharFileInfo){
			$this->embeddedLoader->addPath($file);
		} else { //regular old phar plugin
			$this->loader->addPath("$file/src");
		}
	}

	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 *
	 * @return null|PluginDescription
	 */
	public function getPluginDescription(string $file) : ?PluginDescription{
		$phar = new \Phar($file);
		if(isset($phar["plugin.yml"])){
			$pluginYml = $phar["plugin.yml"];
			if($pluginYml instanceof \PharFileInfo){
				return new PluginDescription($pluginYml->getContent());
			}
		}

		return null;
	}

	public function getAccessProtocol() : string{
		return "phar://";
	}
}
