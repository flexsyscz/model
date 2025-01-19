<?php

declare(strict_types=1);

namespace Flexsyscz\Model\ConsistencyChecker;

use Nette\Database\Structure;


final class Checker
{
	private Structure $structure;

	/** @var string[]|null */
	private ?array $errors = null;

	/** @var string[]|null */
	private ?array $warnings = null;


	public function __construct(Structure $structure)
	{
		$this->structure = $structure;
	}


	/**
	 * @return  array<int|string, array<int|string, array<string>>>
	 */
	public function report(): array
	{
		$report = [];
		foreach ($this->structure->getTables() as $table) {
			$tableName = $table['name'];
			foreach ($this->structure->getColumns($tableName) as $column) {
				$columnName = $column['name'];
				$report[$tableName][$columnName] = [
					'type' => $column['vendor']['type'],
					'nullable' => $column['nullable'],
					'collation' => $column['vendor']['collation'],
				];
			}
		}

		return $report;
	}


	/**
	 * @param  array<int|string, array<int|string, array<string>>> $remote
	 * @param  array<int|string, array<int|string, array<string>>>|null $local
	 * @param  string[]|null $description
	 * @return bool
	 */
	public function check(array $remote, ?array $local = null, ?array $description = null): bool
	{
		$this->errors = [];
		$this->warnings = [];

		$local = $local ?? $this->report();
		$description = $description && array_key_exists('remote', $description) && array_key_exists('local', $description) ? $description : ['remote' => 'REMOTE', 'local' => 'LOCAL'];
		$parameters = ['type', 'nullable', 'collation'];

		foreach ($remote as $table => $columns) {
			if (!array_key_exists($table, $local)) {
				$this->errors[] = sprintf("[%s] Table '%s' not found", $description['local'], $table);
				continue;
			}

			foreach ($columns as $name => $column) {
				if (!array_key_exists($name, $local[$table])) {
					$this->errors[] = sprintf("[%s] Column '%s'.'%s' not found", $description['local'], $table, $name);
					continue;
				}

				foreach ($parameters as $parameter) {
					if (!array_key_exists($parameter, $local[$table][$name]) && array_key_exists($parameter, $column)) {
						$this->errors[] = sprintf("[%s] Column '%s' has not defined parameter '%s'", $description['local'], $name, $parameter);
					} else {
						if (!array_key_exists($parameter, $column)) {
							$this->errors[] = sprintf("[%s] Column '%s' has not defined parameter '%s'", $description['remote'], $name, $parameter);
						} else if ($column[$parameter] !== $local[$table][$name][$parameter]) {
							$this->warnings[] = sprintf("[%s] Column '%s' parameter '%s' is not equal with local: '%s !== %s'", $description['remote'], $name, $parameter, $column[$parameter], $local[$table][$name][$parameter]);
						}
					}
				}
			}
		}

		foreach ($local as $table => $columns) {
			if (!array_key_exists($table, $remote)) {
				$this->errors[] = sprintf("[%s] Table '%s' not found", $description['remote'], $table);
				continue;
			}

			foreach ($columns as $name => $column) {
				if (!array_key_exists($name, $remote[$table])) {
					$this->errors[] = sprintf("[%s] Column '%s'.'%s' not found", $description['remote'], $table, $name);
					continue;
				}

				foreach ($parameters as $parameter) {
					if (!array_key_exists($parameter, $remote[$table][$name]) && array_key_exists($parameter, $column)) {
						$this->errors[] = sprintf("[%s] Column '%s' has not defined parameter '%s'", $description['remote'], $name, $parameter);
					} else {
						if (!array_key_exists($parameter, $column)) {
							$this->errors[] = sprintf("[%s] Column '%s' has not defined parameter '%s'", $description['local'], $name, $parameter);
						} else if ($column[$parameter] !== $remote[$table][$name][$parameter]) {
							$this->warnings[] = sprintf("[%s] Column '%s' parameter '%s' is not equal with local: '%s !== %s'", $description['local'], $name, $parameter, $column[$parameter], $remote[$table][$name][$parameter]);
						}
					}
				}
			}
		}

		return empty($this->errors);
	}


	/**
	 * @return string[]|null
	 */
	public function getErrors(): ?array
	{
		return $this->errors;
	}


	/**
	 * @return string[]|null
	 */
	public function getWarnings(): ?array
	{
		return $this->warnings;
	}
}
