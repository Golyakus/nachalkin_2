<?php
namespace app\utils;

abstract class ButtonTaskType extends TaskType
{
	protected function getHtmlAnswerType() { return $this->getType(); }
	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		extract($params);
		parent::ProcessAnswerElement($elem, $params);
		$name = $this->title;
		$t = $this->getHtmlAnswerType();
		$retval = "<input type=\"$t\" ";
		if ($this->correct && $action == TaskType::RENDER_VIEW_ACTION) 
			$retval .= "checked ";
		$retval .= " name=\"$name\">" . $elem;
		echo $retval;
		return false;
	}
	function __construct()
	{
		parent::__construct();
		$this->endElement['ans-element'] = function($self, $elem, $params) {
			echo '</input><br>';
		};
	}
}

?>


