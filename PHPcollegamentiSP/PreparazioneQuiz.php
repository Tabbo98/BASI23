<?php
// Avvio sessione, connessione al database
session_start();
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

//verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'utente è autenticato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../PagineSito/AccediRegistrati.php");
    exit();
}

// Verifica se l'idInvito è stato fornito come parametro nell'URL
if (isset($_GET['idInvito'])) {
    // Recupero idInvito dall'URL
    $idInvitoAccettato = $_GET['idInvito'];

    // Recupero email dalla variabile di sessione
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Recupero "codice" e "dominio" dalla tabella "Invito"
        $queryInvito = "SELECT codice, dominio FROM Invito WHERE id = ?";
        $stmtInvito = $conn->prepare($queryInvito);
        $stmtInvito->bind_param("i", $idInvitoAccettato);
        $stmtInvito->execute();
        $resultInvito = $stmtInvito->get_result();
        $invito = $resultInvito->fetch_assoc();
        $stmtInvito->close();

        if ($invito) {
            // Recupero il codice e il dominio dell'invito
            $codice = $invito['codice'];
            $dominio = $invito['dominio'];

            // Recupero tutti gli id dalla tabella "Contenuto" dove il dominio è uguale al valore di $dominio
            $queryContenuto = "SELECT id FROM Contenuto WHERE dominio = ?";
            $stmtContenuto = $conn->prepare($queryContenuto);
            $stmtContenuto->bind_param("s", $dominio);
            $stmtContenuto->execute();
            $resultContenuto = $stmtContenuto->get_result();

            // Lista degli id
            $listaIdDomande = array();

            // Ciclo sui risultati e aggiunta degli id alla lista
            while ($row = $resultContenuto->fetch_assoc()) {
                $listaIdDomande[] = $row['id'];
            }

            // Chiude lo statement
            $stmtContenuto->close();

            $_SESSION['listaIdDomande'] = $listaIdDomande;

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Location: ../PagineSito/Gioco.php");
            exit();
        } else {
            echo "Nessun dato trovato per l'ID dell'invito specificato.";
        }
    } else {
        echo "Email non disponibile nella variabile di sessione.";
    }
} else {
    echo "ID dell'invito non fornito nell'URL.";
}

$conn->close();
?>
