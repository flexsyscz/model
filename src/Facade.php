<?php

declare(strict_types=1);

namespace Flexsyscz\Model;

use Nextras;


abstract class Facade
{
	protected Nextras\Orm\Model\Model $orm;

	public function __construct(Nextras\Orm\Model\Model $orm)
	{
		$this->orm = $orm;
	}


	public function getRepository(): Nextras\Orm\Repository\IRepository
	{
		$className = (string) preg_replace('#Facade$#', 'Repository', get_class($this));
		if(!class_exists($className) || !is_subclass_of($className, Nextras\Orm\Repository\IRepository::class)) {
			throw new InvalidArgumentException("Repository '$className' does not exist.");
		}

		return $this->orm->getRepository($className);
	}


	public function flush(): void
	{
		$this->orm->flush();
	}


	abstract public function create(): Nextras\Orm\Entity\IEntity;
	abstract public function update(Nextras\Orm\Entity\IEntity $entity, Values $values): void;
	abstract public function delete(Nextras\Orm\Entity\IEntity $entity): void;
}
