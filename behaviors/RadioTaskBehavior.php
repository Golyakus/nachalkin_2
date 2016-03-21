<?php
namespace app\behaviors;

class RadioTaskBehavior extends TaskBehavior
{
	const INPUT_NAME = 'nradio';

	function __construct()
	{
		parent::__construct();
		$this->name = self::INPUT_NAME;
		$this->correctAnswer = '';
	}
	public function newAnswerElement() {}
	public function setNameSeed() {}
}


?>
