<?php
namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class BaseController
{
    const FLASH_MESSAGE = 'flash';
    const FLASH_MESSAGE_INFO = 'info';
    const FLASH_MESSAGE_ERROR = 'danger';
    const FLASH_MESSAGE_WARNING = 'warning';
    const FLASH_MESSAGE_SUCCESS = 'success';
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Session
     */
    protected $session;

    /**
     * BaseController constructor.
     * @param Request $request
     * @param Session $session
     */
    public function __construct(
        Request $request,
        Session $session
    ) {
        $this->request = $request;
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    abstract public function execute();

    /**
     * @param $type
     * @param $message
     */
    public function addFlashMessage($type, $message)
    {
        $messages = $this->session->get(self::FLASH_MESSAGE);
        if (!$messages) {
            $messages = [];
        }
        if (!isset($messages[$type])) {
            $messages[$type] = [];
        }
        $messages[$type][] = $message;
        $this->session->set(self::FLASH_MESSAGE, $messages);
    }

    /**
     * @param bool $clear
     * @return mixed
     */
    public function getFlashMessages($clear = true)
    {
        $messages = $this->session->get(self::FLASH_MESSAGE);
        if ($clear) {
            $this->session->set(self::FLASH_MESSAGE, null);
        }
        return $messages;
    }

    /**
     * @param bool $clear
     * @return string
     */
    public function renderFlashMessages($clear = true)
    {
        $messages = $this->getFlashMessages($clear);
        $html = '';
        if ($messages) {
            foreach ($messages as $type => $messageList) {
                $html .= '<div class="alert alert-'.$type.'"><ul>';
                foreach ($messageList as $message) {
                    $html .= '<li>'.$message.'</li>';
                }
                $html .= '</ul></div>';
            }
        }
        return $html;
    }
}
