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

// Recupero email e ruolo dai parametri GET
if (isset($_GET['email']) && isset($_GET['ruolo'])) {
    $email = $_GET['email'];
    $ruolo = $_GET['ruolo'];
} else {
    // Redirect alla pagina di Errore se i parametri non sono presenti
    header("Location: ../ErrorPage.php");
    exit();
}

// Controllo se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $maxUtenti = $_POST["maxUtenti"];
    $dominio = $_POST["dominio"];
    $descrizione = $_POST['descrizione'];
    $titolo = $_POST["titolo"];

    // Inizializza $utenti come un array vuoto se non è impostato
    $utenti = isset($_POST["utenti"]) ? $_POST["utenti"] : array();

    // Memorizza i parametri nella sessione
    $_SESSION['crea_sondaggio_params'] = [
        'email' => $email,
        'ruolo' => $ruolo,
        'dominio' => $dominio,
        'descrizione' => $descrizione,
        'titolo' => $titolo,
        'maxUtenti' => $maxUtenti,
        'utenti' => $utenti
    ];

    // Redirect alla pagina di creazione sondaggio
    if ($ruolo === "premium") {
        header("Location: ../PHPcollegamentiSP/CreazioneSondaggioP.php");
        exit();
    } elseif ($ruolo === "azienda") {
        header("Location: ../PHPcollegamentiSP/CreazioneSondaggioA.php");
        exit();
    } else {
        // Redirect alla pagina di Errore
        header("Location: ../ErrorPage.php");
        exit();
    }
}

// Ottieni la lista degli utenti
$utentiQuery = "SELECT email, nome FROM Utente WHERE email <> '$email'";
$utentiResult = $conn->query($utentiQuery);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crea Sondaggio</title>
    <link rel="stylesheet" href="../CSSPagineWeb/CreaSondaggio.css">
</head>
<body>

    <!-- Bottone "HOME" -->
    <a href="../Home.php"><button>HOME</button></a>
    
    <!-- Bottone che reindirizza all'inserimento delle domande nei sondaggi -->
    <button onclick="location.href='CreaDomanda.php'">NUOVA DOMANDA</button>

    <?php if ($ruolo === "premium"): ?>
        <!-- Blocco HTML per il ruolo "premium" -->
        <form action="" method="POST">
            <label for="maxUtenti">Numero di Utenti:</label>
            <select name="maxUtenti" id="maxUtenti" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="dominio">Dominio:</label>
            <input type="text" name="dominio" id="dominio" required>

            <label for="descrizione">Descrizione:</label>
            <textarea name="descrizione" id="descrizione" required></textarea>

            <label for="titolo">Titolo:</label>
            <input type="text" name="titolo" id="titolo" required>

            <h3>Seleziona gli utenti:</h3>
            <?php while ($row = $utentiResult->fetch_assoc()): ?>
                <label>
                    <input type="checkbox" name="utenti[]" value="<?php echo $row['email']; ?>">
                    <?php echo $row['nome']; ?>
                </label><br>
            <?php endwhile; ?>

            <input type="submit" value="CREA">
        </form>

        <script>
            const maxUtenti = document.getElementById("maxUtenti");
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="utenti[]"]');

            function updateCheckboxAvailability() {
                const selectedCount = document.querySelectorAll('input[type="checkbox"][name="utenti[]"]:checked').length;
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = (selectedCount >= maxUtenti.value && !checkbox.checked);
                });
            }

            maxUtenti.addEventListener("change", updateCheckboxAvailability);
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", updateCheckboxAvailability);
            });
        </script>

    <?php elseif ($ruolo === "azienda"): ?>
        <!-- Blocco HTML per il ruolo "azienda" -->
        <form action="" method="POST">
            <label for="maxUtenti">Numero di Utenti:</label>
            <select name="maxUtenti" id="maxUtenti" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="dominio">Dominio:</label>
            <input type="text" name="dominio" id="dominio" required>

            <label for="descrizione">Descrizione:</label>
            <textarea name="descrizione" id="descrizione" required></textarea>

            <label for="titolo">Titolo:</label>
            <input type="text" name="titolo" id="titolo" required>

            <input type="submit" value="CREA">
        </form>
    <?php endif; ?>

</body>
</html>
