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
 *                                                           Tesseract
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @由Tessetact团队创建，GenisysPlus项目组修改
 * @链接 https://github.com/TesseractTeam
 * @链接 https://github.com/Tcanw/GenisysPlus
 *
 */
 
namespace pocketmine\tile;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class BeaconDelayedCheckTask extends Task {
	
	private $pos;
	private $levelId;
	
	public function __construct(Vector3 $pos, $levelId) {
		$this->pos = $pos;
		$this->levelId = $levelId;
	}
	
	public function onRun($currentTick) {
		$level = Server::getInstance()->getLevel($this->levelId);
		if (!Server::getInstance()->isLevelLoaded($level->getName()) || !$level->isChunkLoaded($this->pos->x >> 4, $this->pos->z >> 4)) return;
		$tile = $level->getTile($this->pos);
		if ($tile instanceof Beacon) {
			$tile->scheduleUpdate();
		}
	}
}