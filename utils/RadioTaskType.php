<?php
namespace app\utils;

class RadioTaskType extends ButtonTaskType 
{
	public function getType() { return 'radio'; }
	public function getEditTitle()
	{
		return 'Выбор одного верного ответа из предложенных вариантов';
	}
	public function getPrototypeFilename() { return TaskType::PROTOTYPE_DIR.'task_radio.xml'; }	
}

?>
