<?php
    require_once("NoteController.php");

    $noteController = new NoteController();

    if (isset($_POST["frmAddNote"])) {
        $noteController->addNote();
    } elseif (isset($_POST["frmEditNote"])) {
        $noteController->editNote();
    } elseif (isset($_POST["frmDeleteNote"])) {
        $noteController->deleteNote();
    }
?>
