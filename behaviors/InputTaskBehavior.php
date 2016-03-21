<?php
namespace app\behaviors;

class InputTaskBehavior extends TaskBehavior
{
	public $condition;
	public $answerprefix;
	public $answersuffix;
	public $answervalue;
	public $solution;
	public $isnumeric;

/* return array of label attributes for rendering at ActiveForm */
	public function getAttributeLabels()
	{
		return [
			'answervalue'=> 'Значение ответа',
			'condition'=>'',
			'solution'=>'Решение',
			'answerprefix' => '',
			'answersuffix' => '',
			'isnumeric'=>'',
		];
	}
	public function getRules()
	{
		return [
			[['answervalue'], 'safe'],
            [['condition'], 'safe'],
			[['solution'], 'safe'],
            [['answerprefix'], 'safe'],
            [['answersuffix', 'isnumeric'], 'safe'],  		
		];
	}

	public function loadTypeSpecific(\app\models\Task $task)
	{
		$retval = '<?xml version="1.0" encoding="utf-8"?>
<task struct-type="' . $task->struct_type . '" max_score="' . $task->max_score . '"><body>';
		$retval .= $task->condition . '</body>';
		$retval .= '<answer type="'. $task->struct_type . '" value="' . $task->answervalue . '" max_score="'. $task->max_score . '">Ответ:';
		if ($task->answerprefix)
			$retval .= '<t>'. $task->answerprefix . '</t>';
		//$ansel = '<ans-element ans-type="input" hidden="true" numeric="' . $task->isnumeric ? 'true' : 'false' . " max_score=". $task->max_score . '">'. $task->answervalue . '</ans-element>';
		$ansel = '<ans-element ans-type="input" hidden="true" numeric="' . ($task->isnumeric ? 'true' : 'false') . '" max_score="'. $task->max_score . '">' . $task->answervalue . '</ans-element>';
		var_dump($ansel); 
		$retval .= $ansel;
		if ($task->answersuffix)
			$retval .= '<t>'. $task->answersuffix . '</t>';
		$retval .= '</answer>';
		$retval .= '<solution>' . $task->solution . '</solution>
</task>';
		
		\Yii::trace($retval, "Generated xml");
		$task->content = $retval;
		return true;
	}
}


?>
