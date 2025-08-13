<?php
session_start();
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=supschool;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des évaluations
try {
    $sql = "SELECT id, nom, type, semestre, description, date FROM evaluations";
    $statement = $pdo->query($sql);
    $evaluations = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des évaluations : " . $e->getMessage());
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ajouter un écouteur d'événement pour tous les boutons "Modifier"
        const editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Récupérer les données de l'évaluation depuis les attributs "data-*"
                const id = this.getAttribute("data-id");
                const nom = this.getAttribute("data-nom");
                const type = this.getAttribute("data-type");
                const semestre = this.getAttribute("data-semestre");
                const description = this.getAttribute("data-description");
                const date = this.getAttribute("data-date");

                // Remplir les champs du formulaire dans le modal
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-nom").value = nom;
                document.getElementById("edit-type").value = type;
                document.getElementById("edit-semestre").value = semestre;
                document.getElementById("edit-description").value = description;
                document.getElementById("edit-date").value = date;
            });
        });

        // Ajouter un écouteur d'événement pour tous les boutons "Supprimer"
        const deleteButtons = document.querySelectorAll(".delete-btn");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Récupérer l'ID de l'évaluation depuis les attributs "data-*"
                const id = this.getAttribute("data-id");

                // Remplir le champ caché avec l'ID
                document.getElementById("delete-id").value = id;
            });
        });
    });
</script>

<!-- cdnjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
