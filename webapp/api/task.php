<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Declare the credentials to the database
$dbconnecterror = FALSE;
$dbh = NULL;

require_once 'credentials.php';

	try{
	
		$conn_string = "mysql:host=".$dbserver.";dbname=".$db;
		$dbh= new PDO($conn_string, $dbusername, $dbpassword);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	}	catch(Exception $e){
			//database issues were encountered
			http_response_code(504);
			echo "database issues were encountered";
			exit();
		}
	
	
	
	if ($_SERVER['REQUEST_METHOD'] == "PUT") {
		if (array_key_exists('listID', $_GET)){
			$listID = $_GET['listID'];
		}	else{
				http_response_code(400);
				echo "bad request";
				exit();
			}


		$task = json_decode(file_get_contents('php://input'), true);

		if (array_key_exists('completed', $task)) {
			$complete = $task["completed"];
			if($complete==TRUE){
				$complete=1;
			}	else {
					$complete=0;
				}
				
		}	else {//couldnt find completed
				http_response_code(422);
				echo "bad request for completion";
				exit();
			}
		
		if (array_key_exists('taskName', $task)) {
			$taskName = $task["taskName"];
		}	else {
				http_response_code(422);
				echo "bad request for task name";
				exit();
			}
		if (array_key_exists('taskDate', $task)) {
			$taskDate = $task["taskDate"];
		}	else {
				http_response_code(422);
				echo "bad request for date";
				exit();
			}
			
	//add two fields here
		if (!$dbconnecterror) {
			try {
				$sql = "UPDATE doList SET complete=:complete, listItem=:listItem, finishDate=:finishDate WHERE listID=:listID";
				$stmt = $dbh->prepare($sql);			
				$stmt->bindParam(":complete", $complete);
				$stmt->bindParam(":listItem", $taskName);
				$stmt->bindParam(":finishDate", $taskDate);
				$stmt->bindParam(":listID", $listID);
				$response = $stmt->execute();	
				http_response_code(204);
				echo "ok";
				exit();
			
			}	catch (PDOException $e) {
					http_response_code(504);//GAteway Timeout
					echo "database 1 error";
					echo $e;
					exit();
				 
				}
		}	else{
				http_response_code(504);//Gateway Timeout
				echo "gateway timeout";
				exit();
			}

	}	else if ($_SERVER['REQUEST_METHOD'] == "POST")  {
			$sql = "INSERT INTO doList (complete, listItem, finishDate) VALUES (:complete, :listItem, :finishDate)";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":complete", $complete);
			$stmt->bindParam(":listItem", $_POST['listItem']);
			$stmt->bindParam(":finishDate", $finBy);
			$response = $stmt->execute();
		}else if ($_SERVER['REQUEST_METHOD'] == "DELETE")  {
			$sql = "DELETE FROM doList where listID = :listID";
			$stmt = $dbh->prepare($sql);			
			$stmt->bindParam(":listID", $_POST['listID']);
		
			$response = $stmt->execute();
		}else{ 
			http_response_code(504);//GAteway Timeout
			echo "Expected PUT, POST, or DELETE";
			exit();
		}
		//PUT


?>
