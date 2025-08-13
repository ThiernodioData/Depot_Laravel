<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=supschool;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des utilisateurs
try {
    $sql = "SELECT id, nom, email,password, role, etat, created_at FROM utilisateurs WHERE etat = 1";
    $statement = $pdo->query($sql);
    $utilisateurs = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Gestion du bouton "Modifier"
        const editButtons = document.querySelectorAll(".edit-btn");

        editButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                const nom = this.getAttribute("data-nom");
                const email = this.getAttribute("data-email");
                const role = this.getAttribute("data-role");
                const password = this.getAttribute("data-password");

                document.getElementById("edit-id").value = id;
                document.getElementById("edit-nom").value = nom;
                document.getElementById("edit-email").value = email;
                document.getElementById("edit-role").value = role;
                document.getElementById("edit-password").value = password;
            });
        });

        // Gestion du bouton "Supprimer"
        const deleteButtons = document.querySelectorAll(".delete-btn");

        deleteButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                document.getElementById("delete-id").value = id;
            });
        });
    });
</script>

<!-- cdnjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
