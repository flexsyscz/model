<?php

declare(strict_types=1);

namespace Tests\Resources\Users;

use Nextras\Dbal\Connection;
use Nextras\Orm\Mapper;


final class UsersMapper extends Mapper\Dbal\DbalMapper
{
	public function refresh(): void
	{
		$stack = explode(";\n", file_get_contents(__DIR__ . '/../../config/setup.sql'));

		if (count($stack) > 0) {
			$this->connection->transactional(function (Connection $connection) use ($stack) {
				foreach ($stack as $sql) {
					if (strlen($sql) > 0) {
						$sql = str_replace('[', "[[", $sql);
						$sql = str_replace(']', "]]", $sql);
						$connection->query($sql);
					}
				}
			});
		}
	}
}
