<?php
namespace Model;

use Config\YamlLoader;

class MenuBuilder
{
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $configFile;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * MenuBuilder constructor.
     * @param YamlLoader $yamlLoader
     * @param $configFile
     * @param $baseUrl
     */
    public function __construct(
        YamlLoader $yamlLoader,
        $configFile,
        $baseUrl
    ) {
        $this->yamlLoader = $yamlLoader;
        $this->configFile = $configFile;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return mixed
     */
    private function loadMenu()
    {
        return $this->yamlLoader->load($this->configFile);
    }

    /**
     * @param $selected
     * @return string
     */
    public function renderMenu($selected)
    {
        $menu = $this->loadMenu();
        $html = '';
        if (count($menu) > 0) {
            $html .= '<ul class="sidebar-menu" data-widget="tree">';
            foreach ($menu as $id => $item) {
                $html .= $this->renderItem($item, $id, $selected);
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * @param array $item
     * @param $id
     * @param $selected
     * @return string
     */
    private function renderItem(array $item, $id, $selected)
    {
        $class = '';
        if ($this->hasChildren($item)) {
            $class .= ' treeview';
        }
        if ($id == $selected) {
            $class .= ' active';
        }
        $html = '<li'.(($class) ? ' class="'.$class.'"' : '').' id="menu-item-'.$id.'">';
        $html.= '<a href="'.(array_key_exists('url', $item) ? $this->baseUrl . '/'. $item['url'] : '#').'">';
        if (isset($item['icon'])) {
            $html .= '<i class="'.$item['icon'].'"></i>';
        }
        $html .= '<span>'.$item['label'].'</span>';
        if ($this->hasChildren($item)) {
            $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
        }
        $html .= "</a>";
        if ($this->hasChildren($item)) {
            $html .= '<ul class="treeview-menu">';
            foreach ($item['children'] as $childId => $child) {
                $html .= $this->renderItem($child, $id.'-'.$childId, $selected);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * @param array $item
     * @return bool
     */
    private function hasChildren(array $item)
    {
        return isset($item['children']) && is_array($item['children']) && count($item['children']);
    }
}
