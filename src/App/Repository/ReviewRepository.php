<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Review;
use Core\Database\PdoWrapper;

class ReviewRepository
{

	/** @var PdoWrapper */
	private $pdoWrapper;

	public function __construct(PdoWrapper $pdoWrapper)
	{
		$this->pdoWrapper = $pdoWrapper;
	}

	public function get($id)
	{
		$row = $this->pdoWrapper->getFirst("SELECT * FROM review WHERE id = ?", [$id]);
		return $row ? $this->transformRow($row) : NULL;
	}


	public function getReviewsForUser($userId)
	{
		$reviews = $this->pdoWrapper->getAll("SELECT * FROM review WHERE user_id = ?", [$userId]);
		return $this->transformRows($reviews);
	}

	/**
	 * @return Review[]
	 */
	public function getReviewsForArticle($articleId)
	{
		$reviews = $this->pdoWrapper->getAll("SELECT * FROM review WHERE article_id = ?", [$articleId]);
		return $this->transformRows($reviews);
	}

	public function getReviewCountForArticle($articleId)
	{
		return $this->pdoWrapper->getValue("SELECT COUNT(id) FROM review WHERE done AND article_id = ?", [$articleId]);
	}

	public function save(Review $review)
	{
		if ($review->getId()) {
			$this->update($review);
		} else {
			$this->insert($review);
		}
	}

	private function transformRows($rows)
	{
		$out = [];
		foreach ($rows as $review) {
			$out []= $this->transformRow($review);
		}
		return $out;
	}

	private function transformRow($row)
	{
		$review = new Review($row['id'], $row['user_id'], $row['article_id']);
		$review->fromRow($row);
		return $review;
	}

	private function insert(Review $review)
	{
		$this->pdoWrapper->execute(
			"INSERT INTO review(user_id, article_id, language, technical_quality, utility, audience_diversity, originality, sum, suggestion, text, done) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
			[
				$review->getUserId(),
				$review->getArticleId(),
				$review->getLanguage(),
				$review->getTechnicalQuality(),
				$review->getUtility(),
				$review->getAudienceDiversity(),
				$review->getOriginality(),
				$review->getSum(),
				$review->getSuggestion(),
				$review->getText(),
				$review->isDone()
			]
		);
	}

	private function update(Review $review)
	{
		$this->pdoWrapper->execute(
			"UPDATE review SET user_id = ?, article_id = ?, language = ?, technical_quality = ?, utility = ?, audience_diversity = ?, originality = ?, sum = ?, suggestion = ?, text = ?, done = ? WHERE id = ?",
			[
				$review->getUserId(),
				$review->getArticleId(),
				$review->getLanguage(),
				$review->getTechnicalQuality(),
				$review->getUtility(),
				$review->getAudienceDiversity(),
				$review->getOriginality(),
				$review->getSum(),
				$review->getSuggestion(),
				$review->getText(),
				$review->isDone(),
				$review->getId(),
			]
		);
	}

}