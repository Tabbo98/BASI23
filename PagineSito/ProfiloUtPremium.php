<html>
    <head>
        <meta charset="UTF-8">
        <title>Titolo della pagina</title>
        <link rel="stylesheet" href="../CSSPagineWeb/ProfiloUtPremium.css">
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
        echo '<a href="InvitoSondaggio.php?email=' . $_SESSION['email'] . '&ruolo=' . $_SESSION['ruolo'] . '"><button>INVITA A SONDAGGIO</button></a>';

        // Bottone "CREA SONDAGGIO E/O INSERISCI DOMANDE"
        echo '<a href="CreaSondaggio.php?email=' . $_SESSION['email'] . '&ruolo=' . $_SESSION['ruolo'] . '"><button>CREA SONDAGGIO / DOMANDE</button></a>';
            

        // Bottone "HOME"
        echo '<a href="../Home.php"><button>HOME</button></a>';

        // Form per il caricamento del file .txt
        echo '<form action="../PopolamentoDB.php" method="POST" enctype="multipart/form-data">';
        echo '<input type="file" name="file">';
        echo '<input type="submit" name="upload" value="Carica File">';
        echo '</form>';

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
        if ($_SESSION['ruolo'] === 'premium') {
            // Utente di tipo "generico"
            $email = $_SESSION['email'];

            // Recupera i dati dell'utente dalla tabella "Utente" con ruolo 'premium'
            $sql = "SELECT email, nome, cognome, anno, luogoNascita, totaleBonus, ruolo 
                    FROM Utente 
                    WHERE email='$email' AND ruolo='premium'";
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
            }
        }

            // Recupera i dati dell'utente dalla tabella "Premium,"
            $sql = "SELECT costo, numSondaggi, dataInizioAbbonamento, dataFineAbbonamento FROM Premium WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Mostra i dati dell'utente dalla tabella "Premium"
                echo '<h2>Dati Premium</h2>';
                echo '<table>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>Costo: ' . $row['costo'] . '</td>';
                    echo '<td>NumSondaggi: ' . $row['numSondaggi'] . '</td>';
                    echo '<td>Data Inizio Abbonamento: ' . $row['dataInizioAbbonamento'] . '</td>';
                    echo '<td>Data Fine Abbonamento: ' . $row['dataFineAbbonamento'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
        } else {
            // Tipo di utente non riconosciuto
            echo '<script>alert("Tipo di utente non valido"); window.location.href = "../PagineSito/PaginaDiRitorno.php";</script>';
            exit();
        }
        ?>

</body>
</html>