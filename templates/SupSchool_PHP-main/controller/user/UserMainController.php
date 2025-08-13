<?php
    require_once("UserController.php"); 
    // Inclusion du fichier contenant la classe UserController, ce qui permet d'utiliser ses méthodes pour la gestion de l'authentification et de la déconnexion.

    // Création d'une instance de la classe UserController
    $userController = new UserController();

    // Authentification
    // Vérifie si le formulaire de connexion a été soumis (si l'utilisateur a cliqué sur le bouton de connexion).
    if (isset($_POST["formLogin"])) {
        // Appel de la méthode auth() de l'objet UserController pour effectuer l'authentification de l'utilisateur.
        $userController->auth();
    }

    // Déconnexion
    // Vérifie si un paramètre "logout" est passé dans l'URL, ce qui indique que l'utilisateur souhaite se déconnecter.
    if (isset($_GET["logout"])) {
        // Appel de la méthode logout() de l'objet UserController pour déconnecter l'utilisateur et rediriger vers la page d'accueil.
        $userController->logout();
    }
    if(isset($_POST["frmAddUser"])){
        $userController->addUtilisateur();
    }
    if(isset($_POST["frmEditUser"])){
        $userController->editUtilisateur();
    }
    if(isset($_POST["frmDeleteUser"])){
        $userController->deleteUtilisateur();
    }
?>
