<?php


class InterestsManager {
	
	
	//InterestsManager.class.php
	private $connection;
	
	function __construct($mysqli){
		//selle klassi muutuja
		$this->connection = $mysqli;
		}
	function createDropdown(){
		
		$html = '';
		
		$html .= '<select name="dropdownselect">';
			
			//$html .= '<option value="1">Esmaspäev</option>';
			//$html .= '<option value="2">Teisipäev</option>';
			$stmt = $this->connection->prepare("SELECT id, name FROM interests");
			$stmt->bind_result($id, $name);
			$stmt->execute();
			
			//iga rea kohta teen midagi
			while($stmt->fetch()){
				$html .= '<option value="'.$id.'">'.$name.'</option>';	
			}
			
			$stmt->close();
			
		$html .= '</select>';
		
		return $html;
		
	}
		
	function addUserInterests($new_interests, $user_id){
		
		$response = new StdClass();
		//kas selline email on juba olemas?
		$stmt = $this->connection->prepare("SELECT id FROM user_interests WHERE user_id = ? AND interests_id = ?");
		$stmt->bind_param("ii", $user_id, $new_interests_id);
		$stmt->bind_result($user_id, $new_interests_id);
		$stmt->execute();
		
		// ei ole sellist kasutajat - !
		if($stmt->fetch()){
			
			// saadan tagasi errori
			$error = new StdClass();
			$error->id = 0;
			$error->message = "see hubiala juba olemas";
			
			//panen errori responsile külge
			$response->error = $error;
			
			// pärast returni enam koodi edasi ei vaadata funktsioonis
			return $response;
			
			}
				$stmt->close();
			
				$stmt = $this->connection->prepare("INSERT INTO user_interests (user_id, interests_id) VALUES (?,?)");
				$stmt->bind_param("ii", $user_id, $new_interests_id);
				
				if($stmt->execute()){
					// edukalt salvestas
					$success = new StdClass();
					$success->message = "Huviala edukalt salvestatud";
					
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
		
		
		function getUserInterests($user_id){
		
		$html = '';
		
		$stmt = $this->connection->prepare("
			SELECT interests.name FROM user_interests
			INNER JOIN interests ON 
			user_interests.interests_id = interests.id
			WHERE user_interests.user_id = ?
		");
		//echo $user_id;
		$stmt->bind_param("i", $user_id);
		$stmt->bind_result($name);
		$stmt->execute();
		
		while($stmt->fetch()){
			$html .= $name." ";
		}
		
		$stmt->close();
		
		return $html;
		
	}
	}
	
} ?>