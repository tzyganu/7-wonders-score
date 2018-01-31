<?php
namespace Model;

class PlayerCount
{
    const MIN_PLAYERS = 3;
    const MAX_PLAYERS = 8;
    const DEFAULT_PLAYERS = 6;
    /**
     * @return array
     */
    public function getCounts()
    {
        $counts = [];
        for ($i = self::MIN_PLAYERS; $i<=self::MAX_PLAYERS; $i++) {
            $counts[] = [
                'id' => $i,
                'name' => $i
            ];
        }
        return $counts;
    }
}
