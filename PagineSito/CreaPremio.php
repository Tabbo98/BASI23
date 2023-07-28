<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../CSSPagineWeb/CreaPremio.css">
  <title>Crea Premio</title>
</head>

<body>
  <?php
  // Connessione al database
  $conn = new mysqli("localhost", "root", "", "Sondaggi23");

  // Verifica della connessione
  if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
  }
  
    session_start();
    // Verifica se l'utente è autenticato 
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: ../PagineSito/AccediRegistrati.php");
        exit();
    }

    $email = $_SESSION['email'];
  ?>

  <h1>Crea Premio</h1>

  <form method="post" enctype="multipart/form-data" action="../PHPcollegamentiSP/CreazionePremio.php">
    <input type="hidden" name="email" value="<?php echo $email; ?>">

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>
    <br>

    <label for="descrizione">Descrizione del Premio:</label>
    <textarea id="descrizione" name="descrizione" required></textarea>
    <br>

    <label for="foto_premio">Foto Premio:</label>
    <input type="file" id="foto_premio" name="foto" required>
    <br>

    <label for="punti_necessari">Punti Necessari:</label>
    <input type="number" id="punti_necessari" name="punti" min="20" required>
    <br>

    <input type="submit" name="submit" value="CREA PREMIO">
  </form>

</body>

</html>
