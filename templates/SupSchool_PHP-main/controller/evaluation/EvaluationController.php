<?php 
    session_start();
    require_once("../../model/EvaluationRepository.php");

    class EvaluationController
    {
        private $evaluationRepository;

        public function __construct()
        {
            $this->evaluationRepository = new EvaluationRepository();
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

        public function addEvaluation()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = trim($_POST['nom'] ?? '');
                $type = trim($_POST['type'] ?? '');
                $semestre = trim($_POST['semestre'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $date = trim($_POST['date'] ?? '');
                $createdBy = $_SESSION['id'] ?? null;
    
                if (empty($nom) || empty($type) || empty($semestre) || empty($date)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur d'ajout"
                    );
                }
    
                try {
                    $lastInsertId = $this->evaluationRepository->add(
                        $nom,
                        $type,
                        $semestre,
                        $description,
                        $date,
                        $createdBy
                    );
    
                    if ($lastInsertId) {
                        $this->setSuccessAndRedirect(
                            "Évaluation ajoutée avec succès.",
                            "Ajout réussi",
                            "listeEvaluation"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Une erreur est survenue lors de l'ajout de l'évaluation.",
                            "Erreur d'ajout",
                            "listeEvaluation"
                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de l'ajout.",
                        "Erreur d'ajout",
                        "listeEvaluation"
                    );
                }
            }
        }
    
        public function editEvaluation()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                $nom = trim($_POST['nom'] ?? '');
                $type = trim($_POST['type'] ?? '');
                $semestre = trim($_POST['semestre'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $date = trim($_POST['date'] ?? '');
                $updatedBy = $_SESSION['id'] ?? null;
    
                if (empty($id) || empty($nom) || empty($type) || empty($semestre) || empty($date)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur de modification",
                        "listeEvaluation"
                    );
                }
    
                try {
                    $success = $this->evaluationRepository->edit(
                        $id,
                        $nom,
                        $type,
                        $semestre,
                        $description,
                        $date,
                        $updatedBy
                    );
    
                    if ($success) {
                        $this->setSuccessAndRedirect(
                            "Évaluation modifiée avec succès.",
                            "Modification réussie",
                            "listeEvaluation"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Aucune modification n'a été effectuée.",
                            "Erreur de modification",
                            "listeEvaluation"
                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de la modification.",
                        "Erreur de modification",
                        "listeEvaluation"
                    );
                }
            }
        }
    

        public function desactivateEvaluation()
        {
            $id = $_POST['id'] ?? null;
            $deletedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'évaluation manquant.",
                    "Erreur de désactivation"
                );
            }

            try {
                $success = $this->evaluationRepository->deactivate($id, $deletedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Évaluation désactivée avec succès.",
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

        public function activateEvaluation()
        {
            $id = $_POST['id'] ?? null;
            $updatedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'évaluation manquant.",
                    "Erreur d'activation"
                );
            }

            try {
                $success = $this->evaluationRepository->activate($id, $updatedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Évaluation activée avec succès.",
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

        public function deleteEvaluation()
        {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'évaluation manquant.",
                    "Erreur de suppression"
                );
            }

            try {
                $success = $this->evaluationRepository->delete($id);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Évaluation supprimée définitivement avec succès.",
                        "Suppression réussie",
                        "listeEvaluation"
                    );
                } else {
                    $this->setErrorAndRedirect(
                        "Aucune suppression n'a été effectuée.",
                        "Erreur de suppression",
                        "listeEvaluation"
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
