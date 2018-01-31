<?php
namespace Model;

use Symfony\Component\HttpFoundation\Request;

class UrlBuilder
{
    /**
     * @var Request
     */
    private $request;

    /**
     * UrlBuilder constructor.
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @param $path
     * @param $params
     * @return string
     */
    public function getUrl($path, $params = [])
    {
        $url = $this->request->getBaseUrl();
        if (substr($path, 0, 1) != '/') {
            $url .= '/';
        }
        $url .= $path;
        $query = $this->buildParams($params);
        if ($query) {
            $url .= (strpos($url, '?') === false) ? '?' : '&';
            $url .= $query;
        }
        return $url;
    }

    /**
     * @param $params
     * @return string
     */
    private function buildParams($params)
    {
        $parts = [];
        foreach ($params as $key => $value) {
            if ($value !== null) {
                $parts[] = $key .'='.$value;
            }
        }
        return implode("&", $parts);
    }
}
