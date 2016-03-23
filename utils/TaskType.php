<?php
namespace app\utils;

use \yii\bootstrap\ActiveForm;

class TaskException extends \yii\base\UserException
{
}

function dummy() 
{ 
	return  function($self, $elem, $action) { return true; };
}

abstract class TaskType 
{

	const RENDER_VIEW_ACTION = 1;
	const RENDER_LIST_ACTION = 2;
	const PARSE_ACTION = 3;
	const RENDER_EDIT_ACTION = 4;	
	const RENDER_SOLVE_ACTION = 5;
	private static $tasktypes = NULL;

	public static function loadTaskType($type)
	{
		if (!self::$tasktypes)
			self::loadAllTypes();
			
		for($i = 0; $i < count(self::$tasktypes); $i++)
		{
			if (self::$tasktypes[$i]->getType() == $type)
				return self::$tasktypes[$i];
		}
		throw new TaskException("Unknow task type $type");
	}

	public static function loadAllTypes()
	{
		if (!self::$tasktypes)
		{
			self::$tasktypes[] = new InputTaskType();
			self::$tasktypes[] = new RadioTaskType();
			self::$tasktypes[] = new CheckTaskType();
			self::$tasktypes[] = new DropdownTaskType();
		}
		return self::$tasktypes;
	}
	const PROTOTYPE_DIR = 'tasktypes/';

	public abstract function getType();
	public abstract function getEditTitle();
	public abstract function getPrototypeFilename();

	// for TaskRecord and ActiveForm rendering

	/**
	* return TaskBehavior subclass instance containing type-specific data properties 
	*/
	public abstract function makeBehavior();

	// template for rendering task editing....
	//public abstract function getFormTemplate();
	//public abstract function setModelAttributes($model);

	
	public abstract function checkAnswer($model, $postResponse);

	static private function check($obj, $s)
	{
		if ($obj != $s)
		{	
			$msg = sprintf("illegal xml format: %s != %s", $obj, $s);
			throw new TaskException($msg); 
		}
	}

	/**
		$params - array of
		$model \app\models\Task
		$form - \yii\bootstrap\ActiveForm
		$action - one of self::RENDER_XXXX_ACTION constants
	*/
	public function traverse($params)
	{
		extract($params);
		if (!isset($model))
			return;
		$taskXml = new \SimpleXMLElement($model->content);
		$this->render($taskXml[0], $params);
	}

	public function render(\SimpleXMLElement $task, $params)
	{
		static::check($task->getName(), 'task');
		
		$struct_type = false;
		foreach($task->attributes() as $attr => $val)
		{
			if ($attr == 'struct-type')
			{
				$struct_type = $val;
				break;
			}	
		}
		static::check($struct_type, $this->getType());
		
		extract($params);
		if (isset($form) && isset($model) &&isset($action) && $action == self::RENDER_EDIT_ACTION)
			echo $form->field($model, 'content')->textArea(['rows' => 15]);
		else
			$this->traverseXMLElement($task[0], $params);	
	}

	private function traverseXMLElement(\SimpleXMLElement $elem, $params)
	{
		try
		{
			if ($this->traverseStart($elem, $params))
			{
				$this->traverseChildren($elem, $params);
				$this->traverseEnd($elem, $params);
			}
		}
		catch (\yii\base\ErrorException $e)
		{
			extract($params);
			\Yii::trace($e->getMessage(), "error");
			if ($action != self::PARSE_ACTION)
				echo $elem->asXml();		
		}
	}
	
	private function traverseChildren(\SimpleXMLElement $elem, $params)
	{	
		$children = $elem->children();
		if ($elem->count())
		{	
			foreach($children as $chld)
			{
				$this->traverseXMLElement($chld, $params);
			}
		}
		else 
		{
			extract($params);
			//\Yii::trace((string)$elem, 'No children');
			if ($action != self::PARSE_ACTION)
				echo $elem;
		}
	}

 /* default implementation of renderStart() and renderEnd() through dictionary of registered lambdas */

	protected $startElement = [];
	protected $endElement = [];
	
	protected function traverseStart(\SimpleXMLElement $elem, $params)
	{	
		return $this->startElement[$elem->getName()]($this, $elem, $params);
	}
	
	protected function traverseEnd(\SimpleXMLElement $elem, $params)
	{
		$this->endElement[$elem->getName()]($this, $elem, $params);
	}
	
	function __construct()
	{
		$this->startElement['task'] = function($self, $elem, $params) {
			extract($params);
			if ($action != self::PARSE_ACTION)
				echo "<div>";
			return true;
		};
		$this->endElement['task'] = function($self, $elem, $params) {
			extract($params);
			if ($action != self::PARSE_ACTION)
			{
				echo '</div>';
			}
		};
		$this->startElement['body'] = function($self, $elem, $params) {
			extract($params);
			if ($action != self::PARSE_ACTION)
				echo '<div>';
			return true;
		};
		$this->endElement['body'] = function($self, $elem, $params) {
			extract($params);
			if ($action != self::PARSE_ACTION)
				echo '</div>';
		};
		$this->startElement['answer'] = function($self, $elem, $params) {
			extract($params);
			foreach($elem->attributes() as $attr=>$val)
				if ($attr == 'type')
					$answer_type = $val;
				else if ($attr == 'max_score')
					$model->max_score = (string)$val;
			Tasktype::check($answer_type, $self->getType());		
			if ($action != self::PARSE_ACTION)
				echo '<div>';
			return true;
		};
		$this->endElement['answer'] = function($self, $elem, $params) {
			extract($params);
			if ($action != self::PARSE_ACTION)
				echo '</div>';
		};
		$this->startElement['ans-element'] = function($self, $elem, $params) {
			return $this->processAnswerElement($elem, $params);
		};
		$this->endElement['ans-element'] = dummy(); // assume that all processing is done above....
		$this->startElement['t'] = dummy();
		$this->endElement['t'] = dummy();	
		$this->startElement['solution'] = function($self, $elem, $params) {
			extract($params);
			if ($action == self::RENDER_SOLVE_ACTION)
				return false; // ignore element while solving....
			if ($action != self::PARSE_ACTION)
				echo $elem;
			return true;
		};
	}

	/** creates array of variables name=value from attributes 
	*/
	private function processAttributes(\SimpleXMLElement $elem)
	{
		$scope = [];
		foreach($elem->attributes() as $attr=>$val)
			$scope[$attr] = (string)$val; 
		return $scope;
	}

	protected function onAttributes(\SimpleXMLElement $elem, $scope, $params)
	{
	}

	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		$scope = $this->processAttributes($elem);
		$this->onAttributes($elem, $scope, $params);
		return true;
	}
}

?>
