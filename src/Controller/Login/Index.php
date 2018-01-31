<?php
namespace Controller\Login;

use Controller\ControllerInterface;
use Model\ResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Index implements ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $pageTitle;

    /**
     * Index constructor.
     * @param Request $request
     * @param Session $session
     * @param \Twig_Environment $twig
     * @param ResponseFactory $responseFactory
     * @param string $template
     * @param string $pageTitle
     */
    public function __construct(
        Request $request,
        Session $session,
        \Twig_Environment $twig,
        ResponseFactory $responseFactory,
        $template = 'login/index.html.twig',
        $pageTitle = 'Login'
    ) {
        $this->request         = $request;
        $this->session         = $session;
        $this->twig            = $twig;
        $this->responseFactory = $responseFactory;
        $this->template        = $template;
        $this->pageTitle       = $pageTitle;

    }

    /**
     * @return string|RedirectResponse
     */
    public function execute()
    {
        if ($this->session->get('user')) {
            $url = $this->request->getBaseUrl().'/';
            return $this->responseFactory->create(ResponseFactory::REDIRECT, ['url' => $url]);
        }
        return $this->twig->render(
            $this->template,
            [
                'back' => $this->request->get('back', null),
                'page_title' => $this->pageTitle
            ]
        );
    }
}
