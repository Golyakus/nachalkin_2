<?php

use yii\db\Migration;

class m160415_135250_add_roles extends Migration
{
    public function up()
    {
		$rbac = \Yii::$app->authManager;
	
		// Создаем роли
		$guest = $rbac->createRole('guest');
		$guest->description = 'Неустановленный пользователь может только авторизовываться';
		$rbac->add($guest);

		$pupil = $rbac->createRole('pupil');
		$pupil->description = 'Ученик может решать задачи';
		$rbac->add($pupil);

		$teacher = $rbac->createRole('teacher');
		$teacher->description = 'Учитель может создавать и редактировать (свои) КИМ';
		$rbac->add($teacher);

		$adminteacher = $rbac->createRole('admin-teacher');
		$adminteacher->description = 'Учитель-администратор может создавать и редактировать темы, КИМ и задачи';
		$rbac->add($adminteacher);

		$admin = $rbac->createRole('admin');
		$admin->description = 'Администратор может выполнять любые действия';
		$rbac->add($admin);

		// иерархия ролей
		$rbac->addChild($admin, $adminteacher);
		$rbac->addChild($adminteacher, $teacher);
		$rbac->addChild($teacher, $pupil);
		$rbac->addChild($pupil, $guest);

		// присваиваем роли пользователям
		$rbac->assign(
			$pupil, 103 // vasili
		);
		$rbac->assign(
			$teacher, 101 // teacher
		);
		$rbac->assign(
			$adminteacher, 102 // igor
		);
		$rbac->assign(
			$admin, 100 // admin
		);

		return true;
    }

    public function down()
    {
		$rbac = \Yii::$app->authManager;
		$rbac->removeAll();

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
