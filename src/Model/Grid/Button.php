<?php
namespace Model\Grid;

use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;

class Button
{
    const DEFAULT_CLASS = 'btn btn-primary';
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $label;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var array
     */
    private $params;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Button constructor.
     * @param Request $request
     * @param UrlBuilder $urlBuilder
     * @param $label
     * @param $url
     * @param null $class
     * @param array $params
     */
    public function __construct(
        Request $request,
        UrlBuilder $urlBuilder,
        $label,
        $url,
        $class = null,
        $params = []
    ) {
        $this->request      = $request;
        $this->urlBuilder   = $urlBuilder;
        $this->setLabel($label);
        $this->setUrl($url);
        if ($class !== null) {
            $this->setClass($class);
        } else {
            $this->setClass(self::DEFAULT_CLASS);
        }
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->urlBuilder->getUrl($this->url, $this->params);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
}
