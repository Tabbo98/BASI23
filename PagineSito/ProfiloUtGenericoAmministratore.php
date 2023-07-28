<html>
<head>
    <meta charset="UTF-8">
    <title>Titolo della pagina</title>
    <link rel="stylesheet" href="../CSSPagineWeb/ProfiloUtGenericoAmministratore.css">
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

        // Bottone "INVITA A SONDAGGIO"
        echo '<a href="InvitoSondaggio.php"><button>INVITA A SONDAGGIO</button></a>';

        // Bottone "HOME"
        echo '<a href="../Home.php"><button>HOME</button></a>';

        if ($_SESSION['ruolo'] === 'amministratore') {
        // Bottone "CREA PREMIO"
        echo '<a href="../PagineSito/CreaPremio.php"><button>CREA PREMIO</button></a>';
        }

        // Form con checkbox per i dati dei sondaggi
        echo '<form action="../PHPcollegamentiSP/AggiornaDomini.php" method="POST">';
        echo '<h2>Scegli i Domini:</h2>';

        // Recupera i dati dei sondaggi dalla tabella "Sondaggio"
        $sql = "SELECT DISTINCT dominio FROM Sondaggio";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dominio = $row['dominio'];
                echo '<input type="radio" name="sondaggi[]" value="' . $dominio . '">' . $dominio . '<br>';
            }
        }

        echo '<br>';
        echo '<input type="submit" name="invia" value="Invia">';
        echo '</form>';

            // Recupera il valore di "totaleBonus" dell'utente loggato
            $email = $_SESSION['email'];
            $sql = "SELECT totaleBonus FROM Utente WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totaleBonus = $row['totaleBonus'];
            } else {
                $totaleBonus = 0;
            }        
            // Recupera i dati dei premi dalla tabella "Premio"
            $sql = "SELECT nome, descrizione, foto, punti FROM Premio";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                // Mostra i dati dei premi in una tabella
                echo '<h2>Premi</h2>';
                echo '<table>';
                echo '<tr>';
                echo '<th>Nome</th>';
                echo '<th>Descrizione</th>';
                echo '<th>Foto</th>';
                echo '<th>Punti</th>';
                echo '</tr>';
    
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['nome'] . '</td>';
                    echo '<td>' . $row['descrizione'] . '</td>';
                    echo '<td><img src="' . $row['foto'] . '" alt="Foto Premio" width="100"></td>';
    
                    // Colore del testo dei punti
                    $punti = $row['punti'];
    
                    if ($punti <= $totaleBonus) {
                        echo '<td style="color: green;">' . $punti . '</td>';
                    } else {
                        echo '<td style="color: red;">' . $punti . '</td>';
                    }
    
                    echo '</tr>';
                }
    
                echo '</table>';
            } else {
                echo 'Nessun premio disponibile.';
            }

        // Verifica il tipo di utente
        if ($_SESSION['ruolo'] === 'generico') {
            // Utente di tipo "generico"
            $email = $_SESSION['email'];

            // Recupera i dati dell'utente dalla tabella "Utente"
            $sql = "SELECT email, nome, cognome, anno, luogoNascita, totaleBonus, ruolo 
                    FROM Utente 
                    WHERE email='$email' AND ruolo='generico'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostra i dati dell'utente in una tabella
                echo '<table>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>Nome: ' . $row['nome'] . '</td>';
                    echo '<td>Cognome: ' . $row['cognome'] . '</td>';
                    echo '<td>Anno: ' . $row['anno'] . '</td>';
                    echo '<td>luogoNascita: ' . $row['luogoNascita'] . '</td>';
                    echo '<td>totaleBonus: ' . $row['totaleBonus'] . '</td>';
                    echo '<td>Ruolo: ' . $row['ruolo'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Nessun dato disponibile per l\'utente.';
            }
        } elseif ($_SESSION['ruolo'] === 'amministratore') {
            // Utente di tipo "amministratore"
            $email = $_SESSION['email'];

            // Recupera i dati dell'utente dalla tabella "Utente" per amministratore
            $sql = "SELECT email, nome, cognome, anno, luogoNascita, totaleBonus, ruolo 
                    FROM Utente 
                    WHERE email='$email' AND ruolo='amministratore'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostra i dati dell'utente dalla tabella "Utente" per amministratore
                echo '<h2>Dati Utente</h2>';
                echo '<table>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>Nome: ' . $row['nome'] . '</td>';
                    echo '<td>Cognome: ' . $row['cognome'] . '</td>';
                    echo '<td>Anno: ' . $row['anno'] . '</td>';
                    echo '<td>luogoNascita: ' . $row['luogoNascita'] . '</td>';
                    echo '<td>totaleBonus: ' . $row['totaleBonus'] . '</td>';
                    echo '<td>Ruolo: ' . $row['ruolo'] . '</td>';
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