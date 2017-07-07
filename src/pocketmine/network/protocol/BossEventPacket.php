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
namespace pocketmine\network\protocol;
#include <rules/DataPacket.h>
class BossEventPacket extends DataPacket{
	const NETWORK_ID = Info::BOSS_EVENT_PACKET;
	const TYPE_SHOW = 0;
	const TYPE_REGISTER_PLAYER = 1;
	const TYPE_HIDE = 2;
	const TYPE_UNREGISTER_PLAYER = 3;
	const TYPE_SET_HEALTH_PERCENT = 4;
	const TYPE_SET_TITLE = 5;
	const TYPE_FLAGS = 6; //TODO: check this
	const TYPE_COLOUR = 7; //this is probably wrong
	public $entityUniqueId;
	public $eventType;
	//TODO: find unknowns
	public $playerEid;
	public $healthPercent;
	public $title;
	public $flags;
	public $unknownVarint1; //unsigned
	public $unknownVarint2; //unsigned
	public function decode(){
		$this->entityUniqueId = $this->getEntityId();
		$this->eventType = $this->getUnsignedVarInt();
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
				$this->playerEid = $this->getEntityId();
				break;
			case self::TYPE_SET_HEALTH_PERCENT:
				$this->healthPercent = $this->getLFloat();
				break;
			case self::TYPE_SET_TITLE:
				$this->title = $this->getString();
				break;
			case self::TYPE_SHOW:
				$this->title = $this->getString();
				$this->healthPercent = $this->getLFloat();
			case self::TYPE_FLAGS:
				$this->flags = $this->getShort();
			case self::TYPE_COLOUR:
				$this->unknownVarint1 = $this->getUnsignedVarInt();
				$this->unknownVarint2 = $this->getUnsignedVarInt();
				break;
			default:
				break;
		}
	}
	public function encode(){
		$this->reset();
		$this->putEntityId($this->entityUniqueId);
		$this->putUnsignedVarInt($this->eventType);
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
				$this->putEntityId($this->playerEid);
				break;
			case self::TYPE_SET_HEALTH_PERCENT:
				$this->putLFloat($this->healthPercent);
				break;
			case self::TYPE_SET_TITLE:
				$this->putString($this->title);
				break;
			case self::TYPE_SHOW:
				$this->putString($this->title);
				$this->putLFloat($this->healthPercent);
			case self::TYPE_FLAGS:
				$this->putShort($this->flags);
			case self::TYPE_COLOUR:
				$this->putUnsignedVarInt($this->unknownVarint1);
				$this->putUnsignedVarInt($this->unknownVarint1);
				break;
			default:
				break;
		}
	}
}
