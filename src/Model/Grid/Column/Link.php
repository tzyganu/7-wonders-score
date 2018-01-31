<?php
namespace Model\Grid\Column;

use Model\Grid\Column;
use Symfony\Component\HttpFoundation\Request;

class Link extends Column
{
    /**
     * @var string
     */
    protected $url;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var array
     */
    protected $params;

    /**
     * Link constructor.
     * @param Request $request
     * @param array $options
     */
    public function __construct(
        Request $request,
        array $options
    ) {
        $this->request = $request;
        parent::__construct($options);
    }

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        $fields = parent::getConstructorOptionsFields();
        $fields[] = 'url';
        $fields[] = 'params';
        return $fields;
    }


    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return '<a href="'.$value.'">'.$this->getLabel().'</a>';
    }

    /**
     * @param $row
     * @param null $default
     * @return string
     * @throws \Exception
     */
    public function render($row, $default = null)
    {
        $getParams = [];
        foreach ($this->getParams() as $name => $index) {
            if (is_array($row)) {
                if (isset($row[$index])) {
                    $getParams[$name] = $row[$index];
                }
            } elseif (is_object($row)) {
                $method = $index;
                if (method_exists($row, $method)) {
                    $getParams[$name] = $row->$method();
                }
            } else {
                throw new \Exception("Row must be object or array");
            }
        }
        $valueParams = [];
        foreach ($getParams as $key => $value) {
            if ($value !== null) {
                $valueParams[] = $key.'='.urlencode($value);
            }
        }
        $stringParams = implode('&', $valueParams);
        $url = $this->request->getBaseUrl().'/'.$this->getUrl();
        if (strpos($url, '?') !== false) {
            $url .= '&';
        } else {
            $url .= '?';
        }
        return $this->formatValue($url.$stringParams);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUrl()
    {
        if (!$this->url) {
            throw new \Exception("Url not set for link column");
        }
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}
