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
        <h1>Gestion des Évaluations</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Home</a></li>
                <li class="breadcrumb-item">Évaluations</li>
                <li class="breadcrumb-item active">Liste</li>
                <li class="ms-auto"><a href="#modal-add-evaluation" class="btn btn-outline-primary mb-3  fw-bold" data-bs-toggle="modal">Ajouter</a></li>
            </ol>
        </nav>
        </div><!-- End Page Title -->

        <!-- ================== Section Content ================== -->
        <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Liste des Évaluations</h5>

                    <!-- Tableau des évaluations -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Semestre</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluations as $evaluation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($evaluation['id']) ?></td>
                                    <td><?= htmlspecialchars($evaluation['nom']) ?></td>
                                    <td><?= htmlspecialchars($evaluation['type']) ?></td>
                                    <td><?= htmlspecialchars($evaluation['semestre']) ?></td>
                                    <td><?= htmlspecialchars($evaluation['description']) ?></td>
                                    <td><?= htmlspecialchars($evaluation['date']) ?></td>
                                    <td>
                                        <button 
                                            class="btn btn-primary btn-sm edit-btn" 
                                            data-id="<?= htmlspecialchars($evaluation['id']) ?>" 
                                            data-nom="<?= htmlspecialchars($evaluation['nom']) ?>" 
                                            data-type="<?= htmlspecialchars($evaluation['type']) ?>" 
                                            data-semestre="<?= htmlspecialchars($evaluation['semestre']) ?>" 
                                            data-description="<?= htmlspecialchars($evaluation['description']) ?>" 
                                            data-date="<?= htmlspecialchars($evaluation['date']) ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-edit-evaluation">
                                            Edit
                                        </button>

                                        <!-- Lien Supprimer -->
                                        <a href="#modal-delete-evaluation" 
                                        class="btn btn-danger btn-sm delete-btn" 
                                        data-bs-toggle="modal" 
                                        data-id="<?= htmlspecialchars($evaluation['id']) ?>">Del</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            </div>

            <!-- Modal Ajouter Évaluation -->
            <div class="modal fade" id="modal-add-evaluation" tabindex="-1" aria-labelledby="modal-add-evaluation-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-add-evaluation-label">Ajouter Évaluation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulaire d'ajout -->
                            <form action="evaluationMainController" method="POST">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="Examen">Examen</option>
                                        <option value="Test">Test</option>
                                        <option value="Projet">Projet</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="semestre" class="form-label">Semestre</label>
                                    <select class="form-select" id="semestre" name="semestre" required>
                                        <option value="Semestre 1">Semestre 1</option>
                                        <option value="Semestre 2">Semestre 2</option>
                                        <option value="Semestre 3">Semestre 3</option>
                                        <option value="Semestre 4">Semestre 4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="datetime-local" class="form-control" id="date" name="date">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="frmAddEvaluation">Ajouter</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Modifier Évaluation -->
            <div class="modal fade" id="modal-edit-evaluation" tabindex="-1" aria-labelledby="modal-edit-evaluation-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-edit-evaluation-label">Modifier Évaluation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="evaluationMainController" method="POST">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="edit-nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-type" class="form-label">Type</label>
                                    <select class="form-select" id="edit-type" name="type" required>
                                        <option value="Examen">Examen</option>
                                        <option value="Test">Test</option>
                                        <option value="Projet">Projet</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-semestre" class="form-label">Semestre</label>
                                    <select class="form-select" id="edit-semestre" name="semestre" required>
                                        <option value="Semestre 1">Semestre 1</option>
                                        <option value="Semestre 2">Semestre 2</option>
                                        <option value="Semestre 3">Semestre 3</option>
                                        <option value="Semestre 4">Semestre 4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit-description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-date" class="form-label">Date</label>
                                    <input type="datetime-local" class="form-control" id="edit-date" name="date">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="frmEditEvaluation" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmation Suppression -->
            <div class="modal fade" id="modal-delete-evaluation" tabindex="-1" aria-labelledby="modal-delete-evaluation-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-delete-evaluation-label">Supprimer Évaluation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer cette évaluation ?</p>
                            <form action="evaluationMainController" method="POST">
                                <input type="hidden" id="delete-id" name="id">
                                <div class="modal-footer">
                                    <button type="submit" name="frmDeleteEvaluation" class="btn btn-danger">Oui</button>
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
    <?php require_once("../../../sections/admin/footer.php")?>
   
    <!-- ================== Section script ================== -->
    <?php require_once("../../../sections/admin/script.php")?>
	
    <!-- ================== Message Error Or Success ================== -->
    <?php require_once("../../../sections/admin/msgErrorOrSuccess.php"); ?>

</body>
</html>
