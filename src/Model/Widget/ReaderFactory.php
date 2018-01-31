<?php
namespace Model\Widget;

use Model\Factory;

class ReaderFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * ReaderFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return Reader
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Reader::class, $data);
    }
}
