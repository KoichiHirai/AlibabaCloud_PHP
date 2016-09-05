<?php 
include_once '../aliyun-openapi-php-sdk/aliyun-php-sdk-core/Config.php'; 
use Ecs\Request\V20140526 as Ecs; 

$iClientProfile = DefaultProfile::getProfile("cn-hongkong",$_SERVER['ALIYUN_KEY'],$_SERVER['ALIYUN_SECRET']); 
$client = new DefaultAcsClient($iClientProfile); 

//getting the number of instances before creating an instance
$request = new Ecs\DescribeInstancesRequest();
$request->setMethod("GET");
$response = $client->getAcsResponse($request);


$initialTotal = $response->TotalCount;


// Creating the instance
try{

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
$request->setSystemDiskCategory($diskCategory);
$opt = "optimized";
$request->setIoOptimized($opt);
                                                                                                                       
$request->setMethod("GET");
                                                                                                                       
                                                                                                                       
$response = $client->getAcsResponse($request);
//$response = $client->doAction($request); 
}catch(Exception $e){

// When cannot create the instance
$csv = ",NG,,NG,,NG,,NG,\n";
echo "error: cannot create the instance\n";                                                        
file_put_contents("task.csv",$csv,FILE_APPEND);
exit();
}

//getting an instanceID
$getInstanceId = $response->InstanceId;
$csv = $getInstanceId . "," . "OK";


$start = microtime(true);


// monitoring the state
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
	if($response->TotalCount == $initialTotal + 1){
		break;
	}
}



$end = microtime(true);

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
		echo "error: cannot find the instance\n";
		exit();
	}
}

echo "\nInstance Information------------------------------\n" . "InstanceID: ". $instanceId . "\n" .
"RegionID: " . $level_3->RegionId . "\n" . "ZoneID: " . $level_3->ZoneId . "\n" .
"ImageID: " . $level_3->ImageId . "\n" . "CPU: " . $level_3->Cpu . "\n" .
"Memory: " . $level_3->Memory . "\n" . 
"--------------------------------------------------";


// Showing the time of creating the instance
$calculatedTime = $end - $start;
echo "\nCreating time: ".$calculatedTime."sec\n";
$csv = $csv . "," . $calculatedTime;


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

	//when cannot start the instance
	if($level_3->Status == "Stopped"){
		$csv = $csv . ",NG,,NG,,NG,\n";
		file_put_contents("task.csv",$csv,FILE_APPEND);
		echo "error: cannot start the instance\n";
		exit();
	}
	
}

$end = microtime(true);

//Showing the status of the instance (wanna show only one instance)
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";

//Showing the time of starting
$calculatedTime = $end - $start;
echo "Starting time: ".$calculatedTime."sec\n";

$csv = $csv . ",OK,". $calculatedTime;

// Stoping the instance  
$request = new Ecs\StopInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);
$response = $client->getAcsResponse($request);  

$start = microtime(true);

//monitoring the status
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

	//when cannot stop the instance
	if($level_3->Status == "Running"){
        	$csv = $csv . ",NG,,NG,\n";
        	file_put_contents("task.csv",$csv,FILE_APPEND);
        	exit();
	}
}

$end = microtime(true);
                                                                                                                 
//Showing the status of the instance
echo "\nInstace status: ";
print_r($level_3->Status);
echo "\n";

//Showing the time of stopping
$calculatedTime = $end - $start;
echo "Stopping time: ".$calculatedTime."sec\n";

$csv = $csv . ",OK,". $calculatedTime;


// removing the instance
try{
$request = new Ecs\DeleteInstanceRequest(); 
$request->setMethod("GET");  
$request->setInstanceId($getInstanceId);  
$response = $client->getAcsResponse($request);
}catch(Exception $e){
                                                                                                                       
// When cannot remove the instance
$csv = ",NG,\n";
echo "error: cannot remove the instance\n";
file_put_contents("task.csv",$csv,FILE_APPEND);
exit();
}

$start = microtime(true);


// monitoring the status
while(true){
        $request = new Ecs\DescribeInstancesRequest();
        $request->setMethod("GET");
        $response = $client->getAcsResponse($request);
        if($response->TotalCount == $initialTotal){
                break;
        }
}



$end = microtime(true);                                                                                                        

/*
//Showing the status of the instance
$request = new Ecs\DescribeInstanceStatusRequest();
$request->setMethod("GET"); 
$zoneId = "cn-hongkong-b"; 
$request->setZoneId($zoneId);
$response = $client->getAcsResponse($request);
echo "\n";
print_r($response);
*/

$calculatedTime = $end - $start;
echo "\nfinished removing the instance(instanceID: " . $getInstanceId . ")\n" . 
     "Removeing time: " . $calculatedTime . "sec" . "\n\n";
$csv = $csv . ",OK," . $calculatedTime . "\n";

file_put_contents("task.csv",$csv,FILE_APPEND);
