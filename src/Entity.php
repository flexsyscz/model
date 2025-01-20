<?php

declare(strict_types=1);

namespace Flexsyscz\Model;

use Flexsyscz\DateTime\DateTimeProvider;
use Nette\Security\Resource;
use Nextras;


abstract class Entity extends Nextras\Orm\Entity\Entity implements Resource
{
	public function getResourceId(): string
	{
		return static::class;
	}


	/**
	 * @param array<mixed> $values
	 * @return Nextras\Orm\Entity\IEntity
	 */
	public static function fromValues(array $values): Nextras\Orm\Entity\IEntity
	{
		$entity = new (static::class);

		foreach ($values as $name => $value) {
			$entity->setValue($name, $value);
		}

		return $entity;
	}


	public function onCreate(): void
	{
		try {
			$this->setValue('createdAt', DateTimeProvider::now());
		} catch (Nextras\Orm\Exception\InvalidArgumentException) {
		}
	}


	public function onBeforeUpdate(): void
	{
		try {
			$this->setValue('updatedAt', DateTimeProvider::now());
		} catch (Nextras\Orm\Exception\InvalidArgumentException) {
		}
	}
}
