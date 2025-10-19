-- ============================================
-- BASE DE DONNEES COMPLETE : SUPERMARCHER
-- Version finale avec triggers, vues et données test
-- ============================================

CREATE DATABASE IF NOT EXISTS `supermarcher` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `supermarcher`;

-- ============================================
-- 1) Utilisateur
-- ============================================
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(100),
  `prenom` VARCHAR(100),
  `email` VARCHAR(150) UNIQUE,
  `role` VARCHAR(50),
  `pwd` VARCHAR(50),
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO utilisateur(nom, prenom, email, role, pwd) VALUES
('Feza', 'Janvier', 'gradi@gmail.com', 'admin', '123456'),
('Doe', 'John', 'john@gmail.com', 'caissier', '123456');

-- ============================================
-- 2) Taux
-- ============================================
CREATE TABLE IF NOT EXISTS `taux` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `designation` VARCHAR(50) NOT NULL,
  `remise` DECIMAL(5,2) DEFAULT 0,
  `taux_cdf` DECIMAL(18,4) DEFAULT 1,
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO taux(designation, remise, taux_cdf) VALUES
('Standard', 0, 2000),
('VIP', 10, 2000);

-- ============================================
-- 3) Categorie
-- ============================================
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `designation` VARCHAR(50),
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` INT DEFAULT NULL,
  KEY `fk_categorie1_idx` (`id_utilisateur`),
  CONSTRAINT `fk_categorie1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO categorie(designation, id_utilisateur) VALUES
('Boissons',1),
('Alimentation',1);

-- ============================================
-- 4) Produit
-- ============================================
CREATE TABLE IF NOT EXISTS `produit` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_categorie` INT DEFAULT NULL,
  `designation` VARCHAR(255),
  `prix_unitaire` DECIMAL(13,4) NOT NULL DEFAULT 0,
  `stock` DECIMAL(13,4) DEFAULT 0,
  `stock_alerte` DECIMAL(13,4) DEFAULT 0,
  `id_utilisateur` INT DEFAULT NULL,
  `prix_unitaire_achat` DECIMAL(13,4) DEFAULT 0,
  KEY `produit_ibfk1_idx` (`id_categorie`),
  KEY `produit_ibfk2_idx` (`id_utilisateur`),
  CONSTRAINT `produit_ibfk1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`) ON DELETE SET NULL,
  CONSTRAINT `produit_ibfk2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO produit(id_categorie, designation, prix_unitaire, stock, stock_alerte, prix_unitaire_achat) VALUES
(1,'Coca Cola', 1.5, 100, 10, 1.0),
(2,'Pain', 0.5, 200, 20, 0.3);

-- ============================================
-- 5) Clients
-- ============================================
CREATE TABLE IF NOT EXISTS `clients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `noms` VARCHAR(255),
  `Numero` VARCHAR(45),
  `email` VARCHAR(255),
  `type_client` VARCHAR(45) DEFAULT 'non_abonnée',
  `id_taux` INT DEFAULT NULL,
  `validation` VARCHAR(45) DEFAULT 'false',
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_idfk1_clients_idx` (`id_taux`),
  CONSTRAINT `fk_idfk1_clients` FOREIGN KEY (`id_taux`) REFERENCES `taux` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO clients(noms, Numero, email, id_taux) VALUES
('Alice', '0999123456','alice@gmail.com',1),
('Bob', '0999876543','bob@gmail.com',2);

-- ============================================
-- 6) TypeOperation
-- ============================================
CREATE TABLE IF NOT EXISTS `typeoperation` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `categorie` VARCHAR(255),
  `designation` VARCHAR(255),
  `description` VARCHAR(255),
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO typeoperation(categorie, designation, description) VALUES
('Vente','Vente produit','Vente d un produit'),
('Approvisionnement','Achat produit','Approvisionnement du stock');

-- ============================================
-- 7) Approvisionnement
-- ============================================
CREATE TABLE IF NOT EXISTS `approvisionnement` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_produit` INT DEFAULT NULL,
  `quantite` DECIMAL(13,4) DEFAULT 0,
  `prix_unitaire_achat` DECIMAL(13,4) DEFAULT 0,
  `id_utilisateur` INT DEFAULT NULL,
  `dte_operatoire` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `approv_fk_produit` FOREIGN KEY (`id_produit`) REFERENCES produit(`id`) ON DELETE SET NULL,
  CONSTRAINT `approv_fk_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES utilisateur(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO approvisionnement(id_produit, quantite, prix_unitaire_achat, id_utilisateur) VALUES
(1,50,1.0,1),
(2,100,0.3,1);

-- ============================================
-- 8) Flux (ventes)
-- ============================================
CREATE TABLE IF NOT EXISTS `flux` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `numero_facture` VARCHAR(255),
  `quantite` DECIMAL(13,4) DEFAULT 0,
  `prix_unitaire` DECIMAL(13,4) DEFAULT 0,
  `id_typeoperation` INT DEFAULT NULL,
  `id_client` INT DEFAULT NULL,
  `id_produit` INT DEFAULT NULL,
  `id_utilisateur` INT DEFAULT NULL,
  `dte_operatoire` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `flux_ibfk_1` FOREIGN KEY (`id_typeoperation`) REFERENCES typeoperation(`id`) ON DELETE SET NULL,
  CONSTRAINT `flux_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES clients(`id`) ON DELETE SET NULL,
  CONSTRAINT `flux_ibfk_3` FOREIGN KEY (`id_produit`) REFERENCES produit(`id`) ON DELETE SET NULL,
  CONSTRAINT `flux_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES utilisateur(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO flux(numero_facture, quantite, prix_unitaire, id_typeoperation, id_client, id_produit, id_utilisateur) VALUES
('FAC001',10,1.5,1,1,1,2),
('FAC002',20,0.5,1,2,2,2);

-- ============================================
-- 9) Paiement
-- ============================================
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `dte_operatoire` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `MontantVerser` DECIMAL(24,2) DEFAULT NULL,
  `Operateur` VARCHAR(45),
  `numero_facture` VARCHAR(255),
  `id_client` INT DEFAULT NULL,
  CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES clients(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO paiement(MontantVerser, Operateur, numero_facture, id_client) VALUES
(15,'Doe','FAC001',1),
(10,'Doe','FAC002',2);

-- ============================================
-- 10) Mouvement caisse
-- ============================================
CREATE TABLE IF NOT EXISTS `mouvcaisse` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `dte_operatoire` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `montant` DECIMAL(24,2) DEFAULT 0,
  `type_mouvement` ENUM('entree','sortie') NOT NULL DEFAULT 'entree',
  `id_typeoperation` INT DEFAULT NULL,
  `id_charge` INT DEFAULT NULL,
  `id_paiement` INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- 11) Caisse
