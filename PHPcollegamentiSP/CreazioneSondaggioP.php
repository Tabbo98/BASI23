<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

// Verifica se i parametri sono presenti nella sessione
if (isset($_SESSION['crea_sondaggio_params'])) {
    // Recupera i parametri dalla sessione
    $params = $_SESSION['crea_sondaggio_params'];

    // Esegui le operazioni necessarie con i parametri
    $email = $params['email'];
    $ruolo = $params['ruolo'];
    $dominio = $params['dominio'];
    $descrizione = $params['descrizione'];
    $titolo = $params['titolo'];
    $maxUtenti = $params['maxUtenti'];
    $utenti = $params['utenti'];

    // Rimuovi i parametri dalla sessione se non sono più necessari
    unset($_SESSION['crea_sondaggio_params']);

    // Connessione al database
    $conn = new mysqli("localhost", "root", "", "Sondaggi23");

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    if ($ruolo === "premium") {
        // Chiamata alla stored procedure per il ruolo "premium" con i dati comuni
        $stmt = $conn->prepare("CALL CreazioneSondaggioPremium(?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Preparazione dello statement fallita: " . $conn->error);
        }

        // Bind dei parametri dello statement comuni
        $stmt->bind_param("sssis", $dominio, $descrizione, $titolo, $maxUtenti, $email);

        // Esecuzione dello statement
        if (!$stmt->execute()) {
            die("Errore durante la creazione del sondaggio: " . $stmt->error);
        }

        // Recupero del codice del sondaggio appena creato
        $codiceResult = $conn->query("SELECT LAST_INSERT_ID() AS codice");
        $codiceRow = $codiceResult->fetch_assoc();
        $codice = $codiceRow['codice'];

        // Chiusura dello statement
        $stmt->close();

        // Ciclo per chiamare la stored procedure "AggiungiInvito" per ogni utente selezionato
        foreach ($utenti as $mailUtente) {
            // Chiamata alla stored procedure "AggiungiInvito" per il singolo utente
            $stmt = $conn->prepare("CALL AggiungiInvito(?, ?, ?)");
            if (!$stmt) {
                die("Preparazione dello statement fallita: " . $conn->error);
            }

            // Bind dei parametri dello statement per il singolo utente
            $stmt->bind_param("iss", $codice, $dominio, $mailUtente);

            // Esecuzione dello statement
            if ($stmt->execute()) {
                echo "Invito inviato a: " . $mailUtente . "<br>";
            } else {
                echo "Errore durante l'invio dell'Invito a: " . $mailUtente . " - " . $stmt->error . "<br>";
                // Reindirizzamento a un'altra pagina con messaggio di errore
                $error = "Errore durante l'inserimento dell'Invito: " . $stmt->error;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Location: ../ErrorPage.php?error=" . urlencode($error));
                $stmt->close();
                exit();
            }
            $stmt->close();
        }

        // Chiusura della connessione
        $conn->close();

        // Reindirizzamento alla pagina di successo
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/CreaSondaggio.php?email=" . urlencode($email) . "&ruolo=" . urlencode($ruolo));
        exit();
    } else {
        // Chiusura della connessione
        $conn->close();

        // Ruolo non riconosciuto
        die("Ruolo non valido.");
    }
} else {
    die("Parametri mancanti nella sessione.");
}
?>