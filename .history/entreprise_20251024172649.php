<?php

    session_start();
    include_once("connexion/connexion.php");

    // Vérifie si l'utilisateur est connecté
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }
    // Connexion à la base de données  
    include_once('connexion/connexion.php');
   
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

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <?php if(empty($_GET["modifier"])) : ?>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Nouvelle entreprise</h1>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Génerer rapport</a>
                        </div>
                    <!-- gestion de message  -->
                        <div class="alerte">
                            <?php
                                if(isset($_GET['msg']) && !empty($_GET["msg"]) ) { ?>

                                    <div><p> <?= htmlspecialchars($_GET["msg"]) ?> </p></div>
                                <?php } ?> 
                        </div>
                        <form action="include/entreprise.php" method="post" class="formulaire">
                            <table class="table table-borderless">
                                <tr>
                                    <td><label for="nom">Nom de l'entreprise</label></td>
                                    <td>
                                        <input type="text" id="nom" name="nom" class="form-control" placeholder="Ex: Penuel Fusion" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="adresse">Adresse</label></td>
                                    <td>
                                        <input type="text" id="adresse" name="adresse" class="form-control" placeholder="Ex: Avenue du Marché" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="telephone">Téléphone</label></td>
                                    <td>
                                        <input type="text" id="telephone" name="telephone" class="form-control" placeholder="Ex: +243970123456" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="email">Email</label></td>
                                    <td>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Ex: contact@entreprise.com" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="submit" name="valider" value="Ajouter" class="btn btn-success">
                                    </td>
                                </tr>
                            </table>
                        </form>

                    <?php endif ?>

                    <!-- une condition pour la modification si la variable n'est pas vide -->
                    <?php if(!empty($_GET["modifier"])) : ?>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Modification de l'entreprise</h1>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-download fa-sm text-white-50"></i> Générer rapport
                            </a>
                        </div>

                        <?php 
                            $mod = htmlspecialchars($_GET['modifier']);
                            $req = $pdo->prepare("SELECT * FROM entreprise WHERE id = ?");
                            $req->execute([$mod]);
                            $affiche = $req->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <form action="include/entreprise.php" method="post" class="formulaire">
                            <table class="table table-borderless">
                                <!-- Champ caché pour l'ID -->
                                <input type="hidden" name="id" value="<?= htmlspecialchars($affiche['id']) ?>">

                                <tr>
                                    <td>Nom de l'entreprise</td>
                                    <td>
                                        <input type="text" name="nom" class="form-control" placeholder="Ex: Penuel Fusion" 
                                            value="<?= htmlspecialchars($affiche['nom']) ?>" required>
                                    </td>
                                    <td>
                                        <input type="submit" name="valider" value="Modifier" class="btn btn-primary">
                                    </td>
                                </tr>

                                <tr>
                                    <td>Adresse</td>
                                    <td>
                                        <input type="text" name="adresse" class="form-control" placeholder="Ex: Avenue du Marché" 
                                            value="<?= htmlspecialchars($affiche['adresse']) ?>" required>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Téléphone</td>
                                    <td>
                                        <input type="text" name="telephone" class="form-control" placeholder="Ex: +243970123456" 
                                            value="<?= htmlspecialchars($affiche['telephone']) ?>" required>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Email</td>
                                    <td>
                                        <input type="email" name="email" class="form-control" placeholder="Ex: contact@entreprise.com" 
                                            value="<?= htmlspecialchars($affiche['email']) ?>" required>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    <?php endif; ?>
                    
                </div>
                <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Liste categorie</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom entreprise </th>
                                            <th>Adresse</th>
                                            <th>telephone</th>
                                            <th>email</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                                                       
                                    <tbody>
                                     <tbody>
                                            <?php
                                            include_once("connexion/connexion.php");

                                            $sql = "SELECT * FROM entreprise ORDER BY nom ASC";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute();

                                            $has = false;
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $has = true;
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_entreprise']) ?></td>
                                                    <td><?= htmlspecialchars($row['nom']) ?></td>
                                                    <td><?= htmlspecialchars($row['adresse']) ?></td>
                                                    <td><?= htmlspecialchars($row['telephone']) ?></td>
                                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                                    <td>
                                                        <a href="entreprise.php?modifier=<?= urlencode($row['id_entreprise']) ?>" class="btn btn-primary">Modifier</a>
                                                        <a href="include/entreprise.php?sup=<?= urlencode($row['id_entreprise']) ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette entreprise ?')">Supprimer</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }

                                            if (!$has) {
                                                echo '<tr><td colspan="6">Aucune entreprise disponible.</td></tr>';
                                            }
                                            ?>
                                            </tbody>                                       
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