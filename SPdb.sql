USE Sondaggi23;

/*STORED PROCEDURES*/

    /*INSERIMENTO UTENTE GENERICO*/

    DELIMITER //
CREATE PROCEDURE CreazioneUtente(
    IN p_email VARCHAR(30),
    IN p_password VARCHAR(255),
    IN p_nome VARCHAR(255),
    IN p_cognome VARCHAR(255),
    IN p_anno INT,
    IN p_luogoNascita VARCHAR(255)
)
BEGIN
    INSERT INTO Utente (email, password, nome, cognome, anno, luogoNascita)
    VALUES (p_email, p_password, p_nome, p_cognome, p_anno, p_luogoNascita);
    
    SELECT LAST_INSERT_ID() AS new_user_id;
END //
DELIMITER ;


    /*INSERIMENTO UTENTE AMMINISTRATORE*/
DELIMITER //

CREATE PROCEDURE InserisciUtenteAmministratore(
    IN p_email VARCHAR(30),
    IN p_password VARCHAR(255)
)
BEGIN
    -- Inserimento nella tabella Utente
    INSERT INTO Utente (email, password, nome, cognome, anno, luogoNascita, ruolo)
    VALUES (p_email, p_password, 'admin', 'admin', 0000, 'Ignoto', 'amministratore');

END //

DELIMITER ;

/*INSERIMENTO UTENTE PREMIUM*/
DELIMITER //

CREATE PROCEDURE InserisciUtentePremium(
    IN p_email VARCHAR(30),
    IN p_password VARCHAR(255),
    IN p_nome VARCHAR(255),
    IN p_cognome VARCHAR(255),
    IN p_anno INT,
    IN p_luogoNascita VARCHAR(255)
)
BEGIN
    -- Inserimento nella tabella Utente
    INSERT INTO Utente (email, password, nome, cognome, anno, luogoNascita, ruolo)
    VALUES (p_email, p_password, p_nome, p_cognome, p_anno, p_luogoNascita, 'premium');

    -- Inserimento nella tabella Premium
    INSERT INTO Premium (email, password)
    VALUES (p_email, p_password);
END //

DELIMITER ;

    /*INSERIMENTO UTENTE AZIENDA*/

DELIMITER //

CREATE PROCEDURE InserisciAzienda(
    IN p_emailA VARCHAR(30),
    IN p_codFiscale VARCHAR(255),
    IN p_nome VARCHAR(255),
    IN p_password VARCHAR(255),
    IN p_sede VARCHAR(255)
)
BEGIN
    INSERT INTO Azienda (emailA, codFiscale, nome, password, sede)
    VALUES (p_emailA, p_codFiscale, p_nome, p_password, p_sede);
END //

DELIMITER ;


    /*INSERIMENTO DOMANDA APERTA*/
DELIMITER //

CREATE PROCEDURE NuovaDomandaAperta (
    IN p_email VARCHAR(30),
    IN p_ruolo VARCHAR(20),
    IN p_dominio VARCHAR(50),
    IN p_testo TEXT,
    IN p_punteggio INT,
    IN p_foto VARCHAR(255),
    IN p_maxCaratteri INT
)
BEGIN
    DECLARE v_domandaId INT;

    -- Inserimento della nuova domanda
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (p_testo, p_punteggio, p_foto);
    
    SET v_domandaId = LAST_INSERT_ID();
    
    INSERT INTO Aperta (id, maxCaratteri)
    VALUES (v_domandaId, p_maxCaratteri);

    -- Inserisco i valori di codice trovati nella tabella Contenuto
    INSERT INTO Contenuto (codice, dominio, id)
    SELECT s.codice, s.dominio, v_domandaId
    FROM Sondaggio s
    WHERE s.dominio = p_dominio;

    -- Verifica il valore di p_ruolo e inserisci i dati appropriati nelle tabelle "InserimentoDPr" o "InserimentoDA"
    IF p_ruolo = 'premium' THEN
        INSERT INTO InserimentoDPr (email, id) VALUES (p_email, v_domandaId);
    ELSEIF p_ruolo = 'azienda' THEN
        INSERT INTO InserimentoDA (emailA, id) VALUES (p_email, v_domandaId);
    END IF;    
END //

DELIMITER ;

                                    /*INSERIMENTO DOMANDA CHIUSA*/
DELIMITER //

