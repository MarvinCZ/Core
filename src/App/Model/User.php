<?php

declare(strict_types=1);

namespace App\Model;

use Core\Model\IUser;

class User implements IUser
{
	const ROLE_AUTHOR = 1;
	const ROLE_REVIEWER = 2;
	const ROLE_ADMIN = 4;

	/** @var int */
	private $id;

	/** @var string */
	private $email;

	/** @var string */
	private $username;

	/** @var string */
	private $firstname;

	/** @var string */
	private $surname;

	/** @var int */
	private $rights;

	/**
	 * User constructor.
	 * @param $id
	 * @param $email
	 * @param $username
	 * @param $firstname
	 * @param $surname
	 * @param $rights
	 * @internal param $name
	 * @internal param $rights
	 */
	public function __construct($id, $email, $username, $firstname, $surname, $rights)
	{
		$this->id = $id;
		$this->email = $email;
		$this->username = $username;
		$this->firstname = $firstname;
		$this->surname = $surname;
		$this->rights = $rights;
	}

	public function getName(): string
	{
		return $this->firstname . ' ' . $this->surname;
	}

	public function getRights(): int
	{
		return $this->rights;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function hasRole(int $role): bool
	{
		return (bool) ($this->rights & $role);
	}

	public function isAuthor(): bool
	{
		return $this->hasRole(self::ROLE_AUTHOR);
	}

	public function isReviewer(): bool
	{
		return $this->hasRole(self::ROLE_REVIEWER);
	}

	public function isAdmin(): bool
	{
		return $this->hasRole(self::ROLE_ADMIN);
	}

}