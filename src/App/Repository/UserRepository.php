<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;
use App\Model\Review;
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
			return $this->transformRow($user);
		}
		return NULL;
	}

	/**
	 * @return User[]
	 */
	public function getAll()
	{
		$rows = $this->pdoWrapper->getAll("SELECT * FROM user");

		return $this->transformRows($rows);
	}

	public function findByUsernameAndPassword($username, $password)
	{
		$row = $this->pdoWrapper->getFirst("SELECT * FROM user WHERE username = ? AND password = ?", [$username, sha1($password)]);

		return $row ? $this->transformRow($row) : NULL;
	}

	public function registerUser($username, $email, $firstname, $surname, $password, $rights)
	{
		$result = $this->pdoWrapper->execute(
			"INSERT INTO user (username, email, firstname, surname, password, rights) VALUES (?,?,?,?,?,?)",
			[$username, $email, $firstname, $surname, $password, $rights]
		);

		return $result;
	}

	/**
	 * Find all users with given role
	 * @param int $role
	 * @return array
	 */
	public function getUsersWithRole(int $role)
	{
		$users = $this->pdoWrapper->getAll("SELECT * FROM user WHERE rights & ? > 0", [$role]);

		$out = [];
		foreach ($users as $user) {
			$out []= $this->transformRow($user);
		}

		return $out;
	}

	/**
	 * Adds proper users to reviews or articles
	 * @param Review[]|Article[] $array
	 */
	public function eagerLoad($array)
	{
		if (!$array) {
			return;
		}
		$idList = [];
		foreach ($array as $entity) {
			$idList []= $entity->getUserId();
		}

		$in  = str_repeat('?,', count($idList) - 1) . '?';
		$rows = $this->pdoWrapper->getAll("SELECT * FROM user WHERE id IN ($in)", $idList);
		$users = $this->transformRows($rows);
		$userIdHash = [];
		foreach ($users as $user) {
			$userIdHash[$user->getId()] = $user;
		}

		foreach ($array as $entity) {
			$entity->setUser($userIdHash[$entity->getUserId()]);
		}
	}

	public function save(User $user)
	{
		if ($user->getId()) {
			$this->update($user);
		} else {
			$this->insert($user);
		}
	}

	private function transformRow($row)
	{
		return new User(
			$row['id'],
			$row['email'],
			$row['username'],
			$row['firstname'],
			$row['surname'],
			$row['rights'],
			$row['password'],
			$row['banned']
		);
	}

	/** @return User[] */
	private function transformRows($rows)
	{
		$out = [];
		foreach ($rows as $row) {
			$out []= $this->transformRow($row);
		}
		return $out;
	}

	private function insert(User $user)
	{
		$this->pdoWrapper->execute(
			"INSERT INTO user (username, email, firstname, surname, password, rights, banned) VALUES (?,?,?,?,?,?,?)",
			[
				$user->getUsername(),
				$user->getEmail(),
				$user->getFirstname(),
				$user->getSurname(),
				$user->getPassword(),
				$user->getRights(),
				$user->isBanned(),
			]
		);
	}

	private function update(User $user)
	{
		$this->pdoWrapper->execute(
			"UPDATE user SET username = ?, email = ?, firstname = ?, surname = ?, password = ?, rights = ?, banned = ? WHERE id = ?",
			[
				$user->getUsername(),
				$user->getEmail(),
				$user->getFirstname(),
				$user->getSurname(),
				$user->getPassword(),
				$user->getRights(),
				$user->isBanned(),
				$user->getId(),
			]
		);
	}

	public function delete(User $user)
	{
		$this->pdoWrapper->execute("DELETE FROM user WHERE id = ?", [$user->getId()]);
	}
}