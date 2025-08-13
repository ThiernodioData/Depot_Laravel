<?php 
session_start();
require_once("../../model/UserRepository.php");

class UserController
{
    private $userRepository;

    // Constructeur : Initialise le repository utilisateur
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * Valide les champs du formulaire de connexion.
     * Vérifie si les champs email et mot de passe sont remplis, si l'email est valide
     * et si le mot de passe respecte la longueur minimale.
     * 
     * @param string $email
     * @param string $password
     * @return string|null Renvoie un message d'erreur ou null si tout est valide.
     */
    private function validateLoginField($email, $password)
    {
        if (empty($email) || empty($password)) {
            return "Tous les champs sont requis.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email invalide.";
        }

        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }

        return null; // Tout est valide
    }

    /**
     * Définit un message d'erreur dans la session et redirige l'utilisateur.
     * 
     * @param string $message Message d'erreur à afficher.
     * @param string $title Titre de l'erreur.
     * @param string $redirectUrl URL de redirection (par défaut vers la page de connexion).
     */
    private function setErrorAndRedirect($message, $title, $redirectUrl = 'login')
    {
        $_SESSION["error"] = $message;
        header(
            "Location:$redirectUrl?error=1&message=" 
            . urlencode($message) . 
            "&title=" . urlencode($title) 
        );
        exit;
    }

    /**
     * Définit un message de succès dans la session et redirige l'utilisateur.
     * 
     * @param string $message Message de succès à afficher.
     * @param string $title Titre du succès.
     * @param string $redirectUrl URL de redirection (par défaut vers le tableau de bord).
     */
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

    /**
     * Authentifie un utilisateur en fonction de son email et mot de passe.
     * Vérifie l'existence de l'utilisateur dans `la base de données et son état actif.
     * Redirige vers des tableaux de bord spécifiques selon le rôle de l'utilisateur.
     * 
     * @param string $email
     * @param string $password
     * @param UserRepository $userRepository Instance de la classe UserRepository.
     */
    private function authUser($email, $password, $userRepository)
    {
        $user = $userRepository->login($email, $password);

        if ($user) {
            // Stocker les informations utilisateur dans la session
            $_SESSION["id"] = $user["id"];
            $_SESSION["nom"] = $user["nom"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["etat"] = $user["etat"];

            // Vérifie si le compte est activé
            if ($user["etat"] != 1) {
                $this->setErrorAndRedirect(
                    "Votre compte a été désactivé. Contactez l'administrateur.",
                    "Accès interdit"
                );
            }

            // Redirige en fonction du rôle de l'utilisateur
            switch ($user["role"]) {
                case 'Admin':
                    $this->setSuccessAndRedirect("Bienvenue, Admin.", "Connexion réussie", "admin");
                    break;
                case 'etudiant':
                    $this->setSuccessAndRedirect("Bienvenue, Étudiant.", "Connexion réussie", "student_dashboard");
                    break;
                case 'professeur':
                    $this->setSuccessAndRedirect("Bienvenue, Professeur.", "Connexion réussie", "teacher_dashboard");
                    break;
                default:
                    $this->setErrorAndRedirect(
                        "Rôle utilisateur non reconnu.",
                        "Accès interdit"
                    );
            }
        } else {
            // Si l'email ou le mot de passe est incorrect
            $this->setErrorAndRedirect(
                "Email ou mot de passe incorrect.",
                "Connexion échouée"
            );
        }
    }

    /**
     * Gère le processus d'authentification de l'utilisateur.
     * Valide les champs du formulaire, puis tente de connecter l'utilisateur.
     */
    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            // Validation des champs du formulaire
            $messageError = $this->validateLoginField($email, $password);

            if ($messageError) {
                // Redirige avec un message d'erreur si validation échoue
                $this->setErrorAndRedirect($messageError, "Erreur de validation", "login");
            }

            // Authentifie l'utilisateur
            $this->authUser($email, $password, $this->userRepository);
        }
    }

    /**
     * Déconnecte l'utilisateur en supprimant les données de session.
     * Redirige l'utilisateur vers la page d'accueil avec un message de succès.
     */
    public function logout()
    {
        session_unset();
        session_destroy();

        $this->setSuccessAndRedirect(
            "Vous avez été déconnecté avec succès.",
            "Déconnexion réussie",
            "home"
        );
    }

    //---
    
    public function addUtilisateur()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'Utilisateur');
            $photo = $_FILES['photo'] ?? null;
            $createdBy = $_SESSION['id'] ?? null;

            if (empty($nom) || empty($email) || empty($password) || empty($role)) {
                $this->setErrorAndRedirect("Tous les champs obligatoires doivent être remplis.", "Erreur d'ajout");
            }


            // Gestion de l'upload de la photo
            $photoName = null;
            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uploadDir = "../../public/images/utilisateurs/";
                $photoName = uniqid() . '_' . basename($photo['name']);
                $uploadPath = $uploadDir . $photoName;

                if (!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    $this->setErrorAndRedirect("Erreur lors du téléchargement de la photo.", "Erreur d'ajout");
                }
            }

            try {
                $lastInsertId = $this->userRepository->add(
                    $nom,
                    $photoName,
                    $email,
                    $password,
                    $role,
                    $createdBy
                );

                if ($lastInsertId) {
                    $this->setSuccessAndRedirect("Utilisateur ajouté avec succès.", "Ajout réussi", "listeUser");
                } else {
                    $this->setErrorAndRedirect("Une erreur est survenue lors de l'ajout de l'utilisateur.", "Erreur d'ajout", "listeUser");
                }
            } catch (Exception $e) {
                error_log("Erreur: " . $e->getMessage());
                $this->setErrorAndRedirect("Une erreur est survenue lors de l'ajout.", "Erreur d'ajout", "listeUser");
            }
        }
    }

    public function editUtilisateur()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'Utilisateur');
            $photo = $_FILES['photo'] ?? null;
            $updatedBy = $_SESSION['id'] ?? null;

            if (empty($id)) {
                $this->setErrorAndRedirect("ID utilisateur manquant.", "Erreur de modification");
            }

            if (empty($nom) || empty($email) || empty($role)) {
                $this->setErrorAndRedirect("Tous les champs obligatoires doivent être remplis.", "Erreur de modification");
            }


            $photoName = null;
            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uploadDir = "../../public/images/utilisateurs/";
                $photoName = uniqid() . '_' . basename($photo['name']);
                $uploadPath = $uploadDir . $photoName;

                if (!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
                    $this->setErrorAndRedirect("Erreur lors du téléchargement de la photo.", "Erreur de modification");
                }
            }

            try {
                $success = $this->userRepository->edit(
                    $id,
                    $nom,
                    $photoName,
                    $email,
                    $password,
                    $role,
                    $updatedBy
                );

                if ($success) {
                    $this->setSuccessAndRedirect("Utilisateur modifié avec succès.", "Modification réussie", "listeUser");
                } else {
                    $this->setErrorAndRedirect("Aucune modification n'a été effectuée.", "Erreur de modification", "listeUser");
                }
            } catch (Exception $e) {
                error_log("Erreur: " . $e->getMessage());
                $this->setErrorAndRedirect("Une erreur est survenue lors de la modification.", "Erreur de modification", "listeUser");
            }
        }
    }

    public function deactivateUtilisateur()
    {
        $id = $_POST['id'] ?? null;
        $deletedBy = $_SESSION['id'] ?? null;

        if (!$id) {
            $this->setErrorAndRedirect("Identifiant de l'utilisateur manquant.", "Erreur de désactivation");
        }

        try {
            $success = $this->userRepository->deactivate($id, $deletedBy);

            if ($success) {
                $this->setSuccessAndRedirect("Utilisateur désactivé avec succès.", "Désactivation réussie");
            } else {
                $this->setErrorAndRedirect("Aucune désactivation n'a été effectuée.", "Erreur de désactivation");
            }
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            $this->setErrorAndRedirect("Une erreur est survenue lors de la désactivation.", "Erreur de désactivation");
        }
    }

    public function activateUtilisateur()
    {
        $id = $_POST['id'] ?? null;
        $updatedBy = $_SESSION['id'] ?? null;

        if (!$id) {
            $this->setErrorAndRedirect("Identifiant de l'utilisateur manquant.", "Erreur d'activation");
        }

        try {
            $success = $this->userRepository->activate($id, $updatedBy);

            if ($success) {
                $this->setSuccessAndRedirect("Utilisateur activé avec succès.", "Activation réussie");
            } else {
                $this->setErrorAndRedirect("Aucune activation n'a été effectuée.", "Erreur d'activation");
            }
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            $this->setErrorAndRedirect("Une erreur est survenue lors de l'activation.", "Erreur d'activation");
        }
    }

    public function deleteUtilisateur()
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->setErrorAndRedirect("Identifiant de l'utilisateur manquant.", "Erreur de suppression");
        }

        try {
            $success = $this->userRepository->delete($id);

            if ($success) {
                $this->setSuccessAndRedirect("Utilisateur supprimé définitivement avec succès.", "Suppression réussie", "listeUser");
            } else {
                $this->setErrorAndRedirect("Aucune suppression n'a été effectuée.", "Erreur de suppression", "listeUser");
            }
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            $this->setErrorAndRedirect("Une erreur est survenue lors de la suppression.", "Erreur de suppression");
        }
    }
}

?>
