<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	
	$listID = $_POST['listID'];
	
	if (array_key_exists('fin', $_POST)) {
		$complete = 1;
	} else {
		$complete = 0;
	}
	if (empty($_POST['finBy'])) {
		$finBy = null;
	} else {
		$finBy = $_POST['finBy'];
	}
	$listItem = $_POST['listItem'];
	
	//make call to the api
	$url="http://3.219.174.205/api/task.php?listID=$listID";
	$data = array('completed'=>$complete,'taskName'=>$listItem,'taskDate'=>$finBy);
	$data_json = json_encode($data);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	if($httpcode==204){
		header("Location: index.php");
	}else{			
		//api errors	
		header("Location: index.php?error=edit");
	}			
}
?>
