<?php

declare(strict_types=1);

namespace Flexsyscz\Model\Wrappers;

use BackedEnum;
use Flexsyscz\Model\InvalidArgumentException;
use Nette\Utils\Callback;
use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;


class EnumWrapper extends ImmutableValuePropertyWrapper
{
	private string $enumClass;


	public function __construct(PropertyMetadata $propertyMetadata)
	{
		parent::__construct($propertyMetadata);

		if (count($propertyMetadata->types) !== 1) {
			throw new InvalidArgumentException('Invalid count of types.');
		}

		$this->enumClass = key($propertyMetadata->types);
		if (!class_exists($this->enumClass)) {
			throw new InvalidArgumentException(sprintf('Class %s not found.', $this->enumClass));
		}
	}


	/**
	 * @param mixed $value
	 * @return string|null
	 */
	public function convertToRawValue($value): ?string
	{
		if ($value instanceof BackedEnum) {
			return (string) $value->value;
		}

		return null;
	}


	/**
	 * @param string|null $value
	 * @return BackedEnum|null
	 */
	public function convertFromRawValue($value): ?BackedEnum
	{
		$enumClass = $this->enumClass;
		$result = $value === null
			? null
			: forward_static_call(Callback::check([$enumClass, 'from']), $value);

		return $result instanceof BackedEnum ? $result : null;
	}
}
