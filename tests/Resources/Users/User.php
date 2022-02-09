<?php

declare(strict_types=1);

namespace Tests\Resources\Users;

use Flexsyscz\Model\Entity;
use Nextras\Dbal\Utils\DateTimeImmutable;


/**
 * @property        int                             $id                         {primary}
 * @property        string                          $username
 * @property        string                          $password
 * @property        UserType                        $type                       {wrapper \Flexsyscz\Model\Wrappers\EnumWrapper}
 * @property 		string							$profile					{wrapper \Flexsyscz\Model\Wrappers\JsonWrapper}
 * @property 		string							$metadata					{wrapper \Flexsyscz\Model\Wrappers\SerializationWrapper}
 * @property        DateTimeImmutable               $createdAt
 * @property        DateTimeImmutable|null          $updatedAt
 */
class User extends Entity
{
}
