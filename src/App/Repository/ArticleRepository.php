<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Article;
use App\Model\Review;
use Core\Database\PdoWrapper;

class ArticleRepository
{

	/** @var PdoWrapper */
	private $pdoWrapper;

	public function __construct(PdoWrapper $pdoWrapper)
	{
		$this->pdoWrapper = $pdoWrapper;
	}

	public function save(Article $article)
	{
		if ($article->getId()) {
			$this->update($article);
		} else {
			$this->insert($article);
		}
	}

	public function get($id)
	{
		$row = $this->pdoWrapper->getFirst("SELECT * FROM article WHERE id = ?", [$id]);
		return $row ? $this->transformRow($row) : NULL;
	}

	public function getAll()
	{
		return $this->getWhere("1");
	}

	public function getWhere($query, $params = [])
	{
		$result = $this->pdoWrapper->getAll("SELECT * FROM article WHERE " . $query, $params);
		return $this->transformRows($result);
	}

	public function remove($id)
	{
		$this->pdoWrapper->execute("DELETE FROM article WHERE id = ?", [$id]);
	}

	private function insert(Article $article)
	{
		$this->pdoWrapper->execute(
			"INSERT INTO article(name, authors, abstract, file, user_id, published, rejected) VALUES (?,?,?,?,?,?,?)",
			[
				$article->getName(),
				$article->getAuthors(),
				$article->getAbstract(),
				$article->getFilePath(),
				$article->getUserId(),
				$article->isPublished(),
				$article->isRejected(),
			]
		);
	}

	private function update(Article $article)
	{
		$this->pdoWrapper->execute(
			"UPDATE article SET name=?, authors=?, abstract=?, file=?, user_id=?, published=?, rejected=? WHERE id = ?",
			[
				$article->getName(),
				$article->getAuthors(),
				$article->getAbstract(),
				$article->getFilePath(),
				$article->getUserId(),
				$article->isPublished(),
				$article->isRejected(),
				$article->getId(),
			]
		);
	}

	/** @return Article[] */
	private function transformRows($rows)
	{
		$out = [];
		foreach ($rows as $row) {
			$out []= $this->transformRow($row);
		}
		return $out;
	}

	private function transformRow($row): Article
	{
		$article = new Article($row['id']);

		$article->setName($row['name']);
		$article->setAuthors($row['authors']);
		$article->setAbstract($row['abstract']);
		$article->setFilePath($row['file']);
		$article->setUserId($row['user_id']);
		$article->setPublished((bool) $row['published']);
		$article->setRejected((bool) $row['rejected']);

		return $article;
	}

	/**
	 * Adds proper articles to reviews
	 * @param Review[] $reviews
	 */
	public function eagerLoad($reviews)
	{
		if (!$reviews) {
			return;
		}
		$idList = [];
		foreach ($reviews as $review) {
			$idList []= $review->getArticleId();
		}

		$in  = str_repeat('?,', count($idList) - 1) . '?';
		$articleRows = $this->pdoWrapper->getAll("SELECT * FROM article WHERE id IN ($in)", $idList);
		$articles = $this->transformRows($articleRows);
		$articleIdHash = [];
		foreach ($articles as $article) {
			$articleIdHash[$article->getId()] = $article;
		}

		foreach ($reviews as $review) {
			$review->setArticle($articleIdHash[$review->getArticleId()]);
		}
	}
}