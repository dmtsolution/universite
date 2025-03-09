-- Création de la base de données
CREATE DATABASE universite;

-- Utilisation de la base de données créée
USE universite;

-- Création des Tables

-- Table UTILISATEUR
CREATE TABLE UTILISATEUR (
id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
email_utilisateur VARCHAR(255) NOT NULL UNIQUE,
mot_de_passe VARCHAR(255) NOT NULL,
date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
role ENUM('enseignant', 'eleve', 'secretaire', 'admin') NOT NULL
);
-- Index pour optimiser l'authentification et la recherche par email
CREATE INDEX idx_utilisateur_email ON UTILISATEUR(email_utilisateur);
-- Index pour optimiser les requêtes filtrant par rôle
CREATE INDEX idx_utilisateur_role ON UTILISATEUR(role);
-- Index pour optimiser les requêtes sur la date d'inscription
CREATE INDEX idx_utilisateur_date ON UTILISATEUR(date_inscription);

-- Table ADMIN
CREATE TABLE ADMIN(
id INT AUTO_INCREMENT PRIMARY KEY,
id_admin INT NOT NULL,
nom_admin VARCHAR(50) NOT NULL,
prenom_admin VARCHAR(30) NOT NULL,
FOREIGN KEY (id_admin) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE
);
-- Index pour optimiser la recherche par nom
CREATE INDEX idx_admin_nom ON ADMIN(nom_admin, prenom_admin);

-- Table SECRETAIRE
CREATE TABLE SECRETAIRE(
id INT AUTO_INCREMENT PRIMARY KEY,
id_secretaire INT NOT NULL,
nom_secretaire VARCHAR(50) NOT NULL,
prenom_secretaire VARCHAR(30) NOT NULL,
FOREIGN KEY (id_secretaire) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE
);
-- Index pour optimiser la recherche par nom
CREATE INDEX idx_secretaire_nom ON SECRETAIRE(nom_secretaire, prenom_secretaire);

-- Table GROUPE
CREATE TABLE GROUPE (
id_groupe INT AUTO_INCREMENT PRIMARY KEY,
nom_groupe VARCHAR(50) NOT NULL UNIQUE,
specialite_groupe VARCHAR(30) NOT NULL
);
-- Index pour optimiser la recherche par spécialité
CREATE INDEX idx_groupe_specialite ON GROUPE(specialite_groupe);

-- Table ELEVE
CREATE TABLE ELEVE (
id INT AUTO_INCREMENT PRIMARY KEY,
id_eleve INT NOT NULL,
nom_eleve VARCHAR(50) NOT NULL,
prenom_eleve VARCHAR(30) NOT NULL,
date_naissance DATE NOT NULL,
sexe ENUM('masculin', 'feminin') NOT NULL,
id_groupe INT NULL,
FOREIGN KEY (id_groupe) REFERENCES GROUPE(id_groupe) ON DELETE SET NULL,
FOREIGN KEY (id_eleve) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE
);
-- Index pour optimiser la recherche par nom
CREATE INDEX idx_eleve_nom ON ELEVE(nom_eleve, prenom_eleve);
-- Index pour optimiser les requêtes par groupe
CREATE INDEX idx_eleve_groupe ON ELEVE(id_groupe);
-- Index pour optimiser les requêtes par date de naissance
CREATE INDEX idx_eleve_date_naissance ON ELEVE(date_naissance);

-- Table ENSEIGNANT
CREATE TABLE ENSEIGNANT (
id INT AUTO_INCREMENT PRIMARY KEY,
id_enseignant INT NOT NULL,
nom_enseignant VARCHAR(50) NOT NULL,
prenom_enseignant VARCHAR(30) NOT NULL,
specialite VARCHAR(30) NOT NULL,
fonction_enseignant ENUM('Vacataire', 'ATER', 'MdC', 'Professeur') NOT NULL,
FOREIGN KEY (id_enseignant) REFERENCES UTILISATEUR(id_utilisateur) ON DELETE CASCADE
);
-- Index pour optimiser la recherche par nom
CREATE INDEX idx_enseignant_nom ON ENSEIGNANT(nom_enseignant, prenom_enseignant);
-- Index pour optimiser les requêtes par spécialité
CREATE INDEX idx_enseignant_specialite ON ENSEIGNANT(specialite);
-- Index pour optimiser les requêtes par fonction
CREATE INDEX idx_enseignant_fonction ON ENSEIGNANT(fonction_enseignant);

