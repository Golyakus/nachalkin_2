
<?php
/*
	$params - array[action, showsolution, model]
	$body
	$answer_prefix
	array $ans_element[correct,text]
	$answer_suffix
	$answer[max_score]

	$solution
*/
?>
<div><?= $body ?></div>
<div>Ответ:
<?php
	extract($params);

	if (isset($answer_prefix)) echo $answer_prefix;
	if ($action == \app\utils\TaskType::RENDER_SOLVE_ACTION)
	{
		$name = $model->getInputElementName();
		$select = "<select class='task-form-select' name='$name'>";
	}
	else if ($action == \app\utils\TaskType::RENDER_VIEW_ACTION)
	{
		$select = "<select class='task-form-select'>";
	} 
	echo $select;
	foreach ($ans_element as $option)
	{
		echo '<option ';
		if ($action == \app\utils\TaskType::RENDER_VIEW_ACTION && is_array($option) && isset($option['correct']) && $option['correct'] == 'true')
			echo 'selected ';
		echo '>';
		if (is_array($option))
			echo $option['text'];
		else
			echo (string)$option;
		echo '</option>';
	}
	echo '</select>';
	if (isset($answer_suffix)) echo $answer_suffix;
	if (isset($solution) && $showsolution)
	{
		echo '<br/>'; 
		echo 'Решение:'; 
		echo $solution;
	}
?>
</div>



