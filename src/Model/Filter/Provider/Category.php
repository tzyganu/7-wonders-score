<?php
namespace Model\Filter\Provider;

class Category implements ProviderInterface
{
    /**
     * @var \Service\Category
     */
    private $categoryService;
    /**
     * @var array
     */
    private $values;

    /**
     * Category constructor.
     * @param \Service\Category $categoryService
     */
    public function __construct(
        \Service\Category $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->values === null) {
            $this->values = [];
            foreach ($this->categoryService->getCategories() as $category) {
                /** @var \Wonders\Category $category */
                $this->values[] = [
                    'label' => $category->getName(),
                    'value' => $category->getId()
                ];
            }
        }
        return $this->values;
    }
}
