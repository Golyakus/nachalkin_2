<?php
namespace app\behaviors;

abstract class TaskBehavior extends \yii\base\Behavior
{
	/* return array of label attributes for rendering at ActiveForm */
	public abstract function getAttributeLabels();
	/* returns array of configuration for ActiveRecord rules for added attributes */
	public abstract function getRules();

	/* loads added attribures from fields of Task active record */
	public abstract function loadTypeSpecific(\app\models\Task $task);
}

?>
