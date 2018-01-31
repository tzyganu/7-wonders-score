<?php
namespace Model\Console;

use Model\Factory;
use Symfony\Component\Console\Question\Question;

class QuestionFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * QuestionFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return Question
     */
    public function create($data)
    {
        return $this->factory->create(Question::class, $data);
    }
}
