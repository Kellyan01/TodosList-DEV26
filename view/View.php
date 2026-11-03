<?php
//Class View générique qui ne doit pas être instancié
class View {
    //METHODS
    public function display():void{
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Exemple de Page</title>
        </head>
        <body>
            <header></header>
            <main>
                <h1>Page Vide à Compléter</h1>
            </main>
            <footer></footer>
        </body>
        </html>
<?php
    }
}