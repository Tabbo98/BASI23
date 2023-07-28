<?php
// Connessione al database
session_start();
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione  
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se il form è stato sottoposto tramite il metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query per verificare le credenziali nella tabella "Azienda"
    $sql = "SELECT * 
            FROM Azienda 
            WHERE emailA='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Credenziali corrette trovate nella tabella "Azienda", procedi con il codice
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        $_SESSION['ruolo'] = $row['ruolo']; // Aggiungi il tipo di utente alla sessione
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: ../PagineSito/ProfiloAzienda.php");
        exit();
    } else {
        // Nessuna corrispondenza trovata nella tabella "Azienda", verifica per "Amministratore"
        $sql = "SELECT * 
                FROM Utente 
                WHERE email='$email' AND password='$password' AND ruolo='amministratore'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Credenziali corrette trovate per "Amministratore", procedi con il codice
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;
            $_SESSION['ruolo'] = $row['ruolo'];
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Location: ../PagineSito/ProfiloUtGenericoAmministratore.php");
            exit();
        } else {
            // Nessuna corrispondenza trovata per "Amministratore", verifica la tabella "Premium"
            $sql = "SELECT * 
                    FROM Premium 
                    WHERE email='$email' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Credenziali corrette trovate nella tabella "Premium", procedi con il codice
                $row = $result->fetch_assoc();
                $_SESSION['email'] = $email;
                $_SESSION['logged_in'] = true;
                $_SESSION['ruolo'] = $row['ruolo'];
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Location: ../PagineSito/ProfiloUtPremium.php");
                exit();
            } else {
                // Nessuna corrispondenza trovata nella tabella "Premium", verifica "Utente Generico"
                $sql = "SELECT * 
                FROM Utente 
                WHERE email='$email' AND password='$password' AND ruolo='generico'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Credenziali corrette trovate per "Utente Generico", procedi con il codice
                    $row = $result->fetch_assoc();
                    $_SESSION['email'] = $email;
                    $_SESSION['logged_in'] = true;
                    $_SESSION['ruolo'] = $row['ruolo'];
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    header("Location: ../PagineSito/ProfiloUtGenericoAmministratore.php");
                    exit();
                } else {
                    // Nessuna corrispondenza trovata, mostra l'alert di errore e reindirizza dopo aver premuto il bottone "OK"
                    echo '<script>alert("Credenziali non valide"); window.location.href = "../PagineSito/AccediRegistrati.php";</script>';
                    exit();
                }
            }
        }
    }
}

$conn->close();
?>
