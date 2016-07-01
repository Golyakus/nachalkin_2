<?php

namespace app\modules\pupil;

/**
 * pupil module definition class
 */
class PupilModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\pupil\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
		$this->layout = 'main.php';
    }
}
