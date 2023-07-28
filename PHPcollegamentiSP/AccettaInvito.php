<?php
session_start();
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'ID dell'invito è stato fornito come parametro
if (isset($_GET['id'])) {
    // Recupero id
    $idInvito = $_GET['id'];

    //Recupero "codice" e "dominio" dalla tabella "Invito" dato "$idInvito"
    $stmt = $conn->prepare("SELECT codice, dominio FROM Invito WHERE id = ?");
    if (!$stmt) {
        die("Preparazione dello statement fallita: " . $conn->error);
    }

    // Bind 
    $stmt->bind_param("i", $idInvito);

    if (!$stmt->execute()) {
        die("Errore durante l'esecuzione dello statement: " . $stmt->error);
    }

    //risultati
    $stmt->bind_result($codice, $dominio);
    $stmt->fetch();
    $stmt->close();

    // Recupero "maxUtenti" dalla tabella "Sondaggio" dati "codice" e "dominio"
    $stmt = $conn->prepare("SELECT maxUtenti FROM Sondaggio WHERE codice = ? AND dominio = ?");
    if (!$stmt) {
        die("Preparazione dello statement fallita: " . $conn->error);
    }

    // Bind
    $stmt->bind_param("ss", $codice, $dominio);

    if (!$stmt->execute()) {
        die("Errore durante l'esecuzione dello statement: " . $stmt->error);
    }

    // risultato
    $stmt->bind_result($maxUtenti);
    $stmt->fetch();
    $stmt->close();

    //Verifica se il numero di record con attributo "esito" uguale a "accettato" nella tabella Invito è uguale o superiore a $maxUtenti
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Invito WHERE codice = ? AND dominio = ? AND esito = 'accettato'");
    if (!$stmt) {
        die("Preparazione dello statement fallita: " . $conn->error);
    }

    // Bind
    $stmt->bind_param("ss", $codice, $dominio);

    if (!$stmt->execute()) {
        die("Errore durante l'esecuzione dello statement: " . $stmt->error);
    }

    //risultati
    $stmt->bind_result($numInvitiAccettati);
    $stmt->fetch();
    $stmt->close();

    // Se il numero di inviti accettati è uguale o superiore a $maxUtenti
    if ($numInvitiAccettati >= $maxUtenti) {
        //rifiuto invito
        $stmt = $conn->prepare("CALL RifiutaInvito(?)");
        if (!$stmt) {
            die("Preparazione dello statement fallita: " . $conn->error);
        }
        $stmt->bind_param("i", $idInvito);
        $stmt->execute();
        $stmt->close();

        //chiusura sondaggio, max utenti raggiunto
        $stmt = $conn->prepare("CALL ChiudiSondaggio(?, ?)");
        if (!$stmt) {
            die("Preparazione dello statement fallita: " . $conn->error);
        }
        $stmt->bind_param("ss", $codice, $dominio);
        $stmt->execute();
        $stmt->close();

        //reindirizzamento
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/InvitoSondaggio.php?messaggio_testo=MAX+UTENTI+RAGGIUNTO");
        exit();
    }

    //Se il numero di inviti accettati è inferiore a $maxUtenti
    $stmt = $conn->prepare("CALL AccettaInvito(?)");
    if (!$stmt) {
        die("Preparazione dello statement fallita: " . $conn->error);
    }

    // Bind
    $stmt->bind_param("i", $idInvito);

    if ($stmt->execute()) {
        $stmt->close();
        // Comportamento per l'aggiornamento "accettato"
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PHPcollegamentiSP/PreparazioneQuiz.php?idInvito=" . $idInvito);          
        exit();
    } else {
        // Comportamento per un errore
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../ErrorPage.php");
        $stmt->close();
        exit();
    }
} else {
    echo "ID dell'invito non fornito.";
}

$conn->close();
?>
