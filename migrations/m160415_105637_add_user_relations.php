<?php

use yii\db\Migration;

class m160415_105637_add_user_relations extends Migration
{
    public function up()
    {
		$this->addForeignKey('FK_user_task_created', 'task', 'created_by', 'user','id');
		$this->addForeignKey('FK_user_task_updated', 'task', 'updated_by', 'user','id');

		$this->addForeignKey('FK_user_subject_created', 'subject', 'created_by', 'user','id');
		$this->addForeignKey('FK_user_subject_updated', 'subject', 'updated_by', 'user','id');

		$this->addForeignKey('FK_user_theme_created', 'theme', 'created_by', 'user','id');
		$this->addForeignKey('FK_user_theme_updated', 'theme', 'updated_by', 'user','id');

		$this->addForeignKey('FK_user_taskresult', 'taskresult', 'user_id', 'user','id');

		$this->addForeignKey('FK_user_kim_created', 'kim', 'created_by', 'user','id');
		$this->addForeignKey('FK_user_kim_updated', 'kim', 'updated_by', 'user','id');
		return true;
    }

    public function down()
    {
		$this->dropForeignKey('FK_user_task_created', 'task');
     	$this->dropForeignKey('FK_user_task_updated', 'task');

		$this->dropForeignKey('FK_user_subject_created', 'subject');
     	$this->dropForeignKey('FK_user_subject_updated', 'subject');

		$this->dropForeignKey('FK_user_theme_created', 'theme');
     	$this->dropForeignKey('FK_user_theme_updated', 'theme');

		$this->dropForeignKey('FK_user_taskresult', 'taskresult');

		$this->dropForeignKey('FK_user_kim_created', 'kim');
     	$this->dropForeignKey('FK_user_kim_updated', 'kim');
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
