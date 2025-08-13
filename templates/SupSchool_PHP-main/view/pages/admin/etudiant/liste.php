<?php
    require_once("programme.php");
?>
<!DOCTYPE html>
<html lang="en">

<!-- ================== Section Head ================== -->
<?php require_once("../../../sections/admin/head.php")?>

<body>
    <!-- ==================  Verifier Section ================== -->
    <?php require_once("../../../sections/admin/verifierSession.php")?>



    <!-- ================== Section Menu Haut ================== -->
    <?php require_once("../../../sections/admin/menuHaut.php")?>
    

    <!-- ================== Section Menu Gauche ================== -->
    <?php require_once("../../../sections/admin/menuGauche.php")?>
    
    <!-- ================== Section Base Content ================== -->
    <main id="main" class="main">

        <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
                <li class=" ms-auto"><a href="#modal-add-etudiant" class="btn btn-success mb-3" data-bs-toggle="modal">Ajouter </a></li>

            </ol>
        </nav>
        </div><!-- End Page Title -->

        
		<!-- ================== Section Content ================== -->
        <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datatables Etudiant</h5>


                    <!-- Tableau des étudiants -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Matricule</th>
                                <th>Adresse</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <tr>
                                    <td><?= htmlspecialchars($etudiant['id']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['email']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['adresse']) ?></td>
                                    <td><?= htmlspecialchars($etudiant['created_at']) ?></td>
                                    <td>
                                        <button 
                                            class="btn btn-primary btn-sm edit-btn" 
                                            data-id="<?= htmlspecialchars($etudiant['id']) ?>" 
                                            data-nom="<?= htmlspecialchars($etudiant['nom']) ?>" 
                                            data-email="<?= htmlspecialchars($etudiant['email']) ?>" 
                                            data-matricule="<?= htmlspecialchars($etudiant['matricule']) ?>" 
                                            data-adresse="<?= htmlspecialchars($etudiant['adresse']) ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-edit-etudiant">
                                            Edit
                                        </button>

                                        <!-- Lien Supprimer -->
                                        <a href="#modal-delete-etudiant" 
                                        class="btn btn-danger btn-sm delete-btn" 
                                        data-bs-toggle="modal" 
                                        data-id="<?= htmlspecialchars($etudiant['id']) ?>">Del</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            </div>

            <!-- Modal Ajouter Étudiant -->
            <div class="modal fade" id="modal-add-etudiant" tabindex="-1" aria-labelledby="modal-add-etudiant-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-add-etudiant-label">Ajouter Étudiant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulaire d'ajout -->
                            <form action="etudiantMainController" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="matricule" class="form-label">Matricule</label>
                                    <input type="text" class="form-control" id="matricule" name="matricule" required>
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="adresse" name="adresse" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="frmAddEtudiant">Ajouter</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Modifier Étudiant -->
            <div class="modal fade" id="modal-edit-etudiant" tabindex="-1" aria-labelledby="modal-edit-etudiant-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-edit-etudiant-label">Modifier Étudiant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="etudiantMainController" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="edit-nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-photo" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="edit-photo" name="photo" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="edit-password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-matricule" class="form-label">Matricule</label>
                                    <input type="text" class="form-control" id="edit-matricule" name="matricule" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="edit-adresse" name="adresse" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="frmEditEtudiant" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal Confirmation Suppression -->
            <div class="modal fade" id="modal-delete-etudiant" tabindex="-1" aria-labelledby="modal-delete-etudiant-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-delete-etudiant-label">Supprimer Étudiant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer cet étudiant ?</p>
                            <form action="etudiantMainController" method="POST">
                                <input type="hidden" id="delete-id" name="id">
                                <div class="modal-footer">
                                    <button type="submit" name="frmDeleteEtudiant" class="btn btn-danger">Oui</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>



        </div>
        </section>

    </main>

		

    <!-- ================== Section Footer ================== -->
    <?php require_once("../../../sections/admin/footer.php")?>
   
    <!-- ================== Section script ================== -->
    <?php require_once("../../../sections/admin/script.php")?>
	
    <!-- ================== Message Error Or Success ================== -->
	<?php require_once("../../../sections/admin/msgErrorOrSuccess.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


</body>

</html>