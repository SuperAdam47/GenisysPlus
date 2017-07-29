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

namespace pocketmine\block;

use pocketmine\item\Tool;

class Concrete extends Solid {

	protected $id = self::CONCRETE;

	/**
	 * Concrete constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return float
	 */
	public function getHardness(){
		return 1.8;
	}

	/**
	 * @return int
	 */
	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return mixed
	 */
	public function getName(){
		static $names = [
			0 => "White Concrete",
			1 => "Orange Concrete",
			2 => "Magenta Concrete",
			3 => "Light Blue Concrete",
			4 => "Yellow Concrete",
			5 => "Lime Concrete",
			6 => "Pink Concrete",
			7 => "Gray Concrete",
			8 => "Silver Concrete",
			9 => "Cyan Concrete",
			10 => "Purple Concrete",
			11 => "Blue Concrete",
			12 => "Brown Concrete",
			13 => "Green Concrete",
			14 => "Red Concrete",
			15 => "Black Concrete",
		];
		return $names[$this->meta & 0x0f];
	}

}