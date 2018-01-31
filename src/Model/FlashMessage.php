<?php
namespace Model;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashMessage
{
    const FLASH_MESSAGE         = 'flash';
    const FLASH_MESSAGE_INFO    = 'info';
    const FLASH_MESSAGE_ERROR   = 'error';
    const FLASH_MESSAGE_WARNING = 'warning';
    const FLASH_MESSAGE_SUCCESS = 'success';
    /**
     * @var Session
     */
    private $session;

    /**
     * FlashMessage constructor.
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    /**
     * @param $type
     * @param $message
     * @throws \Exception
     */
    public function addFlashMessage($type, $message)
    {
        $allowedTypes = [
            self::FLASH_MESSAGE_INFO,
            self::FLASH_MESSAGE_ERROR,
            self::FLASH_MESSAGE_WARNING,
            self::FLASH_MESSAGE_SUCCESS
        ];
        if (!in_array($type, $allowedTypes)) {
            throw new \Exception("Disallowed flash message type {$type}");
        }
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
                $html .= '<div class="alert alert-'.$this->getRenderClass($type).'"><ul>';
                foreach ($messageList as $message) {
                    $html .= '<li>'.$message.'</li>';
                }
                $html .= '</ul></div>';
            }
        }
        return $html;
    }

    /**
     * @param $message
     */
    public function addErrorMessage($message)
    {
        $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $message);
    }

    /**
     * @param $message
     */
    public function addSuccessMessage($message)
    {
        $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, $message);
    }

    /**
     * @param $message
     */
    public function addInfoMessage($message)
    {
        $this->addFlashMessage(self::FLASH_MESSAGE_INFO, $message);
    }

    /**
     * @param $message
     */
    public function addWarningMessage($message)
    {
        $this->addFlashMessage(self::FLASH_MESSAGE_WARNING, $message);
    }

    /**
     * @param $type
     * @return mixed
     */
    private function getRenderClass($type)
    {
        $map = [
            self::FLASH_MESSAGE_ERROR => 'danger'
        ];
        if (isset($map[$type])) {
            return $map[$type];
        }
        return $type;
    }
}
