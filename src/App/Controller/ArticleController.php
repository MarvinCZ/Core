<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\User;
use App\Repository\ArticleRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Core\Controller\BaseController;
use Core\Http\Request;
use Core\Model\IUser;

class ArticleController extends BaseController
{
	/** @var ArticleRepository */
	private $articleRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var ReviewRepository */
	private $reviewRepository;

	function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, ReviewRepository $reviewRepository)
	{
		$this->articleRepository = $articleRepository;
		$this->userRepository = $userRepository;
		$this->reviewRepository = $reviewRepository;
	}

	public function myArticles()
	{
		$this->requiredRole(User::ROLE_AUTHOR);

		$userId = $this->getUser()->getId();
		$articles = $this->articleRepository->getWhere("user_id = ?", [$userId]);
		$this->renderView('article/list.twig', ['articles' => $articles]);
	}

	public function delete($id)
	{
		$this->requiredLogin();
		$article = $this->articleRepository->get($id);

		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$canDelete = $article->getUserId() == $this->getUser()->getId() || $this->getUser()->hasRole(User::ROLE_ADMIN);

		if ($canDelete) {
			$this->articleRepository->remove($id);
			$this->addFlashMessage('success', 'Článek smazán');
			$this->redirect('/');
		} else {
			$this->addFlashMessage('danger', 'Nemáte dostatečná práva');
			$this->redirect('/');
		}
	}

	public function createForm()
	{
		$article = new Article(NULL);
		$article->setAuthors($this->getUser()->getName());
		$this->renderView('article/form.twig', ['article' => $article]);
	}

	public function updateForm($id)
	{
		$article = $this->articleRepository->get($id);

		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$this->renderView('article/form.twig', ['article' => $article]);
	}

	public function show($id)
	{
		$article = $this->articleRepository->get($id);

		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$owner = $this->isLoggedIn() && $article->getUserId() == $this->getUser()->getId();
		$canViewOthers = $this->isLoggedIn() && ($this->getUser()->hasRole(User::ROLE_REVIEWER) || $this->getUser()->hasRole(User::ROLE_ADMIN));

		if (!($article->isPublished() || $canViewOthers || $owner)) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$canEdit = $owner || ($this->isLoggedIn() && $this->getUser()->hasRole(User::ROLE_ADMIN));
		$reviewers = [];
		if ($this->isLoggedIn() && $this->getUser()->hasRole(User::ROLE_ADMIN)) {
			$reviewers = $this->rejectReviewer($this->userRepository->getUsersWithRole(User::ROLE_REVIEWER), $article->getUserId());
		}

		$reviews = [];
		if ($owner || $canViewOthers) {
			$reviews = $this->reviewRepository->getReviewsForArticle($article->getId());
			$this->userRepository->eagerLoad($reviews);
		}

		$canPublish = !$article->isPublished() && $this->getUser()->hasRole(User::ROLE_ADMIN) && $this->reviewRepository->getReviewCountForArticle($id) >= 3;

		$this->renderView('article/show.twig', [
			'article' => $article,
			'canEdit' => $canEdit,
			'reviewers' => $reviewers,
			'reviews' => $reviews,
			'canPublish' => $canPublish,
			'expandedInfo' => $owner || $canViewOthers
		]);
	}

	private function rejectReviewer($reviewers, $rejectId) {
		return array_filter($reviewers, function(IUser $user) use ($rejectId) {
			return $user->getId() != $rejectId;
		});
	}

	public function save(Request $request)
	{
		$id = $request->getParameter('id');
		$name = $request->getParameter('name');
		$authors = $request->getParameter('authors');
		$abstract = $request->getParameter('abstract');
		$filePath = '';

		if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {
			if ($_FILES['file']['error'] != 0) {
				$this->addFlashMessage('danger', 'Chyba nahrávání souboru');
				$this->redirect('/article-create');
			} else {
				$filePath = uniqid("", TRUE) . '.pdf';
				move_uploaded_file($_FILES['file']['tmp_name'], APP_DIR . '/public/storage/' . $filePath);
			}
		} elseif ($id === NULL) {
			$this->addFlashMessage('danger', 'Je nutné přidat soubor');
			$this->redirect('/article-create');
		}

		$article = $id ?
			$this->articleRepository->get($id) :
			new Article(NULL);

		$article->setName($name);
		$article->setAuthors($authors);
		$article->setAbstract($abstract);
		$article->setUserId($this->getUser()->getId());

		if (!empty($filePath)) {
			$article->setFilePath($filePath);
		}

		$this->articleRepository->save($article);
		$this->addFlashMessage('success', 'Článek uložen');
		$this->redirect('/my-articles');
	}
}