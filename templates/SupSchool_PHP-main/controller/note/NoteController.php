<?php
    session_start();
    require_once("../../model/NoteRepository.php");

    class NoteController
    {
        private $noteRepository;

        public function __construct()
        {
            $this->noteRepository = new NoteRepository();
        }

        private function setErrorAndRedirect($message, $title, $redirectUrl = 'admin')
        {
            $_SESSION["error"] = $message;
            header(
                "Location:$redirectUrl?error=1&message=" 
                . urlencode($message) . 
                "&title=" . urlencode($title) 
            );
            exit;
        }

        private function setSuccessAndRedirect($message, $title, $redirectUrl = 'admin')
        {
            $_SESSION["success"] = $message;
            header(
                "Location:$redirectUrl?success=1&message=" 
                . urlencode($message) . 
                "&title=" . urlencode($title) 
            );
            exit;
        }

        public function addNote()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $note = trim($_POST['note'] ?? '');
                $idEtudiant = trim($_POST['id_etudiant'] ?? '');
                $idEvaluation = trim($_POST['id_evaluation'] ?? '');
                $createdBy = $_SESSION['id'] ?? null;

                if (empty($note) || empty($idEtudiant) || empty($idEvaluation)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur d'ajout"
                    );
                }

                try {
                    $lastInsertId = $this->noteRepository->add(
                        $note,
                        $idEtudiant,
                        $idEvaluation,
                        $createdBy
                    );

                    if ($lastInsertId) {
                        $this->setSuccessAndRedirect(
                            "Note ajoutée avec succès.",
                            "Ajout réussi",
                            "listeNote"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Une erreur est survenue lors de l'ajout de la note.",
                            "Erreur d'ajout",
                            "listeNote"
                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de l'ajout.",
                        "Erreur d'ajout",
                        "listeNote"
                    );
                }
            }
        }

        public function editNote()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                $note = trim($_POST['note'] ?? '');
                $idEtudiant = trim($_POST['id_etudiant'] ?? '');
                $idEvaluation = trim($_POST['id_evaluation'] ?? '');
                $updatedBy = $_SESSION['id'] ?? null;

                if (empty($id) || empty($note) || empty($idEtudiant) || empty($idEvaluation)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur de modification",
                        "listeNote"
                    );
                }

                try {
                    $success = $this->noteRepository->edit(
                        $id,
                        $note,
                        $idEtudiant,
                        $idEvaluation,
                        $updatedBy
                    );

                    if ($success) {
                        $this->setSuccessAndRedirect(
                            "Note modifiée avec succès.",
                            "Modification réussie",
                            "listeNote"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Aucune modification n'a été effectuée.",
                            "Erreur de modification",
                            "listeNote"
                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de la modification.",
                        "Erreur de modification",
                        "listeNote"
                    );
                }
            }
        }

        public function desactivateNote()
        {
            $id = $_POST['id'] ?? null;
            $deletedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de la note manquant.",
                    "Erreur de désactivation"
                );
            }

            try {
                $success = $this->noteRepository->deactivate($id, $deletedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Note désactivée avec succès.",
                        "Désactivation réussie"
                    );
                } else {
                    $this->setErrorAndRedirect(
                        "Aucune désactivation n'a été effectuée.",
                        "Erreur de désactivation"
                    );
                }
            } catch (Exception $e) {
                error_log("Erreur: " . $e->getMessage());
                $this->setErrorAndRedirect(
                    "Une erreur est survenue lors de la désactivation.",
                    "Erreur de désactivation"
                );
            }
        }

        public function activateNote()
        {
            $id = $_POST['id'] ?? null;
            $updatedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de la note manquant.",
                    "Erreur d'activation"
                );
            }

            try {
                $success = $this->noteRepository->activate($id, $updatedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Note activée avec succès.",
                        "Activation réussie"
                    );
                } else {
                    $this->setErrorAndRedirect(
                        "Aucune activation n'a été effectuée.",
                        "Erreur d'activation"
                    );
                }
            } catch (Exception $e) {
                error_log("Erreur: " . $e->getMessage());
                $this->setErrorAndRedirect(
                    "Une erreur est survenue lors de l'activation.",
                    "Erreur d'activation"
                );
            }
        }

        public function deleteNote()
        {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de la note manquant.",
                    "Erreur de suppression"
                );
            }

            try {
                $success = $this->noteRepository->delete($id);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Note supprimée définitivement avec succès.",
                        "Suppression réussie",
                        "listeNote"
                    );
                } else {
                    $this->setErrorAndRedirect(
                        "Aucune suppression n'a été effectuée.",
                        "Erreur de suppression",
                        "listeNote" 
                    );
                }
            } catch (Exception $e) {
                error_log("Erreur: " . $e->getMessage());
                $this->setErrorAndRedirect(
                    "Une erreur est survenue lors de la suppression.",
                    "Erreur de suppression"
                );
            }
        }
    }
?>
