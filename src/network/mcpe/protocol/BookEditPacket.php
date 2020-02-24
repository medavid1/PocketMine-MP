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

use pocketmine\network\BadPacketException;
use pocketmine\network\mcpe\handler\PacketHandler;

class BookEditPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::BOOK_EDIT_PACKET;

	public const TYPE_REPLACE_PAGE = 0;
	public const TYPE_ADD_PAGE = 1;
	public const TYPE_DELETE_PAGE = 2;
	public const TYPE_SWAP_PAGES = 3;
	public const TYPE_SIGN_BOOK = 4;

	/** @var int */
	public $type;
	/** @var int */
	public $inventorySlot;
	/** @var int */
	public $pageNumber;
	/** @var int */
	public $secondaryPageNumber;

	/** @var string */
	public $text;
	/** @var string */
	public $photoName;

	/** @var string */
	public $title;
	/** @var string */
	public $author;
	/** @var string */
	public $xuid;

	protected function decodePayload() : void{
		$this->type = $this->buf->getByte();
		$this->inventorySlot = $this->buf->getByte();

		switch($this->type){
			case self::TYPE_REPLACE_PAGE:
			case self::TYPE_ADD_PAGE:
				$this->pageNumber = $this->buf->getByte();
				$this->text = $this->buf->getString();
				$this->photoName = $this->buf->getString();
				break;
			case self::TYPE_DELETE_PAGE:
				$this->pageNumber = $this->buf->getByte();
				break;
			case self::TYPE_SWAP_PAGES:
				$this->pageNumber = $this->buf->getByte();
				$this->secondaryPageNumber = $this->buf->getByte();
				break;
			case self::TYPE_SIGN_BOOK:
				$this->title = $this->buf->getString();
				$this->author = $this->buf->getString();
				$this->xuid = $this->buf->getString();
				break;
			default:
				throw new BadPacketException("Unknown book edit type $this->type!");
		}
	}

	protected function encodePayload() : void{
		$this->buf->putByte($this->type);
		$this->buf->putByte($this->inventorySlot);

		switch($this->type){
			case self::TYPE_REPLACE_PAGE:
			case self::TYPE_ADD_PAGE:
				$this->buf->putByte($this->pageNumber);
				$this->buf->putString($this->text);
				$this->buf->putString($this->photoName);
				break;
			case self::TYPE_DELETE_PAGE:
				$this->buf->putByte($this->pageNumber);
				break;
			case self::TYPE_SWAP_PAGES:
				$this->buf->putByte($this->pageNumber);
				$this->buf->putByte($this->secondaryPageNumber);
				break;
			case self::TYPE_SIGN_BOOK:
				$this->buf->putString($this->title);
				$this->buf->putString($this->author);
				$this->buf->putString($this->xuid);
				break;
			default:
				throw new \InvalidArgumentException("Unknown book edit type $this->type!");
		}
	}

	public function handle(PacketHandler $handler) : bool{
		return $handler->handleBookEdit($this);
	}
}
