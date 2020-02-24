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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\handler\PacketHandler;

class NpcRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::NPC_REQUEST_PACKET;

	/** @var int */
	public $entityRuntimeId;
	/** @var int */
	public $requestType;
	/** @var string */
	public $commandString;
	/** @var int */
	public $actionType;

	protected function decodePayload() : void{
		$this->entityRuntimeId = $this->buf->getEntityRuntimeId();
		$this->requestType = $this->buf->getByte();
		$this->commandString = $this->buf->getString();
		$this->actionType = $this->buf->getByte();
	}

	protected function encodePayload() : void{
		$this->buf->putEntityRuntimeId($this->entityRuntimeId);
		$this->buf->putByte($this->requestType);
		$this->buf->putString($this->commandString);
		$this->buf->putByte($this->actionType);
	}

	public function handle(PacketHandler $handler) : bool{
		return $handler->handleNpcRequest($this);
	}
}
