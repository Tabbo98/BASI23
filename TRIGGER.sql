USE Sondaggi23;
-- AGGIORNAMENTO CAMPO TOTALEBONUS DOPO ACCETTAZIONE INVITO
DELIMITER //

CREATE TRIGGER IncrementaTotaleBonus
AFTER UPDATE ON Invito
FOR EACH ROW
BEGIN
    -- Controlla se l'invito è stato accettato
    IF NEW.esito = 'accettato' THEN
        -- Incrementa il campo totaleBonus dell'utente corrispondente
        UPDATE Utente SET totaleBonus = totaleBonus + 0.5 WHERE email = NEW.email;
    END IF;
END //

DELIMITER ;

-- INCREMENTA NUM SONDAGGI PER UTENTE PREMIUM DOPO ACCETTAZIONE
DELIMITER //

CREATE TRIGGER AggiornaNumSondaggiPremium
AFTER UPDATE ON Invito
FOR EACH ROW
BEGIN
    -- Step 1: Verifica se l'email è presente nella tabella Premium
    IF EXISTS (SELECT 1 FROM Premium WHERE email = NEW.email) THEN
        -- Incrementa il campo numSondaggi del record corrispondente
        UPDATE Premium SET numSondaggi = numSondaggi + 1 WHERE email = NEW.email;
    END IF;
END //

DELIMITER ;



