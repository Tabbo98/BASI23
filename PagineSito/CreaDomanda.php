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

// Recupero email e ruolo dalla sessione
$email = $_SESSION['email'];
$ruolo = $_SESSION['ruolo'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crea Domanda</title>
    <link rel="stylesheet" href="../CSSPagineWeb/CreaDomanda.css">
</head>
<body>

<?php
// Verifica se è stato passato un parametro "success" nella query string dell'URL
if (isset($_GET['success'])) {
    // Controlla il valore del parametro "success"
    $success = $_GET['success'];

    if ($success == 1) {
        // Messaggio di successo
        echo '<script>alert("Domanda inserita con successo!");</script>';
    } elseif ($success == 0) {
        // Messaggio di errore
        echo '<script>alert("Si è verificato un errore nell\'inserimento della domanda!");</script>';
    }
}
?>

    <!-- Bottone "HOME" -->
    <a href="../Home.php"><button>HOME</button></a>

    <h1>Crea Domanda</h1>

    <!-- Bottoni per selezionare il tipo di domanda -->
    <div>
        <button id="apertaBtn">APERTA</button>
        <button id="chiusaBtn">CHIUSA</button>
    </div>

    <!-- Form per domanda APERTA -->
    <form id="apertaForm" action="../PHPcollegamentiSP/InserimentoDomandaAperta.php" method="POST" enctype="multipart/form-data">

        <!-- Recupera attributi dominio dalla tabella Sondaggio -->
        <?php
        $sql = "SELECT DISTINCT dominio FROM Sondaggio";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $dominio = $row['dominio'];
            echo '<input type="radio" name="dominio" value="' . $dominio . '">' . $dominio . '<br>';
        }
        ?>

        <div>
            <label for="testo">Testo:</label>
            <textarea name="testo" id="testo" required></textarea>
        </div>
        <div>
            <label for="punteggio">Punti:</label>
            <input type="number" name="punteggio" id="punteggio" min="1" max="100" required>
        </div>
        <div>
            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto" required>
        </div>
        <div>
            <label for="maxCaratteri">Massimo caratteri risposta:</label>
            <input type="number" name="maxCaratteri" id="maxCaratteri" min="50" max="255" required>
        </div>

        <input type="submit" value="CREA DOMANDA APERTA">
    </form>

    <!-- Form per domanda CHIUSA -->
    <form id="chiusaForm" action="../PHPcollegamentiSP/InserimentoDomandaChiusa.php" method="POST" enctype="multipart/form-data">

        <!-- Recupera attributi dominio dalla tabella Sondaggio -->
        <?php
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            $dominio = $row['dominio'];
            echo '<input type="radio" name="dominio" value="' . $dominio . '">' . $dominio . '<br>';
        }
        ?>

        <div>
            <label for="testo">Testo:</label>
            <textarea name="testo" id="testo" required></textarea>
        </div>
        <div>
            <label for="punteggio">Punti:</label>
            <input type="number" name="punteggio" id="punteggio" min="1" max="100" required>
        </div>
        <div>
            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto" required>
        </div>
        <div>
            <label for="opzione1">Opzione 1:</label>
            <input type="text" name="opzione1" id="opzione1" required>
        </div>
        <div>
            <label for="opzione2">Opzione 2:</label>
            <input type="text" name="opzione2" id="opzione2" required>
        </div>
        <div>
            <label for="opzione3">Opzione 3:</label>
            <input type="text" name="opzione3" id="opzione3" required>
        </div>

        <input type="submit" value="CREA DOMANDA CHIUSA">
    </form>

    <script>
        // Nascondi il form per domanda aperta all'avvio
        document.getElementById('apertaForm').style.display = 'none';

        // Gestisci il click sul bottone "APERTA"
        document.getElementById('apertaBtn').addEventListener('click', function() {
            document.getElementById('apertaForm').style.display = 'block';
            document.getElementById('chiusaForm').style.display = 'none';
        });

        // Gestisci il click sul bottone "CHIUSA"
        document.getElementById('chiusaBtn').addEventListener('click', function() {
            document.getElementById('apertaForm').style.display = 'none';
            document.getElementById('chiusaForm').style.display = 'block';
        });
    </script>
</body>
</html>
