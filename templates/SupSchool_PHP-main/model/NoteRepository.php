<?php
require_once("DBRepository.php");

class NoteRepository extends DBRepository
{
    // Récupérer toutes les notes
    public function getAll(int $etat)
    {
        $sql = "SELECT
                    n.*, 
                    e.nom AS etudiant_nom, 
                    ev.nom AS evaluation_nom,
                    u1.email AS created_by_email,
                    u2.email AS updated_by_email
                FROM
                    notes n
                LEFT JOIN
                    etudiants e ON n.id_etudiant = e.id
                LEFT JOIN
                    evaluations ev ON n.id_evaluation = ev.id
                LEFT JOIN
                    utilisateurs u1 ON n.created_by = u1.id
                LEFT JOIN
                    utilisateurs u2 ON n.updated_by = u2.id
                WHERE n.etat = :etat";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['etat' => $etat]);
            return $statement->fetchAll(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $error) {
            $etatLabel = $etat == 1 ? "actives" : "supprimées";
            error_log("Erreur lors de la récupération des notes $etatLabel: " . $error->getMessage());
            throw $error;
        }
    }

    // Récupérer une note par son ID
    public function getById(int $id)
    {
        $sql = "SELECT * FROM notes WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de la récupération de la note avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Ajouter une nouvelle note
    public function add($note, $idEtudiant, $idEvaluation, $createdBy)
    {
        $sql = "INSERT INTO notes (note, id_etudiant, id_evaluation, etat, created_at, created_by)
                VALUES (:note, :id_etudiant, :id_evaluation, default, NOW(), :created_by)";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'note' => $note,
                'id_etudiant' => $idEtudiant,
                'id_evaluation' => $idEvaluation,
                'created_by' => $createdBy,
            ]);

            return $this->db->lastInsertId() ?: null;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'ajout de la note: " . $error->getMessage());
            throw $error;
        }
    }

    // Modifier une note
    public function edit($id, $note, $idEtudiant, $idEvaluation, $updatedBy)
    {
        $sql = "UPDATE notes
                SET note = :note, id_etudiant = :id_etudiant, id_evaluation = :id_evaluation,
                    updated_at = NOW(), updated_by = :updated_by
                WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute([
                'note' => $note,
                'id_etudiant' => $idEtudiant,
                'id_evaluation' => $idEvaluation,
                'updated_by' => $updatedBy,
                'id' => $id
            ]);

            return $statement->rowCount() >= 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la modification de la note avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Désactiver une note
    public function deactivate($id, $deletedBy)
    {
        $sql = "UPDATE notes SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['deleted_by' => $deletedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la désactivation de la note avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Réactiver une note
    public function activate($id, $updatedBy)
    {
        $sql = "UPDATE notes SET etat = 1, updated_at = NOW(), updated_by = :updated_by WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['updated_by' => $updatedBy, 'id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de l'activation de la note avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }

    // Supprimer définitivement une note
    public function delete(int $id)
    {
        $sql = "DELETE FROM notes WHERE id = :id";

        try {
            $statement = $this->db->prepare($sql);
            $statement->execute(['id' => $id]);
            return $statement->rowCount() > 0;
        } catch (PDOException $error) {
            error_log("Erreur lors de la suppression définitive de la note avec l'ID $id: " . $error->getMessage());
            throw $error;
        }
    }
}
