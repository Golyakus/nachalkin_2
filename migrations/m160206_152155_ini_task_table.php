<?php

use yii\db\Migration;

class m160206_152155_ini_task_table extends Migration
{
    public function up()
    {
		$this->createTable("task", 
		[ 'id'=>'pk',
		  'created_at'=>'timestamp',
		  'created_by'=>'string NOT NULL',
		  'content'=>'text NOT NULL'
		]		
		);
		return true;
    }

    public function down()
    {
        $this->dropTable("task");

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
