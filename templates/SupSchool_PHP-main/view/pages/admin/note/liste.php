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
        <h1>Gestion des Notes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin">Home</a></li>
                <li class="breadcrumb-item">Notes</li>
                <li class="breadcrumb-item active">Liste</li>
                <li class="ms-auto"><a href="#modal-add-note" class="btn btn-outline-primary mb-3 fw-bold" data-bs-toggle="modal">Ajouter</a></li>
            </ol>
        </nav>
        </div><!-- End Page Title -->

        <!-- ================== Section Content ================== -->
        <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Liste des Notes</h5>

                    <!-- Tableau des notes -->
                    <table class="table table-striped">
                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Étudiant</th>
                                        <th>Évaluation</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notes as $note): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($note['id']) ?></td>
                                            <td><?= htmlspecialchars($note['etudiant']) ?></td>
                                            <td><?= htmlspecialchars($note['evaluation']) ?></td>
                                            <td><?= htmlspecialchars($note['note']) ?></td>
                                            <td>
                                                <button 
                                                    class="btn btn-primary btn-sm edit-btn" 
                                                    data-id="<?= $note['id'] ?>" 
                                                    data-etudiant="<?= $note['etudiant'] ?>" 
                                                    data-evaluation="<?= $note['evaluation'] ?>" 
                                                    data-note="<?= $note['note'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modal-edit-note">
                                                    Modifier
                                                </button>
                                                <a href="#modal-delete-note" 
                                                   class="btn btn-danger btn-sm delete-btn" 
                                                   data-bs-toggle="modal" 
                                                   data-id="<?= $note['id'] ?>">Supprimer</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                    </table>
                </div>
            </div>

            </div>

            <!-- Modal Ajouter Note -->
                <div class="modal fade" id="modal-add-note" tabindex="-1" aria-labelledby="modal-add-note-label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-add-note-label">Ajouter Note</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="noteMainController" method="POST">
                                    <div class="mb-3">
                                        <label for="etudiant" class="form-label">Étudiant</label>
                                        <select name="id_etudiant" id="etudiant" class="form-select" required>
                                            <option value="">Sélectionnez un étudiant</option>
                                            <?php foreach ($etudiants as $etudiant): ?>
                                                <option value="<?= $etudiant['id'] ?>"><?= htmlspecialchars($etudiant['nom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="evaluation" class="form-label">Évaluation</label>
                                        <select name="id_evaluation" id="evaluation" class="form-select" required>
                                            <option value="">Sélectionnez une évaluation</option>
                                            <?php foreach ($evaluations as $evaluation): ?>
                                                <option value="<?= $evaluation['id'] ?>"><?= htmlspecialchars($evaluation['nom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Note</label>
                                        <input type="number" class="form-control" id="note" min="0" max="20" name="note" step="0.01" required>
                                    </div>
                                   
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" name="frmAddNote">Ajouter</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            <!-- Modal Modifier Note -->
            <div class="modal fade" id="modal-edit-note" tabindex="-1" aria-labelledby="modal-edit-note-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-edit-note-label">Modifier Note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="noteMainController" method="POST">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-etudiant" class="form-label">Étudiant</label>
                                    <select name="id_etudiant" id="edit-etudiant" class="form-select" required>
                                        <option value="">Sélectionnez un étudiant</option>
                                        <?php foreach ($etudiants as $etudiant): ?>
                                            <option value="<?= $etudiant['id'] ?>"><?= htmlspecialchars($etudiant['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-evaluation" class="form-label">Évaluation</label>
                                    <select name="id_evaluation" id="edit-evaluation" class="form-select" required>
                                        <option value="">Sélectionnez une évaluation</option>
                                        <?php foreach ($evaluations as $evaluation): ?>
                                            <option value="<?= $evaluation['id'] ?>"><?= htmlspecialchars($evaluation['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-note" class="form-label">Note</label>
                                    <input type="number" min="0" max="20" class="form-control" id="edit-note" name="note" step="0.01" required>
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="submit" name="frmEditNote" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmation Suppression -->
            <div class="modal fade" id="modal-delete-note" tabindex="-1" aria-labelledby="modal-delete-note-label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-delete-note-label">Supprimer Note</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer cette note ?</p>
                            <form action="noteMainController" method="POST">
                                <input type="hidden" id="delete-id" name="id">
                                <div class="modal-footer">
                                    <button type="submit" name="frmDeleteNote" class="btn btn-danger">Oui</button>
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
   
    <!-- ================== Section script ================== -->
    <?php require_once("../../../sections/admin/script.php") ?>
	
    <!-- ================== Message Error Or Success ================== -->
    <?php require_once("../../../sections/admin/msgErrorOrSuccess.php"); ?>

</body>
</html>
