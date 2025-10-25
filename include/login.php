<?php
session_start();
include_once("../connexion/connexion.php");

if (isset($_POST['valider'])) {
    $name_and_email = trim($_POST['name_and_email']);
    $password = trim($_POST['password']);

    if (!empty($name_and_email) && !empty($password)) {

        // Requête corrigée : éviter les erreurs de priorite
        $requete = $pdo->prepare("
            SELECT * FROM utilisateur 
            WHERE nom = ? OR email = ? 
            AND pwd = MD5(?)
        ");
        $requete->execute([$name_and_email, $name_and_email, $password]);

        if ($requete->rowCount() === 1) {
            $userinfo = $requete->fetch(PDO::FETCH_ASSOC);

            // Création de la session utilisateur
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['prenom'] = $userinfo['prenom'];
            $_SESSION['email'] = $userinfo['email'];
            $_SESSION['role'] = $userinfo['role'];
            $_SESSION['id_entreprise'] = $userinfo['id_entreprise'];

            // Redirection selon le rôle (optionnel)
            switch ($userinfo['role']) {
                case 'Administrateur':
                    header("Location: ../utilisateur.php");
                    break;
                case 'Gestionnaire':
                    header("Location: ../index.php");
                    break;
                default:
                    header("Location: ../produit.php");
                    break;
            }
            exit;
        } else {
            $erreur = "Nom/email ou mot de passe incorrect !";
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
