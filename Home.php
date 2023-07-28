<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Home.css">
    <title>Applicativo Web</title>
</head>
<body>
<div class="container">
<?php
// Connessione al database
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Bottone "ACCEDI o REGISTRATI"
echo '<a href="PagineSito/AccediRegistrati.php"><button>ACCEDI o REGISTRATI</button></a>';

// Esegue la vista della tabella Utente ordinando i record per totaleBonus decrescente
$sql = "CREATE VIEW ClassificaUtenti AS SELECT nome, cognome, anno, luogoNascita, totaleBonus FROM Utente ORDER BY totaleBonus DESC";
$result = $conn->query($sql);

// Mostra la vista creata in una tabella con titolo "CLASSIFICA!"
echo '<h2>CLASSIFICA!</h2>';
echo '<table>';
echo '<tr>';
echo '<th>Nome</th>';
echo '<th>Cognome</th>';
echo '<th>Anno</th>';
echo '<th>Luogo di Nascita</th>';
echo '<th>Totale Bonus</th>';
echo '</tr>';

$sql = "SELECT * FROM ClassificaUtenti";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['nome'] . '</td>';
        echo '<td>' . $row['cognome'] . '</td>';
        echo '<td>' . $row['anno'] . '</td>';
        echo '<td>' . $row['luogoNascita'] . '</td>';
        echo '<td>' . $row['totaleBonus'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6">Nessun dato disponibile.</td></tr>';
}

echo '</table>';

// Elimina la vista creata
$sql = "DROP VIEW ClassificaUtenti";
$conn->query($sql);

$conn->close();
?>

</div>
</body>
</html>
