<?php

use yii\db\Migration;

class m160229_090726_ini_theme_table extends Migration
{
    public function up()
    {
		$this->createTable("domain",
		[
			'id'=>'pk',
			'title' => 'string not NULL'
		]);

		$this->insert('domain',
		[
			'title' => 'Математика',
		]
		);

		$this->insert('domain',
		[
			'title' => 'Русский язык',
		]
		);

		$this->insert('domain',
		[
			'title' => 'Метапредметность',
		]
		);


		$this->createTable("theme", 
		[ 'id'=>'pk',
		  'created_at'=>'datetime not NULL',
		  'created_by'=>'string NOT NULL',
		  'updated_at'=>'timestamp',
		  'updated_by'=>'string NOT NULL',
		  'title' => 'string',
		  'description'=>'text',
		  'parent' => 'int(11)',
		]		
		);
		date_default_timezone_set('Europe/Moscow');
		$curTime = new DateTime();
		$curTime = $curTime->format('Y-m-d H:i:s');
		$this->insert('theme', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'title'=>'Математика, 4 класс',
			'description'=>'',
		]);

		$this->insert('theme', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'title'=>'Русский язык, 4 класс',
			'description'=>'',
		]);

		$this->insert('theme', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'title'=>'Метапредметность, 4 класс',
			'description'=>'',
		]);

		$this->insert('theme', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'title'=>'Математика, 5 класс',
			'description'=>'',
		]);

		$this->insert('theme', 
			['created_by'=>'igor', 
			'updated_by'=>'igor', 
			'created_at'=> $curTime,
			'title'=>'Русский язык, 5 класс',
			'description'=>'',
		]);

		$this->addColumn('task', 'theme_id', 'int(11) not NULL');

		return true;

    }

    public function down()
    {
		$this->dropColumn('task', 'theme_id');
        $this->dropTable("theme");

		try
		{	
			$this->dropTable('domain');
		}
		catch (\Yii\base\Exception $err)
		{
			echo 'Ignoring error: ' . $err->getMessage();
		}


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
