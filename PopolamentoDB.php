<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Sondaggi23";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$file = $_FILES['file'];

if ($file) {
    $filename = $file['tmp_name'];

    // Variabili per salvare i dati
    $dominio = "";
    $codice = 0;
    $domande = [];
    $testo = "";
    $opzioneA = "";
    $opzioneB = "";
    $opzioneC = "";

    // Lettura del file riga per riga
    $fileHandle = fopen($filename, "r");
    if ($fileHandle) {
        // Lettura della prima riga
        $primaRiga = fgets($fileHandle);
        $primaRiga = trim($primaRiga);

        if ($primaRiga === "DOMANDE APERTE") {
            while (($riga = fgets($fileHandle)) !== false) {
                $riga = trim($riga);

                // Controllo se la riga contiene il valore di "dominio" e "codice"
                if (!empty($riga) && strpos($riga, ',') !== false) {
                    list($dominio, $codice) = explode(',', $riga);
                    $dominio = trim($dominio);
                    $codice = trim($codice);
                }
                // Controllo se la riga contiene una domanda
                elseif (!empty($riga)) {
                    $domande[] = $riga;
                }
                // Controllo se la riga vuota segna la fine delle domande
                elseif (empty($riga) && !empty($dominio) && $codice > 0) {
                    // Inserimento delle domande nella tabella Domanda
                    foreach ($domande as $domanda) {
                        $domanda = mysqli_real_escape_string($conn, $domanda);
                        $query = "INSERT INTO Domanda (testo) VALUES ('$domanda')";
                        mysqli_query($conn, $query);
                        $domande_ids[] = mysqli_insert_id($conn);
                    }

                    // Inserimento dei dati nella tabella Contenuto
                    foreach ($domande_ids as $domanda_id) {
                        $query = "INSERT INTO Contenuto (codice, dominio, id) VALUES ('$codice', '$dominio', '$domanda_id')";
                        mysqli_query($conn, $query);
                    }

                    // Inserimento dei dati nella tabella Aperta
                    foreach ($domande_ids as $domanda_id) {
                        $query = "INSERT INTO Aperta (id) VALUES ('$domanda_id')";
                        mysqli_query($conn, $query);
                    }

                    // Reset delle variabili per il prossimo set di domande
                    $dominio = "";
                    $codice = 0;
                    $domande = [];
                }
            }
        } elseif ($primaRiga === "DOMANDE CHIUSE") {

// Lettura del file riga per riga
$righe = file($file['tmp_name']);
foreach ($righe as $riga) {
    $riga = trim($riga);
    if ($riga === "FINE DOMANDE") {
        // Salvataggio dell'ultima domanda
        if (!empty($dominio) && $codice > 0) {
            // Inserimento dei dati nel database
            $testo = mysqli_real_escape_string($conn, $testo);
            $opzioneA = mysqli_real_escape_string($conn, $opzioneA);
            $opzioneB = mysqli_real_escape_string($conn, $opzioneB);
            $opzioneC = mysqli_real_escape_string($conn, $opzioneC);

            // Inserimento della domanda nel database
            $query = "INSERT INTO Domanda (testo) VALUES ('$testo')";
            mysqli_query($conn, $query);
            $domanda_id = mysqli_insert_id($conn);

            // Inserimento dei dati nella tabella Contenuto
            $query = "INSERT INTO Contenuto (codice, dominio, id) VALUES ('$codice', '$dominio', '$domanda_id')";
            mysqli_query($conn, $query);

            // Inserimento dei dati nella tabella Chiusa
            $query = "INSERT INTO Chiusa (id, opzioneA, opzioneB, opzioneC) VALUES ('$domanda_id', '$opzioneA', '$opzioneB', '$opzioneC')";
            mysqli_query($conn, $query);

            // Reset delle variabili per la prossima domanda
            $dominio = "";
            $codice = 0;
            $testo = "";
            $opzioneA = "";
            $opzioneB = "";
            $opzioneC = "";
        }
        break; // Esci dal ciclo
    } elseif ($riga === "DOMANDE CHIUSE") {
        // Salta la riga "DOMANDE CHIUSE"
        continue;
    } elseif (!empty($riga) && strpos($riga, ',') !== false) {
        // Controllo se la riga contiene il valore di "dominio" e "codice"
        list($dominio, $codice) = explode(',', $riga);
        $dominio = trim($dominio);
        $codice = intval(trim($codice));
    } elseif (!empty($riga)) {
        // Controllo se la riga contiene il testo della domanda
        $testo = $riga;
    } elseif (empty($riga) && !empty($dominio) && $codice > 0) {
        // Controllo se la riga vuota segna la fine delle opzioni
        // Inserimento dei dati nel database
        $testo = mysqli_real_escape_string($conn, $testo);
        $opzioneA = mysqli_real_escape_string($conn, $opzioneA);
        $opzioneB = mysqli_real_escape_string($conn, $opzioneB);
        $opzioneC = mysqli_real_escape_string($conn, $opzioneC);

        // Inserimento della domanda nel database
        $query = "INSERT INTO Domanda (testo) VALUES ('$testo')";
        mysqli_query($conn, $query);
        $domanda_id = mysqli_insert_id($conn);

        // Inserimento dei dati nella tabella Contenuto
        $query = "INSERT INTO Contenuto (codice, dominio, id) VALUES ('$codice', '$dominio', '$domanda_id')";
        mysqli_query($conn, $query);

        // Inserimento dei dati nella tabella Chiusa
        $query = "INSERT INTO Chiusa (id, opzioneA, opzioneB, opzioneC) VALUES ('$domanda_id', '$opzioneA', '$opzioneB', '$opzioneC')";
        mysqli_query($conn, $query);

        // Reset delle variabili per la prossima domanda
        $testo = "";
        $opzioneA = "";
        $opzioneB = "";
        $opzioneC = "";
    } elseif (empty($riga) && !empty($testo)) {
        // Controllo se la riga vuota segna la fine del testo della domanda
        continue; // Salta la riga vuota
    } elseif (!empty($riga)) {
        // Controllo se la riga contiene una delle opzioni
        $optionIdentifier = substr($riga, 0, 2);
        $optionText = trim(substr($riga, 2));
        if ($optionIdentifier === "a)") {
            $opzioneA = $optionText;
        } elseif ($optionIdentifier === "b)") {
            $opzioneB = $optionText;
        } elseif ($optionIdentifier === "c)") {
            $opzioneC = $optionText;
        }
    }
} 

        } else {
            echo '<script>alert("IL FILE NON RISPETTA LE CARATTERISTICHE NECESSARIE"); window.location.href = "../PagineSito/ProfiloUtPremium.php";</script>';
            exit();
        }

        fclose($fileHandle);
    } else {
        echo '<script>alert("IMPOSSIBILE APRIRE IL FILE"); window.location.href = "../PagineSito/ProfiloUtPremium.php";</script>';
    }
} else {
    echo '<script>alert("NESSUN FILE CARICATO"); window.location.href = "../PagineSito/ProfiloUtPremium.php";</script>';
}

// Chiusura della connessione al database
$conn->close();

?>