CREATE PROCEDURE NuovaDomandaChiusa(
    IN p_email VARCHAR(30),
    IN p_ruolo VARCHAR(20),
    IN p_dominio VARCHAR(50),
    IN p_testoDomanda TEXT,
    IN p_punteggio INT,
    IN p_foto VARCHAR(255),
    IN p_testoOpzione1 VARCHAR(255),
    IN p_testoOpzione2 VARCHAR(255),
    IN p_testoOpzione3 VARCHAR(255)
)
BEGIN
    DECLARE v_domandaId INT;

    -- Inserimento della nuova domanda
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (p_testoDomanda, p_punteggio, p_foto);
    
    SET v_domandaId = LAST_INSERT_ID();
    
    INSERT INTO Chiusa (id)
    VALUES (v_domandaId);
    
    -- Inserimento delle opzioni per la domanda chiusa
    INSERT INTO Opzione (testo, id)
    VALUES (p_testoOpzione1, v_domandaId);
    
    INSERT INTO Opzione (testo, id)
    VALUES (p_testoOpzione2, v_domandaId);
    
    INSERT INTO Opzione (testo, id)
    VALUES (p_testoOpzione3, v_domandaId);

    -- Inserimento valori di codice trovati nella tabella Contenuto
    INSERT INTO Contenuto (codice, dominio, id)
    SELECT s.codice, s.dominio, v_domandaId
    FROM Sondaggio s
    WHERE s.dominio = p_dominio;

    -- Verifica il valore di p_ruolo e inserisci i dati appropriati nelle tabelle "InserimentoDPr" o "InserimentoDA"
    IF p_ruolo = 'premium' THEN
        INSERT INTO InserimentoDPr (email, id) VALUES (p_email, v_domandaId);
    ELSEIF p_ruolo = 'azienda' THEN
        INSERT INTO InserimentoDA (emailA, id) VALUES (p_email, v_domandaId);
    END IF;
END //

DELIMITER ;



                    /*CREAZIONE SONDAGGIO PREMIUM*/
DELIMITER //

CREATE PROCEDURE CreazioneSondaggioPremium(
    IN p_dominio VARCHAR(50),
    IN p_descrizione TEXT,
    IN p_titolo VARCHAR(255),
    IN p_maxUtenti INT,
    IN p_email VARCHAR(30)
)
BEGIN
        -- Inserisce un nuovo record nella tabella Sondaggio
        INSERT INTO Sondaggio (dominio, descrizione, titolo, maxUtenti)
        VALUES (p_dominio, p_descrizione, p_titolo, p_maxUtenti);

        INSERT INTO CreazioneSPr (email, codice, dominio)
        VALUES (p_email, LAST_INSERT_ID(), p_dominio);
END //

DELIMITER ;


                    /*CREAZIONE SONDAGGIO AZIENDA*/
DELIMITER //
CREATE PROCEDURE CreazioneSondaggioAzienda(
    IN p_dominio VARCHAR(50),
    IN p_descrizione TEXT,
    IN p_titolo VARCHAR(255),
    IN p_maxUtenti INT,
    IN p_email VARCHAR(30)
)
BEGIN
        -- Inserisce un nuovo record nella tabella Sondaggio
        INSERT INTO Sondaggio (dominio, descrizione, titolo, maxUtenti)
        VALUES (p_dominio, p_descrizione, p_titolo, p_maxUtenti);

        -- Recupera codice del sondaggio appena creato e inserisce un nuovo record nella tabella Creazione
        INSERT INTO CreazioneSA (emailA, codice, dominio)
        VALUES (p_email, LAST_INSERT_ID(), p_dominio);
END //
DELIMITER ;

                                /*INSERISCI PREMIO*/
DELIMITER //

CREATE PROCEDURE InserisciPremio(
    IN p_email VARCHAR(30),
    IN p_nome VARCHAR(255),
    IN p_descrizione TEXT,
    IN p_foto VARCHAR(255),
    IN p_punti INT
)
BEGIN
    -- Verifica se esiste già un record con lo stesso valore di "nome"
    IF NOT EXISTS (SELECT 1 FROM Premio WHERE nome = p_nome) THEN
        -- Inserisce il record nella tabella Premio
        INSERT INTO Premio (nome, descrizione, foto, punti)
        VALUES (p_nome, p_descrizione, p_foto, p_punti);

        INSERT INTO Inserimento (email, nome)
        VALUES (p_email, p_nome);

    END IF;
END //

DELIMITER ;


                            /*INSERISCI INVITO*/                            
DELIMITER //

CREATE PROCEDURE AggiungiInvito(
    IN p_codice INT(10), 
    IN p_dominio VARCHAR(50), 
    IN p_email VARCHAR(30)
)
BEGIN
    -- Verifica se non esiste già un record con i dati specificati
    IF NOT EXISTS (
        SELECT 1 FROM Invito WHERE codice = p_codice AND dominio = p_dominio AND email = p_email AND esito = 'invitato'
    ) THEN
        -- Inserisci un nuovo record nella tabella Invito
        INSERT INTO Invito (codice, dominio, email) VALUES (p_codice, p_dominio, p_email);
    END IF;
END //

DELIMITER ;


                                /*STORED PROCEDURE DI AGGIORNAMENTO (COMPLESSE)*/

                /*AGGIORNA DOMINIO*/
DELIMITER //

