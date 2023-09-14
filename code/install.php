<?php
    require_once("connect.php");

    if($mysqliConnection->connect_errno){
        printf("connection to database failed.<br />");
    }else{
        printf("connection to database enstablished.<br />");

        $deleteTable = mysqli_query($mysqliConnection,"
            DROP TABLE IF EXISTS utente;
        ");
        
        $createTable = mysqli_query($mysqliConnection,"
        CREATE TABLE IF NOT EXISTS utente (
            id int auto_increment PRIMARY KEY,
            username varchar(20) NOT NULL UNIQUE,
            password varchar (20) NOT NULL,
            nome varchar(20) NOT NULL,
            cognome varchar(20),
            indirizzo varchar(50),
            email varchar(320) NOT NULL,
            emailDiRecupero varchar(320),
            reputazione float DEFAULT 1,
            livelloAutorizzazione varchar(20) DEFAULT 'novizio',
            ruolo varchar(20) DEFAULT 'utente interno',
            stato varchar(10) DEFAULT 'attivo',
            
            CONSTRAINT checkReputazione CHECK (reputazione>=1 AND reputazione<=1000),
            CONSTRAINT checkLivAutoriz CHECK (livelloAutorizzazione='novizio'
                                        OR livelloAutorizzazione='principiante'
                                        OR livelloAutorizzazione='intermedio'
                                        OR livelloAutorizzazione='specialista'
                                        OR livelloAutorizzazione='innovatore'),
            CONSTRAINT checkRuolo CHECK (ruolo='utente interno'
                            OR ruolo='moderatore'
                            OR ruolo='admin'),
            CONSTRAINT checkStato CHECK (stato='attivo' OR stato='sospeso')
        );");

        $populateTable1 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione)
                            VALUES ('peaceWarrior108', '73he32jd', 'Socrate',
                            'Priverno', 'peacewarrior108@mail.org',
                            'peacewarrior108@mail.eu', 221, 'principiante');");

        $populateTable2 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione, ruolo)
                            VALUES ('master_mason84', 'h8d3d3j29', 'Gengi',
                            'Khanna', 'Toronto', 'gengikhanna@mail.org',
                            652, 'specialista', 'moderatore');");
        
        $populateTable3 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, email, 
                            emailDiRecupero, livelloAutorizzazione, ruolo)
                            VALUES ('ifritD3LaMancha', 'h38e932e3', 'Ifrit',
                            'Cervantes', 'ifritcervantes@mail.org',
                            'ifritcervantes@mail.eu', 'novizio', 'admin');");

        $populateTable4 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo, stato)
                            VALUES ('guruGang99', 'ndeidewi2', 'Guru',
                            'Gang', 'München', 'guru@mail.org', 'guruhype@mail.eu',
                            56, 'novizio', 'utente interno', 'sospeso');");
        
        $populateTable5 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo, stato)
                            VALUES ('mikasaAck', 'k3d29kdwkks', 'Mikasa',
                            'Tucasa', 'Tokyo', 'ackermann.m@mail.org', 'ackymic@mail.eu',
                            342, 'principiante', 'utente interno', 'attivo');");
        
        $populateTable6 = mysqli_query($mysqliConnection,"                            
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo, stato)
                            VALUES ('geppyMond1', 'newf39s2s', 'Gep',
                            'Mondano', 'Roma', 'geppy.mondanity.org', 'gep.gamb@mail.eu',
                            489, 'intermedio', 'moderatore', 'attivo');");
        
        $populateTable7 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, email, 
                            reputazione, livelloAutorizzazione)
                            VALUES ('sofi', 'sodyangel3l3', 'Sofi',
                            'sofi@mail.org', 676, 'specialista');");
        
        $populateTable8 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo, stato)
                            VALUES ('umberto', 'dni23dn3iv', 'Umberto', 'Galimberti',
                            'Monza', 'galimberti.u@mail.org', 'galim.greco@mail.eu', 900,
                            'innovatore', 'utente interno', 'attivo');");
        
        $populateTable9 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione, ruolo, stato)
                            VALUES ('marinaDiamond', 'xeniddwd', 'Marina',
                            'Diamantis', 'Ellada', 'mary.d@mail.org',
                            401, 'intermedio', 'utente interno', 'sospeso');");
        
        $populateTable10 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione, ruolo)
                            VALUES ('debyLin', 'nzosqow921', 'Debian',
                            'Linux', 'Helsinki', 'deby@mail.org',
                            69, 'novizio', 'moderatore');");
        
        $populateTable11 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione, ruolo)
                            VALUES ('marcusaureliusss', 'dmqiwdi3dj', 'Marcus',
                            'Aurelius', 'Roma', 'm.aurelius@imperium.org',
                            259, 'principiante', 'admin');");
        
        $populateTable12 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione, ruolo)
                            VALUES ('eschiLOL', 'gnotiS3uton', 'Kata',
                            'Metron', 'Qui e ora', 'katametron@mail.org',
                            138, 'novizio', 'utente interno');");
        
        $populateTable13 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo)
                            VALUES ('yogiSadh', 'Fbwsm89dd', 'Sadhasana',
                            'Meditatio', 'Bharat', 'sadhg@mail.org', 'sahds@mail.eu',
                            985, 'innovatore', 'utente interno');");
        
        $populateTable14 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo, stato)
                            VALUES ('abbaDocet', '1endherfW', 'Boss',
                            'Abba', 'Sweden', 'dancequeen@mail.org', 'mammamia@mail.eu',
                            12, 'novizio', 'admin', 'attivo');");
        
        $populateTable15 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, ruolo, stato)
                            VALUES ('fuckri', 'babylan2', 'Perseo',
                            'Persian', 'Babilonia', 'babeletower@mail.org',
                            602, 'moderatore', 'sospeso');");
        
        $populateTable16 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione, 
                            ruolo)
                            VALUES ('mamacass70', 'mkyrwnkndfmsc', 'Cass',
                            'Elliot', 'America', 'mamacass@mail.org', 'cohenmamy@mail.eu',
                            2, 'novizio', 'admin');");
        
        $populateTable17 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, email, 
                            emailDiRecupero, reputazione)
                            VALUES ('ethernalCircle0', 'etheyisqw', 'Ether',
                            'Nalcircle', 'ether.c@mail.org', 'nalcircle@mail.eu', 65);");
        
        $populateTable18 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione)
                            VALUES ('friedWNiet', 'ttyre2wn12lp', 'Friedrich',
                            'Nietzsche', 'Rocken', 'nice@mail.org', 'heidegger@mail.eu',
                            504, 'intermedio');");
        
        $populateTable19 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, email, 
                            emailDiRecupero, reputazione, livelloAutorizzazione)
                            VALUES ('lorrainClaude', 'i102r3nffa', 'Claude',
                            'Lorrain', 'lory.claude@mail.org', 'lorryys@mail.eu',
                            103, 'novizio');");
        
        $populateTable20 = mysqli_query($mysqliConnection,"
        INSERT INTO utente (username, password, nome, cognome, indirizzo, email, 
                            reputazione, livelloAutorizzazione)
                            VALUES ('gokusDragon', '4s32jaskS', 'Goku',
                            'Son', 'Vegeta', 's.goku@mail.org', 42, 'novizio');");

        if($deleteTable){
            printf("succeded in deleting previous table.<br />");
        }else{
            printf("failed in deleting previous table.<br />");
        }
        if($createTable){
            printf("succeded in creating new table.<br />");
        }else{
            printf("failed in creating new table.<br />");
        }
        if($populateTable1 && $populateTable2 && $populateTable3 && $populateTable4 && $populateTable5 && $populateTable6 && $populateTable7 && $populateTable8 && $populateTable9 && $populateTable10 && $populateTable11 && $populateTable12 && $populateTable13 && $populateTable14 && $populateTable15 && $populateTable16 && $populateTable17 && $populateTable18 && $populateTable19 && $populateTable20){
            printf("succeded in populating table.<br />");
        }else{
            printf("failed in populating table.<br />");
        }
    }
?>