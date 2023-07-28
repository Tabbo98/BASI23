<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'ID dell'invito è stato fornito come parametro
if (isset($_GET['id'])) {
    // Recupera l'ID dell'invito dalla query string
    $idInvito = $_GET['id'];

    // Chiamata alla stored procedure con l'ID dell'invito
    $stmt = $conn->prepare("CALL RifiutaInvito(?)"); 
    $stmt->bind_param("i", $idInvito);
    $result = $stmt->execute(); 

    // Controllo dell'esecuzione dello statement
    if ($result) {
        $stmt->close();
        // Comportamento per l'aggiornamento "rifiutato"
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/InvitoSondaggio.php?messaggio_testo=INVITO+RIFIUTATO");
        exit();
    } else {
        $stmt->close();
        // Comportamento per l'errore nell'esecuzione dello statement
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../ErrorPage.php");
        exit();
    }

} else {
    echo "ID dell'invito non fornito.";
}
$conn->close();
?>
