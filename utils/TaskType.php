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
	/* return TaskBehavior subclass instance containing type-specific data properties */
	//public abstract function makeBehavior();

	// template for rendering task editing....
	//public abstract function getFormTemplate();
	//public abstract function setModelAttributes($model);

	static private function check($obj, $s)
	{
		if ($obj != $s)
		{	
			$msg = sprintf("illegal xml format: %s != %s", $obj, $s);
			throw new TaskException($msg); 
		}
	}

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
		TaskType::check($task->getName(), 'task');
		
		$struct_type = false;
		foreach($task->attributes() as $attr => $val)
		{
			if ($attr == 'struct-type')
			{
				$struct_type = $val;
				break;
			}	
		}
		TaskType::check($struct_type, $this->getType());
		
		extract($params);
		if (isset($form) && isset($model) &&isset($action) && $action == TaskType::RENDER_EDIT_ACTION)
			echo $form->field($model, 'content')->textArea(['rows' => 10]);
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
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo $elem->asXml();
			}		
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
			\Yii::trace((string)$elem, 'No children');
			if ($action == RENDER_VIEW_ACTION)
			{
				echo $elem;
			}
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

	protected $title;
	protected $answer_value;
	
	function __construct()
	{
		$this->startElement['task'] = function($self, $elem, $params) {
			extract($params);
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				$this->title = "task";
				//echo $form->field($model, 'content')->hiddenInput();
				echo "<div>";
			}
			return true;
		};
		$this->endElement['task'] = function($self, $elem, $params) {
			extract($params);
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo '</div>';
			}
		};
		$this->startElement['body'] = function($self, $elem, $params) {
			extract($params);
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo '<div>';
			}
			return true;
		};
		$this->endElement['body'] = function($self, $elem, $params) {
			extract($params);
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo '</div>';
			}
		};
		$this->startElement['answer'] = function($self, $elem, $params) {
			extract($params);
			foreach($elem->attributes() as $attr=>$val)
				if ($attr == 'type')
					$answer_type = $val;
				else if ($attr == 'value')
					$this->answer_value = $val;
			Tasktype::check($answer_type, $self->getType());		
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo '<div>';
			}
			return true;
		};
		$this->endElement['answer'] = function($self, $elem, $params) {
			extract($params);
			if ($action == TaskType::RENDER_VIEW_ACTION)
			{
				echo '</div>';
			}		
		};
		$this->startElement['ans-element'] = function($self, $elem, $params) {
			return $this->processAnswerElement($elem, $params);
		};
		$this->endElement['ans-element'] = dummy(); // assume that all processing is done above....
		$this->startElement['t'] = dummy();
		$this->endElement['t'] = dummy();	
	}

	protected $correct;

	protected function onAttribute($attr, $val)
	{
		if ($attr == 'correct')
			$this->correct = $val == 'true';
	}

	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		$this->correct = false;
		foreach($elem->attributes() as $attr=>$val)
			$this->onAttribute($attr,$val);
		return true;
	}
}

?>
