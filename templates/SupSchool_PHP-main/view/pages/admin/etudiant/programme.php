<?php
session_start();
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=supschool;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des étudiants
try {
    $sql = "SELECT id, nom, photo, email, matricule, adresse, created_at FROM etudiants WHERE etat = 1";
    $statement = $pdo->query($sql);
    $etudiants = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des étudiants : " . $e->getMessage());
}

?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ajouter un écouteur d'événement pour tous les boutons "Modifier"
        const editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach((button) => {
            button.addEventListener("click", function () {
                // Récupérer les données de l'étudiant depuis les attributs "data-*"
                const id = this.getAttribute("data-id");
                const nom = this.getAttribute("data-nom");
                const email = this.getAttribute("data-email");
                const matricule = this.getAttribute("data-matricule");
                const adresse = this.getAttribute("data-adresse");

                // Remplir les champs du formulaire dans le modal
                document.getElementById("edit-id").value = id;
                document.getElementById("edit-nom").value = nom;
                document.getElementById("edit-email").value = email;
                document.getElementById("edit-matricule").value = matricule;
                document.getElementById("edit-adresse").value = adresse;
            });
        });
    });


    document.addEventListener("click", function (event) {
        if (event.target && event.target.classList.contains("delete-btn")) {
            // Récupération de l'ID depuis l'attribut data-id
            const id = event.target.getAttribute("data-id");
            
            // Remplir le champ caché avec l'ID
            document.getElementById("delete-id").value = id;
        }
    });
</script>


<!-- cdnjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
