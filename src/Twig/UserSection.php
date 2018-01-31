<?php
namespace Twig;

use Symfony\Component\HttpFoundation\Session\Session;

class UserSection implements FunctionInterface
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template;

    /**
     * UserSection constructor.
     * @param Session $session
     * @param \Twig_Environment $twig
     * @param string $template
     */
    public function __construct(
        Session $session,
        \Twig_Environment $twig,
        $template = 'misc/user.html.twig'
    ) {
        $this->session = $session;
        $this->twig = $twig;
        $this->template = $template;
    }

    /**
     * @return \Twig_Function
     */
    public function getFunction()
    {
        $session = $this->session;
        $twig = $this->twig;
        $template = $this->template;
        return new \Twig_Function('user_section', function () use ($session, $twig, $template) {
            return $twig->render($template, ['session' => $session]);
        });
    }
}
