<?php
require_once __DIR__ . '/../autoload.php';
use bright_tech\wechat\Wechat;


$w = new Wechat('wx3eae1258010cfa23', '9a880e160b566c145cf56815dd4f4910');

try {
   $result = $w->getAccessToken();
   var_dump($result);
}catch (\Exception $e){
    echo $e->getMessage();
}
