<?php
namespace Model\Filter\Provider;

class PlayerCount implements ProviderInterface
{
    /**
     * @var \Model\PlayerCount
     */
    private $playerCount;
    /**
     * @var array
     */
    private $values;

    /**
     * PlayerCount constructor.
     * @param \Model\PlayerCount $playerCount
     */
    public function __construct(
        \Model\PlayerCount $playerCount
    ) {
        $this->playerCount = $playerCount;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->values === null) {
            $this->values = [];
            foreach ($this->playerCount->getCounts() as $count) {
                $this->values[] = [
                    'label' => $count['id'],
                    'value' => $count['name']
                ];
            }
        }
        return $this->values;
    }
}
