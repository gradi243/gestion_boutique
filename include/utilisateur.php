<?php
require_once("../connexion/connexion.php");

if (isset($_POST['valider'])) {
    $id = htmlspecialchars($_POST["id"]);
    $nom = htmlspecialchars($_POST["nom"]);
    $prenom = htmlspecialchars($_POST["prenom"]);
    $email = htmlspecialchars($_POST["mail"]);
    $role = htmlspecialchars($_POST["role"]);
    $pwd = htmlspecialchars($_POST["password"]);
    $id_entreprise = htmlspecialchars($_POST["id_entreprise"]);

    // Vérification des champs obligatoires
    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($role) && !empty($id_entreprise)) {

        try {
            if (!empty($id)) {
                // MODIFICATION
                if (!empty($pwd)) {
                    // un nouveau mot de passe est saisi alors on va l'hache
                    $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);
                    $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, role = ?, pwd = ?, id_entreprise = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nom, $prenom, $email, $role, $pwd_hash, $id_entreprise, $id]);
                } else {
                    // Sinon, on ne modifie pas le mot de passe
                    $sql = "UPDATE utilisateur 
                            SET nom = ?, prenom = ?, email = ?, role = ?, id_entreprise = ?
                            WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nom, $prenom, $email, $role, $id_entreprise, $id]);
                }

                if ($stmt->rowCount() > 0) {
                    $msg = "Utilisateur modifié avec succès !";
                } else {
                    $msg = "Aucune modification détectée.";
                }

            } else {
                // AJOUT
                if (empty($pwd)) {
                    $msg = "Le mot de passe est obligatoire pour un nouvel utilisateur.";
                    header("Location:../utilisateur.php?msg=" . urlencode($msg));
                    exit();
                }

                // Vérifie si un utilisateur existe déjà avec le même email
                $check = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
                $check->execute([$email]);

                if ($check->rowCount() > 0) {
                    $msg = "Cet email est déjà utilisé par un autre utilisateur.";
                    header("Location:../utilisateur.php?msg=" . urlencode($msg));
                    exit();
                }

                // Hachage du mot de passe
                $pwd_hash = password_hash($pwd, PASSWORD_DEFAULT);

                $sql = "INSERT INTO utilisateur (nom, prenom, email, role, pwd, dateAdd, id_entreprise)
                        VALUES (?, ?, ?, ?, ?, NOW(), ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $prenom, $email, $role, $pwd_hash, $id_entreprise]);

                if ($stmt->rowCount() > 0) {
                    $msg = "Utilisateur ajouté avec succès !";
                } else {
                    $msg = "Erreur lors de l ajout de l utilisateur.";
                }
            }

        } catch (PDOException $e) {
            $msg = "Erreur SQL : " . $e->getMessage();
        }

    } else {
        $msg = "Veuillez remplir tous les champs obligatoires.";
    }

    header("Location:../utilisateur.php?msg=" . urlencode($msg));
    exit();
}

// SUPPRESSION
if (isset($_GET['sup'])) {
    try {
        $sup = htmlspecialchars($_GET["sup"]);
        $sql = "DELETE FROM utilisateur WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sup]);

        if ($stmt->rowCount() > 0) {
            $msg = "Suppression effectuée avec succès.";
        } else {
            $msg = "Échec de la suppression.";
        }
    } catch (PDOException $e) {
        $msg = "Erreur SQL : " . $e->getMessage();
    }

    header("Location:../utilisateur.php?msg=" . urlencode($msg));
    exit();
}
?>
