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

namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;


class ElderGuardian extends Animal {
	const NETWORK_ID = 50;

	public $width = 1.45;
	public $length = 1.45;
	public $height = 0;

	public $dropExp = [5, 5];

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Elder Guardian";
	}

	public function initEntity(){
		$this->setMaxHealth(80);
		$this->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_ELDER, true);
		parent::initEntity();
	}

	/**
	 * @param Player $player
	 */
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = ElderGuardian::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}

	/**
	 * @return array
	 */
	public function getDrops(){
		$drops = [
			ItemItem::get(ItemItem::PRISMARINE_CRYSTALS, 0, mt_rand(0, 1))
		];
		$drops[] = ItemItem::get(ItemItem::PRISMARINE_SHARD, 0, mt_rand(0, 2));

		return $drops;
	}
}
