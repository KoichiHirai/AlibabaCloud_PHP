<?php 
include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile("cn-hongkong",$_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET']); 
$client = new DefaultAcsClient($iClientProfile); 

$request = new Ecs\StartInstanceRequest(); 
$request->setMethod("GET"); 

$instanceId = "i-62gx8dwm0";
$request->setInstanceId($instanceId);

$response = $client->getAcsResponse($request); 
print_r($response);
