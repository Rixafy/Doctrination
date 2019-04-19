<?php

declare(strict_types=1);

namespace Rixafy\Language\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class LanguageNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Language with id "' . $id . '" not found.');
	}

	public static function byIso(string $iso): self
	{
		return new self('Language with iso "' . $iso . '" not found.');
	}
}
