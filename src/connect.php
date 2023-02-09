
<?php


$dsn="mysql:dbname=dbarslanhan;host=localhost";
    try{
      $connexion=new PDO($dsn,"root","root");
      $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
      print("Connexion réussie !");
    }
    catch(PDOException $e){
      printf("Échec de la connexion : %s\n", $e->getMessage());
      exit();
    }

// ?>
</body>
</html>


