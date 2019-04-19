<?php

declare(strict_types=1);

namespace Rixafy\Language\Exception;

use Exception;

class LanguageNotProvidedException extends Exception
{
	public static function neverSet(): self
	{
		new self('Language was never set and the default language is missing.');
	}
}