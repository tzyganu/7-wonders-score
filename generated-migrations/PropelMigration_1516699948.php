<?php

\Propel\Runtime\Propel::init('generated-conf/config.php');

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1516699948.
 * Generated on 2018-01-23 11:32:28 by arnia
 */
class PropelMigration_1516699948
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        $wonders = [
            1 => [
                'Alexandria',
                'Babylon',
                'Ephesos',
                'Gizah',
                'Halikarnassus',
                'Olympia',
                'Rhodos'
            ],
            2 => [
                'Abu Simbel',
                'Great Wall',
                'Manneken Pis',
                'Stonehenge'
            ],
            3 => [
                'Roma'
            ],
            4 => [
                'Byzantium',
                'Petra',
            ]
        ];
        foreach ($wonders as $groupId => $wonderNames) {
            $dbWonders = \Wonders\WonderQuery::create()->filterByName($wonderNames, \Propel\Runtime\ActiveQuery\Criteria::IN)->find();
            foreach ($dbWonders as $wonder) {
                $wonderGroupWonder = new \Wonders\WonderGroupWonder();
                $wonderGroupWonder->setWonderId($wonder->getId())
                    ->setWonderGroupId($groupId)->save();
            }
        }
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
          'default' => $this->getInstallWonderGroupSql(),
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
          'default' => $this->getUninstallWonderGroupSql(),
        );
    }

    private function getInstallWonderGroupSql()
    {
        return '
            CREATE TABLE IF NOT EXISTS `wonder_group` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
            CREATE TABLE IF NOT EXISTS  `wonder_group_wonder` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `wonder_group_id` int(11) DEFAULT NULL,
              `wonder_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `FK_WONDER_GROUP_WONDER_UNIQUE` (`wonder_group_id`,`wonder_id`),
              KEY `FK_WONDER_GROUP_WONDER_WONDER_GROUP_ID_idx` (`wonder_group_id`),
              KEY `FK_WONDER_GROUP_WONDER_WONDER_ID_idx` (`wonder_id`),
              CONSTRAINT `FK_WONDER_GROUP_WONDER_WONDER_GROUP_ID` FOREIGN KEY (`wonder_group_id`) REFERENCES `wonder_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `FK_WONDER_GROUP_WONDER_WONDER_ID` FOREIGN KEY (`wonder_id`) REFERENCES `wonder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
            INSERT INTO `wonder_group` (`id`, `name`) VALUES (1, \'Standard\'), (2, \'Wonders pack\'), (3, \'Leaders\'), (4, \'Cities\');
        ';
    }

    private function getUninstallWonderGroupSql()
    {
        return '
            DROP TABLE `wonder_group_wonder`;
            DROP TABLE `wonder_group`;
        ';
    }
}
