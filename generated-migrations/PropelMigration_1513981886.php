<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1513981886.
 */
class PropelMigration_1513981886
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
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
          'default' => $this->getInitSql(),
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
  'default' => '',
);
    }

    private function getInitSql()
    {
        return '

        CREATE TABLE IF NOT EXISTS `user` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `username` varchar(45) NOT NULL,
          `password` varchar(255) NOT NULL,
          `active` tinyint(4) DEFAULT \'1\',
          PRIMARY KEY (`id`),
          UNIQUE KEY `USERNAME_UNIQUE` (`username`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        
        CREATE TABLE IF NOT EXISTS `game` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `date` date NOT NULL,
          `user_id` int(11) NOT NULL DEFAULT \'0\',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        
        CREATE TABLE `category` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `optional` varchar(45) NOT NULL DEFAULT \'0\',
          `icon_class` varchar(100) DEFAULT NULL,
          `sort_order` int(10) NOT NULL DEFAULT \'0\',
          PRIMARY KEY (`id`),
          UNIQUE KEY `NAME_CATEGORY_UNIQUE` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        
        CREATE TABLE `player` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `name_UNIQUE` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        
        CREATE TABLE IF NOT EXISTS  `wonder` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `name_UNIQUE` (`name`)
        ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

        
        CREATE TABLE IF NOT EXISTS  `game_category` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `game_id` int(11) DEFAULT NULL,
          `category_id` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `FK_GAME_CATEGORY_UNIQUE` (`game_id`,`category_id`),
          KEY `FK_GAME_CATEGORY_GAME_ID_idx` (`game_id`),
          KEY `FK_GAME_CATEGORY_CATEGORY_ID_idx` (`category_id`),
          CONSTRAINT `FK_GAME_CATEGORY_CATEGORY_ID` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `FK_GAME_CATEGORY_GAME_ID` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        
        
        CREATE TABLE IF NOT EXISTS `game_player` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `game_id` int(11) DEFAULT NULL,
          `player_id` int(11) DEFAULT NULL,
          `wonder_id` int(11) DEFAULT NULL,
          `side` varchar(10) DEFAULT NULL,
          `points` int(11) NOT NULL,
          `place` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `FK_GAME_PLAYER_UNIQUE` (`game_id`,`player_id`),
          KEY `FK_GAME_PLAYER_GAME_ID_idx` (`game_id`),
          KEY `FK_GAME_PLAYER_PLAYER_ID_idx` (`player_id`),
          KEY `FK_GAME_PLAYER_WONDER_ID_idx` (`wonder_id`),
          CONSTRAINT `FK_GAME_PLAYER_GAME_ID` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `FK_GAME_PLAYER_PLAYER_ID` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
          CONSTRAINT `FK_GAME_PLAYER_WONDER_ID` FOREIGN KEY (`wonder_id`) REFERENCES `wonder` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
        
        CREATE TABLE IF NOT EXISTS `score` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `game_id` int(11) DEFAULT NULL,
          `player_id` int(11) DEFAULT NULL,
          `category_id` int(11) DEFAULT NULL,
          `value` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `GAME_SCORE_CATEGORY_PLAYER_UNIQUE` (`game_id`,`player_id`,`category_id`),
          KEY `GAME_ID` (`game_id`),
          KEY `FK_SCORE_PLAYER_ID_idx` (`player_id`),
          KEY `FK_SCORE_CATEGORY_ID_idx` (`category_id`),
          CONSTRAINT `FK_SCORE_CATEGORY_ID` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
          CONSTRAINT `FK_SCORE_GAME_ID` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
          CONSTRAINT `FK_SCORE_PLAYER_ID` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=393 DEFAULT CHARSET=utf8;
        
        INSERT INTO `category` VALUES (1,\'Military\',\'0\',\'fa fa-shield\',1),(2,\'Cash\',\'0\',\'fa fa-money\',2),(3,\'Wonder\',\'0\',\'fa fa-exclamation-triangle\',3),(4,\'Blue\',\'0\',\'category-icon category-icon-blue\',4),(5,\'Yellow\',\'0\',\'category-icon category-icon-yellow\',5),(6,\'Guilds\',\'0\',\'category-icon category-icon-purple\',7),(7,\'Science\',\'0\',\'fa fa-flask\',6),(8,\'Leaders & Cities\',\'1\',\'fa fa-user-circle category-icon category-icon-black\',8);
        INSERT INTO `wonder` VALUES (11,\'Abu Simbel\'),(1,\'Alexandria\'),(2,\'Babylon\'),(10,\'Byzantium\'),(3,\'Ephesos\'),(4,\'Gizah\'),(13,\'Great Wall\'),(5,\'Halikarnassus\'),(14,\'Manneken Pis\'),(6,\'Olympia\'),(8,\'Petra\'),(7,\'Rhodos\'),(9,\'Roma\'),(12,\'Stonehenge\');
        ';
    }
}
