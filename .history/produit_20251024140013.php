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
                        <h1 class="h3 mb-0 text-gray-800">Nouveau produit</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Génerer rapport</a>
                    </div>
                    <form action="include/produit.php" method="post" class="formulaire">
                        <table>
                            <tr>
                                <td>Nom produit</td>
                                <td><input type="text" class="form-control" name="produit" placeholder="Ex: telephone samsung"></td>
                                <td>Stock alerte</td>
                                <td><input type="text" name="stockalerte" class="form-control" placeholder="Ex 10"></td>
                            </tr>
                            <tr>
                                <td>Categorie</td>                                
                                <td>
                                    <select name="categorie" class="form-control">
                                        <?php                                
                                            include_once('connexion/connexion.php');
                                            $requette="SELECT * FROM categorie";
                                            $execute=$pdo->prepare($requette);
                                            $execute->execute();

                                            while($lire=$execute->fetch()){
                                        ?>
                                        <option value="<?= htmlspecialchars($lire["id"])?>"><?= htmlspecialchars($lire["designation"])?></option>
                                        <?php } ?>
                                    </select>
                                   
                                </td>

                                <td></td>
                                <td><input type="submit" name="valider" value="ajouter"></td>
                                
                            </tr>
                            <tr>
                                <td>PU vente</td>
                                <td><input type="text" name="puv" class="form-control" placeholder="50$ ou 25000fc"></td>
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
                        <h1 class="h3 mb-0 text-gray-800">modifier le produit</h1>                        
                    </div>
                    <form action="include/produit.php" method="post" class="formulaire">
                        <?php 
                        if (isset($_GET["modifier"])) {
                            $mod = htmlspecialchars($_GET['modifier']);
                            include_once('connexion/connexion.php');
                            // Préparation de la requête avec une jointure entre produit et categorie
                            $sql = "SELECT 
                                        produit.id,
                                        produit.designation,
                                        produit.prix_unitaire AS prix_vente,
                                        produit.stock,
                                        produit.stock_alerte,
                                        produit.prix_unitaire_achat,
                                        categorie.id AS id_categorie,
                                        categorie.designation AS categorie
                                    FROM produit
                                    INNER JOIN categorie ON produit.id_categorie = categorie.id
                                    WHERE produit.id = ?";

                            $req = $pdo->prepare($sql);
                            $req->execute([$mod]);
                            $affiche = $req->fetch(PDO::FETCH_ASSOC);
                        }
                        ?>
                        <table>
                            <tr>
                                <input type="hidden" name="id" value="<?= htmlspecialchars($affiche["id"]) ?>">
                                <td>Nom produit</td>
                                <td><input type="text" value="<?= htmlspecialchars($affiche["designation"]) ?>" class="form-control" name="produit" placeholder="Ex: telephone samsung"></td>
                                <td>Stock alerte</td>
                                <td><input type="text" name="stockalerte" value="<?= htmlspecialchars($affiche["stock_alerte"]) ?>" class="form-control" placeholder="Ex 10"></td>
                            </tr>
                            <tr>
                                <td>Categorie</td>                                
                                <td>
                                    <select name="categorie" class="form-control">
                                        <?php
                                            include_once('connexion/connexion.php');

                                            // Récupération de toutes les catégories
                                            $requette = "SELECT * FROM categorie";
                                            $execute = $pdo->prepare($requette);
                                            $execute->execute();

                                            while ($lire = $execute->fetch()) {
                                                // Vérifie si cette catégorie est celle du produit à modifier
                                                $selected = (isset($affiche["id_categorie"]) && $affiche["id_categorie"] == $lire["id"]) ? "selected" : "";
                                        ?>
                                            <option value="<?= htmlspecialchars($lire["id"]) ?>" <?= $selected ?>>
                                                <?= htmlspecialchars($lire["designation"]) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <td></td>
                                    <td><input type="submit" name="valider" value="ajouter"></td>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>PU vente</td>
                                <td><input type="text" value="<?= htmlspecialchars($affiche["prix_unitaire_achat"]) ?>" name="puv" class="form-control" placeholder="50$ ou 25000fc"></td>
                            </tr>
                        </table>
                    </form>
                    
                </div>
                <?php endif ?>


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
                                            <th>nom produit</th>
                                            <th>Categorie</th>
                                            <th>PU Vente</th>
                                            <th>Utilisateur</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        <tr>
                                            <?php
                                             include_once("connexion/connexion.php");

                                             $sql = "SELECT 
                                                        produit.id,
                                                        produit.designation,
                                                        produit.prix_unitaire AS prix_vente,
                                                        produit.stock,
                                                        produit.stock_alerte,
                                                        produit.prix_unitaire_achat,
                                                        categorie.id AS id_categorie,
                                                        categorie.designation AS categorie
                                                    FROM produit
                                                    INNER JOIN categorie ON produit.id_categorie = categorie.id WHERE id_entreprise = ?
                                                    ";
                                              
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                while ($row = $stmt->fetch()) 
                                                {
                                            ?>
                                                    <td><?=  htmlspecialchars($row['id']) ?> </td>
                                                    <td><?=  htmlspecialchars($row['designation']) ?> </td>
                                                    <td><?=  htmlspecialchars($row['categorie'])  ?> </td>                                  
                                                    <td><?=  htmlspecialchars($row['prix_vente'])  ?> </td> 
                                                    <td><?=  htmlspecialchars($row['stock_alerte'])  ?> </td>
                                                    <td>
                                                        <a href="produit.php?modifier=<?= htmlspecialchars($row['id']) ?>" class="btn btn-primary">Modifier</a>
                                                        <a href="include/produit.php?sup=<?=  htmlspecialchars($row['id']) ?>" class="btn btn-danger">Supprimer</a>
                                                    </td> 

                                        </tr>
                                        <?php 
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