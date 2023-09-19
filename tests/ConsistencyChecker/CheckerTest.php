<?php

declare(strict_types=1);

namespace Tests\ConsistencyChecker;

use Flexsyscz\Model\ConsistencyChecker\Checker;
use Nette;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class CheckerTest extends TestCase
{
	private Checker $checker;


	public function setUp(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->addConfig(__DIR__ . '/../config/dbal.neon')
			->addConfig(__DIR__ . '/../config/local.neon');

		$configurator->setTempDirectory(__DIR__ . '/../temp');
		$container = $configurator->createContainer();

		$this->checker = $container->getByType(Checker::class);
	}

	public function testChecker(): void
	{
		$result = $this->checker->check($this->checker->report());
		Assert::true($result);
	}
}

(new CheckerTest())->run();
