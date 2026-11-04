<?php
//Class Controller Generic qui ne doit être instancié
class Controller{
    //ATTRIBUT
    private array $models = [];

    //CONSTRUCTEUR : laisser vide, car c'est controller concret qui sait comment il doit se construire

    //GETTER ET SETTER
    /**
     * Get the value of models
     *
     * @return array
     */
    public function getModels(): array {
        return $this->models;
    }

    /**
     * Set the value of models
     *
     * @param array $models
     *
     * @return self
     */
    public function setModels(array $models): self {
        $this->models = $models;
        return $this;
    }

    //METHODS pour ajouter et retire un élément des attribut tableau
    public function addModel(string $key, Model $newModel):self{
        $this->models[$key] = $newModel;
        return $this;
    }
    public function removeModel(string $key):self{
        unset($this->models[$key]);
        return $this;

    }

    //METHODS
    //une méthode render générique qui vérifie si j'ai une view pour le header, le contenu principal et le footer, puis lance leur affichage si c'est le cas
    public function testMethods(array $tabMethods):void{
        if(!in_array($_SERVER['REQUEST_METHOD'], $tabMethods)){
            http_response_code(405);
            echo(json_encode(['code' => 405,'message' => 'Méthode HTTP nons autorisée']));
            exit;
        }
    }
}