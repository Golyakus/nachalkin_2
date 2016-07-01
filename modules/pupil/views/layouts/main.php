<?php

/* @var $this \yii\web\View */
/* @var $content string */
use \yii\helpers\Html;
use \yii\widgets\Breadcrumbs;

app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <div class="content-wrapper">
    	<!-- mainmenu -->
		<div class="pull-right"><div><?= \Yii::$app->user->getIdentity()->getFullName() ?> <br/> <?=\Yii::$app->user->getIdentity()->group ?></div><?= Html::a('Выйти',['/site/logout'])?></div>
   		<?= 
			Breadcrumbs::widget([
      			'homeLink' => [ 
                      'label' => 'Все предметы',
                      'url' => Yii::$app->homeUrl,
                 ],
      			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]);
		
    	?> 
    <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
