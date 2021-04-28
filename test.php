<?php
require_once __DIR__ . "/vendor/autoload.php";
error_reporting(E_ALL);
ini_set('display_errors', 'ON');
sdump(\Helper\NumberHelper::hidePhone([13132693017,13132693018]));
$user_message_list=[
    ['id'=>1,'content'=>'content1','reply_id'=>'0'],
    ['id'=>2,'content'=>'content2','reply_id'=>'1'],
    ['id'=>3,'content'=>'content3','reply_id'=>'1'],
    ['id'=>4,'content'=>'content4','reply_id'=>'2'],
    ['id'=>5,'content'=>'content5','reply_id'=>'1'],
    ['id'=>6,'content'=>'content6','reply_id'=>'4'],
    ['id'=>7,'content'=>'content7','reply_id'=>'3'],
];
$user_message_list2 = $user_message_list;
$list =[];
\Helper\ArrayHelper::unlimitList($user_message_list, $list, 0,
    'id','reply_id');
$list2 =[];
\Helper\ArrayHelper::unlimitTree($user_message_list2, $list, 0,
    'id','reply_id');
sdump($list, $list2,
\Helper\NumberHelper::hidePhone([13132693017]));

;

