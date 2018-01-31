<?php
namespace Model\Filter\Provider;

class Wonder implements ProviderInterface
{
    /**
     * @var \Service\Wonder
     */
    private $wonderService;
    /**
     * @var array
     */
    private $values;

    /**
     * Wonder constructor.
     * @param \Service\Wonder $wonderService
     */
    public function __construct(
        \Service\Wonder $wonderService
    ) {
        $this->wonderService = $wonderService;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->values === null) {
            $this->values = [];
            foreach ($this->wonderService->getWonders() as $wonder) {
                /** @var \Wonders\Wonder $wonder */
                $this->values[] = [
                    'label' => $wonder->getName(),
                    'value' => $wonder->getId()
                ];
            }
        }
        return $this->values;
    }
}
