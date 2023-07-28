<?php
session_start();
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if (isset($_POST['invia'])) {
    // Verifica se sono stati selezionati dei domini
    if (isset($_POST['sondaggi'])) {
        // Recupera i valori selezionati delle checkbox
        $dominiSelezionati = $_POST['sondaggi'];

        // Esegui la stored procedure con i valori selezionati
        $dominiString = implode(",", $dominiSelezionati);

        // Prepara la stored procedure
        $stmt = $conn->prepare("CALL AggiornaDomini(?, ?)");
        $stmt->bind_param("ss", $_SESSION['email'], $dominiString);

        // Esegui la stored procedure
        if ($stmt->execute()) {
            // Chiudi la connessione
            $stmt->close();
            // Reindirizzamento alla pagina precedente
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Chiudi la connessione
            $stmt->close();
            // Reindirizzamento a una pagina di errore
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Location: ../ErrorPage.php");
            exit();
        }
    } else {
        echo "Nessun dominio selezionato.";
    }
}

$conn->close();
?>
