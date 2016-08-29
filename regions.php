<?php

include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile($_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET'], "YVLbbMVbN7KUwR8SWj1cYgKs5af10P"); 
$client = new DefaultAcsClient($iClientProfile); 

$request = new Ecs\DescribeRegionsRequest(); 
$request->setMethod("GET"); 
$response = $client->getAcsResponse($request); 
print_r($response);
