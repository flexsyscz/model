<?php

declare(strict_types=1);

namespace Flexsyscz\Model\Wrappers;

use Nette;
use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;


class JsonWrapper extends ImmutableValuePropertyWrapper
{
	/**
	 * @param string|array<mixed>|null $value
	 * @return string|null
	 * @throws Nette\Utils\JsonException
	 */
	public function convertToRawValue($value): ?string
	{
		return is_scalar($value) || is_array($value) ? Nette\Utils\Json::encode($value) : null;
	}


	/**
	 * @param string|null $value
	 * @return mixed
	 * @throws Nette\Utils\JsonException
	 */
	public function convertFromRawValue($value): mixed
	{
		return is_string($value) ? Nette\Utils\Json::decode($value) : null;
	}
}
