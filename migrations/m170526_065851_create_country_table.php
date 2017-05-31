<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m170526_065851_create_country_table extends Migration
{
    private $table = 'country';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'code'       => $this->string(4)->notNull(),
            'name'       => $this->string(255)->notNull(),
            'isoNumeric' => $this->string(),
            'geonameId'  => $this->integer(),
            'PRIMARY KEY (code)',
        ], $tableOptions);

        $countriesInsert = require(__DIR__ . '/countries.sql.php');
        Yii::$app->db->createCommand()->batchInsert(
            $this->table,
            ['code', 'name', 'isoNumeric', 'geonameId'],
            $countriesInsert)
        ->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
