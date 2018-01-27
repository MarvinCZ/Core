<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Core\Controller\BaseController;
use Core\Exceptions\DuplicateKeyException;
use Core\Http\Request;
use Core\Repository\IUserRepository;
use Core\Session;

class LoginController extends BaseController
{
	/** @var Session */
	private $session;

	/** @var UserRepository */
	private $userRepository;

	public function __construct(Session $session, IUserRepository $userRepository)
	{
		$this->session = $session;
		$this->userRepository = $userRepository;
	}

	public function login(Request $request)
	{
		$username = $request->getParameter('username');
		$password = $request->getParameter('password');

		$userId = $this->userRepository->findByUsernameAndPassword($username, $password);

		if (!$userId) {
			$this->addFlashMessage('danger', 'Uživatel neexistuje nebo zadané heslo není správné');
			$this->redirect('/login');
		}

		$this->addFlashMessage('success', 'Úspěšně přihlášeno');
		$this->session->set('user_id', $userId);
		$this->redirect('');
	}

	public function loginForm()
	{
		$this->renderView('login/login.twig');
	}

	public function registerForm()
	{
		$this->renderView('login/register.twig');
	}

	public function register(Request $request)
	{
		$username = $request->getParameter('username');
		$firstname = $request->getParameter('firstname');
		$surname = $request->getParameter('surname');
		$email = $request->getParameter('email');
		$password = sha1($request->getParameter('password'));

		$result = NULL;
		try {
			$result = $this->userRepository->registerUser($username, $email, $firstname, $surname, $password, 1);
		} catch (DuplicateKeyException $e) {
			$this->addFlashMessage('danger', 'Uživatelské jméno je již registrované');
			$this->redirect('/register');
		}

		if ($result) {
			$this->addFlashMessage('success', 'Úspěšně registrováno, nyní se můžete přihlásit');
			$this->redirect('/');
		} else {
			$this->addFlashMessage('danger', 'Při registraci došlo k chybě');
			$this->redirect('/register');
		}
	}

	public function logout()
	{
		$this->addFlashMessage('success', 'Úspěšně odhlášeno');
		$this->session->unset('user_id');
		$this->redirect('');
	}

}