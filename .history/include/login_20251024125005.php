<?php
session_start();
// appele de la methode de connexion
include_once("../connexion/connexion.php");
//include_once("../login.php");

if(isset($_POST['valide'])){
    $name_and_email=htmlspecialchars($_POST['name_and_email']);
    $password=htmlspecialchars($_POST['password']);

    if(!empty($name_and_email) AND !empty($password)){
        $requete=$pdo->prepare("SELECT * FROM utilisateur WHERE (name=? OR email=?) AND password=?");
        $requete->execute(array($name_and_email,$name_and_email,$password));
        $userexist=$requete->rowCount();
        if($userexist==1){
            $userinfo=$requete->fetch();
            $_SESSION['id']=$userinfo['id'];
            $_SESSION['name']=$userinfo['name'];
            $_SESSION['email']=$userinfo['email'];
            header("Location:../produit.php");
        }else{
            $erreur="Mauvais name/email ou mot de passe!";
        }
    }else{
        $erreur="Tous les champs doivent être complétés!";
    }
}



?>