-- Table COURS
CREATE TABLE COURS (
id_cours INT AUTO_INCREMENT PRIMARY KEY,
nom_cours VARCHAR(50) NOT NULL,
volume_horaire INT NOT NULL,
annee_cours INT NOT NULL,
semestre INT NOT NULL CHECK (semestre BETWEEN 1 AND 2),
id_enseignant INT NOT NULL,
FOREIGN KEY (id_enseignant) REFERENCES ENSEIGNANT(id_enseignant) ON DELETE CASCADE
);
-- Index pour optimiser la recherche des cours et leurs durées
CREATE INDEX idx_cours_duree ON COURS(id_cours, volume_horaire);
-- Index pour optimiser les requêtes par enseignant
CREATE INDEX idx_cours_enseignant ON COURS(id_enseignant);
-- Index pour optimiser les requêtes par année et semestre
CREATE INDEX idx_cours_annee_semestre ON COURS(annee_cours, semestre);
-- Index pour optimiser la recherche par nom de cours
CREATE INDEX idx_cours_nom ON COURS(nom_cours);

-- Table SEANCE
CREATE TABLE SEANCE (
id_seance INT AUTO_INCREMENT PRIMARY KEY,
titre_seance VARCHAR(50) NOT NULL,
description_seance VARCHAR(255) NULL,
date_seance DATE NOT NULL,
debut_seance TIME NOT NULL,
fin_seance TIME NOT NULL,
type_seance ENUM('CM', 'TD', 'TP') NOT NULL,
salle_seance VARCHAR(20) NOT NULL,
id_cours INT NOT NULL,
id_enseignant INT NOT NULL,
FOREIGN KEY (id_cours) REFERENCES COURS(id_cours) ON DELETE CASCADE,
FOREIGN KEY (id_enseignant) REFERENCES ENSEIGNANT(id_enseignant) ON DELETE CASCADE
);
-- Index pour optimiser la recherche des séances d'un cours spécifique
CREATE INDEX idx_seance_cours ON SEANCE(id_cours);
-- Index pour optimiser la recherche des séances d'un enseignant spécifique
CREATE INDEX idx_seance_enseignant ON SEANCE(id_enseignant);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_seance_date ON SEANCE(date_seance);
-- Index pour optimiser les requêtes par type de séance
CREATE INDEX idx_seance_type ON SEANCE(type_seance);
-- Index pour optimiser les requêtes par salle
CREATE INDEX idx_seance_salle ON SEANCE(salle_seance);
-- Index pour optimiser les requêtes d'emploi du temps (date + heures)
CREATE INDEX idx_seance_planning ON SEANCE(date_seance, debut_seance, fin_seance);

-- Table QUESTION
CREATE TABLE QUESTION(
id_question INT AUTO_INCREMENT PRIMARY KEY,
id_seance INT NOT NULL,
id_eleve INT NOT NULL,
question TEXT NOT NULL,
date_question DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_seance) REFERENCES SEANCE(id_seance) ON DELETE CASCADE,
FOREIGN KEY (id_eleve) REFERENCES ELEVE(id_eleve) ON DELETE CASCADE
);
-- Index pour optimiser les requêtes par séance
CREATE INDEX idx_question_seance ON QUESTION(id_seance);
-- Index pour optimiser les requêtes par élève
CREATE INDEX idx_question_eleve ON QUESTION(id_eleve);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_question_date ON QUESTION(date_question);
-- Index pour optimiser les requêtes combinées (élève + séance)
CREATE INDEX idx_question_eleve_seance ON QUESTION(id_eleve, id_seance);

-- Table EXERCICE
CREATE TABLE EXERCICE (
id_exercice INT AUTO_INCREMENT PRIMARY KEY,
titre_exercice VARCHAR(255) NOT NULL,
type_exercice ENUM('TD', 'TP') NOT NULL,
id_seance INT NOT NULL,
id_enseignant INT NOT NULL,
fichier_exercice VARCHAR(255) NOT NULL,
commentaire_exercice TEXT NULL,
date_exercice DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_seance) REFERENCES SEANCE(id_seance) ON DELETE CASCADE,
FOREIGN KEY (id_enseignant) REFERENCES ENSEIGNANT(id_enseignant) ON DELETE CASCADE
);
-- Index pour optimiser les requêtes par séance
CREATE INDEX idx_exercice_seance ON EXERCICE(id_seance);
-- Index pour optimiser les requêtes par enseignant
CREATE INDEX idx_exercice_enseignant ON EXERCICE(id_enseignant);
-- Index pour optimiser les requêtes par type d'exercice
CREATE INDEX idx_exercice_type ON EXERCICE(type_exercice);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_exercice_date ON EXERCICE(date_exercice);

