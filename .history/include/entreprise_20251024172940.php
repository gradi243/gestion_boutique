<?php
// Connexion à la base de données
require_once("../connexion/connexion.php");

if (isset($_POST['valider'])) {
    $id = !empty($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
    $nom = !empty($_POST['nom']) ? htmlspecialchars($_POST['nom']) : null;
    $adresse = !empty($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : null;
    $telephone = !empty($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : null;
    $email = !empty($_POST['email']) ? htmlspecialchars($_POST['email']) : null;

    if (!empty($nom) && !empty($adresse) && !empty($telephone) && !empty($email)) {
        try {
            // Vérifie si l'entreprise existe déjà pour éviter les doublons
            $check = $pdo->prepare("SELECT id FROM entreprise WHERE nom = ? AND id <> ?");
            $check->execute([$nom, $id ?? 0]);
            if ($check->rowCount() > 0) {
                $msg = "Cette entreprise existe déjà.";
                header("Location:../entreprise.php?msg=" . urlencode($msg));
                exit();
            }

            if ($id) {
                // Modification
                $sql = "UPDATE entreprise SET nom = ?, adresse = ?, telephone = ?, email = ? WHERE id_entreprise = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $adresse, $telephone, $email, $id]);
                $msg = ($stmt->rowCount() > 0) ? "Entreprise modifiée avec succès !" : "Aucune modification détectée.";
            } else {
                // Ajout
                $sql = "INSERT INTO entreprise (nom, adresse, telephone, email) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $adresse, $telephone, $email]);
                $msg = ($stmt->rowCount() > 0) ? "Entreprise ajoutée avec succès !" : "Erreur lors de l'ajout.";
            }

            header("Location:../entreprise.php?msg=" . urlencode($msg));
            exit();

        } catch (PDOException $e) {
            $msg = "Erreur SQL : " . $e->getMessage();
            header("Location:../entreprise.php?msg=" . urlencode($msg));
            exit();
        }

    } else {
        $msg = "Veuillez remplir tous les champs.";
        header("Location:../entreprise.php?msg=" . urlencode($msg));
        exit();
    }
}

// Suppression
if (isset($_GET['sup'])) {
    try {
        $sup = htmlspecialchars($_GET['sup']);
        $sql = "DELETE FROM entreprise WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sup]);
        $msg = ($stmt->rowCount() > 0) ? "Entreprise supprimée avec succès." : "Échec de la suppression.";
        header("Location:../entreprise.php?msg=" . urlencode($msg));
        exit();
    } catch (PDOException $e) {
        $msg = "Erreur SQL : " . $e->getMessage();
        header("Location:../entreprise.php?msg=" . urlencode($msg));
        exit();
    }
}
?>
