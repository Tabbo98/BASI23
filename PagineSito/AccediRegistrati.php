<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />  
  <link rel="stylesheet" href="../CSSPagineWeb/AccediRegistrati.css">
  <title>Accedi/Registrati</title>
</head>

<body>
  <!-- freccia di ritorno -->
  <a href="../Home.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
  </a>

  <h1>Accedi/Registrati</h1>

  <div class="button-container">
    <button onclick="showForm('accessoForm')">Accedi</button>
    <button onclick="showForm('registrazioneForm')">Registrati</button>
  </div>

  <!-- Form di accesso -->
  <form id="accessoForm" class="accesso-form" style="display: none;" method="post" action="../PHPcollegamentiSP/Accedere.php"
    onsubmit="return validateForm(this.id)">
    <h2>ACCESSO</h2>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" maxlength="30" required>
    <br>

    <label for="password">Password:</label>
    <div class="password-container">
    <input type="password" id="password" name="password" required>
      <i class="fas fa-eye" id="showPassword" onclick="togglePasswordVisibility('password', 'showPassword')"></i>
    </div>
    <br>

    <input type="submit" value="ACCEDI!!">
  </form>

  <!-- Form di registrazione -->
  <form id="registrazioneForm" class="registrazione-form" style="display: none;">
    <h2>SCEGLI IL TIPO DI UTENTE</h2>
    <button onclick="showForm('genericoForm'); return false;">GENERICO</button>
    <button onclick="showForm('premiumForm'); return false;">PREMIUM</button>
    <button onclick="showForm('aziendaForm'); return false;">AZIENDA</button>
    <button onclick="showForm('amministratoreForm'); return false;">AMMINISTRATORE</button>
  </form>

  <!-- Form di registrazione - Generico -->
  <form id="genericoForm" class="registration-form" style="display: none;" method="post"
    action="../PHPcollegamentiSP/CreazioneUtenteGenerico.php" onsubmit="return validateForm(this.id)">
    <h2>REGISTRAZIONE GENERICO</h2>
    <label for="emailGenerico">Email:</label>
    <input type="text" id="emailGenerico" name="email" maxlength="30" required>
    <br>

    <label for="passwordGenerico">Password:</label>
    <div class="password-container">
      <input type="password" id="passwordGenerico" name="password" required>
      <i class="fas fa-eye" id="showPasswordGenerico" onclick="togglePasswordVisibility('passwordGenerico', 'showPasswordGenerico')"></i>
    </div>
    <br>

    <label for="nomeGenerico">Nome:</label>
    <input type="text" id="nomeGenerico" name="nome" required>
    <br>

    <label for="cognomeGenerico">Cognome:</label>
    <input type="text" id="cognomeGenerico" name="cognome" required>
    <br>

    <label for="annoGenerico">Anno:</label>
    <input type="number" id="annoGenerico" name="anno" min="1900" max="2004" required>

    <br>

    <label for="luogoNascitaGenerico">Luogo di Nascita:</label>
    <input type="text" id="luogoNascitaGenerico" name="luogoNascita" required>
    <br>

    <input type="submit" value="REGISTRATI!!">
  </form>

  <!-- Form di registrazione - PREMIUM!! -->
  <form id="premiumForm" class="registration-form" style="display: none;" method="post"
    action="../PHPcollegamentiSP/CreazioneUtentePremium.php" onsubmit="return validateForm(this.id)">
    <h2>REGISTRAZIONE PREMIUM</h2>
    <label for="emailPremium">Email:</label>
    <input type="text" id="emailPremium" name="email" maxlength="30" required>
    <br>

    <label for="passwordPremium">Password:</label>
    <div class="password-container">
      <input type="password" id="passwordPremium" name="password" required>
      <i class="fas fa-eye" id="showPasswordPremium" onclick="togglePasswordVisibility('passwordPremium', 'showPasswordPremium')"></i>
    </div>
    <br>

    <label for="nomePremium">Nome:</label>
    <input type="text" id="nomePremium" name="nome" required>
    <br>

    <label for="cognomePremium">Cognome:</label>
    <input type="text" id="cognomePremium" name="cognome" required>
    <br>

    <label for="annoPremium">Anno:</label>
    <input type="number" id="annoPremium" name="anno" min="1900" max="2004" required>

    <br>    

    <label for="luogoNascitaPremium">Luogo di Nascita:</label>
    <input type="text" id="luogoNascitaPremium" name="luogoNascita" required>
    <br>

    <input type="submit" value="REGISTRATI!!">
  </form>

  <!-- Form di registrazione - Azienda -->
  <form id="aziendaForm" class="registration-form" style="display: none;" method="post"
    action="../PHPcollegamentiSP/CreazioneAzienda.php" onsubmit="return validateForm(this.id)">
    <h2>REGISTRAZIONE AZIENDA</h2>
    <label for="emailAzienda">Email:</label>
    <input type="text" id="emailAzienda" name="email" maxlength="30" required>
    <br>

    <label for="codiceFiscale">Codice Fiscale:</label>
    <input type="text" id="codiceFiscale" name="codFiscale" required>
    <br>

    <label for="nomeAzienda">Nome:</label>
    <input type="text" id="nomeAzienda" name="nome" required>
    <br>

    <label for="sede">Sede:</label>
    <input type="text" id="sede" name="sede" required>
    <br>

    <label for="passwordAzienda">Password:</label>
    <div class="password-container">
      <input type="password" id="passwordAzienda" name="password" required>
      <i class="fas fa-eye" id="showPasswordAzienda" onclick="togglePasswordVisibility('passwordAzienda', 'showPasswordAzienda')"></i>
    </div>
    <br>

    <input type="submit" value="REGISTRATI!!">
  </form>

  <!-- Form di registrazione - Amministratore -->
  <form id="amministratoreForm" class="registration-form" style="display: none;" method="post"
    action="../PHPcollegamentiSP/CreazioneUtenteAmministratore.php" onsubmit="return validateForm(this.id)">
    
    <h2>REGISTRAZIONE AMMINISTRATORE</h2>
    <label for="emailAmministratore">Email:</label>
    <input type="text" id="emailAmministratore" name="email" maxlength="30" required>
    <br>

    <label for="passwordAmministratore">Password:</label>
    <div class="password-container">
      <input type="password" id="passwordAmministratore" name="password" required>
      <i class="fas fa-eye" id="showPasswordAmministratore" onclick="togglePasswordVisibility('passwordAmministratore', 'showPasswordAmministratore')"></i>
    </div>
    <br>

    <input type="submit" value="REGISTRATI!!">
  </form>

  <script>
    function showForm(formId) {
      const forms = document.getElementsByClassName("registration-form");
      for (let i = 0; i < forms.length; i++) {
        forms[i].style.display = "none";
      }
      document.getElementById(formId).style.display = "block";
    }

    function togglePasswordVisibility(passwordId, iconId) {
      const passwordInput = document.getElementById(passwordId);
      const eyeIcon = document.getElementById(iconId);
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      }
    }

    function validateForm(formId) {
      const form = document.getElementById(formId);
      const emailInput = form.querySelector('input[name="email"]');
      const passwordInput = form.querySelector('input[name="password"]');
      const emailPremiumInput = form.querySelector('input[name="emailPremium"]');
      const passwordPremiumInput = form.querySelector('input[name="passwordPremium"]');
      const emailAmministratoreInput = form.querySelector('input[name="emailAmministratore"]');
      const passwordAmministratoreInput = form.querySelector('input[name="passwordAmministratore"]');
      const codiceFiscaleInput = form.querySelector('input[name="codiceFiscale"]');
      const passwordAziendaInput = form.querySelector('input[name="passwordAzienda"]');

      if (formId === "genericoForm") {
        const nomeGenericoInput = form.querySelector('input[name="nomeGenerico"]');
        const cognomeGenericoInput = form.querySelector('input[name="cognomeGenerico"]');
        const luogoNascitaGenericoInput = form.querySelector('input[name="luogoNascitaGenerico"]');

        if (
          emailInput.value.trim() === "" ||
          passwordInput.value.trim() === "" ||
          nomeGenericoInput.value.trim() === "" ||
          cognomeGenericoInput.value.trim() === "" ||
          luogoNascitaGenericoInput.value.trim() === ""
        ) {
          alert("Per favore, completa tutti i campi del form.");
          return false;
        }
      } else if (formId === "premiumForm") {
        const nomePremiumInput = form.querySelector('input[name="nomePremium"]');
        const cognomePremiumInput = form.querySelector('input[name="cognomePremium"]');
        const luogoNascitaPremiumInput = form.querySelector('input[name="luogoNascitaPremium"]');

        if (
          emailPremiumInput.value.trim() === "" ||
          passwordPremiumInput.value.trim() === "" ||
          nomePremiumInput.value.trim() === "" ||
          cognomePremiumInput.value.trim() === "" ||
          luogoNascitaPremiumInput.value.trim() === ""
        ) {
          alert("Per favore, completa tutti i campi del form.");
          return false;
        }
      } else if (formId === "aziendaForm") {
        const sedeInput = form.querySelector('input[name="sede"]');

        if (
          codiceFiscaleInput.value.trim() === "" ||
          sedeInput.value.trim() === "" ||
          passwordAziendaInput.value.trim() === ""
        ) {
          alert("Per favore, completa tutti i campi del form.");
          return false;
        }
      } else if (formId === "amministratoreForm") {
        if (
          emailAmministratoreInput.value.trim() === "" ||
          passwordAmministratoreInput.value.trim() === ""
        ) {
          alert("Per favore, completa tutti i campi del form.");
          return false;
        }
      }

      return true;
    }
  </script>
</body>

</html>
