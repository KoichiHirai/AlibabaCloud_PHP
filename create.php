<?php 

include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 
$iClientProfile = DefaultProfile::getProfile("cn-hongkong", $_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET'] ); 
$client = new DefaultAcsClient($iClientProfile); 
$request = new Ecs\CreateInstanceRequest(); 

$imageId = "ubuntu1204_32_40G_cloudinit_20160427.raw";
$request->setImageId($imageId);
$instanceType = "ecs.n1.tiny";
$request->setInstanceType($instanceType);
$name = "created-by-php";
$request->setInstanceName($name);
$securityId = "sg-62txsl3lo";
$request->setSecurityGroupId($securityId);
$diskCategory = "cloud_efficiency";
//var_dump($diskCategory);
$request->setSystemDiskCategory($diskCategory);
var_dump($request->getSystemDiskCategory());
exit();
$opt = "optimized";
$request->setIoOptimized($opt);

$request->setMethod("GET");

$response = $client->getAcsResponse($request); 
print_r($response);
