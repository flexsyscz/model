<?php

declare(strict_types=1);

namespace Tests\Model;

use Nette;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Tester\Assert;
use Tester\TestCase;
use Tests\Resources\Orm;
use Tests\Resources\Users\User;
use Tests\Resources\Users\UserType;

require __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EntityTest extends TestCase
{
	private Orm $orm;


	public function setUp(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->addConfig(__DIR__ . '/../config/dbal.neon')
			->addConfig(__DIR__ . '/../config/local.neon');

		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$container = $configurator->createContainer();

		$this->orm = $container->getByType(Orm::class);
		$this->orm->users->refresh();
	}

	public function testEntityEnumWrapper(): void
	{
		$users = $this->orm->users->findBy(['username' => 'john.doe']);
		foreach ($users->fetchAll() as $user) {
			if ($user instanceof User) {
				Assert::equal(UserType::MANAGER->value, $user->type->value);
			}
		}
	}


	public function testEntityJsonWrapper(): void
	{
		$users = $this->orm->users->findBy(['username' => 'john.doe']);
		foreach ($users->fetchAll() as $user) {
			if ($user instanceof User) {
				Assert::equal(1, $user->profile->array[0]);
				Assert::equal('gold', $user->profile->color);
				Assert::equal(true, $user->profile->boolean);
				Assert::equal(null, $user->profile->null);
			}
		}
	}


	public function testEntitySerializationWrapper(): void
	{
		$users = $this->orm->users->findBy(['username' => 'john.doe']);
		foreach ($users->fetchAll() as $user) {
			if ($user instanceof User) {
				Assert::equal('2020-10-28 12:04', $user->metadata['created']);
				Assert::equal('2020-11-01 11:21', $user->metadata['updated']);
				Assert::equal(null, $user->metadata['photo']);
			}
		}
	}


	public function testCreateEntity(): void
	{
		$user = User::fromValues([
			'username' => 'jack.black',
			'password' => '12345',
			'type' => UserType::ADMIN,
			'profile' => ['test' => 'aaa'],
			'metadata' => ['meta' => true],
		]);

		$this->orm->users->persistAndFlush($user);

		Assert::true($user instanceof User, sprintf('Expected %s', User::class));
		if ($user instanceof User) {
			Assert::notNull($user->createdAt, sprintf('Expected %s', DateTimeImmutable::class));
		}
	}


	public function testUpdateEntity(): void
	{
		$newPassword = '123456';
		$users = $this->orm->users->findBy(['username' => 'john.doe']);
		foreach ($users->fetchAll() as $user) {
			if ($user instanceof User) {
				$user->password = $newPassword;
				$this->orm->users->persistAndFlush($user);
			}
		}

		$users = $this->orm->users->findBy(['username' => 'john.doe']);
		foreach ($users->fetchAll() as $user) {
			Assert::equal($newPassword, $user->password);
			Assert::notNull($user->updatedAt, sprintf('Expected %s', DateTimeImmutable::class));
		}
	}
}

(new EntityTest)->run();
