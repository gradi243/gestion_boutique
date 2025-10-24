<?php
session_start();
include_once("../connexion/connexion.php");

if (isset($_POST['valider'])) {
    $name_and_email = htmlspecialchars($_POST['name_and_email']);
    $password = htmlspecialchars($_POST['password']);

    if (!empty($name_and_email) && !empty($password)) {

        //recherche utilisateur par nom ou email
        $requete = $pdo->prepare("SELECT * FROM utilisateur WHERE (nom = ? OR email = ?) AND pwd = MD5(?)");
        $requete->execute([$name_and_email, $name_and_email, $password]);
        $userexist = $requete->rowCount();

        if ($userexist == 1) {
            $userinfo = $requete->fetch();

            // creation de la session utilisateur
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['email'] = $userinfo['email'];
            $_SESSION['role'] = $userinfo['role'];
            $_SESSION['id_entreprise'] = $userinfo['id_entreprise'];

            // redirection vers la page d'accueil
            header("Location: ../produit.php");
            exit;
        } else {
            $erreur = "nom/email ou mot de passe incorrect !";
            header('Location: ../login.php?msg=' . urlencode($erreur));
            exit;
        }
    } else {
        $erreur = "Tous les champs doivent être remplis !";
        header('Location: ../login.php?msg=' . urlencode($erreur));
        exit;
    }
}
?>
