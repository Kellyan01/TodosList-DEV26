<?php
//Class Model générique qui ne doit pas être instancié
class Model {
    //ATTRIBUT
    private PDO $pdo;

    //CONSTRUCTEUR
    public function __construct(PDO $newPdo){
        $this->pdo = $newPdo;
    }

    //GETTER ET SETTER
    /**
     * Get the value of pdo
     *
     * @return PDO
     */
    public function getPdo(): PDO {
        return $this->pdo;
    }

    /**
     * Set the value of pdo
     *
     * @param PDO $pdo
     *
     * @return self
     */
    public function setPdo(PDO $pdo): self {
        $this->pdo = $pdo;
        return $this;
    }
}