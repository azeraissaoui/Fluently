<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260207122537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, ressource VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, cours_precedent_id_id INT DEFAULT NULL, id_niveau_id INT NOT NULL, UNIQUE INDEX UNIQ_FDCA8C9CC603F320 (cours_precedent_id_id), INDEX IDX_FDCA8C9C8B0B20A6 (id_niveau_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, capacite INT NOT NULL, statut VARCHAR(50) NOT NULL, date_creation DATE NOT NULL, id_langue_id INT NOT NULL, id_niveau_id INT NOT NULL, INDEX IDX_4B98C21AA9806EA (id_langue_id), INDEX IDX_4B98C218B0B20A6 (id_niveau_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE groupe_user (groupe_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_257BA9FE7A45358C (groupe_id), INDEX IDX_257BA9FEA76ED395 (user_id), PRIMARY KEY (groupe_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, drapeau VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, popularite VARCHAR(50) NOT NULL, date_ajout DATE NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE langue_user (langue_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B11E19E32AADBACD (langue_id), INDEX IDX_B11E19E3A76ED395 (user_id), PRIMARY KEY (langue_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(255) NOT NULL, type_message VARCHAR(50) NOT NULL, emoji_react VARCHAR(255) DEFAULT NULL, is_epingle TINYINT NOT NULL, date_creation DATE NOT NULL, date_modif DATE NOT NULL, statut_message VARCHAR(50) NOT NULL, id_groupe_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, INDEX IDX_B6BD307FFA7089AB (id_groupe_id), INDEX IDX_B6BD307F79F37AE5 (id_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image_couverture VARCHAR(255) NOT NULL, difficulte VARCHAR(50) NOT NULL, ordre INT NOT NULL, seuil_score_max DOUBLE PRECISION NOT NULL, seuil_score_min DOUBLE PRECISION NOT NULL, id_langue_id INT NOT NULL, INDEX IDX_4BDFF36BAA9806EA (id_langue_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE objectif (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, date_deb DATE NOT NULL, date_fin DATE NOT NULL, statut VARCHAR(100) NOT NULL, id_user_id INT NOT NULL, INDEX IDX_E2F8685179F37AE5 (id_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, enonce VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, score_max DOUBLE PRECISION NOT NULL, id_test_id INT NOT NULL, INDEX IDX_B6F7494EC0C0AD29 (id_test_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, contenu_rep VARCHAR(255) NOT NULL, is_correct TINYINT NOT NULL, score DOUBLE PRECISION NOT NULL, date_reponse DATE NOT NULL, id_question_id INT NOT NULL, INDEX IDX_5FB6DEC76353B48 (id_question_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_reservation DATE NOT NULL, statut VARCHAR(50) NOT NULL, id_session_id INT NOT NULL, id_user_id INT NOT NULL, INDEX IDX_42C84955C4B56C08 (id_session_id), INDEX IDX_42C8495579F37AE5 (id_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, date_heure DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, lien_reunion VARCHAR(255) NOT NULL, id_group_id INT NOT NULL, id_user_id INT NOT NULL, INDEX IDX_D044D5D4AE8F35D2 (id_group_id), INDEX IDX_D044D5D479F37AE5 (id_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(100) NOT NULL, date_limite DATE NOT NULL, statut VARCHAR(50) NOT NULL, priorite VARCHAR(50) NOT NULL, id_objectif_id INT NOT NULL, INDEX IDX_93872075D6FD723 (id_objectif_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, date_passage DATE NOT NULL, resultat DOUBLE PRECISION NOT NULL, duree TIME NOT NULL, type VARCHAR(50) NOT NULL, id_langue_id INT NOT NULL, id_user_id INT NOT NULL, INDEX IDX_D87F7E0CAA9806EA (id_langue_id), INDEX IDX_D87F7E0C79F37AE5 (id_user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, role VARCHAR(100) NOT NULL, statut VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CC603F320 FOREIGN KEY (cours_precedent_id_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C8B0B20A6 FOREIGN KEY (id_niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21AA9806EA FOREIGN KEY (id_langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C218B0B20A6 FOREIGN KEY (id_niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE groupe_user ADD CONSTRAINT FK_257BA9FE7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_user ADD CONSTRAINT FK_257BA9FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE langue_user ADD CONSTRAINT FK_B11E19E32AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE langue_user ADD CONSTRAINT FK_B11E19E3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FFA7089AB FOREIGN KEY (id_groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36BAA9806EA FOREIGN KEY (id_langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE objectif ADD CONSTRAINT FK_E2F8685179F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC0C0AD29 FOREIGN KEY (id_test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C4B56C08 FOREIGN KEY (id_session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4AE8F35D2 FOREIGN KEY (id_group_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075D6FD723 FOREIGN KEY (id_objectif_id) REFERENCES objectif (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CAA9806EA FOREIGN KEY (id_langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CC603F320');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C8B0B20A6');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21AA9806EA');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C218B0B20A6');
        $this->addSql('ALTER TABLE groupe_user DROP FOREIGN KEY FK_257BA9FE7A45358C');
        $this->addSql('ALTER TABLE groupe_user DROP FOREIGN KEY FK_257BA9FEA76ED395');
        $this->addSql('ALTER TABLE langue_user DROP FOREIGN KEY FK_B11E19E32AADBACD');
        $this->addSql('ALTER TABLE langue_user DROP FOREIGN KEY FK_B11E19E3A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FFA7089AB');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F79F37AE5');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36BAA9806EA');
        $this->addSql('ALTER TABLE objectif DROP FOREIGN KEY FK_E2F8685179F37AE5');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC0C0AD29');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76353B48');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C4B56C08');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4AE8F35D2');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D479F37AE5');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075D6FD723');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CAA9806EA');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C79F37AE5');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_user');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE langue_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE objectif');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
