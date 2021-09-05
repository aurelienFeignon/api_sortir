<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905133237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE campus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE etat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lieu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE participant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sortie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ville_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE campus (id INT NOT NULL, nom VARCHAR(150) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE etat (id INT NOT NULL, libelle VARCHAR(8) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lieu (id INT NOT NULL, ville_id INT NOT NULL, nom VARCHAR(150) NOT NULL, rue VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F577D59A73F0036 ON lieu (ville_id)');
        $this->addSql('CREATE TABLE participant (id INT NOT NULL, campus_id INT NOT NULL, nom VARCHAR(70) NOT NULL, prenom VARCHAR(70) NOT NULL, username VARCHAR(50) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, chemin_img VARCHAR(255) DEFAULT NULL, administrateur BOOLEAN NOT NULL, actif BOOLEAN NOT NULL, api_token VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11F85E0677 ON participant (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11E7927C74 ON participant (email)');
        $this->addSql('CREATE INDEX IDX_D79F6B11AF5D55E1 ON participant (campus_id)');
        $this->addSql('CREATE TABLE sortie (id INT NOT NULL, organisateur_id INT NOT NULL, etat_id INT NOT NULL, campus_id INT NOT NULL, lieu_id INT NOT NULL, nom VARCHAR(100) NOT NULL, date_heure_debut TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duree INT NOT NULL, nb_inscription_max INT NOT NULL, infos_sortie VARCHAR(255) NOT NULL, date_limite_inscriptions DATE NOT NULL, motif VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D5E86FF ON sortie (etat_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2AF5D55E1 ON sortie (campus_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F26AB213CC ON sortie (lieu_id)');
        $this->addSql('CREATE TABLE sortie_participant (sortie_id INT NOT NULL, participant_id INT NOT NULL, PRIMARY KEY(sortie_id, participant_id))');
        $this->addSql('CREATE INDEX IDX_E6D4CDADCC72D953 ON sortie_participant (sortie_id)');
        $this->addSql('CREATE INDEX IDX_E6D4CDAD9D1C3019 ON sortie_participant (participant_id)');
        $this->addSql('CREATE TABLE ville (id INT NOT NULL, nom VARCHAR(85) NOT NULL, code_postal VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE lieu ADD CONSTRAINT FK_2F577D59A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F26AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie_participant ADD CONSTRAINT FK_E6D4CDADCC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sortie_participant ADD CONSTRAINT FK_E6D4CDAD9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11AF5D55E1');
        $this->addSql('ALTER TABLE sortie DROP CONSTRAINT FK_3C3FD3F2AF5D55E1');
        $this->addSql('ALTER TABLE sortie DROP CONSTRAINT FK_3C3FD3F2D5E86FF');
        $this->addSql('ALTER TABLE sortie DROP CONSTRAINT FK_3C3FD3F26AB213CC');
        $this->addSql('ALTER TABLE sortie DROP CONSTRAINT FK_3C3FD3F2D936B2FA');
        $this->addSql('ALTER TABLE sortie_participant DROP CONSTRAINT FK_E6D4CDAD9D1C3019');
        $this->addSql('ALTER TABLE sortie_participant DROP CONSTRAINT FK_E6D4CDADCC72D953');
        $this->addSql('ALTER TABLE lieu DROP CONSTRAINT FK_2F577D59A73F0036');
        $this->addSql('DROP SEQUENCE campus_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE etat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lieu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE participant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sortie_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ville_id_seq CASCADE');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('DROP TABLE sortie_participant');
        $this->addSql('DROP TABLE ville');
    }
}
