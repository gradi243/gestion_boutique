<?php
// Connexion à la base de données
require_once ("../connexion/connexion.php");
// Inclusion du fichier de gestion des catégories
require_once ("../include/categorie.php");


if (isset($_POST['valider'])) {
    $id=htmlspecialchars($_POST["id"]);
    $nom = $_POST['categorie'];
    $autre = $_POST['autreinfo'];

    if(!empty($id)){
        //verifie que tousle champs sont rempli
         if(!empty($id) && !empty($nom) && !empty($autre)){
            try {
                // Requête de mise à jour
                $sql = "UPDATE categorie SET designation = ?, autreinfo = ? WHERE id = ?";
                $traitement_sql = $pdo->prepare($sql);
                $traitement_sql->execute([$nom, $autre, $id]);

                // Vérifie si la mise à jour a bien eu lieu
                if ($traitement_sql->rowCount() > 0) {
                    $msg = "Catégorie modifiée avec succès !";
                } else {
                    $msg = " Aucune modification détectée.";
                }

                header("Location:../categorie.php?msg=". urlencode($msg));
                exit();

            } catch (PDOException $e) {
                $msg = " Erreur SQL : " . $e->getMessage();
                header("Location:../categorie.php?msg=" . urlencode($msg));
                exit();
            }
        }
   
    }else{

         // Vérifie que les champs ne sont pas vides
        if (!empty($nom) && !empty($autre)) {
            try {
                $sql = "INSERT INTO categorie(designation,autreinfo) VALUES (?,?)";
                $traitement_sql = $pdo->prepare($sql);
                $traitement_sql->execute([$nom, $autre]);
            if ($traitement_sql->rowCount() > 0) {
                    
                    $msg=" Catégorie ajoutée avec succès !";
                    header("location:../categorie.php?msg=$msg");
                    exit();
                } else {
                    $msg="Erreur lors de l ajout de la catégorie.";
                    header("location:categorie.php?msg=$msg");
                    exit();
                }
            } catch (PDOException $e) {           
                $msg="erreur sql";
                header("location:../categorie.php?msg=$msg");
            }
        } else {
            $msg="Veuillez remplir tous les champs.";
            header("location:../categorie.php?msg=$msg");
        }

    }
}

if(isset($_GET['sup'])){
   try{
         $sup= htmlspecialchars($_GET["sup"]);

        $sql="DELETE FROM categorie WHERE id=?";
        $traitement_sql=$pdo->prepare($sql);
        $traitement_sql->execute([$sup]);

        // verifier si l'execute avec succes
        if($traitement_sql->rowcount()>0){
            $msg="la suppression effectue avec succès";     
        }else{
            $msg="eche de la suppression";        
        }
        header("location:../categorie.php?msg=".urlencode($msg));
    }
    catch (PDOException $e){
        $msg = " Erreur SQL : " . $e->getMessage();
        header("Location:../categorie.php?msg=" . urlencode($msg));
        exit();
    }

}
?>