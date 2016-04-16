<?php

/*
return \yii\helpers\ArrayHelper::merge(
	require (__DIR__ . '/environ/base.php' ), // base settings common for all types and tagets
	require (__DIR__ . '/environ/web_base.php'),
	require (__DIR__ . '/environ/local.php')
);
*/



$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'school_task_editor',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
		'authManager' => [
			'class' => '\yii\rbac\DbManager',
			'defaultRoles' => ['guest'],
		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'aj94rtJ8xEaXRadSJSm49FrVDUvMhpwi',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
/*
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'task/newtask/<type:\w+>/<subjectId:\d+>/<themeId:\d+>' => 'task/newtask',
				'task/update/<id:\d+>/<subjectId:\d+>' => 'task/update',
				'task/updatekim/<id:\d+>/<subjectId:\d+>' => 'task/updatekim',
				'task/solve/<id:\d+>' => 'task/solve',
				'task/choosetype/<subjectId:\d+>/<themeId:\d+>' => 'task/choosetype',
				'theme/<subjectId:\d+>/<themeId:\d+>' => 'theme',
				'theme/view/<subjectId:\d+>/<id:\d+>'=> 'theme/view',
				'theme/list/<subjectId:\d+>' => 'theme/list',
				'theme/update/<id:\d+>/<subjectId:\d+>' => 'theme/update',
				'theme/description/<id:\d+>/<subjectId:\d+>' => 'theme/description',
                'kim/create/<subjectId:\d+>' => 'kim/create',
            ],
        ],
		'view'=> [
			'renderers' => [
				'xml' => [
					'class' => '\app\utils\TaskRenderer'
				]
			]
		],
    ],
    'params' => $params,
	'aliases' => [
		'@web' => __DIR__ . '/../web',
	],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
