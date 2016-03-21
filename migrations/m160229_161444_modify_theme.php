<?php

use yii\db\Migration;

class m160229_161444_modify_theme extends Migration
{
    public function up()
    {
		$this->addColumn('theme', 'complexity', 'int(6)');
		return true;
    }

    public function down()
    {
        $this->dropColumn('theme', 'complexity');

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
