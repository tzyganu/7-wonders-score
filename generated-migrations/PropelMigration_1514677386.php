<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1514677386.
 * Generated on 2017-12-31 01:43:06 by arnia
 */
class PropelMigration_1514677386
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
            'default' => $this->getUpdateCategoriesIconsSql()
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
          'default' => $this->getUpdateCategoriesIconsSql(true),
        );
    }

    protected function getIconsMigrationMap()
    {
        return [
            [
                'new' => 'fa fa-square blue',
                'old' => 'category-icon category-icon-blue'
            ],
            [
                'new' => 'fa fa-square yellow',
                'old' => 'category-icon category-icon-yellow'
            ],
            [
                'new' => 'fa fa-square purple',
                'old' => 'category-icon category-icon-purple'
            ],
            [
                'new' => 'fa fa-square black',
                'old' => 'fa fa-user-circle category-icon category-icon-black'
            ],
            [
                'new' => 'fa fa-shield red',
                'old' => 'fa fa-shield',
            ],
            [
                'new' => 'fa fa-flask green',
                'old' => 'fa fa-flask',
            ],
            [
                'new' => 'glyphicon glyphicon-triangle-top yellow',
                'old' => 'glyphicon glyphicon-triangle-top',
            ]
        ];
    }

    protected function getUpdateCategoriesIconsSql($reverse = false)
    {
        $queries = [];
        foreach ($this->getIconsMigrationMap() as $map) {
            $new = ($reverse) ? $map['old'] : $map['new'];
            $old = ($reverse) ? $map['new'] : $map['old'];
            $queries[] = "UPDATE category SET icon_class = '{$new}' WHERE icon_class = '{$old}';";
        }
        return implode(' ',$queries);
    }
}
