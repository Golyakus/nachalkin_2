<?php

use yii\db\Migration;

class m160313_131738_ini_taskresult_table extends Migration
{
    public function up()
    {
		$this->createTable("taskresult", 
		[ 'id'=>'pk',
		  'created_at'=>'datetime not NULL',
		  'updated_at'=>'timestamp',
		  'user_id' => 'int(11) not NULL',
		  'task_id' => 'int(11) not NULL',
		  'score' => 'string',
		  'num_tries' => 'int default 0',
		]
		);

		$this->addForeignKey('FK_taskresult_task', 'taskresult', 'task_id', 'task','id');

		return true;

    }

    public function down()
    {
		$this->dropForeignKey('FK_taskresult_task', 'taskresult');
        $this->dropTable('taskresult');

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
