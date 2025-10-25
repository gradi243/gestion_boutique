<?php
//demarage de la session pour affiche les information de l'utilisateur a fait de voir l'entrprise
session_start();
// Connexion à la base de données

require_once ("../connexion/connexion.php");
// Inclusion du fichier de gestion des produits
require_once ("../include/produit.php");

if (isset($_POST['valider'])) {
    $id = htmlspecialchars($_POST["id"]);

    $nom = htmlspecialchars($_POST["produit"]);
    $categorie = htmlspecialchars($_POST['categorie']);
    $prix_v = htmlspecialchars($_POST["puv"]);
    $alerte = htmlspecialchars($_POST["stockalerte"]);
    $user = $_SESSION['id'];
    // lamodification
    if (!empty($id)) {
        if (!empty($nom) && !empty($categorie) && !empty($prix_v)) {
            try {
                // Vérifie si un autre produit du même nom existe déjà (autre que celui en cours)
                $requette = "SELECT * FROM produit WHERE designation = ? AND id_categorie = ? AND id != ?";
                $execute_requette = $pdo->prepare($requette);
                $execute_requette->execute([$nom, $categorie, $id]);

                if ($execute_requette->rowCount() > 0) {
                    $msg = "Ce produit existe déjà dans cette catégorie.";
                } else {
                    $sql = "UPDATE produit 
                            SET id_categorie = ?, designation = ?, prix_unitaire = ?,
                                stock_alerte = ?, id_utilisateur = ?
                            WHERE id = ?";
                    $traitement = $pdo->prepare($sql);
                    $traitement->execute([$categorie, $nom, $prix_v, $alerte, $user, $id]);

                    if ($traitement->rowCount() > 0) {
                        $msg = "Produit modifié avec succès !";
                    } else {
                        $msg = "Aucune modification détectée.";
                    }
                }

                header("Location:../produit.php?msg=" . urlencode($msg));
                exit();

            } catch (PDOException $e) {
                $msg = "Erreur SQL : " . $e->getMessage();
                header("Location:../produit.php?msg=" . urlencode($msg));
                exit();
            }
        }
    }
    // l'insertion de produit dans la base de donne
    else {
        if (!empty($nom) && !empty($categorie) && !empty($prix_v)) {
            try {
                // Vérifie si le produit existe déjà
                $requette = "SELECT * FROM produit WHERE designation = ? AND id_categorie = ?";
                $execution_requette = $pdo->prepare($requette);
                $execution_requette->execute([$nom, $categorie]);

                if ($execution_requette->rowCount() > 0) {
                    $msg = "Ce produit existe déjà dans cette catégorie.";
                } else {
                    //l'insertion
                    $sql = "INSERT INTO produit(id_categorie, designation, prix_unitaire,stock_alerte, id_utilisateur)
                            VALUES (?, ?, ?, ?, ?)";
                    $traitement = $pdo->prepare($sql);
                    $traitement->execute([$categorie, $nom, $prix_v,$alerte, $user]);

                    if ($traitement->rowCount() > 0) {
                        $msg = "Produit ajouté avec succès !";
                    } else {
                        $msg = "Erreur lors de l'ajout du produit.";
                    }
                }
                header("Location:../produit.php?msg=" . urlencode($msg));
                exit();

            } catch (PDOException $e) {
                $msg = "Erreur SQL : " . $e->getMessage();
                header("Location:../produit.php?msg=" . urlencode($msg));
                exit();
            }
        } else {
            $msg = "Veuillez remplir tous les champs obligatoires.";
            header("Location:../produit.php?msg=" . urlencode($msg));
            exit();
        }
    }
}

// code de la supression 
if (isset($_GET['sup'])) {
    try {
        $sup = htmlspecialchars($_GET["sup"]);
        $sql = "DELETE FROM produit WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sup]);

        if ($stmt->rowCount() > 0) {
            $msg = "Suppression effectuée avec succès !";
        } else {
            $msg = "Échec de la suppression.";
        }

        header("Location:../produit.php?msg=" . urlencode($msg));
        exit();

    } catch (PDOException $e) {
        $msg = "Erreur SQL : " . $e->getMessage();
        header("Location:../produit.php?msg=" . urlencode($msg));
        exit();
    }
}
?>
