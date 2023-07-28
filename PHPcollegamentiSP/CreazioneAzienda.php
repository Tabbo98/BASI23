<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se il form è stato sottoposto tramite il metodo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Acquisizione dei valori forniti dall'utente tramite il metodo POST
    $email = $_POST['email'];
    $codFiscale = $_POST['codFiscale'];
    $nome = $_POST['nome'];
    $sede = $_POST['sede'];
    $password = $_POST['password'];

    // Creazione dello statement
    $stmt = $conn->prepare("CALL InserisciAzienda(?, ?, ?, ?, ?)");

    // Bind dei parametri
    $stmt->bind_param("sssss",$email, $codFiscale, $nome, $password, $sede);

    if ($stmt->execute()) {

        // Reindirizzamento a un'altra pagina con messaggio di successo
        $message = "Azienda inserita con successo";
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/ProfiloAzienda.php?message=" . urlencode($message));
        $stmt->close();
        exit();
    } else {
        // Reindirizzamento a un'altra pagina con messaggio di errore
        $error = "Errore durante l'inserimento dell'azienda: " . $stmt->error;
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