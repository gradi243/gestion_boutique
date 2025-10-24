<?php
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
                            <h1 class="h3 mb-0 text-gray-800">Nouvelle categorie</h1>
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
                        <form action="include/categorie.php" method="post" class="formulaire">
                            <table>
                                <tr>
                                    <td>Nom categoerie</td>
                                    <td><input type="text" class="form-control" name="categorie" placeholder="Ex: Electronique"></td>
                                    <td><input type="submit" name="valider" value="Ajouter"></td>
                                </tr>
                                <tr>
                                    <td>Autre Info</td>
                                    <td><input type="text" class="form-control" name="autreinfo" placeholder="Ex:electronique nouvelle géneration"></td>
                                </tr>
                            </table>
                        </form>
                    <?php endif ?>

                    <!-- une condition pour la modification si la variable n'est pas vide -->
                      <?php if(!empty($_GET["modifier"])) : ?>
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Modification de categorie</h1>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Génerer rapport</a>
                        </div>
                        <?php 
                           if(isset($_GET["modifier"])){
                            $mod= htmlspecialchars($_GET['modifier']);
                            $req=$pdo->query("SELECT * FROM categorie where id=$mod ");
                            $affiche=$req->fetch();
                            
                            }
                        ?>
                        <form action="include/categorie.php" method="post" class="formulaire">
                            <table>
                                <tr>
                                    <input type="hidden" value="<?= htmlspecialchars($affiche['id']) ?>" name="id">
                                    <td>Nom categoerie</td>
                                    <td><input type="text" class="form-control" name="categorie" placeholder="Ex: Electronique" value="<?= htmlspecialchars($affiche['designation']) ?>"></td>
                                    <td><input type="submit" name="valider" value="Modifier"></td>
                                </tr>
                                <tr>
                                    <td>Autre Info</td>
                                    <td><input type="text" value="<?= htmlspecialchars($affiche['autreinfo']) ?>" class="form-control" name="autreinfo" placeholder="Ex:electronique nouvelle géneration" <?= htmlspecialchars($affiche['autreinfo']) ?>></td>
                                </tr>
                            </table>
                        </form>
                    <?php endif ?>                    
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
                                            <th>nom categorie</th>
                                            <th>Autre information</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                                                       
                                    <tbody>
                                     <tr>
<?php
session_start();
include_once("connexion/connexion.php");

// Vérifie que l'utilisateur est connecté et que son id_entreprise est en session
if (!isset($_SESSION['id']) || !isset($_SESSION['id_entreprise'])) {
    echo '<tr><td colspan="4">Veuillez vous connecter.</td></tr>';
    exit;
}

$id_entreprise = $_SESSION['id_entreprise'];

$sql = "
    SELECT c.id, c.designation, c.autreinfo
    FROM categorie c
    INNER JOIN utilisateur u ON c.id_utilisateur = u.id
    WHERE u.id_entreprise = ?
    ORDER BY c.designation ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_entreprise]);

$has = false;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $has = true;
    ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['designation']) ?></td>
        <td><?= htmlspecialchars($row['autreinfo']) ?></td>
        <td>
            <a href="categorie.php?modifier=<?= urlencode($row['id']) ?>" class="btn btn-primary">Modifier</a>
            <a href="include/categorie.php?sup=<?= urlencode($row['id']) ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
        </td>
    </tr>
    <?php
}

if (!$has) {
    echo '<tr><td colspan="4">Aucune catégorie pour votre entreprise.</td></tr>';
}
?>
</tr>

                                       
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