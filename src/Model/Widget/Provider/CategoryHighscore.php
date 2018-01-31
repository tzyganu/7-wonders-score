<?php
namespace Model\Widget\Provider;

class CategoryHighscore extends Category implements ProviderInterface
{
    /**
     * @var string
     */
    protected $expression = 'MAX(value)';
}
