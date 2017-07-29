<?php

/*
 *  _______                                     ______  _
 * /  ____ \                                   |  __  \| \
 * | |    \_|              _                   | |__| || |
 * | |   ___  ___  _  ___ (_) ___  __    _ ___ |  ____/| | _   _  ___
 * | |  |_  |/(_)\| '/_  || |/___\(_)\  ///___\| |     | || | | |/___\
 * | \___|| | |___| |  | || |_\_\   \ \// _\_\ | |     | || | | |_\_\
 * \______/_|\___/|_|  |_||_|\___/   \ /  \___/|_|     |_||__/,_|\___/
 *                                   //
 *                                  (_)                Power by:
 *                                                           Pocketmine-MP
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @由Pocketmine-MP团队创建，GenisysPlus项目组修改
 * @链接 http://www.pocketmine.net/
 * @链接 https://github.com/Tcanw/GenisysPlus
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\plugin\PharPluginLoader;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ExtractPluginCommand extends VanillaCommand {

	/**
	 * ExtractPluginCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"Extracts the source code from a Phar plugin",
			"/extractplugin <pluginName>"
		);
		$this->setPermission("pocketmine.command.extractplugin");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}

		if(count($args) === 0){
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->usageMessage);
			return true;
		}

		$pluginName = trim(implode(" ", $args));
		if($pluginName === "" or !(($plugin = Server::getInstance()->getPluginManager()->getPlugin($pluginName)) instanceof Plugin)){
			$sender->sendMessage(TextFormat::RED . "Invalid plugin name, check the name case.");
			$this->sendPluginList($sender);
			return true;
		}
		$description = $plugin->getDescription();

		if(!($plugin->getPluginLoader() instanceof PharPluginLoader)){
			$sender->sendMessage(TextFormat::RED . "Plugin " . $description->getName() . " is not in Phar structure.");
			return true;
		}

		$folderPath = Server::getInstance()->getPluginPath() . DIRECTORY_SEPARATOR . "GenisysPlus" . DIRECTORY_SEPARATOR . $description->getName() . "_v" . $description->getVersion() . "/";
		if(file_exists($folderPath)){
			$sender->sendMessage("Plugin already exists, overwriting...");
		}else{
			@mkdir($folderPath);
		}

		$reflection = new \ReflectionClass("pocketmine\\plugin\\PluginBase");
		$file = $reflection->getProperty("file");
		$file->setAccessible(true);
		$pharPath = str_replace("\\", "/", rtrim($file->getValue($plugin), "\\/"));

		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pharPath)) as $fInfo){
			$path = $fInfo->getPathname();
			@mkdir(dirname($folderPath . str_replace($pharPath, "", $path)), 0755, true);
			file_put_contents($folderPath . str_replace($pharPath, "", $path), file_get_contents($path));
		}
		$license = "
  _____            _               _____           
 / ____|          (_)             |  __ \          
| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___  
| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \ 
| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/ 
                         __/ |                    
                        |___/         
 ";
		$sender->sendMessage($license);
		$sender->sendMessage("Source plugin " . $description->getName() . " v" . $description->getVersion() . " has been created on " . $folderPath);
		return true;
	}

	/**
	 * @param CommandSender $sender
	 */
	private function sendPluginList(CommandSender $sender){
		$list = "";
		foreach(($plugins = $sender->getServer()->getPluginManager()->getPlugins()) as $plugin){
			if(strlen($list) > 0){
				$list .= TextFormat::WHITE . ", ";
			}
			$list .= $plugin->isEnabled() ? TextFormat::GREEN : TextFormat::RED;
			$list .= $plugin->getDescription()->getFullName();
		}

		$sender->sendMessage(new TranslationContainer("pocketmine.command.plugins.success", [count($plugins), $list]));
	}
}
