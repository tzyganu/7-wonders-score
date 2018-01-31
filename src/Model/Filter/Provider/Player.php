<?php
namespace Model\Filter\Provider;

class Player implements ProviderInterface
{
    /**
     * @var \Service\Player
     */
    private $playerService;
    /**
     * @var array
     */
    private $values;

    /**
     * Player constructor.
     * @param \Service\Player $playerService
     */
    public function __construct(
        \Service\Player $playerService
    ) {
        $this->playerService = $playerService;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if ($this->values === null) {
            $this->values = [];
            foreach ($this->playerService->getPlayers() as $player) {
                /** @var \Wonders\Player $player */
                $this->values[] = [
                    'label' => $player->getName(),
                    'value' => $player->getId()
                ];
            }
        }
        return $this->values;
    }
}
