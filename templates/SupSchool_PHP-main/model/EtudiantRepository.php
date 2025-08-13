<?php
require_once("DBRepository.php");

class EtudiantRepository extends DBRepository
{
    // Récupérer la liste des étudiants
    public function getAll(int $etat)
    {
        $sql = "SELECT
                    e.*, 
                    u1.email AS created_by_email,
                    u2.email AS updated_by_email
                FROM
                    etudiants e
                LEFT JOIN
                    utilisateurs u1 ON e.created_by = u1.id
                LEFT JOIN
                    utilisateurs u2 ON e.updated_by = u2.id
                WHERE e.etat = :etat";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['etat' => $etat]);
            return $statement->fetchAll(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $error) {
            $etatLabel = $etat == 1 ? "actifs" : "supprimés";
            error_log("Erreur lors de la récupération des étudiants $etatLabel: " . $error->getMessage());
            throw $error;
        }
    }

    // Récupérer un étudiant par son ID
    public function getById(int $id)
    {
        $sql = "SELECT * FROM etudiants WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de la récupération de l'étudiant avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Ajouter un nouvel étudiant
    public function add($nom, $photo, $email, $password, $matricule, $adresse, $createdBy, $idUtilisateur)
    {
        $sql = "INSERT INTO etudiants (nom, photo, email, password, matricule, adresse, etat, created_at, created_by, id_utilisateur)
                VALUES (:nom, :photo, :email, :password, :matricule, :adresse, default, NOW(), :created_by, :id_utilisateur)";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nom' => $nom,
                'photo' => $photo,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'matricule' => $matricule,
                'adresse' => $adresse,
                'created_by' => $createdBy,
                'id_utilisateur' => $idUtilisateur,
            ]);

            return $this->db->lastInsertId() ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'ajout de l'étudiant $nom: " . $error->getMessage());
            throw $error;
        }
    }

    // Modifier un étudiant
    public function edit($id, $nom, $photo, $email, $password, $matricule, $adresse, $updatedBy)
    {
        $sql = "UPDATE etudiants
                SET nom = :nom, photo = :photo, email = :email, password = :password, matricule = :matricule, adresse = :adresse,
                    updated_at = NOW(), updated_by = :updated_by
                WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nom' => $nom,
                'photo' => $photo,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'matricule' => $matricule,
                'adresse' => $adresse,
                'updated_by' => $updatedBy,
                'id' => $id
            ]);

            return $statement->rowCount() >= 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la modification de l'étudiant $nom: " . $error->getMessage());
            throw $error;
        }
    }

    // Désactiver un étudiant
    public function deactivate($id, $deletedBy)
    {
        $sql = "UPDATE etudiants SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['deleted_by' => $deletedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la désactivation de l'étudiant avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Réactiver un étudiant
    public function activate($id, $updatedBy)
    {
        $sql = "UPDATE etudiants SET etat = 1, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['updated_by' => $updatedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'activation de l'étudiant avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Supprimer définitivement un étudiant
    public function delete(int $id)
    {
        $sql = "DELETE FROM etudiants WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la suppression définitive de l'étudiant avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }
}
