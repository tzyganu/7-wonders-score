<?php
namespace Model\Widget\Provider;

class CategoryAverage extends Category implements ProviderInterface
{
    /**
     * @var string
     */
    protected $expression = 'AVG(value)';
}
