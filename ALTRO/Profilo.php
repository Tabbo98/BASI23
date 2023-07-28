<?php
if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}
$conn = new mysqli("localhost", "root", "", "Sondaggi23");

session_start();
  if ($_SESSION['logged_in'] && isset($_SESSION['email']) && isset($_SESSION['tipo_utente'])) {
    // L'utente è loggato e le informazioni di sessione sono presenti
    $email = $_SESSION['email'];
    $tipoUtente = $_SESSION['tipo_utente'];

    // Puoi utilizzare $email e $tipoUtente per eseguire le azioni necessarie nella pagina Profilo.php
} else {
    // L'utente non è loggato o le informazioni di sessione sono incomplete
    echo '<script>alert("Devi effettuare l\'accesso per visualizzare questa pagina."); window.location.href = "AccediRegistrati.php";</script>';
    exit();
}

  // Query per recuperare tutte le informazioni dell'utente dalla tabella Utente dove l'email corrisponde all'email dell'utente loggato
  $query = "SELECT u.email, u.nome, u.cognome, COUNT(s.nome) AS num_premi_vinti, SUM(p.punti) AS punti_totali
            FROM Utente u
            LEFT JOIN Storico s ON u.email = s.email
            LEFT JOIN Premio p ON s.nome = p.nome
            WHERE u.email = '$email'";
  $result = mysqli_query($conn, $query);

  // Verifica del risultato della query
  if ($result && mysqli_num_rows($result) > 0) {
    // Estrazione dei dati dal risultato della query
    $row = mysqli_fetch_assoc($result);
    $nome = $row['nome'];
    $cognome = $row['cognome'];
    $email = $row['email'];
    $num_premi_vinti = $row['num_premi_vinti'];
    $punti_totali = $row['punti_totali'];
  }


// Recupera l'elenco degli utenti registrati dal database
$sql_users = "SELECT * FROM Utente WHERE email != '$email'";
$result_users = mysqli_query($conn, $sql_users);

// Recupera l'elenco dei domini preferiti dal database
$sql_domains = "SELECT DISTINCT dominio FROM Sondaggio";
$result_domains = mysqli_query($conn, $sql_domains);

// Verifica se è stato inviato il form per l'aggiornamento dei domini preferiti
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['aggiorna'])) {
    // Recupera i valori delle checkbox selezionate
    $selectedDomains = isset($_POST['domini']) ? $_POST['domini'] : array();

    // Chiamata al file PHP che esegue la stored procedure per l'aggiornamento dei domini preferiti
    include('aggiorna_domini.php');
  }
}
?>

                                              <!-- HTML -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSSPagineWeb/Profilo.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <title>Profilo</title>
</head>

<body>
  <!-- Freccia di ritorno -->
  <a href="../Home.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
  </a>

  <!-- Intestazione della pagina -->
  <header>
    <h1>Profilo</h1>
    <a href="Sondaggi.php">Sondaggi</a>
  </header>

  <!-- Bottone "Dati Personali" -->
  <button class="tab-button" onclick="showDatiPersonali()">Dati Personali</button>

  <!-- Bottone "Invita a Sondaggio" -->
  <?php if ($_SESSION['ruolo'] === 'amministratore') : ?>
    <button class="tab-button" onclick="showInvitaSondaggio()">Invita a Sondaggio</button>
  <?php endif; ?>

  <!-- Bottone "Premi" -->
  <button class="tab-button" onclick="showPremi()">Premi</button>

  <!-- Contenuto della pagina -->
  <div class="content">
    <!-- Dati Personali -->
    <div id="dati-personali" class="tab-content">
      <h2>Dati Personali</h2>
      <table>
        <tr>
          <td><strong>Nome:</strong></td>
          <td><?php echo $nome; ?></td>
        </tr>
        <tr>
          <td><strong>Cognome:</strong></td>
          <td><?php echo $cognome; ?></td>
        </tr>
        <tr>
          <td><strong>Email:</strong></td>
          <td><?php echo $email; ?></td>
        </tr>
        <tr>
          <td><strong>Premi Vinti:</strong></td>
          <td><?php echo $num_premi_vinti; ?></td>
        </tr>
        <tr>
          <td><strong>Punti Totali:</strong></td>
          <td><?php echo $punti_totali; ?></td>
        </tr>
      </table>
    </div>

    <!-- Invita a Sondaggio -->
    <?php if ($_SESSION['ruolo'] === 'amministratore') : ?>
      <div id="invita-sondaggio" class="tab-content">
        <h2>Invita a Sondaggio</h2>
        <form method="POST" action="">
          <table>
            <tr>
              <td><strong>Seleziona Utenti:</strong></td>
              <td>
                <select name="utenti[]" multiple>
                  <?php while ($row_users = mysqli_fetch_assoc($result_users)) : ?>
                    <option value="<?php echo $row_users['email']; ?>"><?php echo $row_users['email']; ?></option>
                  <?php endwhile; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td><strong>Seleziona Sondaggio:</strong></td>
              <td>
                <select name="sondaggio">
                  <?php while ($row_sondaggi = mysqli_fetch_assoc($result_sondaggi)) : ?>
                    <option value="<?php echo $row_sondaggi['nome']; ?>"><?php echo $row_sondaggi['nome']; ?></option>
                  <?php endwhile; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="invia" value="Invia" />
              </td>
            </tr>
          </table>
        </form>
      </div>
    <?php endif; ?>

    <!-- Premi -->
    <div id="premi" class="tab-content">
      <h2>Premi</h2>
      <!-- Aggiungi il codice per visualizzare i premi dell'utente -->
    </div>

    <!-- Domini preferiti -->
    <div id="domini-preferiti" class="tab-content">
      <h2>Domini Preferiti</h2>
      <form method="POST" action="">
        <?php while ($row_domains = mysqli_fetch_assoc($result_domains)) : ?>
          <label>
            <input type="checkbox" name="domini[]" value="<?php echo $row_domains['dominio']; ?>" />
            <?php echo $row_domains['dominio']; ?>
          </label>
          <br>
        <?php endwhile; ?>
        <br>
        <input type="submit" name="aggiorna" value="Aggiorna" />
      </form>
    </div>
  </div>

                                    <!-- JAVASCRIPT -->

  <script>
    function showDatiPersonali() {
      document.getElementById('dati-personali').style.display = 'block';
      document.getElementById('invita-sondaggio').style.display = 'none';
      document.getElementById('premi').style.display = 'none';
      document.getElementById('domini-preferiti').style.display = 'none';
    }

    function showInvitaSondaggio() {
      document.getElementById('dati-personali').style.display = 'none';
      document.getElementById('invita-sondaggio').style.display = 'block';
      document.getElementById('premi').style.display = 'none';
      document.getElementById('domini-preferiti').style.display = 'none';
    }

    function showPremi() {
      document.getElementById('dati-personali').style.display = 'none';
      document.getElementById('invita-sondaggio').style.display = 'none';
      document.getElementById('premi').style.display = 'block';
      document.getElementById('domini-preferiti').style.display = 'none';
    }

    function showDominiPreferiti() {
      document.getElementById('dati-personali').style.display = 'none';
      document.getElementById('invita-sondaggio').style.display = 'none';
      document.getElementById('premi').style.display = 'none';
      document.getElementById('domini-preferiti').style.display = 'block';
    }
  </script>
</body>

</html>
