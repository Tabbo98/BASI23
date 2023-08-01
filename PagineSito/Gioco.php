<!-- Gioco.php -->
<!DOCTYPE html>
<html>
<head>
    <title>GIOCA!</title>
    <link rel="stylesheet" href="../CSSPagineWeb/Gioco.css">
</head>
<body>
<?php
// Avvio sessione, connessione al database, verifica della connessione e verifica se l'utente è autenticato
session_start();
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

// Verifica se "email" e "listaIdDomande" sono presenti nella sessione, se sì le salva in variabili
if (isset($_SESSION['email'], $_SESSION['listaIdDomande'])) {
    $email = $_SESSION['email'];
    $listaIdDomande = $_SESSION['listaIdDomande'];

    // Mescola la lista di ID
    shuffle($listaIdDomande);
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST['submitRispostaChiusa'])) {
            // La risposta alla domanda chiusa è stata inviata, esegui la stored procedure RispostaDomandaChiusa
            $risposta = $_POST['rispostaDomanda'];
            $idDomanda = $_POST['idDomanda'];
            $punteggio = $_POST['punteggioDomanda'];
            $opzioni = $_POST['opzioni'];
            $opzione2 = isset($opzioni[1]) ? $opzioni[1] : '';
            $opzione3 = isset($opzioni[2]) ? $opzioni[2] : '';
    
            $stmtRispostaChiusa = $conn->prepare("CALL RispostaDomandaChiusa(?, ?, ?, ?, ?, ?)");
            $stmtRispostaChiusa->bind_param("sisiss", $email, $idDomanda, $risposta, $punteggio, $opzione2, $opzione3);
            $stmtRispostaChiusa->execute();
            $stmtRispostaChiusa->close();
            $risposta = null;
            $idDomanda = null;
            $punteggio = null;
        } elseif (isset($_POST['submitRispostaAperta'])) {
            // La risposta alla domanda aperta è stata inviata, esegui la stored procedure RispostaDomandaAperta
            $risposta = $_POST['rispostaDomanda'];
            $idDomanda = $_POST['idDomanda'];
            $punteggio = $_POST['punteggioDomanda'];
    
            $stmtRispostaAperta = $conn->prepare("CALL RispostaDomandaAperta(?, ?, ?, ?)");
            $stmtRispostaAperta->bind_param("sisi", $email, $idDomanda, $risposta, $punteggio);
            $stmtRispostaAperta->execute();
            $stmtRispostaAperta->close();
            $risposta = null;
            $idDomanda = null;
            $punteggio = null;
        }
    
        // Rimuovi la domanda corrente dalla lista delle domande da visualizzare
        $index = array_search($idDomanda, $listaIdDomande);
        if ($index !== false) {
            unset($listaIdDomande[$index]);
        }
    }

    // Visualizza la domanda corrente
    if (!empty($listaIdDomande)) {
        $currentQuestionId = reset($listaIdDomande);
        $queryChiusa = "SELECT id FROM Chiusa WHERE id = ?";
        $stmtChiusa = $conn->prepare($queryChiusa);
        $stmtChiusa->bind_param("i", $currentQuestionId);
        $stmtChiusa->execute();
        $resultChiusa = $stmtChiusa->get_result();

        if ($resultChiusa->num_rows > 0) {
            // La domanda è chiusa, recupera i dati dalla tabella "Domanda" e "Opzione"
            $queryDomanda = "SELECT testo, foto, punteggio FROM Domanda WHERE id = ?";
            $stmtDomanda = $conn->prepare($queryDomanda);
            $stmtDomanda->bind_param("i", $currentQuestionId);
            $stmtDomanda->execute();
            $resultDomanda = $stmtDomanda->get_result();
            $domanda = $resultDomanda->fetch_assoc();

            // Recupera le opzioni dalla tabella "Opzione"
            $queryOpzioni = "SELECT testo FROM Opzione WHERE id = ?";
            $stmtOpzioni = $conn->prepare($queryOpzioni);
            $stmtOpzioni->bind_param("i", $currentQuestionId);
            $stmtOpzioni->execute();
            $resultOpzioni = $stmtOpzioni->get_result();
            $opzioni = array();

            while ($rowOpzione = $resultOpzioni->fetch_assoc()) {
                $opzioni[] = $rowOpzione['testo'];
            }

            // Visualizza la domanda chiusa e le opzioni per la risposta
            echo '<div class="domanda">';
            echo '<h2>Domanda Chiusa</h2>';
            echo '<p>' . $domanda['testo'] . '</p>';
            echo '<img src="' . $domanda['foto'] . '" alt="Foto Domanda" width="250">';
            echo '<form method="post">';
            foreach ($opzioni as $opzione) {
                echo '<input type="radio" name="rispostaDomanda" value="' . $opzione . '"> ' . $opzione . '<br>';
            }
            // Aggiungi gli input hidden per l'invio delle opzioni
            echo '<input type="hidden" name="opzioni[]" value="' . implode('|', $opzioni) . '">';
            echo '<input type="hidden" name="idDomanda" value="' . $currentQuestionId . '">';
            echo '<input type="hidden" name="punteggioDomanda" value="' . $domanda['punteggio'] . '">';
            echo '<input type="submit" name="submitRispostaChiusa" value="Invia Risposta Domanda Chiusa">';
            echo '</form>';
            echo '</div>';
        } else {
            // La domanda è aperta, recupera i dati dalla tabella "Domanda" e "Aperta"
            $queryDomanda = "SELECT testo, foto, punteggio FROM Domanda WHERE id = ?";
            $stmtDomanda = $conn->prepare($queryDomanda);
            $stmtDomanda->bind_param("i", $currentQuestionId);
            $stmtDomanda->execute();
            $resultDomanda = $stmtDomanda->get_result();
            $domanda = $resultDomanda->fetch_assoc();

            // Recupera il numero massimo di caratteri dalla tabella "Aperta"
            $queryAperta = "SELECT maxCaratteri FROM Aperta WHERE id = ?";
            $stmtAperta = $conn->prepare($queryAperta);
            $stmtAperta->bind_param("i", $currentQuestionId);
            $stmtAperta->execute();
            $resultAperta = $stmtAperta->get_result();
            $aperta = $resultAperta->fetch_assoc();

            // Visualizza la domanda aperta e il campo di input per la risposta
            echo '<div class="domanda">';
            echo '<h2>Domanda Aperta</h2>';
            echo '<p>' . $domanda['testo'] . '</p>';
            echo '<img src="' . $domanda['foto'] . '" alt="Foto Domanda" width="250">';
            echo '<form method="post">';
            echo '<textarea name="rispostaDomanda" maxlength="' . $aperta['maxCaratteri'] . '"></textarea><br>';
            echo '<input type="hidden" name="idDomanda" value="' . $currentQuestionId . '">';
            echo '<input type="hidden" name="punteggioDomanda" value="' . $domanda['punteggio'] . '">';
            echo '<input type="submit" name="submitRispostaAperta" value="Invia Risposta Domanda Aperta">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        // Reindirizza l'utente alla pagina di completamento dopo aver risposto a tutte le domande
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/InvitoSondaggio.php?SONDAGGIO+COMPLETATO+;)");
        exit();
    }
} else {
    echo "Email o lista di domande non forniti.";
}

$conn->close();
?>
</body>
</html>
