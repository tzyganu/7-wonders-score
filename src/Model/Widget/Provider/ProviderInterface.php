<?php
namespace Model\Widget\Provider;

interface ProviderInterface
{
    /**
     * @return \Model\Widget[]
     */
    public function getWidgets();
}
