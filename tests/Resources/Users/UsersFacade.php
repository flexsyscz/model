<?php

declare(strict_types=1);

namespace Tests\Resources\Users;

use Flexsyscz\Model\Facade;
use Flexsyscz\Model\Values;
use Nextras;


final class UsersFacade extends Facade
{
	public function create(): Nextras\Orm\Entity\IEntity
	{
		$entity = new User();

		$this->getRepository()->persist($entity);
		return $entity;
	}


	public function update(Nextras\Orm\Entity\IEntity $entity, Values $values): void
	{
		if($entity instanceof User) {
			$this->getRepository()->persist($entity);
		}
	}


	public function delete(Nextras\Orm\Entity\IEntity $entity): void
	{
		if($entity instanceof User) {
			$this->getRepository()->remove($entity);
		}
	}


	/**
	 * @return User[]|Nextras\Orm\Entity\IEntity[]
	 */
	public function getAllUsers(): array
	{
		return $this->getRepository()->findAll()->fetchAll();
	}
}
