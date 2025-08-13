<?php
    require_once("DBRepository.php");
    class UserRepository extends DBRepository{

        // Permet de vérifier si un utilisateur existe
        public function login($email, $password)
        {
            $sql = "SELECT * FROM utilisateurs WHERE email = :email AND etat = 1";
        
            try {
                $statement = $this->db->prepare($sql);
                $statement->execute(['email' => $email]);
                $user = $statement->fetch(PDO::FETCH_ASSOC);
        
                // Comparaison du mot de passe en clair
                if ($user && $user['password'] === $password) {
                    return $user;
                }
                return false;
        
            } catch (PDOException $error) {
                error_log("Erreur lors de la connexion de l'utilisateur : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la connexion.");
            }
        }
        

        // Permet de créer un compte utilisateur
        public function register($nom, $photo, $email, $password, $role, $createdBy)
        {
            $sql = "INSERT INTO utilisateurs (nom, photo, email, password, role, etat, created_at, created_by)
                    VALUES (:nom, :photo, :email, :password, :role, DEFAULT, NOW(), :created_by)";
        
            try {
                $statement = $this->db->prepare($sql);
        
                // Enregistrement du mot de passe en clair
                $statement->execute([
                    'nom' => $nom,
                    'photo' => $photo,
                    'email' => $email,
                    'password' => $password, // Pas de hashage
                    'role' => $role,
                    'created_by' => $createdBy
                ]);
        
                return $this->db->lastInsertId() ?: null;
        
            } catch (PDOException $error) {
                error_log("Erreur lors de la création du compte utilisateur : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la création du compte.");
            }
        }
        

        // Récupérer la liste des utilisateurs
        public function getAll(int $etat, string $role = null)
        {
            $sql = "SELECT * FROM utilisateurs WHERE etat = :etat";

            if ($role) {
                $sql .= " AND role = :role";
            }

            $sql .= " ORDER BY created_at DESC"; // Trier du plus récent au plus ancien

            try {
                $statement = $this->db->prepare($sql);
                $params = ['etat' => $etat];

                if ($role) {
                    $params['role'] = $role;
                }

                $statement->execute($params);
                return $statement->fetchAll(PDO::FETCH_ASSOC) ?: null;

            } catch (PDOException $error) {
                $etatLabel = $etat == 1 ? "actifs" : "supprimés";
                error_log("Erreur lors de la récupération des utilisateurs $etatLabel : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la récupération des utilisateurs.");
            }
        }
        
        // Récupérer un utilisateur via son ID
        public function getById(int $id)
        {
            $sql = "SELECT * FROM utilisateurs WHERE id = :id";

            try {
                $statement = $this->db->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->execute();
                
                return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

            } catch (PDOException $error) {
                error_log("Erreur lors de la récupération de l'utilisateur d'ID $id : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la récupération de l'utilisateur.");
            }
        }

        // Récupérer un utilisateur via son email
        public function getUserByEmail($email)
        {
            $sql = "SELECT * FROM utilisateurs WHERE email = :email";

            try {
                $statement = $this->db->prepare($sql);
                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->execute();
                
                return $statement->fetch(PDO::FETCH_ASSOC) ?: null;

            } catch (PDOException $error) {
                error_log("Erreur lors de la récupération de l'utilisateur avec l'email $email : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la récupération de l'utilisateur.");
            }
        }

        // Permet de désactiver un utilisateur
        public function desactivate($id, $deletedBy)
        {
            $sql = "UPDATE utilisateurs 
                    SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by 
                    WHERE id = :id";

            try {
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'deleted_by' => $deletedBy,
                    'id' => $id
                ]);

                return $statement->rowCount() > 0; // Retourne true si au moins une ligne est affectée
            } catch (PDOException $error) {
                error_log("Erreur lors de la désactivation de l'utilisateur d'id $id : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la désactivation de l'utilisateur.");
            }
        }


        // Permet d'activer un utilisateur
        public function activate($id, $updatedBy)
        {
            $sql = "UPDATE utilisateurs 
                    SET etat = 1, updated_at = NOW(), updated_by = :updated_by 
                    WHERE id = :id";

            try {
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'updated_by' => $updatedBy,
                    'id' => $id
                ]);

                return $statement->rowCount() > 0; // Retourne true si au moins une ligne est affectée
            } catch (PDOException $error) {
                error_log("Erreur lors de l'activation de l'utilisateur d'id $id : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de l'activation de l'utilisateur.");
            }
        }

        // Permet de mettre à jour le mot de passe d'un utilisateur
        public function updatePassword($userId, $password, $updatedBy)
        {
            $sql = "UPDATE utilisateurs 
                    SET password = :password, updated_at = NOW(), updated_by = :updated_by 
                    WHERE id = :id";
        
            try {
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'password' => $password, // Utilisation du mot de passe en clair
                    'updated_by' => $updatedBy,
                    'id' => $userId
                ]);
        
                return $statement->rowCount() > 0; // Retourne true si au moins une ligne est affectée
            } catch (PDOException $error) {
                error_log("Erreur lors de la modification du mot de passe de l'utilisateur d'id $userId : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la mise à jour du mot de passe.");
            }
        }

        // -------------------
        // Ajouter un nouvel utilisateur
        public function add($nom, $photo, $email, $password, $role, $createdBy)
        {
            $sql = "INSERT INTO utilisateurs (nom, photo, email, password, role, etat, created_at, created_by)
                    VALUES (:nom, :photo, :email, :password, :role, default, NOW(), :created_by)";

            try {
                $statement = $this->db->prepare($sql);
                $statement->execute([
                    'nom' => $nom,
                    'photo' => $photo,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'created_by' => $createdBy,
                ]);

                return $this->db->lastInsertId() ?: null;
            } catch (PDOException $error) {
                error_log("Erreur lors de l'ajout de l'utilisateur $nom: " . $error->getMessage());
                throw $error;
            }
        }

        // Permet de modifier un utilisateur
        public function edit($id, $nom, $photo, $email, $password, $role, $updatedBy)
        {
            // Si un nouveau mot de passe est fourni, inclure sa mise à jour
            $passwordPart = $password ? ", password = :password" : "";
        
            $sql = "UPDATE utilisateurs
                    SET nom = :nom, photo = :photo, email = :email, role = :role,
                        updated_at = NOW(), updated_by = :updated_by
                        $passwordPart
                    WHERE id = :id";
        
            try {
                $statement = $this->db->prepare($sql);
        
                // Construire les paramètres pour la requête
                $params = [
                    'nom' => $nom,
                    'photo' => $photo,
                    'email' => $email,
                    'role' => $role,
                    'updated_by' => $updatedBy,
                    'id' => $id
                ];
        
                // Ajouter le mot de passe si nécessaire
                if ($password) {
                    $params['password'] = $password;
                }
        
                $statement->execute($params);
        
                return $statement->rowCount() > 0; // Retourne true si au moins une ligne est affectée
            } catch (PDOException $error) {
                error_log("Erreur lors de la modification de l'utilisateur $nom : " . $error->getMessage());
                throw new Exception("Une erreur est survenue lors de la mise à jour de l'utilisateur.");
            }
        }
        


        // Désactiver un utilisateur
        public function deactivate($id, $deletedBy)
        {
            $sql = "UPDATE utilisateurs SET etat = 0, deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id";

            try {
                $statement = $this->db->prepare($sql);
                $statement->execute(['deleted_by' => $deletedBy, 'id' => $id]);
                return $statement->rowCount() > 0;
            } catch (PDOException $error) {
                error_log("Erreur lors de la désactivation de l'utilisateur avec l'ID $id: " . $error->getMessage());
                throw $error;
            }
        }



        // Supprimer définitivement un utilisateur
        public function delete(int $id)
        {
            $sql = "DELETE FROM utilisateurs WHERE id = :id";

            try {
                $statement = $this->db->prepare($sql);
                $statement->execute(['id' => $id]);
                return $statement->rowCount() > 0;
            } catch (PDOException $error) {
                error_log("Erreur lors de la suppression définitive de l'utilisateur avec l'ID $id: " . $error->getMessage());
                throw $error;
            }
        }
        

    }
?>