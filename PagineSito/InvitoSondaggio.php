<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Titolo della pagina</title>
    <link rel="stylesheet" href="../CSSPagineWeb/InvitoSondaggio.css">
</head>
<body>
<?php
session_start();
// Connessione al database
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

// Verifica se è stato inviato il modulo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invita'])) {
    // Recupera il valore selezionato dalla radio button del sondaggio
    $sondaggioSelezionato = $_POST['sondaggio'];

    // Recupera il valore selezionato dalla radio button dell'utente
    $utenteSelezionato = $_POST['utente'];

    // Redirect alla pagina successiva con i dati selezionati
    header("Location: ../PHPcollegamentiSP/SpedisciInvito.php?sondaggio=" . $sondaggioSelezionato . "&utente=" . $utenteSelezionato);
    exit();
}

// Bottone "HOME"
echo '<a href="../Home.php"><button><i class="fa fa-home"></i> HOME</button></a>';

$emailUtenteLoggato = $_SESSION['email'];

// Creazione della vista filtrata degli inviti
$sqlCreateView = "CREATE OR REPLACE VIEW InvitiSospesi AS
    SELECT id, codice, dominio, email
    FROM Invito
    WHERE email = '$emailUtenteLoggato' AND esito = 'invitato'";
$conn->query($sqlCreateView);

// Recupero dei dati dalla vista
$sqlInviti = "SELECT id, codice, dominio FROM InvitiSospesi";
$resultInviti = $conn->query($sqlInviti);

// Creazione della tabella degli inviti
echo '<h2>Inviti Ricevuti:</h2>';
echo '<table>';
echo '<tr><th>ID</th><th>Codice Sondaggio</th><th>Dominio Sondaggio</th><th>Accetta</th><th>Rifiuta</th></tr>';

if ($resultInviti->num_rows > 0) {
    while ($row = $resultInviti->fetch_assoc()) {
        $idInvito = $row['id'];
        $codiceSondaggio = $row['codice'];
        $dominioSondaggio = $row['dominio'];

        echo '<tr>';
        echo '<td>' . $idInvito . '</td>';
        echo '<td>' . $codiceSondaggio . '</td>';
        echo '<td>' . $dominioSondaggio . '</td>';
        echo '<td><a href="../PHPcollegamentiSP/AccettaInvito.php?id=' . $idInvito . '">ACCETTA</a></td>';
        echo '<td><a href="../PHPcollegamentiSP/RifiutaInvito.php?id=' . $idInvito . '">RIFIUTA</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">Nessun invito ricevuto.</td></tr>';
}

echo '</table>';

// Eliminazione della vista dopo averla utilizzata
$sqlDropView = "DROP VIEW IF EXISTS InvitiNonAccettatiRifiutati";
$conn->query($sqlDropView);

// Form con radio button per i dati del sondaggio e dell'utente
echo '<form action="InvitoSondaggio.php" method="POST">';
echo '<h2>Scegli un Sondaggio:</h2>';

// Recupera i dati dei sondaggi dalla tabella "Sondaggio"
$sql = "SELECT codice, dominio, maxUtenti FROM Sondaggio";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $codice = $row['codice'];
        $dominio = $row['dominio'];
        $maxUtenti = $row['maxUtenti'];

        // Verifica il numero di inviti accettati per il sondaggio corrente
        $sqlCount = "SELECT COUNT(*) AS num_inviti_accettati FROM Invito WHERE codice = '$codice' AND dominio = '$dominio' AND esito = 'accettato'";
        $resultCount = $conn->query($sqlCount);
        $rowCount = $resultCount->fetch_assoc();
        $numInvitiAccettati = $rowCount['num_inviti_accettati'];

        // Controlla se il numero di inviti accettati è inferiore a maxUtenti
        if ($numInvitiAccettati < $maxUtenti) {
            echo '<input type="radio" name="sondaggio" value="' . $codice . '|' . $dominio . '">' . $codice . ' (' . $dominio . ')<br>';
        }
    }
}

echo '<br>';

echo '<h2>Scegli un Utente:</h2>';

// Recupera i dati degli utenti dalla tabella "Utente"
$sql = "SELECT email, nome FROM Utente WHERE email <> '$emailUtenteLoggato'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $nome = $row['nome'];
        echo '<input type="radio" name="utente" value="' . $email . '">' . $nome . ' (' . $email . ')<br>';
    }
}

echo '<br>';
echo '<input type="submit" name="invita" value="INVITA!">';
echo '</form>';


$conn->close();
?>
</body>
</html>
