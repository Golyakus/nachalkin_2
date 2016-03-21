<?php

namespace app\utils;

use app\models\Theme;
use app\models\Task;
use app\models\Taskresult;

class ThemeTreeElement {
	public $model;
	private $taskCount; // integer
	private $solvedTaskCount;
	public $isLeaf; // bool
	public $children; // array of ThemeTreeElement

	public function print_r()
	{
		print_r($this->model);
		print_r($this->children);
	}

	function __construct($model, $userId)
	{
		$this->model = $model;
		$this->taskCount = 0;
		$this->solvedTaskCount = 0;
		$this->children = [];
		$this->makeChildren($userId);
	}

	public function getTaskCount() 
	{
		return $this->taskCount;
	}

	public function getSolvedTaskCount() 
	{
		return $this->solvedTaskCount;
	}

	private function makeChildren($userId)
	{
		$this->isLeaf = $this->model->getSubtheme()->count() == 0;
		if (!$this->isLeaf) {
			foreach ($this->model->getSubtheme()->all() as $subModel) {
				$child = new ThemeTreeElement($subModel, $userId);
				$this->children[] = $child;
				$this->taskCount += $child->taskCount;
				$this->solvedTaskCount += $child->solvedTaskCount;
			}
		}
		else {
			$this->taskCount = Task::getTasksForTheme($this->model->id)->count();
			$this->solvedTaskCount = Taskresult::find()->joinWith('task')->where(['taskresult.user_id'=>$userId])->andWhere(['task.theme_id'=>$this->model->id])->
					andWhere(['not', ['taskresult.score' => null]])->count();
			\Yii::trace("Theme ". $this->model->id, $this->taskCount);
		}
	}

} 

?>