/*CREAZIONE STRUTTURA DB*/

-- Creazione del database
CREATE DATABASE IF NOT EXISTS Sondaggi23;

-- Seleziona il database
USE Sondaggi23;

-- Creazione delle tabelle
CREATE TABLE Utente (
    email VARCHAR(30) PRIMARY KEY,
    password VARCHAR(255),
    nome VARCHAR(255),
    cognome VARCHAR(255),
    anno INT,
    luogoNascita VARCHAR(255),
    totaleBonus DECIMAL(10,2) DEFAULT 0.00,
    ruolo VARCHAR(20) DEFAULT 'generico',
    INDEX email_index (email)
);

CREATE TABLE Premio (
    nome VARCHAR(255) PRIMARY KEY,
    descrizione TEXT,
    foto VARCHAR(255),
    punti INT
);

                                    -- DA ELIMINARE!!!!
CREATE TABLE Amministratore (
    email VARCHAR(30) PRIMARY KEY,
    password VARCHAR(255),
    ruolo VARCHAR(20) DEFAULT 'amministratore',
    FOREIGN KEY (email) REFERENCES Utente(email)
);
                                        -- FINO A QUI

CREATE TABLE Premium (
    email VARCHAR(30),
    password VARCHAR(255),
    costo DECIMAL(10,2) DEFAULT 50.00,
    numSondaggi INT DEFAULT 0,
    dataInizioAbbonamento DATE DEFAULT CURDATE(),
    dataFineAbbonamento DATE DEFAULT '2999-12-31',
    ruolo VARCHAR(20) DEFAULT 'premium',
    FOREIGN KEY (email) REFERENCES Utente(email)
);

CREATE TABLE Azienda (
    emailA VARCHAR(30) PRIMARY KEY,
    codFiscale VARCHAR(255),
    nome VARCHAR(255),    
    password VARCHAR(255),
    sede VARCHAR(255),
    ruolo VARCHAR(20) DEFAULT 'azienda'
);

CREATE TABLE Sondaggio (
    codice INT(10) AUTO_INCREMENT,
    dominio VARCHAR(50),
    descrizione TEXT,
    titolo VARCHAR(255),
    dataCreazione DATE DEFAULT CURDATE(),
    maxUtenti INT,
    stato ENUM('APERTO', 'CHIUSO') DEFAULT 'APERTO',
    dataChiusura DATE DEFAULT '2999-12-31',
    PRIMARY KEY (codice, dominio),
    INDEX dom_index (dominio)
);


CREATE TABLE Domanda (
    id INT PRIMARY KEY AUTO_INCREMENT,
    testo TEXT,
    punteggio INT DEFAULT 20,
    foto VARCHAR(255) DEFAULT '../Z)FOTO/ImmagineDefault.jpg'
);

CREATE TABLE Aperta (
    id INT PRIMARY KEY,
    maxCaratteri INT DEFAULT 255 CHECK (maxCaratteri <= 255),
    FOREIGN KEY (id) REFERENCES Domanda(id)
);


CREATE TABLE Chiusa (
    id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES Domanda(id)
);

CREATE TABLE Opzione (
    numProgressivo INT AUTO_INCREMENT,
    testo VARCHAR(255),
    id INT,
    PRIMARY KEY (numProgressivo, id),
    FOREIGN KEY (id) REFERENCES Chiusa(id)
);

CREATE TABLE Inserimento (
    email VARCHAR(30),
    nome VARCHAR(255),
    FOREIGN KEY (email) REFERENCES Amministratore(email),
    FOREIGN KEY (nome) REFERENCES Premio(nome)
);

CREATE TABLE Invito (
    codice INT(10),
    dominio VARCHAR(50),
    id INT AUTO_INCREMENT,
    email VARCHAR(30),
    esito VARCHAR(255)DEFAULT 'invitato',
    PRIMARY KEY (id, codice, dominio, email),
    FOREIGN KEY (codice) REFERENCES Sondaggio(codice),
    FOREIGN KEY (dominio) REFERENCES Sondaggio(dominio),
    FOREIGN KEY (email) REFERENCES Utente(email)

);

CREATE TABLE Contenuto (
    codice INT(10) NOT NULL,
    dominio VARCHAR(50),
    id INT,
    FOREIGN KEY (codice) REFERENCES Sondaggio(codice),
    FOREIGN KEY (dominio) REFERENCES Sondaggio(dominio),
    FOREIGN KEY (id) REFERENCES Domanda(id)
);


CREATE TABLE CreazioneSPr (
    email VARCHAR(30),
    codice INT(10) NOT NULL,    
    dominio VARCHAR(50),
    FOREIGN KEY (email) REFERENCES Premium(email),
    FOREIGN KEY (codice) REFERENCES Sondaggio(codice),
    FOREIGN KEY (dominio) REFERENCES Sondaggio(dominio)

);

CREATE TABLE CreazioneSA (
    emailA VARCHAR(30),
    codice INT(10) NOT NULL,    
    dominio VARCHAR(50),
    FOREIGN KEY (emailA) REFERENCES Azienda(emailA),
    FOREIGN KEY (codice) REFERENCES Sondaggio(codice),
    FOREIGN KEY (dominio) REFERENCES Sondaggio(dominio)

);

CREATE TABLE InserimentoDPr (
    email VARCHAR(30) NOT NULL,
    id INT NOT NULL,
    FOREIGN KEY (email) REFERENCES Premium(email),
    FOREIGN KEY (id) REFERENCES Domanda(id)
);