CREATE PROCEDURE AggiornaDomini(
    IN p_email VARCHAR(30),
    IN p_domini VARCHAR(255)
)
BEGIN
    -- Crea una tabella temporanea per i domini forniti
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_domini (
        dominio VARCHAR(50) PRIMARY KEY
    );

    -- Split della stringa dei domini forniti
    SET @pos = 1;
    SET @length = LENGTH(p_domini) + 1;

    WHILE @pos <= @length DO
        -- Estrae un dominio dalla stringa
        SET @value = SUBSTRING(p_domini, @pos, POSITION(',' IN CONCAT(p_domini, ',')) - @pos);
        SET @pos = @pos + LENGTH(@value) + 1;

        -- Inserisce il dominio nella tabella temporanea
        INSERT INTO tmp_domini (dominio) VALUES (@value);
    END WHILE;

    -- Verifica e inserimento dei record nella tabella Interesse
    INSERT INTO Interesse (email, dominio, codice)
    SELECT p_email, s.dominio, s.codice
    FROM Sondaggio s
    WHERE s.dominio IN (SELECT dominio FROM tmp_domini)
      AND NOT EXISTS (
        SELECT 1
        FROM Interesse i
        WHERE i.email = p_email AND i.dominio = s.dominio AND i.codice = s.codice
      );

    -- Elimina la tabella temporanea
    DROP TEMPORARY TABLE IF EXISTS tmp_domini;
    
END //

DELIMITER ;

                /*ACCETTA INVITO*/
DELIMITER //

CREATE PROCEDURE AccettaInvito(
    IN p_id INT
)
BEGIN
    DECLARE v_codice INT;
    DECLARE v_dominio VARCHAR(50);
    DECLARE v_maxUtenti INT;
    DECLARE v_numAccettati INT;

    -- Recupera codice e dominio dalla tabella Invito
    SELECT codice, dominio INTO v_codice, v_dominio FROM Invito WHERE id = p_id;

    -- Recupera maxUtenti dalla tabella Sondaggio
    SELECT maxUtenti INTO v_maxUtenti FROM Sondaggio WHERE codice = v_codice AND dominio = v_dominio;

    -- Verifica il numero di record "accettati" nella tabella Invito per il codice e dominio corrispondenti
    SET v_numAccettati = (SELECT COUNT(*) FROM Invito WHERE codice = v_codice AND dominio = v_dominio AND esito = 'accettato');

    -- Aggiorna il parametro esito del record con quell'id solo se la condizione è verificata
    IF v_numAccettati < v_maxUtenti THEN
        UPDATE Invito SET esito = 'accettato' WHERE id = p_id;
    END IF;
END //

DELIMITER ;

                /*RIFIUTA INVITO*/
DELIMITER //
CREATE PROCEDURE RifiutaInvito(
    IN p_id INT 
    )
BEGIN
    UPDATE Invito SET esito = 'rifiutato' WHERE id = p_id;
END//
DELIMITER ;

                /*INSERIMENTO RISPOSTA CHIUSA*/
DELIMITER //

CREATE PROCEDURE RispostaDomandaChiusa(
    IN p_emailUtente VARCHAR(30), 
    IN p_idDomanda INT, 
    IN p_risposta VARCHAR(255), 
    IN p_punteggio INT,
    IN p_opzione2 VARCHAR(255),
    IN p_opzione3 VARCHAR(255)
)
BEGIN

    -- Inserisci i dati nella tabella RispostaChiusa
    INSERT INTO RispostaChiusa (emailUtente, id, scelta, opzione2, opzione3)
    VALUES (p_emailUtente, p_idDomanda, p_risposta, p_opzione2, p_opzione3);

    -- Incrementa il campo totaleBonus nella tabella Utente
    UPDATE Utente SET totaleBonus = totaleBonus + p_punteggio WHERE email = p_emailUtente;
END //

DELIMITER ;

                                    /*INSERIMENTO RISPOSTA APERTA*/
DELIMITER //

CREATE PROCEDURE RispostaDomandaAperta(
    IN p_emailUtente VARCHAR(30),
    IN p_idDomanda INT,
    IN p_risposta VARCHAR(255),
    IN p_punteggio INT
)

BEGIN
    -- Inserisci i dati nella tabella RispostaAperta
    INSERT INTO RispostaAperta (emailUtente, id, scelta)
    VALUES (p_emailUtente, p_id, p_risposta);

    -- Incrementa il campo totaleBonus nella tabella Utente
    UPDATE Utente SET totaleBonus = totaleBonus + p_punteggio WHERE email = p_emailUtente;

END //

DELIMITER ;

                        -- CHIUSURA SONDAGGIO
DELIMITER //

CREATE PROCEDURE ChiudiSondaggio(
    IN p_codice INT,
    IN p_dominio VARCHAR(50)
)
BEGIN
    -- Aggiorna il valore di stato a "CHIUSO" nella tabella "Sondaggio"
    UPDATE Sondaggio
    SET stato = 'CHIUSO'
    WHERE codice = p_codice AND dominio = p_dominio;
END //

DELIMITER ;
 
