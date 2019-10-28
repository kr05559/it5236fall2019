<?php
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
	
	
	
	if ($_SERVER['REQUEST_METHOD'] == "GET") {
					
		if (!$dbconnecterror) {
			try {
				$sql = "SELECT * FROM doList";
				$stmt = $dbh->prepare($sql);			
				$response = $stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				http_response_code(200);
				echo json_encode($result);
				exit();
			
			}	catch (PDOException $e) {
					http_response_code(504);//GAteway Timeout
					echo "database 1 error";
					exit();
				 
				}
		}	else{
				http_response_code(504);//Gateway Timeout
				echo "gateway timeout";
				exit();
			}

	}	
		


?>
