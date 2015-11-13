<?php
	require_once("functions.php");
	require_once("interestsManager.class.php");
	
	if(!isset($_SESSION["id_from_db"])){
		header("Location: login.php");
	
		exit();
	}
	if(isset($_GET["logout"])){
		session_destroy();
		
		header("Location: login.php");
	}
	
	//teen uue instanti class InterestsManager
	$InterestsManager = new InterestsManager($mysqli);
	
	if(isset($_GET["new_interests"])){
		
		$added_interests = $InterestsManager->addInterests($_GET["new_interests"]);
		
		if(isset($_GET["dropdownselect"])){
			// saadan valiku id ja kasutaja id
			$added_user_interest = $InterestsManager->addUserInterests($_GET["dropdownselect"],$_SESSION["id_from_db"]);
		}
	}
	
?>

<p>
	Tere, <?=$_SESSION["user_email"];?>
	<a href="?logout=1"> Logi välja</a>
</p>

<h2>Lisa uus hiviala</h2>
<form>
	<input name="new_interests">
	<input type="submit">


<?php if(isset($added_interests->error)): ?>
  
	<p style="color:red;">
		<?=$added_interests->error->message;?>
	</p>
  
  <?php elseif(isset($added_interests->success)): ?>
  
	<p style="color:green;">
		<?=$added_interests->success->message;?>
	</p>
  
  <?php endif; ?>  
  </form>

  <<h2>Minu huvialad</h2>
<form>
<?php if(isset($added_user_interest->error)): ?>
  
	<p style="color:red;">
		<?=$added_user_interest->error->message;?>
	</p>
  
  <?php elseif(isset($added_user_interest->success)): ?>
  
	<p style="color:green;">
		<?=$added_user_interest->success->message;?>
	</p>
  
  <?php endif; ?>  
	<!-- SIIA TULEB RIPPMENÜÜ -->
	<?php echo $InterestsManager->createDropdown();?>
	<input type="submit">
</form>

<p><?php echo $InterestsManager->getUserInterests($_SESSION["id_from_db"]);?></p>