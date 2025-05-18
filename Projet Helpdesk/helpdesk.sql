CREATE DATABASE IF NOT EXISTS helpdesk DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE helpdesk;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('technicien') DEFAULT 'technicien'
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    categorie VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    statut ENUM('ouvert', 'en_cours', 'fermé') DEFAULT 'ouvert',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle_creation_hash VARCHAR(255)
);
