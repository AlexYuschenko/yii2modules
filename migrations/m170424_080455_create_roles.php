<?php

use yii\db\Migration;

class m170424_080455_create_roles extends Migration
{
    public function up()
    {
        $rbac = \Yii::$app->authManager;

        $guest = $rbac->createRole('guest');
        $guest->description = 'Nobody';
        $rbac->add($guest);

        sleep(1);

        $user = $rbac->createRole('user');
        $user->description = 'Can use the query UI and nothing else';
        $rbac->add($user);

        sleep(1);

        $manager = $rbac->createRole('manager');
        $manager->description = 'Can manage entities in database, but not users';
        $rbac->add($manager);

        sleep(1);

        $admin = $rbac->createRole('admin');
        $admin->description = 'Can do anything including managing users';
        $rbac->add($admin);

        $rbac->addChild($admin, $manager);
        $rbac->addChild($manager, $user);
        $rbac->addChild($user, $guest);

        $rbac->assign(
            $admin,
            \app\modules\user\models\User::findOne(['username' => 'admin'])->id
        );

    }

    public function down()
    {
        $manager = \Yii::$app->authManager;
        $manager->removeAll();
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
