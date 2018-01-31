<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Review;
use App\Model\User;
use App\Repository\ArticleRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Core\Controller\BaseController;
use Core\Http\Request;

class ReviewController extends BaseController
{
	/** @var ReviewRepository */
	private $reviewRepository;

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var UserRepository */
	private $userRepository;

	public function __construct(ReviewRepository $reviewRepository, ArticleRepository $articleRepository, UserRepository $userRepository)
	{
		$this->reviewRepository = $reviewRepository;
		$this->articleRepository = $articleRepository;
		$this->userRepository = $userRepository;
	}

	/**
	 * Shows reviews which belongs to logged in user
	 */
	public function list()
	{
		$this->requiredRole(User::ROLE_REVIEWER);

		$reviews = $this->reviewRepository->getReviewsForUser($this->getUser()->getId());
		$this->articleRepository->eagerLoad($reviews);

		$this->renderView('review/list.twig', ['reviews' => $reviews]);
	}

	/**
	 * Creates blank reviews and attaches them to given article and users
	 * If user already has such review he will be ignored
	 *
	 * @param Request $request
	 * @param $id
	 */
	public function addToArticle(Request $request, $id)
	{
		$article = $this->articleRepository->get($id);
		if (!$article) {
			$this->addFlashMessage('danger', 'Článek neexistuje');
			$this->renderJSON();
			return;
		}
		$reviewers = $request->getParameter('reviewers');
		if (empty($reviewers)) {
			$this->addFlashMessage('danger', 'Je nutné vybrat alespoň jednoho uživatele');
			$this->renderJSON();
			return;
		}
		$set = [];
		foreach ($reviewers as $userId) {
			$set[$userId] = true;
		}

		$reviews = $this->reviewRepository->getReviewsForArticle($id);

		if ($reviews) {
			foreach ($reviews as $review) {
				unset($set[$review->getUserId()]);
			}
		}
		unset($set[$article->getUserId()]);

		foreach ($set as $userId => $garbage) {
			$review = new Review(NULL, $userId, (int)$id);
			$this->reviewRepository->save($review);
		}

		$this->addFlashMessage('success', 'Recenze vyžádány');
		$this->renderJSON();
	}

	/**
	 * Shows form for editing review, blocks user when article was published
	 * @param $id
	 */
	public function reviewForm($id)
	{
		$this->requiredRole(User::ROLE_REVIEWER);
		$review = $this->reviewRepository->get($id);

		if (!$review || $this->getUser()->getId() != $review->getUserId()) {
			$this->addFlashMessage('danger', 'Hodnoceni neexistuje');
			$this->redirect('/');
		}

		$this->articleRepository->eagerLoad([$review]);

		if ($review->getArticle()->isPublished()) {
			$this->addFlashMessage('danger', 'Hodnoceni již nelze změnit');
			$this->redirect('/');
		}

		$this->renderView('review/form.twig', ['review' => $review]);
	}

	public function show($id)
	{
		$this->requiredLogin();
		$review = $this->reviewRepository->get($id);
		if (!$review) {
			$this->addFlashMessage('danger', 'Hodnoceni neexistuje');
			$this->redirect('/');
		}
		$this->articleRepository->eagerLoad([$review]);
		$userId = $this->getUser()->getId();
		if ($review->getArticle()->getUserId() != $userId && $review->getUserId() != $userId && !$this->getUser()->hasRole(User::ROLE_ADMIN)) {
			$this->addFlashMessage('danger', 'Nedostatečná práva');
			$this->redirect('/');
		}

		$this->userRepository->eagerLoad([$review]);

		$this->renderView('review/show.twig', ['review' => $review]);

	}

	/**
	 * Saves review
	 * @param Request $request
	 * @param $id
	 */
	public function save(Request $request, $id)
	{
		$this->requiredRole(User::ROLE_REVIEWER);
		$review = $this->reviewRepository->get($id);
		if ($review == NULL || $this->getUser()->getId() != $review->getUserId()) {
			$this->redirect('/');
		}
		$review->setLanguage((int)$request->getParameter('language'));
		$review->setTechnicalQuality((int)$request->getParameter('techquality'));
		$review->setUtility((int)$request->getParameter('utility'));
		$review->setAudienceDiversity((int)$request->getParameter('audience'));
		$review->setOriginality((int)$request->getParameter('originality'));
		$review->setSuggestion((int)$request->getParameter('suggestion'));
		$review->setText($request->getParameter('text'));
		$this->reviewRepository->save($review);

		$this->addFlashMessage('success', 'Úspěšně uloženo');
		$this->redirect('/review/' . $id);
	}
}