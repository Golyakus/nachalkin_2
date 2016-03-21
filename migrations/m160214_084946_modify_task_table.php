<?php

use yii\db\Migration;

class m160214_084946_modify_task_table extends Migration
{
    public function up()
    {
		$this->addColumn('task', 'updated_at', 'timestamp');
		$this->addColumn('task', 'updated_by', 'string NOT NULL');
		$this->addColumn('task', 'max_score', 'string NOT NULL');
		$this->addColumn('task', 'struct_type', 'string NOT NULL');
		return true;
    }

    public function down()
    {
		$this->dropColumn('task', 'updated_at');
		$this->dropColumn('task', 'updated_by');
		$this->dropColumn('task', 'max_score');
		$this->dropColumn('task', 'struct_type');
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
