<?php
// Verifica se è stato inviato il modulo
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['sondaggio']) && isset($_GET['utente'])) {
    // Recupera i dati selezionati dal modulo
    $sondaggioSelezionato = $_GET['sondaggio'];
    $utenteSelezionato = $_GET['utente'];

    // Divide il valore del sondaggio in dominio e codice
    list($codice, $dominio) = explode('|', $sondaggioSelezionato);

    // Esegui la connessione al database
    $conn = new mysqli("localhost", "root", "", "Sondaggi23");

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Chiama la stored procedure con i parametri selezionati
    $stmt = $conn->prepare("CALL AggiungiInvito(?, ?, ?)");
    $stmt->bind_param("sss", $codice, $dominio, $utenteSelezionato);
    $stmt->execute();
    $stmt->close(); 

    // Reindirizza alla pagina successiva con un messaggio di conferma
    header("Location: ../PagineSito/InvitoSondaggio.php?messaggio_testo=INVITO+INVIATO");
    exit();
}
$conn->close();
?>
