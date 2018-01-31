<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\Score;

class ScoreFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * ScoreFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return Score
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Score::class, $data);
    }
}
