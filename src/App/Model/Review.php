<?php

declare(strict_types=1);

namespace App\Model;

class Review
{
	const MIN_REVIEW_COUNT = 3;

	/** @var int */
	private $id;

	/** @var int */
	private $userId;

	/** @var int */
	private $articleId;

	/** @var Article */
	private $article;

	/** @var int */
	private $language = 0;

	/** @var int */
	private $technicalQuality = 0;

	/** @var int */
	private $utility = 0;

	/** @var int */
	private $audienceDiversity = 0;

	/** @var int */
	private $originality = 0;

	/** @var int */
	private $sum = 0;

	/** @var int */
	private $suggestion = 0;

	/** @var string */
	private $text = '';

	/** @var bool */
	private $done;

	/** @var User */
	private $user;

	public function __construct($id, $userId, $articleId)
	{
		$this->id = $id;
		$this->userId = $userId;
		$this->articleId = $articleId;
		$this->done = FALSE;
	}

	public function fromRow($row)
	{
		$this->language = $row['language'] ?? 0;
		$this->technicalQuality = $row['technical_quality'] ?? 0;
		$this->utility = $row['utility'] ?? 0;
		$this->audienceDiversity = $row['audience_diversity'] ?? 0;
		$this->originality = $row['originality'] ?? 0;
		$this->sum = $row['sum'] ?? 0;
		$this->suggestion = $row['suggestion'] ?? 0;
		$this->text = $row['text'] ?? '';
		$this->done = (bool)$row['done'];
		$this->updateSum();
	}

	/**
	 * @return int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->userId;
	}

	/**
	 * @return int
	 */
	public function getArticleId(): int
	{
		return $this->articleId;
	}

	/**
	 * @return int
	 */
	public function getLanguage(): int
	{
		return $this->language;
	}

	/**
	 * @return int
	 */
	public function getTechnicalQuality(): int
	{
		return $this->technicalQuality;
	}

	/**
	 * @return int
	 */
	public function getUtility(): int
	{
		return $this->utility;
	}

	/**
	 * @return int
	 */
	public function getAudienceDiversity(): int
	{
		return $this->audienceDiversity;
	}

	/**
	 * @return int
	 */
	public function getOriginality(): int
	{
		return $this->originality;
	}

	/**
	 * @return int
	 */
	public function getSum(): int
	{
		return $this->sum;
	}

	/**
	 * @return int
	 */
	public function getSuggestion(): int
	{
		return $this->suggestion;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @return bool
	 */
	public function isDone(): bool
	{
		return $this->done;
	}

	/**
	 * @return Article
	 */
	public function getArticle(): Article
	{
		return $this->article;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	public function getRating()
	{
		return $this->sum/5;
	}

	private function updateSum()
	{
		$this->sum = $this->language + $this->technicalQuality + $this->utility + $this->audienceDiversity + $this->originality;
		$this->updateComplete();
	}

	private function updateComplete()
	{
		$this->done =
			$this->language > 0 &&
			$this->technicalQuality > 0 &&
			$this->utility > 0 &&
			$this->audienceDiversity > 0 &&
			$this->originality > 0 &&
			$this->suggestion > 0;
	}

	/**
	 * @param int $language
	 */
	public function setLanguage(int $language)
	{
		$this->language = $language;
		$this->updateSum();
	}

	/**
	 * @param int $technicalQuality
	 */
	public function setTechnicalQuality(int $technicalQuality)
	{
		$this->technicalQuality = $technicalQuality;
		$this->updateSum();
	}

	/**
	 * @param int $utility
	 */
	public function setUtility(int $utility)
	{
		$this->utility = $utility;
		$this->updateSum();
	}

	/**
	 * @param int $audienceDiversity
	 */
	public function setAudienceDiversity(int $audienceDiversity)
	{
		$this->audienceDiversity = $audienceDiversity;
		$this->updateSum();
	}

	/**
	 * @param int $originality
	 */
	public function setOriginality(int $originality)
	{
		$this->originality = $originality;
		$this->updateSum();
	}

	/**
	 * @param int $suggestion
	 */
	public function setSuggestion(int $suggestion)
	{
		$this->suggestion = $suggestion;
		$this->updateComplete();
	}

	/**
	 * @param string $text
	 */
	public function setText(string $text)
	{
		$this->text = $text;
	}

	/**
	 * @param bool $done
	 */
	public function setDone(bool $done)
	{
		$this->done = $done;
	}

	/**
	 * @param Article $article
	 */
	public function setArticle(Article $article)
	{
		$this->article = $article;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

}