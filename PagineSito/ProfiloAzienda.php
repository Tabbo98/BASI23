<html>
    <head>
        <meta charset="UTF-8">
        <title>Titolo della pagina</title>
        <link rel="stylesheet" href="../CSSPagineWeb/ProfiloAzienda.css">
    </head>
<body>
        <?php
        // Connessione al database
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

        // Bottone "CREA SONDAGGIO E/O INSERISCI DOMANDE"
        echo '<a href="CreaSondaggio.php?email=' . $_SESSION['email'] . '&ruolo=' . $_SESSION['ruolo'] . '"><button>CREA SONDAGGIO / DOMANDE</button></a>';    

        // Bottone "HOME"
        echo '<a href="../Home.php"><button>HOME</button></a>';

        // Verifica il tipo di utente
        if ($_SESSION['ruolo'] === 'azienda') {
            // Utente di tipo "azienda"
            $email = $_SESSION['email'];

            // Recupera i dati dell'utente dalla tabella "Azienda"
            $sql = "SELECT emailA, codFiscale, nome, sede FROM Azienda WHERE emailA='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostra i dati dell'utente dalla tabella "Azienda"
                echo '<h2>Dati Utente</h2>';
                echo '<table>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>Codice Fiscale: ' . $row['codFiscale'] . '</td>';
                    echo '<td>Nome: ' . $row['nome'] . '</td>';
                    echo '<td>Sede: ' . $row['sede'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }

        } else {
            // Tipo di utente non riconosciuto
            echo '<script>alert("Tipo di utente non valido"); window.location.href = "../PagineSito/PaginaDiRitorno.php";</script>';
            exit();
        }

        $conn->close();
        ?>
</body>
</html>