<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<title>
	Ajout de la personne <?php echo $_GET['nom']; ?>
</title>
<body>
<?php


$dsn="mysql:dbname=dbarslanhan;host=localhost";
    try{
      $connexion=new PDO($dsn,"root","root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }

// ?>
</body>
</html>


