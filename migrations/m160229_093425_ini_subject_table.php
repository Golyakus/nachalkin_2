<?php

use yii\db\Migration;

class m160229_093425_ini_subject_table extends Migration
{
    public function up()
    {
		$this->createTable("subject", 
		[ 'id'=>'pk',
		  'created_at'=>'datetime not NULL',
		  'created_by'=>'string NOT NULL',
		  'updated_at'=>'timestamp',
		  'updated_by'=>'string NOT NULL',
		  'class' => 'string not NULL',
		  'theme_id' => 'int(11) not NULL'
		]
		);

		$this->addForeignKey('FK_theme_subject', 'subject', 'theme_id', 'theme','id');

		date_default_timezone_set('Europe/Moscow');
		$curTime = new DateTime();
		$curTime = $curTime->format('Y-m-d H:i:s');
		$this->insert('subject', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'theme_id'=> 1,
			'class'=> '4 класс',
		]);

		return true;

    }

    public function down()
    {
        $this->dropForeignKey('FK_theme_subject', 'subject');
		$this->dropTable('subject');

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
