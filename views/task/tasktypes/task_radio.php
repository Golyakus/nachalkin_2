
<?php
/*
	$params - array[action, showsolution, model]
	$body
	$answer_prefix
	array $ans_element[correct,text,score]
	$answer_suffix
	$answer[max_score]

	$solution
*/
?>
<div><?= $body ?></div>
<div>
<?php
	extract($params);

	if (isset($answer_prefix)) echo $answer_prefix;
	foreach ($ans_element as $option)
	{
		echo "<input type='radio' ";
		if ($action == \app\utils\TaskType::RENDER_VIEW_ACTION && isset($option['correct']) && $option['correct'] == 'true')
			echo 'checked ';
		if (isset($option['score']))
			$score = $option['score'];
		else
			$score = '0';
		echo "value='$score' ";
		if ($action == \app\utils\TaskType::RENDER_SOLVE_ACTION)
		{
			$name = $model->getInputElementName();
			echo "name='$name' ";
		}
		echo '>';
		echo $option['text'];
		echo '</input><br/>';
	}
	if (isset($answer_suffix)) echo $answer_suffix;
	if (isset($solution) && $showsolution)
	{
		echo '<br/>'; 
		echo 'Решение:'; 
		echo $solution;
	}
?>
</div>



