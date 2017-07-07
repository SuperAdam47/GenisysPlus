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

namespace pocketmine\network\protocol;

#include <rules/DataPacket.h>

class PlaySoundPacket extends DataPacket{

	const NETWORK_ID = Info::PLAY_SOUND_PACKET;

	public $sound;
	public $x;
	public $y;
	public $z;
	public $volume;
	public $pitch;

	public function decode(){
		$this->sound = $this->getString();
		$this->getBlockCoords($this->x, $this->y, $this->z);
		$this->volume = $this->getFloat();
		$this->pitch = $this->getFloat();
	}

	public function encode(){
		$this->reset();
		$this->putString($this->sound);
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putFloat($this->volume);
		$this->putFloat($this->pitch);
	}

}
