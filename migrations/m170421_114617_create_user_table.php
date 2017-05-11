<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170421_114617_create_user_table extends Migration
{
    private $table = 'user';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string()->notNull()->unique(),
            'first_name'           => $this->string()->notNull(),
            'last_name'            => $this->string()->notNull(),
            'avatar'               => $this->string()->notNull(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string()->notNull()->unique(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
