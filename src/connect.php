
<?php


$dsn="mysql:dbname=DBarslanhan;host=servinfo-mariadb";
    try{
      $connexion=new PDO($dsn,"arslanhan","arslanhan");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }

// ?>
</body>
</html>


