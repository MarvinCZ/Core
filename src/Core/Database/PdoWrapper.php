<?php

declare(strict_types=1);

namespace Core\Database;

use Core\Exceptions\DuplicateKeyException;

class PdoWrapper
{
	/** @var IDatabaseConfiguration */
	private $configuration;

	/** @var \PDO */
	private $connection;

	function __construct(IDatabaseConfiguration $configuration)
	{
		$this->configuration = $configuration;
		$dns = sprintf("mysql:host=%s;dbname=%s", $configuration->getHost(), $configuration->getDatabaseName());
		$this->connection = new \PDO(
			$dns,
			$configuration->getUsername(),
			$configuration->getPassword(),
			[
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				\PDO::ATTR_EMULATE_PREPARES => false,
			]
		);
	}

	public function getAll($query, $params = [])
	{
		$stmt = $this->connection->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getFirst($query, $params = [])
	{
		$stmt = $this->connection->prepare($query);
		$stmt->execute($params);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getValue($query, $params = [])
	{
		$value = $this->getFirst($query, $params);

		return is_array($value) ? reset($value) : $value;
	}

	public function execute($query, $params = [])
	{
		try {
			$stmt = $this->connection->prepare($query);
			$stmt->execute($params);
			return $stmt->rowCount();
		}
		catch (\PDOException $e) {
			if ($e->errorInfo[1] == 1062) {
				throw new DuplicateKeyException();
			}
			throw $e;
		}
	}
}