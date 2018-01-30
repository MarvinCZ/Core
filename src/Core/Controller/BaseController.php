<?php

namespace Core\Controller;

use Core\Application;
use Core\Exceptions\AlreadyRendered;
use Core\Http\Request;
use Core\Model\IUser;
use Core\Repository\IUserRepository;
use Core\Session;
use Core\View;

class BaseController
{
    private $rendered = false;
    private $request;
    /** @var View */
	private $view;

	/** @var IUserRepository */
	private $userRepository;

	/** @var Session */
	private $session;

	/** @var IUser */
	private $user;

	/** @var Application */
	private $application;

	/** @var array */
	private $flashMessages;

	public function init()
	{
		$this->flashMessages = $this->session->get('flashMessages') ?? [];
		$this->session->unset('flashMessages');
	}

	public function setApplication(Application $application)
	{
		$this->application = $application;
	}

	protected function render(string $output)
    {
        if ($this->isRendered()) {
            throw new AlreadyRendered();
        }

        echo $output;
        $this->rendered = true;
    }

    protected function renderJSON($data = [])
    {
    	$data['flashMessages'] = $this->flashMessages;
        $this->render(json_encode($data));
    }

    protected function renderView($name, $params = [])
    {
    	$params['user'] = $this->getUser();
	    $params['flashMessages'] = $this->flashMessages;
    	echo $this->view->renderToString($name, $params);
    }

    protected function isAJAXRequest()
    {
        return strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') !== false ||
            preg_match('/^[\/]js[\/]/', $_SERVER['REQUEST_URI']);
    }

    /**
     * @return bool
     */
    public function isRendered(): bool
    {
        return $this->rendered;
    }

    public function injectRequest(Request $request)
    {
        $this->request = $request;
    }

    public function injectView(View $view)
    {
    	$this->view = $view;
    }

	public function injectUserRepository(IUserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
    }

	public function injectSession(Session $session)
	{
		$this->session = $session;
    }

	public function addFlashMessage($type, $message)
	{
		$this->flashMessages []= ['type' => $type, 'message' => $message];
    }

	public function getUser(): ?IUser
	{
		if (!$this->isLoggedIn()) return NULL;

		if ($this->user == NULL) {
			$this->user = $this->userRepository->getUser($this->session->get('user_id'));
		}

		return $this->user;
    }

	public function isLoggedIn()
	{
		$userId = $this->session->get('user_id');
		return $userId != NULL;
	}

	public function redirect($link)
	{
		$this->session->set('flashMessages', $this->flashMessages);
		$this->application->redirect($link);
	}

	public function requiredRole(int $role)
	{
		$this->requiredLogin();
		if (!$this->getUser()->hasRole($role)) {
			$this->addFlashMessage('danger', 'Nedostatečná oprávění');
			$this->redirect('/');
		}
	}

	public function requiredLogin()
	{
		if (!$this->isLoggedIn()) {
			$this->addFlashMessage('danger', 'Je nutné být přihlášen');
			$this->redirect('/login');
		}
	}
}
