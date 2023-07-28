<?php
session_start();
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'utente è autenticato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

// Recupero email e ruolo dalla sessione
$email = $_SESSION['email'];
$ruolo = $_SESSION['ruolo'];

// Verifica se il form è stato sottoposto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recupero dei valori forniti dal form
    $dominio = $_POST["dominio"];
    $testo = $_POST["testo"];
    $punteggio = $_POST["punteggio"];
    $foto = $_FILES["foto"]["name"]; // File name (da gestire il salvataggio del file nel server)
    $opzione1 = $_POST["opzione1"];
    $opzione2 = $_POST["opzione2"];
    $opzione3 = $_POST["opzione3"];

    $percorsoFoto = "../Z)FOTO/" . $foto;

    // Chiamata alla stored procedure "NuovaDomandaChiusa" con i dati forniti
    $stmt = $conn->prepare("CALL NuovaDomandaChiusa(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Preparazione dello statement fallita: " . $conn->error);
    }

    // Bind dei parametri dello statement
    $stmt->bind_param("ssssissss", $email, $ruolo, $dominio, $testo, $punteggio, $percorsoFoto, $opzione1, $opzione2, $opzione3);

    // Esecuzione dello statement
    if (!$stmt->execute()) {
        die("Errore durante l'inserimento della domanda: " . $stmt->error);
    }

    // Chiusura dello statement
    $stmt->close();

    // Reindirizzamento alla pagina CreaDomanda.php con messaggio di successo
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Location: ../PagineSito/CreaDomanda.php?success=1");
    exit();
} else {
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Location: ../PagineSito/CreaDomanda.php?success=0");    
}

$conn->close();
?>
