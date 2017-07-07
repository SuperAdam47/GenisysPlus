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

#include <rules/DataPacket.h>
namespace pocketmine\network\protocol;


class CameraPacket extends DataPacket{
	
	const NETWORK_ID = Info::CAMERA_PACKET;
	public $eid;
	
	public function decode(){
	}
	
	public function encode(){
		$this->reset();
		$this->putVarInt($this->eid);
		$this->putVarInt($this->eid);
	}

}