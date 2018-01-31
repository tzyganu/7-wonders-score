<?php
namespace Controller;

interface ControllerInterface
{
    /**
     * @return string| \Symfony\Component\HttpFoundation\Response
     */
    public function execute();
}
