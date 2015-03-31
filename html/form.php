<html>
    <head>
        <title>PHPFTP: errore</title>
    </head>
    <body>
        <h1>Pronti a spedire.</h1>
        <p>L'ID gruppo richiesto è <?php echo $id_gruppo ?>.</p>
        <p>Premi il pulsante "Invia" per inviare i dati.</p>
        <form action="/process.php" method="post">>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
