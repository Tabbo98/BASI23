<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Acquisizione dei valori forniti dall'utente tramite il metodo POST
    $emai = $_POST['email'];
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $foto = $_FILES['foto']["name"];
    $punti = $_POST['punti'];

    $percorsoFoto = "../Z)FOTO/" . $foto;
    // Creazione dello statement
    $stmt = $conn->prepare("CALL InserisciPremio(?, ?, ?, ?, ?)");

    // Bind dei parametri
    $stmt->bind_param("ssssi", $email, $nome, $descrizione, $percorsoFoto, $punti);

    if ($stmt->execute()) {

        // Reindirizzamento a un'altra pagina con messaggio di successo
        $message = "Premio inserito con successo";
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/CreaPremio.php?message=" . urlencode($message));
        $stmt->close();
        exit();
    } else {
        // Reindirizzamento a un'altra pagina con messaggio di errore
        $error = "Errore durante l'inserimento del premio: " . $stmt->error;
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../ErrorPage.php?error=" . urlencode($error));
        $stmt->close();
        exit();
    }
}
$conn->close();
?>
