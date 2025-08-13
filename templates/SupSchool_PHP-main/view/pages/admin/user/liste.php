<?php
require_once("programme.php");
?>
<!DOCTYPE html>
<html lang="en">

<!-- ================== Section Head ================== -->
<?php require_once("../../../sections/admin/head.php") ?>

<body>
    <!-- ==================  Verifier Section ================== -->
    <?php require_once("../../../sections/admin/verifierSession.php") ?>

    <!-- ================== Section Menu Haut ================== -->
    <?php require_once("../../../sections/admin/menuHaut.php") ?>

    <!-- ================== Section Menu Gauche ================== -->
    <?php require_once("../../../sections/admin/menuGauche.php") ?>

    <!-- ================== Section Base Content ================== -->
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Gestion des utilisateurs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin">Home</a></li>
                    <li class="breadcrumb-item">Utilisateurs</li>
                    <li class="breadcrumb-item active">Liste</li>
                    <li class="ms-auto">
                        <a href="#modal-add-user" class="btn btn-outline-primary mb-3" data-bs-toggle="modal">Ajouter</a>
                    </li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <!-- ================== Section Content ================== -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des utilisateurs</h5>

                            <!-- Tableau des utilisateurs -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>État</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($utilisateurs as $utilisateur): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($utilisateur['id']) ?></td>
                                            <td><?= htmlspecialchars($utilisateur['nom']) ?></td>
                                            <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                                            <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                                            <td><?= $utilisateur['etat'] ? 'Actif' : 'Inactif' ?></td>
                                            <td>
                                                <button 
                                                    class="btn btn-primary btn-sm edit-btn" 
                                                    data-id="<?= htmlspecialchars($utilisateur['id']) ?>" 
                                                    data-nom="<?= htmlspecialchars($utilisateur['nom']) ?>" 
                                                    data-email="<?= htmlspecialchars($utilisateur['email']) ?>" 
                                                    data-role="<?= htmlspecialchars($utilisateur['role']) ?>" 
                                                    data-password="<?= htmlspecialchars($utilisateur['password']) ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modal-edit-user">
                                                    Edit
                                                </button>

                                                <a href="#modal-delete-user" 
                                                   class="btn btn-danger btn-sm delete-btn" 
                                                   data-id="<?= htmlspecialchars($utilisateur['id']) ?>" 
                                                   data-bs-toggle="modal">
                                                    Del
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Modal Ajouter Utilisateur -->
                <div class="modal fade" id="modal-add-user" tabindex="-1" aria-labelledby="modal-add-user-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-add-user-label">Ajouter Utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulaire d'ajout -->
                                <form action="userMainController" method="POST" enctype="multipart/form-data">
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
                                        <label for="role" class="form-label">Rôle</label>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="Utilisateur">Utilisateur</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="frmAddUser">Ajouter</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Modifier Utilisateur -->
                <div class="modal fade" id="modal-edit-user" tabindex="-1" aria-labelledby="modal-edit-user-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-edit-user-label">Modifier Utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="userMainController" method="POST" enctype="multipart/form-data">
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
                                        <label for="edit-role" class="form-label">Rôle</label>
                                        <select class="form-select" id="edit-role" name="role" required>
                                            <option value="Utilisateur">Utilisateur</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="frmEditUser">Enregistrer</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal Confirmation Suppression -->
                <div class="modal fade" id="modal-delete-user" tabindex="-1" aria-labelledby="modal-delete-user-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-delete-user-label">Supprimer Utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
                                <form action="userMainController" method="POST">
                                    <input type="hidden" id="delete-id" name="id">
                                    <div class="modal-footer">
                                        <button type="submit" name="frmDeleteUser" class="btn btn-danger">Oui</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- ================== Section Footer ================== -->
    <?php require_once("../../../sections/admin/footer.php") ?>

    <!-- ================== Section Script ================== -->
    <?php require_once("../../../sections/admin/script.php") ?>

</body>

</html>
