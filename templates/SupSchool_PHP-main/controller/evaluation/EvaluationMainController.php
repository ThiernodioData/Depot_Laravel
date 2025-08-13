<?php
    require_once("EvaluationController.php");

    $evaluationController = new EvaluationController();

    if (isset($_POST["frmAddEvaluation"])) {
        $evaluationController->addEvaluation();
    } elseif (isset($_POST["frmEditEvaluation"])) {
        $evaluationController->editEvaluation();
    } elseif (isset($_POST["frmDeleteEvaluation"])) {
        $evaluationController->deleteEvaluation();
    }
?>