-- Table DEPO_EXERCICE
CREATE TABLE DEPO_EXERCICE (
id_depo INT AUTO_INCREMENT PRIMARY KEY,
id_exercice INT NOT NULL,
id_eleve INT NOT NULL,
fichier_depo VARCHAR(255) NOT NULL,
commentaire_depo TEXT NULL,
date_depo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_exercice) REFERENCES EXERCICE(id_exercice) ON DELETE CASCADE,
FOREIGN KEY (id_eleve) REFERENCES ELEVE(id_eleve) ON DELETE CASCADE
);
-- Index pour optimiser les requêtes par exercice
CREATE INDEX idx_depo_exercice ON DEPO_EXERCICE(id_exercice);
-- Index pour optimiser les requêtes par élève
CREATE INDEX idx_depo_eleve ON DEPO_EXERCICE(id_eleve);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_depo_date ON DEPO_EXERCICE(date_depo);
-- Index pour optimiser les requêtes combinées (exercice + élève)
CREATE INDEX idx_depo_exercice_eleve ON DEPO_EXERCICE(id_exercice, id_eleve);

-- Table NOTE
CREATE TABLE NOTE (
id_note INT AUTO_INCREMENT PRIMARY KEY,
id_eleve INT NOT NULL,
id_depo INT NOT NULL,
date_note DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
points DECIMAL(5,2) NOT NULL CHECK (points BETWEEN 0 AND 20),
FOREIGN KEY (id_eleve) REFERENCES ELEVE(id_eleve) ON DELETE CASCADE,
FOREIGN KEY (id_depo) REFERENCES DEPO_EXERCICE(id_depo) ON DELETE CASCADE
);
-- Index pour optimiser les requêtes par élève
CREATE INDEX idx_note_eleve ON NOTE(id_eleve);
-- Index pour optimiser les requêtes par dépôt
CREATE INDEX idx_note_depo ON NOTE(id_depo);
-- Index pour optimiser les requêtes par points (pour les statistiques)
CREATE INDEX idx_note_points ON NOTE(points);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_note_date ON NOTE(date_note);
-- Index pour optimiser les requêtes combinées (élève + points)
CREATE INDEX idx_note_eleve_points ON NOTE(id_eleve, points);

-- Table EXAMEN
CREATE TABLE EXAMEN (
id_examen INT AUTO_INCREMENT PRIMARY KEY,
id_eleve INT NOT NULL,
id_cours INT NOT NULL,
points DECIMAL(5,2) NOT NULL CHECK (points BETWEEN 0 AND 20),
explication TEXT NULL,
date_note DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_eleve) REFERENCES ELEVE(id_eleve) ON DELETE CASCADE,
FOREIGN KEY (id_cours) REFERENCES COURS(id_cours) ON DELETE CASCADE,
UNIQUE (id_eleve, id_cours)
);
-- Index pour optimiser la recherche des notes obtenues par un élève dans un cours
CREATE INDEX idx_examen_eleve_cours ON EXAMEN(id_eleve, id_cours, points);
-- Index pour optimiser le calcul de la moyenne, note max et min d'un élève
CREATE INDEX idx_examen_eleve ON EXAMEN(id_eleve, points);
-- Index pour optimiser les requêtes par cours
CREATE INDEX idx_examen_cours ON EXAMEN(id_cours);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_examen_date ON EXAMEN(date_note);
-- Index pour optimiser les statistiques par cours
CREATE INDEX idx_examen_cours_points ON EXAMEN(id_cours, points);

-- Table REPONSE
CREATE TABLE REPONSE (
id_reponse INT AUTO_INCREMENT PRIMARY KEY,
id_question INT NOT NULL,
id_enseignant INT NOT NULL,
reponse TEXT NOT NULL,
date_reponse DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_question) REFERENCES QUESTION(id_question) ON DELETE CASCADE,
FOREIGN KEY (id_enseignant) REFERENCES ENSEIGNANT(id_enseignant) ON DELETE CASCADE
);
-- Index pour optimiser les requêtes par question
CREATE INDEX idx_reponse_question ON REPONSE(id_question);
-- Index pour optimiser les requêtes par enseignant
CREATE INDEX idx_reponse_enseignant ON REPONSE(id_enseignant);
-- Index pour optimiser les requêtes par date
CREATE INDEX idx_reponse_date ON REPONSE(date_reponse);