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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">liste paiement</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Génerer rapport</a>
                    </div>
                    <form action="" class="formulaire">
                        <table>
                            <tr>
                                <td>Date operation</td>
                                <td><input type="datetime-local" class="form-control" name="datepaie"></td>
                                <td>operation</td>
                                <td><input type="text" name="operateur" class="form-control" placeholder="Caissier"></td>
                            </tr>
                            <tr>
                                <td>N°facture</td>
                                <td>
                                    <select name="facture" class="form-control">
                                        <option>001</option>
                                        <option>002</option>
                                        <option>003</option>
                                    </select>
                                </td>
                                <td></td>
                                <td><input type="submit" name="save" value="ajouter"></td>
                            </tr>
                            <tr>
                                <td>montant paie</td>
                                <td><input type="text" name="montant" class="form-control" placeholder="Ex: 50$ ou 30000 fc"></td>
                            </tr>
                        </table>
                    </form>
                    
                </div>
                <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Liste client</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>nom client</th>
                                            <th>Categorie</th>
                                            <th>Contact</th>
                                            <th>adresse</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01</td>
                                            <td>Munjunju</td>
                                            <td>non abonnée</td>
                                            <td>099587564</td>
                                            <td>Butembo, avenue du centre</td>
                                            <td>
                                                <a href="" class="btn btn-primary">Modifier</a>
                                                <a href="" class="btn btn-danger">supprimer</a>
                                            </td>
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