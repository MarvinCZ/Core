<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Review;
use App\Model\User;
use App\Repository\ArticleRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Core\Controller\BaseController;

class AdminController extends BaseController
{

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var ReviewRepository */
	private $reviewRepository;

	public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, ReviewRepository $reviewRepository)
	{
		$this->articleRepository = $articleRepository;
		$this->userRepository = $userRepository;
		$this->reviewRepository = $reviewRepository;
	}

	/**
	 * Makes sure that user is logged and is admin
	 */
	public function init()
	{
		parent::init();
		$this->requiredRole(User::ROLE_ADMIN);
	}

	public function users()
	{
		$users = $this->userRepository->getAll();

		$this->renderView('admin/users.twig', ['users' => $users]);
	}

	/**
	 * Makes article published if conditions are met
	 * @param $id
	 */
	public function publishArticle($id)
	{
		$article = $this->articleRepository->get($id);

		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$canBePublished = $this->reviewRepository->getReviewCountForArticle($id) >= Review::MIN_REVIEW_COUNT;
		if ($canBePublished) {
			$article->setPublished(TRUE);
			$this->articleRepository->save($article);
			$this->addFlashMessage('success', 'Článek byl úspěšně zveřejněn');
		} else {
			$this->addFlashMessage('danger', 'Není dostatek vyplněných recenzí');
		}

		$this->redirect('/article/' . $id);
	}

	/**
	 * Rejects article, it also makes article unpublished
	 * @param $id
	 */
	public function rejectArticle($id)
	{
		$article = $this->articleRepository->get($id);

		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->redirect('/');
		}

		$article->setRejected(TRUE);
		$this->articleRepository->save($article);
		$this->addFlashMessage('success', 'Článek byl odmítnut');
		$this->redirect('/article/' . $id);
	}

	/**
	 * Adds or removes role from given user
	 * @param int $id user id
	 * @param string $action remove or add
	 * @param string $role rote to be added/removed
	 */
	public function role($id, $action, $role)
	{
		if ($action !== "remove" && $action !== "add") {
			$this->addFlashMessage('danger', 'Nedefinovaná akce');
			$this->redirect('/admin/users');
		}

		$roleValue = $this->getRole($role);

		if (!$roleValue) {
			$this->addFlashMessage('danger', 'Nedefinovaná role');
			$this->redirect('/admin/users');
		}

		/** @var User $user */
		$user = $this->userRepository->getUser($id);

		if (!$user) {
			$this->addFlashMessage('danger', 'Uživatel neexistuje');
			$this->redirect('/admin/users');
		}

		if ($action === "remove") {
			$user->removeRole($roleValue);
		} else {
			$user->addRole($roleValue);
		}

		$this->userRepository->save($user);

		$this->addFlashMessage('success', 'Role úspěšně změněna');
		$this->redirect('/admin/users');
	}

	/**
	 * Blocks given user
	 * @param $id
	 */
	public function block($id)
	{
		/** @var User $user */
		$user = $this->userRepository->getUser($id);

		if (!$user) {
			$this->addFlashMessage('danger', 'Uživatel neexistuje');
			$this->redirect('/admin/users');
		}

		$user->setBanned(TRUE);
		$this->userRepository->save($user);

		$this->addFlashMessage('success', 'Uživatel úspěšně zablokován');
		$this->redirect('/admin/users');
	}

	/**
	 * Unblocks given user
	 * @param $id
	 */
	public function unblock($id)
	{
		/** @var User $user */
		$user = $this->userRepository->getUser($id);

		if (!$user) {
			$this->addFlashMessage('danger', 'Uživatel neexistuje');
			$this->redirect('/admin/users');
		}

		$user->setBanned(FALSE);
		$this->userRepository->save($user);

		$this->addFlashMessage('success', 'Uživatel úspěšně odblokován');
		$this->redirect('/admin/users');
	}

	/**
	 * Deletes given user
	 * His articles and reviews will still exists
	 * @param $id
	 */
	public function deleteUser($id)
	{
		/** @var User $user */
		$user = $this->userRepository->getUser($id);

		if (!$user) {
			$this->addFlashMessage('danger', 'Uživatel neexistuje');
			$this->redirect('/admin/users');
		}

		$this->userRepository->delete($user);

		$this->addFlashMessage('success', 'Uživatel úspěšně smazán');
		$this->redirect('/admin/users');
	}

	/**
	 * Lists all articles
	 */
	public function articles()
	{
		$articles = $this->articleRepository->getAll();
		$this->userRepository->eagerLoad($articles);
		$this->renderView('admin/articles.twig', ['articles' => $articles]);
	}

	private function getRole($role)
	{
		$roles = [
			'author' => User::ROLE_AUTHOR,
			'reviewer' => User::ROLE_REVIEWER,
			'admin' => User::ROLE_ADMIN,
		];

		return $roles[$role] ?? NULL;
	}
}