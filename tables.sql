#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

#------------------------------------------------------------
# Database creation
#------------------------------------------------------------

DROP DATABASE IF EXISTS note_organizer;
CREATE DATABASE note_organizer;
USE note_organizer;


#------------------------------------------------------------
# Table: note
#------------------------------------------------------------

CREATE TABLE note(
        id          int (11) Auto_increment  NOT NULL,
        title       Varchar (150) NOT NULL,
        description Text,
        PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: categorie
#------------------------------------------------------------

CREATE TABLE categorie(
        id   int (11) Auto_increment  NOT NULL,
        name Varchar (150) NOT NULL,
        id_parent Int,
        PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: note_categorie
#------------------------------------------------------------

CREATE TABLE note_categorie(
        id_note      Int NOT NULL,
        id_categorie Int NOT NULL,
        PRIMARY KEY (id_note ,id_categorie)
)ENGINE=InnoDB;

#------------------------------------------------------------
# Foreign keys
#------------------------------------------------------------

ALTER TABLE categorie ADD CONSTRAINT FK_categorie_id_parent FOREIGN KEY (id_parent) REFERENCES categorie(id);
ALTER TABLE note_categorie ADD CONSTRAINT FK_note_categorie_id FOREIGN KEY (id_note) REFERENCES note(id);
ALTER TABLE note_categorie ADD CONSTRAINT FK_note_categorie_id_categorie FOREIGN KEY (id_categorie) REFERENCES categorie(id);


#------------------------------------------------------------
# Test inserts
#------------------------------------------------------------

INSERT INTO note (id, title, description) VALUES (1, "oui", "oui"), (2, "non", "non");
INSERT INTO categorie (id, name, id_parent) VALUES (1, "oui ou non", NULL), (2, "oui", 1);
INSERT INTO note_categorie (id_note, id_categorie) VALUES (1, 2), (2, 1), (1, 1);
