<?php
//Class ModelUer
class ModelUser extends Model{
    //ATTRIBUT
    private int $idUser;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $createdAt;
    private string $role;

    //CONSTRUCTEUR

    //GETTER ET SETTER
    /**
     * Get the value of idUser
     *
     * @return int
     */
    public function getIdUser(): int {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @param int $idUser
     *
     * @return self
     */
    public function setIdUser(int $idUser): self {
        $this->idUser = $idUser;
        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return string
     */
    public function getFirstname(): string {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param string $firstname
     *
     * @return self
     */
    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Get the value of lastname
     *
     * @return string
     */
    public function getLastname(): string {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname(string $lastname): self {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return string
     */
    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param string $createdAt
     *
     * @return self
     */
    public function setCreatedAt(string $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the value of role
     *
     * @return string
     */
    public function getRole(): string {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param string $role
     *
     * @return self
     */
    public function setRole(string $role): self {
        $this->role = $role;
        return $this;
    }

    //METHODS
    public function findAll():array | null{
        try{
            //A.BIS : Vérifier si l'email utiliser pour l'enregistrement est disponible
            //Préparer une requête SELECT pour récupérer les données d'un compte utilisant l'email
            $request = $this->getPdo()->prepare('SELECT u.id_user, u.firstname, u.lastname, u.email, u.`password`, u.created_at, r.`role` FROM users u INNER JOIN roles r ON u.id_role = r.id_role');

            //Exécuter la requête
            $request->execute();

            //Récupérer la réponse
            //ici fetch() uniquement, car je n'ai besoin de trouver qu'une seule ligne, et donc qu'un seul et unique tableau associatif au maximum
            return $request->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function findOneByEmail():array | bool | null{
        try{
            //A.BIS : Vérifier si l'email utiliser pour l'enregistrement est disponible
            //Préparer une requête SELECT pour récupérer les données d'un compte utilisant l'email
            $request = $this->getPdo()->prepare('SELECT u.id_user, u.firstname, u.lastname, u.email, u.`password`, u.created_at, r.`role` FROM users u INNER JOIN roles r ON u.id_role = r.id_role WHERE u.email = ?');

            //BindParam() pour injecter l'email dans la requête
            $request->bindParam(1,$this->email,PDO::PARAM_STR);

            //Exécuter la requête
            $request->execute();

            //Récupérer la réponse
            //ici fetch() uniquement, car je n'ai besoin de trouver qu'une seule ligne, et donc qu'un seul et unique tableau associatif au maximum
            return $request->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $error){
            die($error->getMessage());
        }
    }

    public function addUser():void{
        try{
            //B. Préparer ma requête d'insertion en BDD
            $request = $this->getPdo()->prepare('INSERT INTO users (firstname, lastname, email, `password`) VALUES(?, ?, ?, ?)');

            //C. BindParam pour remplir les ? au sein de la requête
            $request->bindParam(1,$this->firstname,PDO::PARAM_STR);
            $request->bindParam(2,$this->lastname,PDO::PARAM_STR);
            $request->bindParam(3,$this->email,PDO::PARAM_STR);
            $request->bindParam(4,$this->password,PDO::PARAM_STR);

            //D. Envoyer la requête à la BDD, pour exécution
            $request->execute();


        }catch(Exception $error){
            die($error->getMessage());
        }
    }

}

?>