<?php
namespace app\behaviors;

abstract class TaskBehavior extends \yii\base\Behavior
{
	/** abstract properties
	 * should be filled in descendants
	*/
	protected $name;
	public $correctAnswer;

	public function getInputElementName()
	{
		return $this->name;
	}
	public function updateCorrectAnswer($value)
	{
		$this->name = $value;
	}

	/**
	 * return score for task according to post response
	*/
	//public abstract function checkAnswer($postResponse);

	/* loads added attribures from fields of Task active record */
	//public abstract function loadTypeSpecific(\app\models\Task $task);
}

?>
