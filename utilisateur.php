<?php
    session_start();
    include_once("connexion/connexion.php");

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php
        include("lien/lien.php");
    ?>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
            include("menugauche/menugauche.php");
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                    include("topbar/topbar.php");
                ?>
                <!-- End of Topbar -->

                <!-- formulaire d'ajout -->
                <?php if(empty($_GET["modifier"])) : ?>
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Nouveau Utilisateur</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Génerer rapport</a>
                    </div>
                    <form action="include/utilisateur.php" method="post" class="formulaire">
                        <table>
                            <tr>
                                <td>Nom </td>
                                <td><input type="text" class="form-control" name="nom" placeholder="Ex: Kasereka"></td>
                                <td>prenom</td>
                                <td><input type="text" name="prenom" class="form-control" placeholder="Ex Isaac"></td>

                            </tr>
                            <tr>
                                <td>Entreprise</td>                                
                                <td>
                                    <select name="id_entreprise" class="form-control">
                                        <?php                                
                                            include_once('connexion/connexion.php');
                                            $requette="SELECT * FROM entreprise";
                                            $execute=$pdo->prepare($requette);
                                            $execute->execute();

                                            while($lire=$execute->fetch()){
                                        ?>
                                        <option value="<?= htmlspecialchars($lire["id_entreprise"])?>"><?= htmlspecialchars($lire["nom"])?></option>
                                        <?php } ?>
                                    </select>                                   
                                </td>

                                <td>role</td>
                                <td>
                                    <select name="role" class="form-control" required>
                                        <option value="">-- Sélectionner un rôle --</option>                                        
                                        <option value="Gestionnaire">Gestionnaire</option>                                        
                                        <option value="Comptable">Comptable</option>
                                        <option value="Secrétaire">Secrétaire</option>
                                        <option value="Administrateur">Administrateur</option>
                                    </select>
                                  
                                </td>
                                
                                
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type="mail" name="mail" class="form-control" placeholder="shopbunia@gmail.com"></td>
                                <td></td>
                                <td><input type="submit" name="valider" value="ajouter"></td>
                            </tr>
                            <tr>
                                <td>Mot de passe</td>
                                <td><input type="password" name="password" class="form-control" placeholder="@isaac+2458^:"></td>
                            </tr>
                        </table>
                    </form>
                    
                </div>
                <?php endif ?>
                 <!-- formulaire de la modification -->
                <?php if(!empty($_GET["modifier"])) : ?>
                        <div class="container-fluid">

                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h1 class="h3 mb-0 text-gray-800">Modifier l'utilisateur</h1>
                            </div>

                            <?php 
                                include_once('connexion/connexion.php');

                                // Récupération de l'utilisateur à modifier
                                $mod = htmlspecialchars($_GET["modifier"]);
                                $req = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
                                $req->execute([$mod]);
                                $affiche = $req->fetch(PDO::FETCH_ASSOC);
                            ?>

                            <form action="include/utilisateur.php" method="post" class="formulaire">
                                <table>
                                    <tr>
                                        <!-- Champ caché pour l'ID -->
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($affiche['id']) ?>">

                                        <td>Nom</td>
                                        <td>
                                            <input type="text" class="form-control" name="nom" 
                                                value="<?= htmlspecialchars($affiche['nom']) ?>" 
                                                placeholder="Ex: Kasereka">
                                        </td>

                                        <td>Prénom</td>
                                        <td>
                                            <input type="text" name="prenom" class="form-control" 
                                                value="<?= htmlspecialchars($affiche['prenom']) ?>" 
                                                placeholder="Ex: Isaac">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Entreprise</td>
                                        <td>
                                            <select name="id_entreprise" class="form-control" required>
                                                <option value="">-- Sélectionner une entreprise --</option>
                                                <?php                                
                                                    $requette = "SELECT * FROM entreprise";
                                                    $execute = $pdo->prepare($requette);
                                                    $execute->execute();
                                                    while($lire = $execute->fetch(PDO::FETCH_ASSOC)){
                                                        $selected = ($affiche['id_entreprise'] == $lire['id_entreprise']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= htmlspecialchars($lire["id_entreprise"]) ?>" <?= $selected ?>>
                                                        <?= htmlspecialchars($lire["nom"]) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </td>

                                        <td>Rôle</td>
                                        <td>
                                            <select name="role" class="form-control" required>
                                                <option value="">-- Sélectionner un rôle --</option>
                                                <option value="Gestionnaire" <?= ($affiche['role'] == 'Gestionnaire') ? 'selected' : '' ?>>Gestionnaire</option>
                                                <option value="Comptable" <?= ($affiche['role'] == 'Comptable') ? 'selected' : '' ?>>Comptable</option>
                                                <option value="Secrétaire" <?= ($affiche['role'] == 'Secrétaire') ? 'selected' : '' ?>>Secrétaire</option>
                                                <option value="Administrateur" <?= ($affiche['role'] == 'Administrateur') ? 'selected' : '' ?>>Administrateur</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Email</td>
                                        <td>
                                            <input type="email" name="mail" class="form-control"
                                                value="<?= htmlspecialchars($affiche['email']) ?>"
                                                placeholder="shopbunia@gmail.com">
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="submit" name="valider" value="Modifier" class="btn btn-primary">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Mot de passe</td>
                                        <td>
                                            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    <?php endif; ?>
                <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Liste produit</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Date d’ajout</th>
                                            <th>Entreprise</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    include_once("connexion/connexion.php");

                                    // Requête pour récupérer tous les utilisateurs avec leur entreprise
                                    $sql = "
                                        SELECT 
                                            u.id,
                                            u.nom,
                                            u.prenom,
                                            u.email,
                                            u.role,
                                            u.dateAdd,
                                            e.nom AS entreprise
                                        FROM utilisateur u
                                        LEFT JOIN entreprise e ON u.id_entreprise = e.id_entreprise
                                        ORDER BY u.nom ASC
                                    ";

                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']) ?></td>
                                                <td><?= htmlspecialchars($row['nom']) ?></td>
                                                <td><?= htmlspecialchars($row['prenom']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['role']) ?></td>
                                                <td><?= htmlspecialchars($row['dateAdd']) ?></td>
                                                <td><?= htmlspecialchars($row['entreprise'] ?? '—') ?></td>
                                                <td>
                                                    <a href="utilisateur.php?modifier=<?= urlencode($row['id']) ?>" class="btn btn-primary">Modifier</a>
                                                    <a href="include/utilisateur.php?sup=<?= urlencode($row['id']) ?>" class="btn btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="8">Aucun utilisateur trouvé.</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Application gestion commercial conçu par &copy; BMSI 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    include("lienjs/lienjs.php");
    ?>
</body>

</html>