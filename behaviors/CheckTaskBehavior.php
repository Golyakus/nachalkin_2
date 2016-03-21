<?php
namespace app\behaviors;

class CheckTaskBehavior extends TaskBehavior
{
	const INPUT_NAME = 'ncheckbox';
	private $nameNumber;

	function __construct()
	{
		parent::__construct();
		$this->correctAnswer = [];
	}

	public function setNameSeed()
	{
		$this->nameNumber = 0;
	}

	public function newAnswerElement()
	{
		$this->nameNumber++;
	}

	public function getInputElementName()
	{
		return $this->nameFromNumber($this->nameNumber);
	}

	private function nameFromNumber($num)
	{
		return self::INPUT_NAME . (string)$num;
	}
		
	public function updateCorrectAnswer($value)
	{
		$this->correctAnswer[$this->getInputElementName()] = $value;
	}

	public function analyseAnswer($max_score, $postResponse)
	{
		
		$answerCount = 0;
		$scoreCount = 0;
		for ($i = 1; $i <= $this->nameNumber; $i++)
		{
			$name = $this->nameFromNumber($i);
			if (isset($postResponse[$name]))
			{
				$answerCount++;
				// suppose integer score for ans-element....
				$scoreCount += (integer)$postResponse[$name];
			}
		}
		if ($answerCount == 0)
			return ""; // no answer given

		// now check the answer given
		// first check for correct answer 
		// 1 - all correct answers should be in answer 


		$ok = true;
		foreach ($this->correctAnswer as $name=>$score)
			if (!isset($postResponse[$name]) || $postResponse[$name] != $score)
			{
				$ok = false;
				break;
			}

		
		if ($ok) // 2 - only correct answers should be in answer 
		{
			if ($answerCount == count($this->correctAnswer))
				return $max_score;
			$ok = false;
		}
		// here $ok should be false - just summarize all scores and return...
		return (string)$scoreCount;
	}

}


?>
