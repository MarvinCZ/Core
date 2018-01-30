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

	/** @var string */
	private $password;

	/** @var bool */
	private $banned;

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
	public function __construct($id, $email, $username, $firstname, $surname, $rights, $password, $banned)
	{
		$this->id = $id;
		$this->email = $email;
		$this->username = $username;
		$this->firstname = $firstname;
		$this->surname = $surname;
		$this->rights = $rights;
		$this->password = $password;
		$this->banned = (bool) $banned;
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

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getFirstname(): string
	{
		return $this->firstname;
	}

	/**
	 * @return string
	 */
	public function getSurname(): string
	{
		return $this->surname;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
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

	public function isBanned(): bool
	{
		return $this->banned;
	}

	public function addRole($role)
	{
		$this->rights = $this->rights | $role;
	}

	public function removeRole($role)
	{
		$this->rights = ($this->rights | $role) - $role;
	}

	/**
	 * @param bool $banned
	 */
	public function setBanned(bool $banned)
	{
		$this->banned = $banned;
	}

}