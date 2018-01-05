<?php

use Propel\Generator\Manager\MigrationManager;

\Propel\Runtime\Propel::init('generated-conf/config.php');

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1515142836.
 * Generated on 2018-01-05 11:00:36 by arnia
 */
class PropelMigration_1515142836
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
//        $connection = $manager->getConnection('default');
//        var_dump($connection);exit;
        $this->updatePlayerCount();
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
          'default' => $this->addPlayerCountColumn(),
        );
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
            'default' => $this->dropPlayerCountColumn(),
        );
    }

    private function addPlayerCountColumn()
    {
        return "ALTER TABLE game ADD COLUMN player_count TINYINT(4) DEFAULT 0;";
    }

    private function dropPlayerCountColumn()
    {
        return "ALTER TABLE game DROP COLUMN player_count";
    }


    private function findGame($id)
    {
        return \Wonders\GameQuery::create()->findOneById($id);
    }
    private  function updatePlayerCount($connection = null)
    {
        $games = \Wonders\GameQuery::create();
        $games
            ->addAsColumn('id', 'Game.id')
            ->useGamePlayerQuery(null, \Propel\Runtime\ActiveQuery\Criteria::LEFT_JOIN)
            ->addAsColumn('players', 'COUNT(*)')
            ->endUse()
            ->groupById()
            ->find($connection);
        foreach ($games as $game) {
            $gameObj = $this->findGame($game->getVirtualColumn('id'));
            if ($gameObj) {
                $gameObj->setPlayerCount($game->getVirtualColumn('players'));
                $gameObj->save($connection);
            }
        }

    }

}
