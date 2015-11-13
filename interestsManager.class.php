<?php
class InterestsManager {
	
	
	//InterestsManager.class.php
	private $connection;
	
	function __construct($mysqli){
		//selle klassi muutuja
		$this->connection = $mysqli;
		
		
		
	}
	
	function addInterests($new_interests){
		
		$response = new StdClass();
		//kas selline email on juba olemas?
		$stmt = $this->connection->prepare("SELECT name FROM interests WHERE name = ?");
		$stmt->bind_param("s", $new_interests);
		$stmt->bind_result($name);
		$stmt->execute();
		
		// ei ole sellist kasutajat - !
		if($stmt->fetch()){
			
			// saadan tagasi errori
			$error = new StdClass();
			$error->id = 0;
			$error->message = "see interests juba olemas";
			
			//panen errori responsile külge
			$response->error = $error;
			
			// pärast returni enam koodi edasi ei vaadata funktsioonis
			return $response;
			
		}
		
		$stmt->close();
	
		$stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
		$stmt->bind_param("s", $new_interests);
		
		if($stmt->execute()){
			// edukalt salvestas
			$success = new StdClass();
			$success->message = "interests edukalt salvestatud";
			
			$response->success = $success;
			
		}else{
			// midagi läks katki
			$error = new StdClass();
			$error->id =1;
			$error->message = "Midagi läks katki!";
			
			//panen errori responsile külge
			$response->error = $error;
			
		}
		
		$stmt->close();
		
		//saada tagasi vastuse, kas success või error
		return $response;
		
		
		
		
		
		//1) kontrollite kas selline huviala on olemas (tabel interests)
		//2) kui ei ole siis lisate juurde
		// kui võtate aluseks createUser funktsiooni siis tuleb muuta seal ainult 4 rida.
		
		
	}
	
	
	
} ?>