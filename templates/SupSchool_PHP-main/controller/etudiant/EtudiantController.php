<?php 
    session_start();
    require_once("../../model/EtudiantRepository.php");

    class EtudiantController
    {
        private $etudiantRepository;

        public function __construct()
        {
            $this->etudiantRepository = new EtudiantRepository();
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

        public function addEtudiant()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = trim($_POST['nom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $matricule = trim($_POST['matricule'] ?? '');
                $adresse = trim($_POST['adresse'] ?? '');
                $photo = $_FILES['photo'] ?? null;
                $createdBy = $_SESSION['id'] ?? null;

                if (empty($nom) || empty($email) || empty($password) || empty($matricule)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur d'ajout"
                    );
                }

                $photoName = null;
                if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = "../../public/images/etudiants/";
                    $photoName = uniqid() . '_' . basename($photo['name']);
                    $uploadPath = $uploadDir . $photoName;

                    if (!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                        $this->setErrorAndRedirect(
                            "Erreur lors du téléchargement de la photo.",
                            "Erreur d'ajout"
                        );
                    }
                }

                try {
                    $lastInsertId = $this->etudiantRepository->add(
                        $nom,
                        $photoName,
                        $email,
                        $password,
                        $matricule,
                        $adresse,
                        $createdBy,
                        $createdBy // `id_utilisateur` est provisoirement égal à `createdBy`
                    );

                    if ($lastInsertId) {
                        $this->setSuccessAndRedirect(
                            "Étudiant ajouté avec succès.",
                            "Ajout réussi",
                            "listeEtudiant"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Une erreur est survenue lors de l'ajout de l'étudiant.",
                            "Erreur d'ajout",
                            "listeEtudiant"

                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de l'ajout.",
                        "Erreur d'ajout",
                        "listeEtudiant"

                    );
                }
            }
        }

        public function editEtudiant()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                $nom = trim($_POST['nom'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $matricule = trim($_POST['matricule'] ?? '');
                $adresse = trim($_POST['adresse'] ?? '');
                $photo = $_FILES['photo'] ?? null;
                $updatedBy = $_SESSION['id'] ?? null;
        
                // Vérifier les champs obligatoires
                if (empty($id)) {
                    $this->setErrorAndRedirect(
                        "ID étudiant manquant. Veuillez réessayer.",
                        "Erreur de modification",
                        "listeEtudiant"
                    );
                }
                
                if (empty($id) || empty($nom) || empty($email) || empty($matricule)) {
                    $this->setErrorAndRedirect(
                        "Tous les champs obligatoires doivent être remplis.",
                        "Erreur de modification",
                        "listeEtudiant"
                    );
                }
        
                // Gestion de l'upload de la photo
                $photoName = null;
                if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = "../../public/images/etudiants/";
                    $photoName = uniqid() . '_' . basename($photo['name']);
                    $uploadPath = $uploadDir . $photoName;
        
                    if (!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                        $this->setErrorAndRedirect(
                            "Erreur lors du téléchargement de la photo.",
                            "Erreur de modification",
                            "listeEtudiant"

                        );
                    }
                }
        
                // Appel au repository pour effectuer la mise à jour
                try {
                    $success = $this->etudiantRepository->edit(
                        $id,
                        $nom,
                        $photoName,
                        $email,
                        $password,
                        $matricule,
                        $adresse,
                        $updatedBy
                    );
        
                    if ($success) {
                        $this->setSuccessAndRedirect(
                            "Étudiant modifié avec succès.",
                            "Modification réussie",
                            "listeEtudiant"
                        );
                    } else {
                        $this->setErrorAndRedirect(
                            "Aucune modification n'a été effectuée.",
                            "Erreur de modification",
                            "listeEtudiant"
                        );
                    }
                } catch (Exception $e) {
                    error_log("Erreur: " . $e->getMessage());
                    $this->setErrorAndRedirect(
                        "Une erreur est survenue lors de la modification.",
                        "Erreur de modification",
                        "listeEtudiant"
                    );
                }
            }
        }
        

        public function desactivateEtudiant()
        {
            $id = $_POST['id'] ?? null;
            $deletedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'étudiant manquant.",
                    "Erreur de désactivation"
                );
            }

            try {
                $success = $this->etudiantRepository->deactivate($id, $deletedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Étudiant désactivé avec succès.",
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

        public function activateEtudiant()
        {
            $id = $_POST['id'] ?? null;
            $updatedBy = $_SESSION['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'étudiant manquant.",
                    "Erreur d'activation"
                );
            }

            try {
                $success = $this->etudiantRepository->activate($id, $updatedBy);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Étudiant activé avec succès.",
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

        public function deleteEtudiant()
        {
            $id = $_POST['id'] ?? null;

            if (!$id) {
                $this->setErrorAndRedirect(
                    "Identifiant de l'étudiant manquant.",
                    "Erreur de suppression"
                );
            }

            try {
                $success = $this->etudiantRepository->delete($id);

                if ($success) {
                    $this->setSuccessAndRedirect(
                        "Étudiant supprimé définitivement avec succès.",
                        "Suppression réussie",
                        "listeEtudiant"
                    );
                } else {
                    $this->setErrorAndRedirect(
                        "Aucune suppression n'a été effectuée.",
                        "Erreur de suppression",
                        "listeEtudiant" 
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
