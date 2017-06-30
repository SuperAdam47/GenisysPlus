<?php

/*
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * 
 *
 * 
 *
 */

namespace pocketmine\item;

class EnderPearl extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(Item::ENDER_PEARL, $meta, $count, "Ender Pearl");
	}
	public function getMaxStackSize() : int{
		return 16;
	}
}