CREATE TABLE IF NOT EXISTS `nutzer` (
  `id`         INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name`       TEXT,
  `vorname`    TEXT,
  `strasse`    TEXT,
  `strasse_nr` TEXT,
  `plz`        TEXT,
  `ort`        TEXT,
  `email`      TEXT,
  `password`   TEXT,
  `is_admin`   TINYINT(1)               DEFAULT NULL
);

-- Administrator: admin@example.com -- Passwort: fa35fa35
INSERT INTO `nutzer` (`id`, `name`, `vorname`, `strasse`, `strasse_nr`, `plz`, `ort`, `email`, `password`, `is_admin`)
VALUES
  (1, 'Administrator', 'Jonas', 'Example Street', '23-27', '12345', 'Berlin', 'admin@example.com',
   'e816c565eed178edda8d7b67996f27c549e9cb9d3d5df2610e3841f0df1ca2f5', 1);

CREATE TABLE IF NOT EXISTS `artikel_gruppen` (
  `id`    INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `titel` TEXT
);

CREATE TABLE IF NOT EXISTS `steuersaetze` (
  `id`         INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `titel`      TEXT,
  `steuersatz` FLOAT                    DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `artikel` (
  `id`                 INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `titel`              TEXT,
  `artikel_gruppen_id` INT                      DEFAULT NULL,
  `netto_preis`        INT                      DEFAULT NULL,
  `mwst_satz`          INT                      DEFAULT NULL,
  `beschreibung`       TEXT,
  `bild_name`          TEXT,
  FOREIGN KEY (`mwst_satz`) REFERENCES `steuersaetze` (`id`),
  FOREIGN KEY (`artikel_gruppen_id`) REFERENCES `artikel_gruppen` (`id`)
);

CREATE TABLE IF NOT EXISTS `bestellungen` (
  `id`           INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nutzer_id`    INT(11)                  DEFAULT NULL,
  `datum`        DATETIME                 DEFAULT NULL,
  `zahl_art`     INT(11)                  DEFAULT NULL,
  `r_name`       TEXT,
  `r_vorname`    TEXT,
  `r_strasse_nr` TEXT,
  `r_plz_ort`    TEXT,
  `bezahlt`      TINYINT(1)               DEFAULT NULL,
  FOREIGN KEY (`nutzer_id`) REFERENCES `nutzer` (`id`),
  FOREIGN KEY (`zahl_art`) REFERENCES `zahlungsart` (`id`)
);

CREATE TABLE IF NOT EXISTS `bestellungs_details` (
  `id`              INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `bestellungen_id` INT(11)                  DEFAULT NULL,
  `artikel_id`      INT(11)                  DEFAULT NULL,
  `netto_preis`     INT(11)                  DEFAULT NULL,
  `mwst`            INT(11)                  DEFAULT NULL,
  `anzahl`          INT(11)                  DEFAULT NULL,
  FOREIGN KEY (`bestellungen_id`) REFERENCES `bestellungen` (`id`),
  FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`)
);

CREATE TABLE IF NOT EXISTS `zahlungsart` (
  `id`          INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `bezeichnung` TEXT
);
