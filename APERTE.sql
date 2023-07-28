    USE Sondaggi23;

    SET @codice = 5;
    SET @dominio = 'Motori';

    SET @domanda1 = 'Qual è la tua esperienza personale con i motori a combustione interna? Descrivi il tuo rapporto con i motori e cosa ti piace o non ti piace di loro.';
    SET @domanda2 = 'In base alla tua conoscenza dei motori e delle tecnologie attuali, quali miglioramenti ritieni siano ancora necessari per rendere i veicoli elettrici più convenienti, performanti e accessibili al grande pubblico? Cosa vorresti vedere sviluppato nell\'ambito dell\'elettrificazione dei trasporti?';
    SET @domanda3 = 'Secondo te, quali sono le principali innovazioni tecnologiche che potrebbero rivoluzionare ulteriormente il settore dei motori e dell\'automotive in generale nei prossimi anni?';
    SET @domanda4 = 'Immagina un futuro in cui i motori a combustione interna non esistono più. Cosa pensi che cambierebbe nell\'industria automobilistica e nell\'esperienza di guida? Quali sfide e opportunità vedresti in un mondo completamente elettrico?';
    SET @domanda5 = 'Quali sono le tue considerazioni riguardo all\'uso di motori ibridi (combustione interna ed elettrici) nei veicoli? Credi che possano rappresentare una soluzione efficace per affrontare le sfide ambientali e di efficienza energetica?';
    SET @domanda6 = 'Come pensi che l\'avvento dei motori elettrici abbia cambiato il panorama dell\'industria automobilistica e quali vantaggi vedi nell\'adozione di veicoli elettrici rispetto ai veicoli a combustione tradizionali?';
    SET @domanda7 = 'Qual è la tua opinione sui motori elettrici e sull\'adozione dei veicoli elettrici? Cosa ne pensi delle loro prestazioni, autonomia, impatto ambientale e infrastrutture di ricarica? Condividi la tua prospettiva e i tuoi punti di vista sull\'evoluzione dei motori nel contesto dell\'elettrificazione dei trasporti.';
    SET @domanda8 = 'Quali considerazioni fai quando scegli un\'automobile in base alle sue prestazioni e al motore? Cosa ti spinge a preferire determinati tipi di motori rispetto ad altri? Condividi le tue riflessioni e le tue preferenze personali.';
    SET @domanda9 = 'Immagina di poter progettare il motore dei tuoi sogni. Come sarebbe questo motore ideale? Descrivi le caratteristiche che ritieni importanti e spiega perché le hai scelte.';
    SET @domanda10 = 'Secondo te, quali sono i principali sviluppi tecnologici che hanno influenzato l\'industria dei motori negli ultimi anni? Spiega la tua opinione e fornisci esempi, se possibile.';

                                                    -- DOMANDA 1 --
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda1, 50, '../Z)FOTO/motori (15).jpg');

    SET @nuovoId1 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId1);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId1);
                                                    -- DOMANDA 2 -- 
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda2, 60, '../Z)FOTO/motori (16).jpg');

    SET @nuovoId2 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId2);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId2);
                                                    -- DOMANDA 3 --

    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda3, 70, '../Z)FOTO/motori (8).jpg');

    SET @nuovoId3 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId3);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId3);
                                                    -- DOMANDA 4 -- 
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda4, 75, '../Z)FOTO/motori (2).jpg');

    SET @nuovoId4 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId4);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId4);

                                                      -- DOMANDA 5 --
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda5, 66, '../Z)FOTO/motori (3).jpg');

    SET @nuovoId5 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId5);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId5);
                                                    -- DOMANDA 6 -- 
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda6, 90, '../Z)FOTO/motori (7).jpg');

    SET @nuovoId6 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId6);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId6);
                                                    -- DOMANDA 7 --

    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda7, 70, '../Z)FOTO/motori (8).jpg');

    SET @nuovoId7 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId7);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId7);
                                                    -- DOMANDA 8 -- 
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda8, 75, '../Z)FOTO/motori (10).jpg');

    SET @nuovoId8 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId8);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId8);

                                                    -- DOMANDA 9 --

    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda9, 80, '../Z)FOTO/motori (5).jpg');

    SET @nuovoId9 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId9);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId9);
                                                    -- DOMANDA 10 -- 
    INSERT INTO Domanda (testo, punteggio, foto)
    VALUES (@domanda10, 85, '../Z)FOTO/motori (13).jpg');

    SET @nuovoId10 = LAST_INSERT_ID();

    INSERT INTO Aperta (id)
    VALUES (@nuovoId10);

    INSERT INTO Contenuto (codice, dominio, id)
    VALUES (@codice, @dominio, @nuovoId10);  