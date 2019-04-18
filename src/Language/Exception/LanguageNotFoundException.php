<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class LanguageNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Language with id "' . $id . '" not found.');
	}

	public static function byIso(UuidInterface $id): self
	{
		return new self('Language with iso "' . $id . '" not found.');
	}
}