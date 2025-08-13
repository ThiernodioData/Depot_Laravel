<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=supschool;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des étudiants pour les listes déroulantes
try {
    $sqlEtudiants = "SELECT id, nom FROM etudiants";
    $etudiants = $pdo->query($sqlEtudiants)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des étudiants : " . $e->getMessage());
}

// Récupération des évaluations pour les listes déroulantes
try {
    $sqlEvaluations = "SELECT id, nom FROM evaluations";
    $evaluations = $pdo->query($sqlEvaluations)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des évaluations : " . $e->getMessage());
}

// Récupération des notes avec les noms des étudiants et évaluations
try {
    $sqlNotes = "
    SELECT 
        n.id, 
        e.nom AS etudiant, 
        ev.nom AS evaluation, 
        n.note, 
        n.created_at AS date
    FROM notes n
    INNER JOIN etudiants e ON n.id_etudiant = e.id
    INNER JOIN evaluations ev ON n.id_evaluation = ev.id
    WHERE n.etat = 1
";
    $notes = $pdo->query($sqlNotes)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des notes : " . $e->getMessage());
}
?>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ajouter un écouteur d'événement pour tous les boutons "Modifier"
        const editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Récupérer les données de la note depuis les attributs "data-*"
                const id = this.getAttribute("data-id");
                const etudiant = this.getAttribute("data-etudiant");
                const evaluation = this.getAttribute("data-evaluation");
                const note = this.getAttribute("data-note");

                // Remplir les champs du formulaire dans le modal
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-etudiant").value = etudiant;
                document.getElementById("edit-evaluation").value = evaluation;
                document.getElementById("edit-note").value = note;
            });
        });

        // Ajouter un écouteur d'événement pour tous les boutons "Supprimer"
        const deleteButtons = document.querySelectorAll(".delete-btn");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Récupérer l'ID de la note depuis les attributs "data-*"
                const id = this.getAttribute("data-id");

                // Remplir le champ caché avec l'ID
                document.getElementById("delete-id").value = id;
            });
        });
    });
</script>

</body>
</html>
