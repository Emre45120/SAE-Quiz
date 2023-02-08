<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
<title>
	Ajout de la personne <?php echo $_GET['nom']; ?>
</title>
<body>
<?php


$dsn="mysql:dbname=DBarslanhan;host=servinfo-mariadb";
    try{
      $connexion=new PDO($dsn,"arslanhan","arslanhan");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
      printf("Connexion réussie");
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }

// ?>
</body>
</html>


