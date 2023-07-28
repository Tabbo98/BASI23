<!DOCTYPE html>
<html>
<head>
    <title>GIOCA!</title>
    <link rel="stylesheet" href="../CSSPagineWeb/Gioco.css">
</head>
<body>
<?php
// Avvio sessione, connessione al database 
session_start();
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'utente è autenticato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

// Verifica se l'email e la listaIdDomande sono presenti nella sessione
if (isset($_SESSION['email'], $_SESSION['listaIdDomande'])) {
    $email = $_SESSION['email'];
    $listaIdDomande = $_SESSION['listaIdDomande'];

    // Mescola randomicamente la listaIdDomande
    shuffle($listaIdDomande);

    // Variabile per verificare se tutte le domande sono state risposte
    $tutteDomandeRisposte = true;

    foreach ($listaIdDomande as $idDomanda) {
        // Verifica se l'idDomanda è presente nella tabella Chiusa e Opzione
        $queryChiusa = "SELECT id FROM Chiusa WHERE id = ?";
        $stmtChiusa = $conn->prepare($queryChiusa);
        $stmtChiusa->bind_param("i", $idDomanda);
        $stmtChiusa->execute();
        $resultChiusa = $stmtChiusa->get_result();

        if ($resultChiusa->num_rows > 0) {
            // La domanda è chiusa, recupera i dati dalla tabella "Domanda" e "Opzione"
            $queryDomanda = "SELECT testo, foto, punteggio FROM Domanda WHERE id = ?";
            $stmtDomanda = $conn->prepare($queryDomanda);
            $stmtDomanda->bind_param("i", $idDomanda);
            $stmtDomanda->execute();
            $resultDomanda = $stmtDomanda->get_result();
            $domanda = $resultDomanda->fetch_assoc();

            // Recupera le opzioni dalla tabella "Opzione"
            $queryOpzioni = "SELECT testo FROM Opzione WHERE id = ?";
            $stmtOpzioni = $conn->prepare($queryOpzioni);
            $stmtOpzioni->bind_param("i", $idDomanda);
            $stmtOpzioni->execute();
            $resultOpzioni = $stmtOpzioni->get_result();
            $opzioni = array();

            while ($rowOpzione = $resultOpzioni->fetch_assoc()) {
                $opzioni[] = $rowOpzione['testo'];
            }

            // Mostra la domanda chiusa e le opzioni per la risposta
            echo '<div class="domanda">';
            echo '<h2>Domanda Chiusa</h2>';
            echo '<p>' . $domanda['testo'] . '</p>';
            echo '<img src="' . $domanda['foto'] . '" alt="Foto Domanda" width="250">';
            echo '<form method="post">';
            foreach ($opzioni as $opzione) {
                echo '<input type="radio" name="rispostaDomanda' . $idDomanda . '" value="' . $opzione . '">' . $opzione . '<br>';
            }
            echo '<input type="hidden" name="idDomanda" value="' . $idDomanda . '">';
            echo '<input type="submit" name="submitRisposta" value="Invia Risposta">';
            echo '</form>';
            echo '</div>';
        } else {
            // La domanda è aperta, recupera i dati dalla tabella "Domanda" e "Aperta"
            $queryDomanda = "SELECT testo, foto, punteggio FROM Domanda WHERE id = ?";
            $stmtDomanda = $conn->prepare($queryDomanda);
            $stmtDomanda->bind_param("i", $idDomanda);
            $stmtDomanda->execute();
            $resultDomanda = $stmtDomanda->get_result();
            $domanda = $resultDomanda->fetch_assoc();

            // Recupera il numero massimo di caratteri dalla tabella "Aperta"
            $queryAperta = "SELECT maxCaratteri FROM Aperta WHERE id = ?";
            $stmtAperta = $conn->prepare($queryAperta);
            $stmtAperta->bind_param("i", $idDomanda);
            $stmtAperta->execute();
            $resultAperta = $stmtAperta->get_result();
            $aperta = $resultAperta->fetch_assoc();

            // Mostra la domanda aperta e il campo di input per la risposta
            echo '<div class="domanda">';
            echo '<h2>Domanda Aperta</h2>';
            echo '<p>' . $domanda['testo'] . '</p>';
            echo '<img src="' . $domanda['foto'] . '" alt="Foto Domanda" width="250">';
            echo '<form method="post">';
            echo '<textarea name="rispostaDomanda' . $idDomanda . '" maxlength="' . $aperta['maxCaratteri'] . '"></textarea><br>';
            echo '<input type="hidden" name="idDomanda" value="' . $idDomanda . '">';
            echo '<input type="submit" name="submitRisposta" value="Invia Risposta">';
            echo '</form>';
            echo '</div>';
        }

        // Verifica se la risposta alla domanda è stata inviata
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submitRisposta']) && $_POST['idDomanda'] === $idDomanda) {
            if (isset($_POST['rispostaDomanda' . $idDomanda])) {
                // La risposta è stata inviata, esegui la stored procedure appropriata
                $risposta = $_POST['rispostaDomanda' . $idDomanda];

                if ($resultChiusa->num_rows > 0) {
                    // La domanda è chiusa, esegui la stored procedure RispostaDomandaChiusa
                    $stmtRispostaChiusa = $conn->prepare("CALL RispostaDomandaChiusa(?, ?, ?, ?)");
                    $stmtRispostaChiusa->bind_param("sisi", $email, $idDomanda, $risposta, $domanda['punteggio']);
                    $stmtRispostaChiusa->execute();
                    $stmtRispostaChiusa->close();
                } else {
                    // La domanda è aperta, esegui la stored procedure RispostaDomandaAperta
                    $stmtRispostaAperta = $conn->prepare("CALL RispostaDomandaAperta(?, ?, ?, ?)");
                    $stmtRispostaAperta->bind_param("sisi", $email, $idDomanda, $risposta, $domanda['punteggio']);
                    $stmtRispostaAperta->execute();
                    $stmtRispostaAperta->close();
                }

                // Imposta la variabile $tutteDomandeRisposte a false per indicare che non tutte le domande sono state risposte
                $tutteDomandeRisposte = false;
            }
        }
    }

    // Verifica se tutte le domande sono state risposte
    if ($tutteDomandeRisposte) {
        // Reindirizza alla pagina "InvitoSondaggio.php" con il parametro "success=1"
        header("Location: ../PagineSito/InvitoSondaggio.php?success=1");
        exit();
    }
} else {
    echo "Email o lista di domande non forniti.";
}

$conn->close();
?>
</body>
</html>