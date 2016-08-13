<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PopulateController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        echo "Заполнение базы. Доступны следующие команды :" . "\n";
        $command = './yii populate/subjects';
        $description = 'Применяется только к пустой базе и создает предметы';
        echo "\t" . $command . "\t" . $description . "\n";
        $command = './yii populate/themes xxx';
        $description = 'Создает подтемы для темы с id=xxx';
        echo "\t" . $command . "\t" . $description . "\n";
 		$command = './yii populate/tasks xxx';
        $description = 'Добавляет задачи в тему с id=xxx';
        echo "\t" . $command . "\t" . $description . "\n";    
 		$command = './yii populate/adduser role id login password e-mail ';
        $description = 'Добавляет в систему пользователя с ролью role и характеристиками (id login password e-mail)';
        echo "\t" . $command . "\t" . $description . "\n";    
	}

	public function actionAdduser($role, $id, $login, $password, $email)
	{
		if (!isset($id) || !isset($login) ||  !isset($password) ||  !isset($email))
		{
			echo "Set id, login, password and e-mail for the user to add.\n";
		}
 		if (!\app\models\User::createUser($id, $login, $password, $email))
			echo "Problems with creating user...User was not added\n";
		\Yii::$app->db->createCommand("INSERT INTO `auth_assignment`(`item_name`, `user_id`, `created_at`) VALUES ('task-editor', $id,NOW())")->execute();
		echo "User was added successfully\n";
	}

    public function actionSubjects()
    {
    	// добавление главной темы (без родителя) - соответствует предмету для какого-либо класса
    	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description` " .
		") VALUES (NOW(),102,102,'Русский язык','')")->execute();
		
    	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description` " .
		") VALUES (NOW(),102,102,'Метапредметность','')")->execute();
		echo "Main themes inserted....\n";
		\Yii::$app->db->createCommand("INSERT INTO `subject`(`created_at`, `created_by`, `updated_by`, `year_id`, `theme_id`)".
		" VALUES (NOW(),102,102,'4',2)")->execute();
		\Yii::$app->db->createCommand("INSERT INTO `subject`(`created_at`, `created_by`, `updated_by`, `year_id`, `theme_id`)".
		" VALUES (NOW(),102,102,'4',3)")->execute();
		echo "Subjects inserted....\n";
    }

    public function actionThemes($id)
    {
    	
    	$th = "'Описание темы 1'";
    	$sql = "INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Тема 1', $th, $id)";

     	\Yii::$app->db->createCommand($sql)->execute();  

		$th = "'Описание темы 2'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Тема 2', $th, $id)")->execute(); 
		$th = "'Описание темы 3'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Тема 3', $th, $id)")->execute();   	
		$th = "'Описание темы 4'";
     	\Yii::$app->db->createCommand("INSERT INTO `theme`(`created_at`, `created_by`, `updated_by`, `title`, `description`, `parent` " .
		") VALUES (NOW(),102,102,'Тема 4', $th, $id)")->execute(); 
		 
		echo "Themes inserted into theme $id \n";  	
    }

    public function actionTasks($id)
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
}
