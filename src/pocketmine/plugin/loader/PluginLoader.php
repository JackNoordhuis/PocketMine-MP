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

namespace pocketmine\plugin\loader;

use pocketmine\plugin\manifest\PluginManifest;

/**
 * Handles different types of plugins
 */
interface PluginLoader{

	/**
	 * Returns whether this PluginLoader can load the plugin in the given path.
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function canLoadPlugin(string $path) : bool;

	/**
	 * Returns the plugins manifest.
	 *
	 * @param string $path
	 *
	 * @return PluginManifest|null
	 */
	public function getPluginManifest(string $path) : ?PluginManifest;

	/**
	 * Returns the protocol prefix used to access files in this plugin format, e.g. file://, phar://
	 *
	 * @return string
	 */
	public function getAccessProtocol() : string;

}