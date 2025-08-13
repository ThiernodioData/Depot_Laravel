<?php
    require_once("EtudiantController.php");

    $etudiantController = new EtudiantController();

    if (isset($_POST["frmAddEtudiant"])) {
        $etudiantController->addEtudiant();
    } elseif (isset($_POST["frmEditEtudiant"])) {
        $etudiantController->editEtudiant();
    } elseif (isset($_POST["frmDeleteEtudiant"])) {
        $etudiantController->deleteEtudiant();
    }
?>