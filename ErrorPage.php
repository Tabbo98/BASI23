<?php
session_start();

// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'utente è autenticato
if (!isset($_SESSION['email'])) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Errore</title>
</head>

<body>
    <h1>SI E' VERIFICATO UN ERRORE!!</h1>
    <p>Ma sei ancora loggato. Torna indietro con il browser.</p>
</body>

</html>
