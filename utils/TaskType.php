<?php
namespace app\utils;

use \yii\bootstrap\ActiveForm;
use \yii;

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
	private $tagsOfInterest = [];
	private $format_version = "0";

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

	public static function getFullPrototypeDir()
	{
		return '@app/views/task/' . Self::PROTOTYPE_DIR; 
	}

	const PROTOTYPE_DIR = 'tasktypes/';

	public abstract function getType();
	public abstract function getEditTitle();
	public abstract function getPrototypeFilename();
	public abstract function getFormTemplate();

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
		return $this->render($taskXml[0], $params);
	}

	/**
		$param[const $action, TaskType $task, ActiveForm $form, Task $model]
	*/
	public function render(\SimpleXMLElement $task, array $params)
	{
		static::check($task->getName(), 'task');
		
		$struct_type = false;
		$fmt_version = '0';
		foreach($task->attributes() as $attr => $val)
		{
			if ($attr == 'struct-type')
			{
				$struct_type = (string)$val;
			}
			else if ($attr == 'format-version')
				$fmt_version = (string)$val;
		}
		static::check($struct_type, $this->getType());
		
		extract($params);
		if (isset($form) && isset($model) &&isset($action) && $action == self::RENDER_EDIT_ACTION)
			echo $form->field($model, 'content')->textArea(['rows' => 15]);
		else if ($fmt_version == 0)
			$this->traverseXMLElement($task[0], $params);
		else
			return $this->processTask($task[0], $params);
	}

	protected function processModel(array $elements, \app\models\Task $model, $action)
	{
		// by default do nothing
	}


	/**
		parses prototype and sets $tagsOfInterest array
	*/
	private function initialParse(\SimpleXMLElement $elem)
	{
		$tag = [];
		$name = $elem->getName();
		foreach ($elem->attributes() as $attr=>$val)
		{
			$tag[$attr] = (string)$val;
		}

		if (isset($this->tagsOfInterest[$name])) // it should be an array of tags...- if not - set it as array
		{
			if ($this->tagsOfInterest[$name]['is_single'])
			{
				unset($this->tagsOfInterest[$name]['is_single']);
				$this->tagsOfInterest[$name] = ['is_single'=> false, 'values' => [$this->tagsOfInterest[$name], $tag]];
			}
			else
				$this->tagsOfInterest[$name]['values'][] = $tag;
		}
		else
		{
			$tag['is_single'] = true;
			$this->tagsOfInterest[$name] = $tag;
		}
		if ($elem->count())
		{
			foreach ($elem->children() as $child) {
				$this->initialParse($child);
			}
		}
	}

	/**
		appends new tags to the list of tags (first argument) and returns it as a result
	*/
	private function appendTags(array $tags, array $elements)
	{
		foreach ($elements as $name=>$val)
			if ($this->tagsOfInterest[$name]['is_single'])
				$tags[$name] = $val;
			else // this is an array...
				$tags[$name][] = $val;
		return $tags;
	}

	/** 
		returns array of tags
		Each tag is array of [text,attributes]
	*/
	protected function parseXml(\SimpleXMLElement $elem)
	{
		$tags = [];
		$name = $elem->getName();
		if (!isset($this->tagsOfInterest[$name]))
			return $tags;
		$tag = [];
		$tag['text'] = (string)$elem;
		$attrCount = 0;
		foreach ($elem->attributes() as $attr=>$val)
		{
			$tag[$attr] = (string)$val;
				$attrCount++;
		}
		if ($attrCount == 0)
			$tag = $tag['text'];
		$tags[$name] = $tag;
		if ($elem->count())
			foreach ($elem->children() as $child)
			{
				$elements = $this->parseXml($child);
				$tags = $this->appendTags($tags, $elements);
			}
		return $tags;
	}

	protected function processTask(\SimpleXMLElement $elem, array $params)
	{
		// for version 2: parse xml, create array of variables and render task elements
		$elements = $this->parseXml($elem);
		$this->processModel($elements, $params['model'], $params['action']);
		$elements['params'] = $params;

		if (isset($params['view']))
		{
			return $params['view']->render($this->getFormTemplate(), $elements/*, $params['view']->context*/);		 
		}
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

		// new stuff - do not remove!!!
		$this->initialParse(new \SimpleXMLElement(file_get_contents(Yii::getAlias( $this->getPrototypeFilename()))));
		if (isset($this->tagsOfInterest['task']['format-version']))
			$this->format_version = $this->tagsOfInterest['task']['format-version']; 
		// end of new stuff


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
