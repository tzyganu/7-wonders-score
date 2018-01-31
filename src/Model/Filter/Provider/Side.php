<?php
namespace Model\Filter\Provider;

class Side implements ProviderInterface
{
    /**
     * @var \Model\Side
     */
    private $side;
    /**
     * @var
     */
    private $values;

    /**
     * Side constructor.
     * @param \Model\Side $side
     */
    public function __construct(
        \Model\Side $side
    ) {
        $this->side = $side;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->values === null) {
            $this->values = [];
            foreach ($this->side->getSides() as $side) {
                $this->values[] = [
                    'label' => $side['id'],
                    'value' => $side['name']
                ];
            }
        }
        return $this->values;
    }
}
