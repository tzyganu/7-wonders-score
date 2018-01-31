<?php
namespace Twig;

class Version implements FunctionInterface
{
    /**
     * @return \Twig_Function
     */
    public function getFunction()
    {
        return new \Twig_Function('version', function () {
            return 'v'.\Model\Version::VERSION;
        });
    }
}
