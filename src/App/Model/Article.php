<?php

declare(strict_types=1);

namespace App\Model;

class Article
{
	/** @var int */
	private $id;
	/** @var string */
	private $name;
	/** @var string */
	private $authors;
	/** @var string */
	private $abstract;
	/** @var string */
	private $file;
	/** @var int */
	private $userId;
	/** @var bool */
	private $published;
	/** @var bool */
	private $rejected;
	/** @var User */
	private $user;

	function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getAuthors(): ?string
	{
		return $this->authors;
	}

	/**
	 * @return string
	 */
	public function getAbstract(): ?string
	{
		return $this->abstract;
	}

	/**
	 * @return string
	 */
	public function getFilePath(): ?string
	{
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getUserId(): ?int
	{
		return $this->userId;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @return bool
	 */
	public function isRejected(): bool
	{
		return $this->rejected;
	}

	/**
	 * @return bool
	 */
	public function isPublished(): bool
	{
		return $this->published;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @param string $authors
	 */
	public function setAuthors(string $authors)
	{
		$this->authors = $authors;
	}

	/**
	 * @param string $abstract
	 */
	public function setAbstract(string $abstract)
	{
		$this->abstract = $abstract;
	}

	/**
	 * @param string $filePath
	 */
	public function setFilePath(string $filePath)
	{
		$this->file = $filePath;
	}

	/**
	 * @param int $userId
	 */
	public function setUserId(int $userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @param bool $published
	 */
	public function setPublished(bool $published)
	{
		if ($published) {
			$this->rejected = FALSE;
		}
		$this->published = $published;
	}

	/**
	 * @param bool $rejected
	 */
	public function setRejected(bool $rejected)
	{
		if ($rejected) {
			$this->published = FALSE;
		}
		$this->rejected = $rejected;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

}