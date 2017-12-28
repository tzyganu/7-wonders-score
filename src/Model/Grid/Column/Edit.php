<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class Edit extends Column
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        $fields = parent::getConstructorOptionsFields();
        $fields[] = 'url';
        return $fields;
    }


    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return '<a href="'.$this->getUrl().$value.'">'.$this->getLabel().'</a>';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUrl()
    {
        if (!$this->url) {
            throw new \Exception("Url not set for edit column");
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
}
