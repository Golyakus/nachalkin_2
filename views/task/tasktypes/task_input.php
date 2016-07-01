
<?php
/*
	$params - array[action, showsolution, model]
	$body
	$answer_prefix
	$ans_element[numeric,max_score,text]
	$answer_suffix
	$solution
*/
?>
<div><?= $body ?></div>
<div>Ответ:
<?php
	extract($params);
	if (isset($answer_prefix)) echo $answer_prefix;
	$answer_length = strlen($ans_element['text']) + 1;
	if ($action == \app\utils\TaskType::RENDER_SOLVE_ACTION)
	{
		$name = $model->getInputElementName();
		$input = "<input class='task-form-input' style='width:$answer_length"."em;'"."name=\"$name\" />";
	}
	else if ($action == \app\utils\TaskType::RENDER_VIEW_ACTION)
	{
		$answer = $ans_element['text'];
		$input = "<input class='task-form-input' maxlength=$answer_length style='width:$answer_length"."em' "."value='$answer' disabled/>";
	}
	echo $input;
// TODO : сейчас игнорируется numeric атрибут - поговорить....
	if (isset($answer_suffix)) echo $answer_suffix;
	if (isset($solution) && $showsolution)
	{
		echo '<br/>'; 
		echo 'Решение:'; 
		echo $solution;
	}
?>
</div>



