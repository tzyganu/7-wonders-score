<?php
namespace Model;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @var string;
     */
    const REDIRECT = 'redirect';
    /**
     * @var string
     */
    const JSON = 'json';
    /**
     * @var array|null
     */
    private $map = [
        self::REDIRECT => RedirectResponse::class,
        self::JSON => JsonResponse::class
    ];
    /**
     * @var Factory
     */
    private $factory;

    /**
     * ResponseFactory constructor.
     * @param Factory $factory
     * @param array|null $map
     */
    public function __construct(
        Factory $factory,
        array $map = null
    ) {
        $this->factory = $factory;
        if ($map != null) {
            $this->map = $map;
        }
    }

    /**
     * @param $type
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function create($type, array $data = [])
    {
        if (!isset($this->map[$type])) {
            throw new \Exception("Not supported response type {$type}");
        }
        return $this->factory->create($this->map[$type], $data);
    }
}
