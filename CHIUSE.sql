    USE Sondaggi23;

        SET @codice = 5;
        SET @dominio = 'Motori';

        SET @domanda1 = 'Quale tipo di motore trovi più ecologicamente sostenibile?';
        SET @domanda2 = 'Quale aspetto del motore consideri più importante per un veicolo fuoristrada?';
        SET @domanda3 = 'Quale tecnologia di risparmio carburante ti sembra più interessante?';
        SET @domanda4 = 'Quale tipo di veicolo preferisci per un lungo viaggio su strada?';
        SET @domanda5 = 'Quale caratteristica ritieni più importante per un veicolo elettrico?';
        SET @domanda6 = 'Quale tipo di motore ti attira di più per le moto?';
        SET @domanda7 = 'Quale aspetto consideri più importante per un veicolo utilitario?';
        SET @domanda8 = 'Quale tipo di motore preferisci per un\'auto da corsa?';

        SET @opzione1a = 'Motore a combustione interna con tecnologia ibrida';
        SET @opzione1b = 'Motore elettrico alimentato da batterie ricaricabili';
        SET @opzione1c = 'Motore a idrogeno con celle a combustibile';
        SET @opzione2a = 'Coppia motrice';
        SET @opzione2b = 'Capacità di superare ostacoli';
        SET @opzione2c = 'Resistenza e affidabilità';
        SET @opzione3a = 'Start-stop automatico';
        SET @opzione3b = 'Sistema di recupero dell\'energia in frenata';
        SET @opzione3c = 'Sistemi di propulsione ibrida';
        SET @opzione4a = 'Auto berlina';
        SET @opzione4b = 'SUV';
        SET @opzione4c = 'Veicolo camper o caravan';
        SET @opzione5a = 'Autonomia (distanza che può percorrere con una singola carica)';
        SET @opzione5b = 'Tempi di ricarica veloci';
        SET @opzione5c = 'Presenza di una vasta rete di stazioni di ricarica';
        SET @opzione6a = 'Motore a quattro cilindri';
        SET @opzione6b = 'Motore a due cilindri';
        SET @opzione6c = 'Motore a tre cilindri';
        SET @opzione7a = 'Capacità di carico';
        SET @opzione7b = 'Versatilità degli spazi interni';
        SET @opzione7c = 'Consumo di carburante ridotto';
        SET @opzione8a = 'Motore V8 aspirato';
        SET @opzione8b = 'Motore turbo';
        SET @opzione8c = 'Motore ibrido ad alte prestazioni';
    -- 													DOMANDA 1 !!!!!!!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda1, 30, '../Z)FOTO/motori (7).jpg');

    SET @domandaId1 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId1);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione1a, @domandaId1);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione1b, @domandaId1);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione1c, @domandaId1);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId1);

    -- 												DOMANDA 2 !!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda2, 40, '../Z)FOTO/motori (8).jpg');

    SET @domandaId2 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId2);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione2a, @domandaId2);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione2b, @domandaId2);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione2c, @domandaId2);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId2);
    -- 													DOMANDA 3 !!!!!!!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda3, 10, '../Z)FOTO/motori (9).jpg');

    SET @domandaId3 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId3);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione3a, @domandaId3);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione3b, @domandaId3);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione3c, @domandaId3);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId3);

    -- 												DOMANDA 4 !!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda4, 15, '../Z)FOTO/motori (10).jpg');

    SET @domandaId4 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId4);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione4a, @domandaId4);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione4b, @domandaId4);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione4c, @domandaId4);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId4);
    -- 													DOMANDA 5 !!!!!!!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda5, 20, '../Z)FOTO/motori (11).jpg');

    SET @domandaId5 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId5);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione5a, @domandaId5);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione5b, @domandaId5);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione5c, @domandaId5);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId5);

    -- 												DOMANDA 6 !!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda6, 25, '../Z)FOTO/motori (12).jpg');

    SET @domandaId6 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId6);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione6a, @domandaId6);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione6b, @domandaId6);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione6c, @domandaId6);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId6);
    -- 													DOMANDA 7 !!!!!!!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda7, 50, '../Z)FOTO/motori (13).jpg');

    SET @domandaId7 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId7);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione7a, @domandaId7);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione7b, @domandaId7);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione7c, @domandaId7);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId7);

    -- 												DOMANDA 8 !!!!!!!!!!!!!!!
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda8, 45, '../Z)FOTO/motori (14).jpg');

    SET @domandaId8 = LAST_INSERT_ID();

    INSERT INTO Chiusa (id)
    VALUES (@domandaId8);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione8a, @domandaId8);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione8b, @domandaId8);

    INSERT INTO Opzione (testo, id)
    VALUES (@opzione8c, @domandaId8);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @domandaId8);