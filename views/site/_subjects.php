<div class="row">
<?php
	foreach ($domainProvider->getModels() as $domain)
    {
    	$subjectForDomain = \app\models\Subject::find()->where(['domain_id'=> $domain->id, 'class' => $subjectModel->class])->select('id')->one();
		if (!$subjectForDomain['id']) // find any class....
			$subjectForDomain = \app\models\Subject::find()->where(['domain_id'=> $domain->id])->select('id')->one();
        echo '<a href="/site/index?subjectId='.$subjectForDomain['id'] . '" class="btn btn-default">' . $domain->title . '</a>';
    }
    ?>
</div>

<?= 
	\yii\bootstrap\ButtonDropdown::widget([
		'label' => $subjectModel->class,
		'options' => [
			'class' => 'btn btn-default',
			'style' => 'margin:5px'
		],
		'dropdown' => [
			'items' => $classItems
		],
	])
?>

