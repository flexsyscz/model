<?php

declare(strict_types=1);

namespace Tests\Resources\Users;

use Nextras\Orm\Repository\Repository;

/**
 * @method  void     refresh()
 */
final class UsersRepository extends Repository
{
	/**
	 * @return array
	 */
	static function getEntityClassNames() : array
	{
		return [User::class];
	}
}
