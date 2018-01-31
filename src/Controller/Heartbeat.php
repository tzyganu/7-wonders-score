<?php
namespace Controller;

use Model\ResponseFactory;

class Heartbeat implements ControllerInterface
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * Heartbeat constructor.
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        ResponseFactory $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        return $this->responseFactory->create(
            ResponseFactory::JSON,
            [
                'data' => ['success' => true]
            ]
        );
    }
}
