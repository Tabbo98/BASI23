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

    // Rimuovi i parametri dalla sessione se non sono più necessari
    unset($_SESSION['crea_sondaggio_params']);

    // Connessione al database
    $conn = new mysqli("localhost", "root", "", "Sondaggi23");

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    if ($ruolo === "azienda") {
        // Chiamata alla stored procedure per il ruolo "azienda" con i dati comuni
        $stmt = $conn->prepare("CALL CreazioneSondaggioAzienda(?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Preparazione dello statement fallita: " . $conn->error);
        }

        // Bind dei parametri dello statement comuni
        $stmt->bind_param("sssis",  $dominio, $descrizione, $titolo, $maxUtenti, $email);

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

// Recupero dei record utente casuali dalla tabella "Interesse" con il dominio corrispondente
$randomUsersQuery = "SELECT DISTINCT email FROM Interesse WHERE dominio = ? ORDER BY RAND() LIMIT ?";
$stmt = $conn->prepare($randomUsersQuery);
if (!$stmt) {
    die("Preparazione dello statement fallita: " . $conn->error);
}

        // Bind dei parametri dello statement per il dominio e il numero massimo di utenti
        $stmt->bind_param("si", $dominio, $maxUtenti);

        // Esecuzione dello statement
        if ($stmt->execute()) {
            $randomUsersResult = $stmt->get_result();

            // Ciclo per chiamare la stored procedure "AggiungiInvito" per ogni utente selezionato casualmente
            while ($row = $randomUsersResult->fetch_assoc()) {
                $utenteEmail = $row['email'];

                // Chiamata alla stored procedure "AggiungiInvito" per il singolo utente
                $stmtInvito = $conn->prepare("CALL AggiungiInvito(?, ?, ?)");
                if (!$stmtInvito) {
                    die("Preparazione dello statement fallita: " . $conn->error);
                }

                // Bind dei parametri dello statement per il singolo utente
                $stmtInvito->bind_param("iss", $codice, $dominio, $utenteEmail);

                // Esecuzione dello statement
                if ($stmtInvito->execute()) {
                    echo "Invito inviato a: " . $utenteEmail . "<br>";
                } else {
                    echo "Errore durante l'invio dell'Invito" . $stmtInvito->error . "<br>";
                    // Reindirizzamento a un'altra pagina con messaggio di errore
                    $error = "Errore durante l'inserimento dell'Invito: " . $stmtInvito->error;
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    header("Location: ../ErrorPage.php?error=" . urlencode($error));
                    $stmtInvito->close();
                    exit();
                }

                // Chiusura dello statement
                $stmtInvito->close();
            }
        } else {
            echo "Errore durante il recupero degli utenti casuali: " . $stmt->error;
        }

        // Chiusura dello statement
        $stmt->close();


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
    die("Parametri mancanti.");
}
?>