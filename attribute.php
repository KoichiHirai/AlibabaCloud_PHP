<?php 
include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile("cn-hongkong",$_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET']); 
$client = new DefaultAcsClient($iClientProfile); 

$request = new Ecs\DescribeInstancesRequest(); 
$request->setMethod("GET"); 
//$zoneId = "cn-hongkong-b"; 
//$request->setZoneId($zoneId); 
$response = $client->getAcsResponse($request); 
var_dump($response);
