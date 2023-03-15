
<?php


$dsn="mysql:dbname=DBaazzouz;host=localhost";
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



