<?php
class ViewAccueil extends View{
    //ATTRIBUT
    private string $message = "";
    private string $messageCo = "";
    private array $data = [];

    //GETTER ET SETTER
    /**
     * Get the value of message
     *
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the value of messageCo
     *
     * @return string
     */
    public function getMessageCo(): string {
        return $this->messageCo;
    }

    /**
     * Set the value of messageCo
     *
     * @param string $messageCo
     *
     * @return self
     */
    public function setMessageCo(string $messageCo): self {
        $this->messageCo = $messageCo;
        return $this;
    }

    /**
     * Get the value of data
     *
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data): self {
        $this->data = $data;
        return $this;
    }

    //METHODS
    function display():void{
    ?>
        <main>
                <h1>Page d'Accueil</h1>
                <?php 
                    //Vérifier si personne n'est connecté
                    // Si c'est le cas, j'affiche les formulaires d'inscription et connexion
                    if(!isset($_SESSION['id'])){
                ?>
                        <section>
                            <h2>Inscription</h2>
                            <form action="" method="post">
                                <label for="prenom">Prenom <input type="text" name="prenom" id="prenom"></label>
                                <label for="nom">Nom <input type="text" name="nom" id="nom"></label>
                                <label for="email">Email <input type="text" name="email" id="email"></label>
                                <label for="password">Mot de Passe <input type="text" name="password" id="password"></label>
                                <input type="submit" name="submit">
                            </form>
                            <p>
                                <?php echo($this->getMessage()) ?>
                            </p>
                        </section>
                        <section>
                            <h2>Connexion</h2>
                            <form action="" method="post">
                                <label for="emailCo">Email <input type="text" name="emailCo" id="emailCo"></label>
                                <label for="passwordCo">Mot de Passe <input type="text" name="passwordCo" id="passwordCo"></label>
                                <input type="submit" name="submitCo">
                            </form>
                        </section>
                <?php
                    }
                ?>
                <!-- Affichage du message de confirmation de connexion -->
                <p>
                    <?php echo($this->getMessageCo()) ?>
                </p>
                <section>
                    <h2>Listes des utilisateurs</h2>
                    <ul>
                        <?php 
                            foreach($this->getData() as $user){
                                //Affichage en nettoyant les données provenant de la BDD
                                echo("<li>".htmlentities($user['firstname'])." ".htmlentities($user['lastname'])." - ".htmlentities($user['email'])."</li>");
                            } 
                        ?>
                    </ul>
                </section>
        </main>
    <?php
    }
}
