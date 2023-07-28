<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se il form è stato sottoposto tramite il metodo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Acquisizione dei valori forniti dall'utente tramite il metodo POST
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $anno = $_POST['anno'];
    $luogoNascita = $_POST['luogoNascita'];


    // Creazione dello statement
    $stmt = $conn->prepare("CALL CreazioneUtente(?, ?, ?, ?, ?,?)");

    // Bind dei parametri
    $stmt->bind_param("ssssis", $email, $password, $nome, $cognome, $anno, $luogoNascita);
 
    if ($stmt->execute()) {

        // Reindirizzamento a un'altra pagina con messaggio di successo
        $message = "Utente generico inserito con successo";
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/AccediRegistrati.php?message=" . urlencode($message));
        $stmt->close();
        exit();
    } else {
        // Reindirizzamento a un'altra pagina con messaggio di errore
        $error = "Errore durante l'inserimento dell'utente generico: " . $stmt->error;
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/AccediRegistrati.php?error=" . urlencode($error));
        $stmt->close();
        exit();
    }
}

// Chiusura della connessione
$conn->close();
?>