CREATE TABLE InserimentoDA (
    emailA VARCHAR(30) NOT NULL,
    id INT NOT NULL,
    FOREIGN KEY (emailA) REFERENCES Azienda(emailA),
    FOREIGN KEY (id) REFERENCES Domanda(id)
);

CREATE TABLE Storico (
    email VARCHAR(30),
    nome VARCHAR(255),
    FOREIGN KEY (email) REFERENCES Utente(email),
    FOREIGN KEY (nome) REFERENCES Premio(nome)
);

CREATE TABLE Interesse (
    email VARCHAR(30),
    codice INT(10) NOT NULL,
    dominio VARCHAR(50),
    FOREIGN KEY (email) REFERENCES Utente(email),
    FOREIGN KEY (codice) REFERENCES Sondaggio(codice),
    FOREIGN KEY (dominio) REFERENCES Sondaggio(dominio)
);

                                    -- DA VERIFICARE
CREATE TABLE RispostaAperta (
    emailUtente VARCHAR(30),
    id INT,
    scelta VARCHAR(255), 
    FOREIGN KEY (emailUtente) REFERENCES Utente(email),
    FOREIGN KEY (id) REFERENCES Aperta(id)
);

CREATE TABLE RispostaChiusa (
    emailUtente VARCHAR(30),
    id INT,
    scelta VARCHAR(255),
    opzione2 VARCHAR(255),
    opzione3 VARCHAR(255),
    FOREIGN KEY (emailUtente) REFERENCES Utente(email),
    FOREIGN KEY (id) REFERENCES Aperta(id)
);

                                /*FINE STRUTTURA DB*/

                                /*POPOLAMENTO TABELLE*/
DELIMITER //

CREATE PROCEDURE PopolamentoTabelle()
BEGIN
/*codice di popolamento solo se le tabelle sono vuote*/
IF (
    (SELECT COUNT(*) FROM Utente) = 0 AND
    (SELECT COUNT(*) FROM Premio) = 0 AND
    (SELECT COUNT(*) FROM Sondaggio) = 0 AND
    (SELECT COUNT(*) FROM Domanda) = 0 AND
    (SELECT COUNT(*) FROM Azienda) = 0
)

THEN

-- Popolamento della tabella Utente
INSERT INTO Utente (email, password, nome, cognome, anno, luogoNascita, totaleBonus)
VALUES
    ('utente1@mail.com', '123', 'Mario', 'Rossi', 1990, 'Roma', 100.00),
    ('utente2@mail.com', '123', 'Laura', 'Bianchi', 1985, 'Milano', 50.00),
    ('utente3@mail.com', '123', 'Giuseppe', 'Verdi', 1982, 'Napoli', 75.00),
    ('utente4@mail.com', '123', 'Anna', 'Gialli', 1995, 'Firenze', 30.00),
    ('utente5@mail.com', '123', 'Luigi', 'Neri', 1998, 'Torino', 80.00);

-- Popolamento della tabella Premio
INSERT INTO Premio (nome, descrizione, foto, punti)
VALUES
    ('Premio1', 'Descrizione premio 1', '../Z)FOTO/premio (1).jpg', 100),
    ('Premio2', 'Descrizione premio 2', '../Z)FOTO/premio (2).jpg', 50),
    ('Premio3', 'Descrizione premio 3', '../Z)FOTO/premio (3).jpg', 75),
    ('Premio4', 'Descrizione premio 4', '../Z)FOTO/premio (4).jpg', 30),
    ('Premio5', 'Descrizione premio 5', '../Z)FOTO/premio (5).jpg', 80);

-- Popolamento della tabella Sondaggio
INSERT INTO Sondaggio (dominio, descrizione, titolo, maxUtenti)
VALUES
    ('Generale', 'Sondaggi con domande varie di cultura generale', 'Cultura Generale Base', 5),
    ('Geografia', 'Sondaggi con domande varie di geografia', 'Mari e Monti', 3),
    ('Storia', 'Sondaggi con domande varie di storia', 'Storia Romana', 4),
    ('Storia', 'Sondaggi con domande varie di storia', 'Storia Contemporanea', 4),
    ('Motori', 'Sondaggi con domande varie sul mondo dei motori', 'Tecnica dei motori', 10),
    ('Sport', 'Sondaggi con domande varie sullo sport', 'Pillole di sport', 8),
    ('Matematica', 'Sondaggi con facili quiz matematici', 'Matematica Facile', 5);

-- Popolamento della tabella Domanda
INSERT INTO Domanda (testo, punteggio, foto)
VALUES
    ('Testo domanda 1', 10, 'foto_domanda1.jpg'),
    ('Testo domanda 2', 5, 'foto_domanda2.jpg'),
    ('Testo domanda 3', 8, 'foto_domanda3.jpg'),
    ('Testo domanda 4', 3, 'foto_domanda4.jpg'),
    ('Testo domanda 5', 7, 'foto_domanda5.jpg');

-- Popolamento della tabella Azienda
INSERT INTO Azienda (emailA, codFiscale, nome, password, sede)
VALUES
    ('google@azienda.com', '000001', 'Google', '123', 'Milano'),
    ('sony@azienda.com', '000011', 'Sony', '123', 'Roma'),
    ('nike@azienda.com', '000111', 'Nike', '123', 'Firenze'),
    ('honda@azienda.com', '001111', 'Honda', '123', 'Napoli'),
    ('apple@azienda.com', '011111', 'Apple', '123', 'Torino');


                /*FINE POPOLAMENTO TABELLE*/
END IF;
END //

DELIMITER ;

CALL PopolamentoTabelle();







