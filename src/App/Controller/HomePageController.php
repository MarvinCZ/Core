<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use Core\Controller\BaseController;

class HomePageController extends BaseController
{

	/** @var ArticleRepository */
	private $articleRepository;

	public function __construct(ArticleRepository $articleRepository)
	{
		$this->articleRepository = $articleRepository;
	}

	public function index()
	{
		$articles = $this->articleRepository->getWhere("published");

		$this->renderView('homepage/index.twig', ['articles' => $articles]);
	}
}