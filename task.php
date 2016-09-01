<?php 
include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile("cn-hongkong",$_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET']); 
$client = new DefaultAcsClient($iClientProfile); 

/* Creating the instance

*/
//getting an instanceID
$getInstanceId = "i-62enfp8sb";

// Showing the information of the instance

$request = new Ecs\DescribeInstancesRequest(); 
$request->setMethod("GET");  
$response = $client->getAcsResponse($request);

$total = $response->TotalCount;
$level_1 = $response->Instances;
$level_2 = $level_1->Instance;

for($count = 0; $count < $total; $count++){
	$level_3 = $level_2[$count];
	$instanceId = $level_3->InstanceId;
	if($instanceId == $getInstanceId){
     		break;
	}
	if($count == $total - 1){
		echo "error: cannot create an instance\n";
		exit();
	}
}

echo "\nInstance Information\n" . "InstanceID: ". $instanceId . "\n" .
"RegionID: " . $level_3->RegionId . "\n" . "ZoneID: " . $level_3->ZoneId . "\n" .
"ImageID: " . $level_3->ImageId . "\n" . "CPU: " . $level_3->Cpu . "\n" .
"Memory: " . $level_3->Memory . "\n" . "Status: " . $level_3->Status . "\n";

// Starting the instance
$request = new Ecs\StartInstanceRequest(); 
$request->setMethod("GET"); 
//instance ID
$instanceId = $getInstanceId; 
$request->setInstanceId($instanceId); 
$response = $client->getAcsResponse($request); 
// Starting the instance

$start = microtime(true);

// monitoring the state
while(true){
	$request = new Ecs\DescribeInstancesRequest();
	$request->setMethod("GET");
	$response = $client->getAcsResponse($request);
                                                                                                                 
	$level_1 = $response->Instances;
	$level_2 = $level_1->Instance;
	$level_3 = $level_2[$count];
                                                                                                             
	if($level_3->Status == "Running"){
		break;
	}
}

$end = microtime(true);

//Showing the time of starting
echo "\nstarting time: ".($end - $start)."sec\n"; 

//Showing the status of the instance (wanna show only one instance)
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";


// Stoping the instance  
$request = new Ecs\StopInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);
$response = $client->getAcsResponse($request);  

$start = microtime(true);

//waitng changing the status
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
                                                                                                                 
        $level_1 = $response->Instances;
        $level_2 = $level_1->Instance;
        $level_3 = $level_2[$count];
                                                                                                                 
        if($level_3->Status == "Stopped"){
                break;
        }
}

$end = microtime(true);
                                                                                                                 
//Showing the time of stopping
echo "\nstopping time: ".($end - $start)."sec\n";


//Showing the status of the instance
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";


// removing the instance
$request = new Ecs\DeleteInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);  
$response = $client->getAcsResponse($request);


/*waitng changing the status*/
                                                                                                              


//Showing the status of the instance
$request = new Ecs\DescribeInstanceStatusRequest();
$request->setMethod("GET"); 
$zoneId = "cn-hongkong-b"; 
$request->setZoneId($zoneId);
$response = $client->getAcsResponse($request);
echo "\n";
print_r($response);


echo "\nfinished removing the instance(instanceID: " . $getInstanceId . ")\n";
