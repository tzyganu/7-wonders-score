<?php

\Propel\Runtime\Propel::init('generated-conf/config.php');

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1518215167.
 * Generated on 2018-02-10 00:26:07 by arnia
 */
class PropelMigration_1518215167
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
    }

    public function postUp(MigrationManager $manager)
    {
        $map = [
            'Military' => '#FF0000',
            'Cash' => '#CCCCCC',
            'Wonder' => '#666666',
            'Blue' => '#0000FF',
            'Yellow' => '#FFFF00',
            'Science' => '#00FF00',
            'Guilds' => '#800080',
            'Leaders & Cities' => '#000000'
        ];
        foreach ($map as $category => $color) {
            $dbCategories = \Wonders\CategoryQuery::create()->filterByName(array_keys($map), \Propel\Runtime\ActiveQuery\Criteria::IN)->find();
            foreach ($dbCategories as $category) {
                $category->setColor($map[$category->getName()]);
                $category->save();
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
          'default' => $this->addColorColumn(),
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
          'default' => $this->dropColorColumn(),
        );
    }

    private function addColorColumn()
    {
        return "ALTER TABLE category ADD COLUMN color VARCHAR(10) DEFAULT NULL;";
    }

    private function dropColorColumn()
    {
        return "ALTER TABLE category DROP COLUMN color";
    }

}
