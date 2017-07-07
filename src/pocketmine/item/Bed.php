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

namespace pocketmine\item;

use pocketmine\block\Block;

class Bed extends Item{

	const WHITE_BED = 0;
	const ORANGE_BED = 1;
	const MAGENTA_BED = 2;
	const LIGTH_BLUE_BED = 3;
	const YELLOW_BED = 4;
	const LIME_BED = 5;
	const PINK_BED = 6;
	const GRAY_BED = 7;
	const LIGHT_GRAY_BED = 8;
	const CYAN_BED = 9;
	const PURPLE_BED = 10;
	const BLUE_BED = 11;
	const BROWN_BED = 12;
	const GREEN_BED = 13;
	const RED_BED = 14;
	const BLACK_BED = 15;

	public function __construct($meta = self::WHITE_BED, $count = 1){
		$this->block = Block::get(Item::BED_BLOCK, $meta);
		parent::__construct(self::BED, $meta, $count, "Bed"); //TODO: Bed Names
	}

	public function getMaxStackSize() : int{
		return 1;
	}
}
