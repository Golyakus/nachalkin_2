<?php
namespace app\behaviors;

class InputTaskBehavior extends TaskBehavior
{
	const INPUT_NAME = 'ntextinput';

	function __construct()
	{
		parent::__construct();
		$this->name = self::INPUT_NAME;
		$this->correctAnswer = '';
	}
}


?>