-- ============================================
CREATE TABLE IF NOT EXISTS `caisse` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `solde` DECIMAL(24,2) NOT NULL,
  `dateUpdate` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `description` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO caisse(solde, description) VALUES
(0,'Solde initial');

-- ============================================
-- 12) Charges
-- ============================================
CREATE TABLE IF NOT EXISTS `charge_type` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `designation` VARCHAR(150) NOT NULL,
  `description` VARCHAR(512),
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO charge_type(designation, description) VALUES
('Loyer','Paiement du loyer du magasin'),
('Electricite','Facture electricite');

CREATE TABLE IF NOT EXISTS `charge_depense` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `libelle` VARCHAR(255) NOT NULL,
  `montant` DECIMAL(24,2) NOT NULL,
  `dte_operatoire` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` INT NULL,
  `id_type` INT NULL,
  `dateAdd` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_charge_depense_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_charge_depense_type` FOREIGN KEY (`id_type`) REFERENCES `charge_type`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO charge_depense(libelle, montant, id_utilisateur, id_type) VALUES
('Loyer janvier', 200,1,1),
('Electricite janvier', 50,1,2);

-- ============================================
-- 13) Triggers automatiques
-- ============================================
DELIMITER $$

-- 13.1 Flux -> decrementer stock
CREATE TRIGGER trg_flux_after_insert
AFTER INSERT ON flux
FOR EACH ROW
BEGIN
  UPDATE produit SET stock = stock - NEW.quantite WHERE id = NEW.id_produit;
END$$

-- 13.2 Approvisionnement -> incrementer stock
CREATE TRIGGER trg_approv_after_insert
AFTER INSERT ON approvisionnement
FOR EACH ROW
BEGIN
  UPDATE produit SET stock = stock + NEW.quantite, prix_unitaire_achat = NEW.prix_unitaire_achat WHERE id = NEW.id_produit;
END$$

-- 13.3 Paiement -> mise a jour caisse
CREATE TRIGGER trg_paiement_after_insert
AFTER INSERT ON paiement
FOR EACH ROW
BEGIN
  INSERT INTO mouvcaisse(dte_operatoire, montant, type_mouvement, id_paiement)
  VALUES(NEW.dte_operatoire, NEW.MontantVerser, 'entree', NEW.id);
  UPDATE caisse SET solde = solde + NEW.MontantVerser ORDER BY id DESC LIMIT 1;
END$$

-- 13.4 Charge_depense -> sortie caisse
CREATE TRIGGER trg_charge_after_insert
AFTER INSERT ON charge_depense
FOR EACH ROW
BEGIN
  INSERT INTO mouvcaisse(dte_operatoire, montant, type_mouvement, id_charge)
  VALUES(NEW.dte_operatoire, NEW.montant, 'sortie', NEW.id);
  UPDATE caisse SET solde = solde - NEW.montant ORDER BY id DESC LIMIT 1;
END$$

DELIMITER ;

-- ============================================
-- 14) Vues / rapports
-- ============================================

-- 14.1 Ventes par produit
CREATE OR REPLACE VIEW vw_ventes_produit AS
SELECT p.designation AS produit, SUM(f.quantite) AS quantite_vendue, SUM(f.quantite*f.prix_unitaire) AS ca_usd
FROM flux f
LEFT JOIN produit p ON p.id = f.id_produit
GROUP BY p.designation;

-- 14.2 Top clients
CREATE OR REPLACE VIEW vw_top_clients AS
SELECT c.noms AS client, SUM(f.quantite*f.prix_unitaire) AS total_depense
FROM flux f
LEFT JOIN clients c ON c.id = f.id_client
GROUP BY c.noms
ORDER BY total_depense DESC;

-- 14.3 Alertes stock
CREATE OR REPLACE VIEW vw_alertes_stock AS
SELECT designation, stock, stock_alerte
FROM produit
WHERE stock <= stock_alerte;

-- 14.4 Bénéfices journaliers
CREATE OR REPLACE VIEW vw_benefices_journaliers AS
SELECT DATE(f.dte_operatoire) AS date_vente,
SUM((f.prix_unitaire - p.prix_unitaire_achat)*f.quantite) AS benefice
FROM flux f
LEFT JOIN produit p ON p.id = f.id_produit
GROUP BY DATE(f.dte_operatoire)
ORDER BY DATE(f.dte_operatoire) DESC;

-- ============================================
-- SCRIPT FINAL COMPLET AVEC DONNEES TEST
-- ============================================
