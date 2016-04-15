<?php

use yii\db\Migration;

class m160229_093425_ini_subject_table extends Migration
{
    public function up()
    {
		$this->createTable("subject", 
		[ 'id'=>'pk',
		  'created_at'=>'datetime not NULL',
		  'created_by'=>'int(11) NOT NULL',
		  'updated_at'=>'timestamp',
		  'updated_by'=>'int(11) NOT NULL',
		  'class' => 'string not NULL',
		  'theme_id' => 'int(11) not NULL',
		  'domain_id' => 'int(11) not null',
		]
		);

		$this->addForeignKey('FK_domain_subject', 'subject', 'domain_id', 'domain','id');

		$this->addForeignKey('FK_theme_subject', 'subject', 'theme_id', 'theme','id');

		date_default_timezone_set('Europe/Moscow');
		$curTime = new DateTime();
		$curTime = $curTime->format('Y-m-d H:i:s');
		$this->insert('subject', 
			['created_by'=>'102', 
			'updated_by'=>'102', 
			'created_at'=> $curTime,
			'theme_id'=> 1,
			'class'=> '4 класс',
			'domain_id' => 1,
		]);

		$this->insert('subject', 
			['created_by'=>'102', 
			'updated_by'=>'102', 
			'created_at'=> $curTime,
			'theme_id'=> 2,
			'class'=> '4 класс',
			'domain_id' => 2,
		]);

		$this->insert('subject', 
			['created_by'=>'102', 
			'updated_by'=>'102', 
			'created_at'=> $curTime,
			'theme_id'=> 3,
			'class'=> '4 класс',
			'domain_id' => 3,
		]);

		$this->insert('subject', 
			['created_by'=>'102', 
			'updated_by'=>'102', 
			'created_at'=> $curTime,
			'theme_id'=> 4,
			'class'=> '5 класс',
			'domain_id' => 1,
		]);

		$this->insert('subject', 
			['created_by'=>'102', 
			'updated_by'=>'102', 
			'created_at'=> $curTime,
			'theme_id'=> 5,
			'class'=> '5 класс',
			'domain_id' => 2,
		]);

	return true;

    }

    public function down()
    {
        $this->dropForeignKey('FK_theme_subject', 'subject');

		try
		{	
			$this->dropForeignKey('FK_domain_subject', 'domain');
		}
		catch (\Yii\base\Exception $err)
		{
			echo 'Ignoring error: ' . $err->getMessage();
		}

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
