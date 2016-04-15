<?php

use yii\db\Migration;

class m160330_172353_populate_math extends Migration
{
    public function up()
    {
		// Вставляем темы 1 уровня в математику
		$id = 1; // математика 4 класс
		$th = "'Описание темы Геометрия'"; // должен быть id=6
    	$sql = "INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Геометрия', $th, $id)";

     	\Yii::$app->db->createCommand($sql)->execute();  

		$th = "'Описание темы Дроби'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Дроби', $th, $id)")->execute(); 
		$th = "'Описание темы Движение'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Движение', $th, $id)")->execute();   	
		$th = "'Описание темы Уравнения'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Уравнения', $th, $id)")->execute(); 

		// Вставляем темы 2 уровня в геометрию (id=6)
		$id = 6;
		$th = "'Описание темы Периметр и площади'"; // должен быть id=10
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Периметр и площади', $th, $id)")->execute();
 
		$th = "'Описание темы Задачи с окружностью'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Задачи с окружностью', $th, $id)")->execute(); 

		// Вставляем темы 3 уровня в Периметр и площади (id=10)
		$id = 10;
		$th = "'Описание темы По стороне ищем периметр'"; // должен быть id=12
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'По стороне ищем периметр', $th, $id)")->execute();
 
		$th = "'Описание темы По полупериметру ищем периметр'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'По полупериметру ищем периметр', $th, $id)")->execute(); 

		$th = "'Описание темы По периметру ищем сторону'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'По периметру ищем сторону', $th, $id)")->execute(); 

		// Вставляем задачи в  По стороне ищем периметр (id=12)
		$this->addTasks(12);
		$this->addTasks(13);
		$this->addTasks(14);
		
		echo "Mathematics (grade 4) is populated\n";

    }

    public function down()
    {
        
		// do not remove ???
        return true;
    }

    private function addTasks($id)
    {
    	
    	$user = "102";
    	$score = 10;
    	$type = "input";
    	$content = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<task struct-type="$type">
  <body>
    Винни-Пух, Сова, Кролик и Пятачок вместе съели 70 бананов. При этом Сова и Кролик съели вместе 45 бананов. Сколько бананов съел Винни-Пух, если известно, что он съел больше всех бананов?     
  </body>
  <answer type="input" value="25" max_score="$score">Ответ:
    <t>Винни-Пух съел</t>
    <ans-element anstype="input" hidden="true" numeric="true" max_score="$score">25</ans-element>
    <t>бананов</t>
  </answer>
</task>
XML;
    	$sql = "INSERT INTO `task`(`updated_at`,`created_by`, `content`, `updated_by`, `max_score`, `struct_type`, `theme_id`) VALUES " .
    	"(NOW(), $user, '$content', $user, $score, '$type' ,$id)";
    	\Yii::$app->db->createCommand($sql)->execute();

    	$user = "102";
    	$score = 10;
    	$type = "check";
    	$content = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<task struct-type="$type">
 <body>
    Сколько дней в году на планете Земля?   
  </body>
  <answer type="check" value="1_2" max_score="$score">
    <ans-element anstype="check-box" correct="true" score="5">365</ans-element>
    <ans-element anstype="check-box" correct="true" score="5">366</ans-element>
    <ans-element anstype="check-box" correct="false" score="-5">367</ans-element>
  </answer>
</task>
XML;
      $sql = "INSERT INTO `task`(`updated_at`,`created_by`, `content`, `updated_by`, `max_score`, `struct_type`, `theme_id`) VALUES " .
      "(NOW(), $user, '$content', $user, $score, '$type' ,$id)";
      \Yii::$app->db->createCommand($sql)->execute();

    	$user = "102";
    	$score = 10;
    	$type = "dropdown";
    	$content = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<task struct-type="$type">
 <body>
    Вчера был день недели, название которого начинается на букву П. Какой день недели будет послезавтра?
  </body>
  <answer type="dropdown" max_score="$score">
    Послезавтра будет
    <ans-element correct="true">понедельник</ans-element>
    <ans-element >вторник</ans-element>
    <ans-element >среда</ans-element>
    <ans-element >четверг</ans-element>
    <ans-element >пятница</ans-element>
    <ans-element >суббота</ans-element>
    <ans-element >воскресенье</ans-element>
  </answer>
</task>
XML;
      $sql = "INSERT INTO `task`(`updated_at`,`created_by`, `content`, `updated_by`, `max_score`, `struct_type`, `theme_id`) VALUES " .
      "(NOW(), $user, '$content', $user, $score, '$type' ,$id)";
      \Yii::$app->db->createCommand($sql)->execute();

    	$user = "102";
    	$score = 10;
    	$type = "radio";
    	$content = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<task struct-type="$type">
  <body> Сколько будет 2 + 2 ?
  </body>
  <answer type="radio" value="2" max_score="$score">
    <ans-element anstype="check-box" score="-5">3</ans-element>
    <ans-element anstype="check-box" correct="true" score="$score">4</ans-element>
    <ans-element anstype="check-box" score="0">Не знаю</ans-element>
  </answer>
</task>
XML;
      $sql = "INSERT INTO `task`(`updated_at`,`created_by`, `content`, `updated_by`, `max_score`, `struct_type`, `theme_id`) VALUES " .
      "(NOW(), $user, '$content', $user, $score, '$type' ,$id)";
      \Yii::$app->db->createCommand($sql)->execute();

      echo "Tasks inserted into theme $id \n"; 
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
