<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSSPagineWeb/PartecipaSondaggio.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <title>Partecipa a sondaggio</title>
</head>

<body>
      <!-- verifica se l'utente ha fatto il log in se no reindirizzamento a pagina di login-->
  <?php
  session_start();
  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // L'utente non è autenticato, mostra l'alert e reindirizza alla pagina di login
    echo '<script>alert("Devi effettuare l\'accesso per visualizzare questa pagina."); window.location.href = "AccediRegistrati.php";</script>';
    exit();
  }
  ?>

  <!-- Freccia di ritorno -->
  <a href="../Home.php" class="back-button">
    <i class="fas fa-arrow-left"></i> </a>

  <!-- Intestazione della pagina -->
  <h1>Partecipa a sondaggio</h1>

  <!-- Selezione della tipologia -->
  <label for="tipologiaSondaggio">Scegli la categoria:</label>
  <select id="tipologiaSondaggio">
    <option value="tipologia1">Politica</option>
    <option value="tipologia2">Sport</option>
    <option value="tipologia3">Attualità</option>
  </select>

  <!-- Bottone "GIOCA!" -->
  <button id="giocaButton">GIOCA!</button>

  <!-- Form del quiz (inizialmente nascosto) -->
  <div id="quizForm" style="display: none;">
    <h2>Quiz</h2>
    <form>
      <!-- Domanda -->
      <label for="domanda">Domanda:</label>
      <p id="domanda">Questa è la domanda del sondaggio.</p>
      <br>
      <!-- Opzioni di risposta -->
      <input type="radio" id="opzione1" name="risposta" value="opzione1">
      <label for="opzione1">Opzione 1</label>
      <br>
      <input type="radio" id="opzione2" name="risposta" value="opzione2">
      <label for="opzione2">Opzione 2</label>
      <br>
      <input type="radio" id="opzione3" name="risposta" value="opzione3">
      <label for="opzione3">Opzione 3</label>
      <br>
      <!-- Pulsante di invio risposta -->
      <input type="submit" value="Invia Risposta">
    </form>
  </div>

  <!-- Script JavaScript per reindirizzare alla pagina "gioca.php" con la tipologia selezionata -->
  <script>
    document.getElementById("giocaButton").addEventListener("click", function() {
      // Ottiene il valore della tipologia selezionata
      var tipologia = document.getElementById("tipologiaSondaggio").value;
      // Reindirizza alla pagina "gioca.php" con il parametro GET "tipologia"
      window.location.href = "Quiz.php?tipologia=" + tipologia;
    });
  </script>
</body>

</html>