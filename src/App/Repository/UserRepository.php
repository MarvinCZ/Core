<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User;
use Core\Database\PdoWrapper;
use Core\Model\IUser;
use Core\Repository\IUserRepository;

class UserRepository implements IUserRepository
{

	/** @var PdoWrapper */
	private $pdoWrapper;

	public function __construct(PdoWrapper $pdoWrapper)
	{
		$this->pdoWrapper = $pdoWrapper;
	}

	public function getUser($id): IUser
	{
		$user = $this->pdoWrapper->getFirst("SELECT * FROM user WHERE id = ?", [$id]);
		if ($user) {
			return new User($user['id'], $user['email'], $user['username'], $user['firstname'], $user['surname'], $user['rights']);
		}
		return NULL;
	}

	public function findByUsernameAndPassword($username, $password): ?int
	{
		$userId = $this->pdoWrapper->getValue("SELECT id FROM user WHERE username = ? AND password = ?", [$username, sha1($password)]);
		return $userId ? $userId : NULL;
	}

	public function registerUser($username, $email, $firstname, $surname, $password, $rights)
	{
		$result = $this->pdoWrapper->execute(
			"INSERT INTO user (username, email, firstname, surname, password, rights) VALUES (?,?,?,?,?,?)",
			[$username, $email, $firstname, $surname, $password, $rights]
		);

		return $result;
	}
}