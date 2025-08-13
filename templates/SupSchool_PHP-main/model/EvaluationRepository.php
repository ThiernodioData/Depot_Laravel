<?php
require_once("DBRepository.php");

class EvaluationRepository extends DBRepository
{
    // Récupérer la liste des évaluations
    public function getAll(int $etat)
    {
        $sql = "SELECT
                    e.*, 
                    u1.email AS created_by_email,
                    u2.email AS updated_by_email
                FROM
                    evaluations e
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
            $etatLabel = $etat == 1 ? "actives" : "supprimées";
            error_log("Erreur lors de la récupération des évaluations $etatLabel: " . $error->getMessage());
            throw $error;
        }
    }

    // Récupérer une évaluation par son ID
    public function getById(int $id)
    {
        $sql = "SELECT * FROM evaluations WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de la récupération de l'évaluation avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Ajouter une nouvelle évaluation
    public function add($nom, $type, $semestre, $description, $date, $createdBy)
    {
        $sql = "INSERT INTO evaluations (nom, type, semestre, description, date, etat, created_at, created_by)
                VALUES (:nom, :type, :semestre, :description, :date, default, NOW(), :created_by)";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nom' => $nom,
                'type' => $type,
                'semestre' => $semestre,
                'description' => $description,
                'date' => $date,
                'created_by' => $createdBy,
            ]);

            return $this->db->lastInsertId() ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'ajout de l'évaluation $nom: " . $error->getMessage());
            throw $error;
        }
    }

    // Modifier une évaluation
    public function edit($id, $nom, $type, $semestre, $description, $date, $updatedBy)
    {
        $sql = "UPDATE evaluations
                SET nom = :nom, type = :type, semestre = :semestre, description = :description, date = :date,
                    updated_at = NOW(), updated_by = :updated_by
                WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'nom' => $nom,
                'type' => $type,
                'semestre' => $semestre,
                'description' => $description,
                'date' => $date,
                'updated_by' => $updatedBy,
                'id' => $id
            ]);

            return $statement->rowCount() >= 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la modification de l'évaluation $nom: " . $error->getMessage());
            throw $error;
        }
    }

    // Désactiver une évaluation
    public function deactivate($id, $deletedBy)
    {
        $sql = "UPDATE evaluations SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['deleted_by' => $deletedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la désactivation de l'évaluation avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Réactiver une évaluation
    public function activate($id, $updatedBy)
    {
        $sql = "UPDATE evaluations SET etat = 1, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['updated_by' => $updatedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'activation de l'évaluation avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Supprimer définitivement une évaluation
    public function delete(int $id)
    {
        $sql = "DELETE FROM evaluations WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la suppression définitive de l'évaluation avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }
}
