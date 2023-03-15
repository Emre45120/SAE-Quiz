
<?php


$dsn="mysql:dbname=DBarslanhan;host=localhost";
    try{
      $connexion=new PDO($dsn,"root","arslanhan45.");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }

// ?>
</body>
</html>



