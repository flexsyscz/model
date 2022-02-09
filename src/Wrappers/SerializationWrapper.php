<?php

declare(strict_types=1);

namespace Flexsyscz\Model\Wrappers;

use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;


class SerializationWrapper extends ImmutableValuePropertyWrapper
{
	/**
	 * @param array<mixed>|null $value
	 * @return string|null
	 */
	public function convertToRawValue($value): ?string
	{
		return is_array($value) ? @serialize($value) : null;
	}


	/**
	 * @param mixed $value
	 * @return array<mixed>|null
	 */
	public function convertFromRawValue($value): ?array
	{
		$result = is_string($value) ? @unserialize($value) : null;
		return is_array($result) ? $result : null;
	}
}
