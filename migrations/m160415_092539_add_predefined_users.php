<?php

use yii\db\Migration;

class m160415_092539_add_predefined_users extends Migration
{
    public function up()
    {
		if (!\app\models\User::createUser(100, 'admin','seva4532', 'admin@nachalkin.ru'))
			return false;
		if (!\app\models\User::createUser(101, 'teacher','teacher101', 'teacher@nachalkin.ru'))
			return false;
		if (!\app\models\User::createUser(102, 'igor','1961igor', 'igolovin@gmail.com'))
			return false;
 		if (!\app\models\User::createUser(103, 'vasili','vasya', 'basili-pupkin@post.ru'))
			return false;
 
		return true;
    }

    public function down()
    {
        \app\models\User::deleteAll();

        return true;
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
