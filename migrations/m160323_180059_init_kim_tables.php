<?php

use yii\db\Migration;

class m160323_180059_init_kim_tables extends Migration
{
    public function up()
    {
    	$this->createTable("kim",
    	[ 	'id'=>'pk',
    		'created_at'=>'datetime not NULL',
			'updated_at'=>'timestamp',
			'created_by'=>'int(11) not NULL',
			'updated_by'=>'int(11) not NULL',
			'theme_id'=>'int(11) not NULL',
			'subject_id'=>'int(11) not NULL',
			'status'=>'int not NULL default 0',
			'solvetime'=>'int not NULL',
    	]
    	);
    	$this->addForeignKey('FK_kim_theme', 'kim', 'theme_id', 'theme', 'id');
    	$this->addForeignKey('FK_kim_subject', 'kim', 'subject_id', 'subject', 'id');
    	return true;
    }

    public function down()
    {
        $this->dropForeignKey('FK_kim_subject', 'kim');
        $this->dropForeignKey('FK_kim_theme', 'kim');
    	$this->dropTable('kim');
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
