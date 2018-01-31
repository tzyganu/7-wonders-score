<?php
namespace Twig;

class FlashMessage implements FunctionInterface
{
    /**
     * @var \Model\FlashMessage
     */
    private $flashMessage;

    /**
     * FlashMessage constructor.
     * @param \Model\FlashMessage $flashMessage
     */
    public function __construct(
        \Model\FlashMessage $flashMessage
    ) {
        $this->flashMessage = $flashMessage;
    }

    /**
     * @return \Twig_Function
     */
    public function getFunction()
    {
        $flashMessage = $this->flashMessage;
        return new \Twig_Function('flash_messages', function () use ($flashMessage) {
            return $flashMessage->renderFlashMessages(true);
        });
    }
}
