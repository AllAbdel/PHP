<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516121913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, application_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, date DATE NOT NULL, date_echeance DATE NOT NULL, statut VARCHAR(50) NOT NULL, montant_ht DOUBLE PRECISION NOT NULL, montant_tva DOUBLE PRECISION NOT NULL, montant_ttc DOUBLE PRECISION NOT NULL, INDEX IDX_FE86641019EB6921 (client_id), INDEX IDX_FE8664103E030ACD (application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE8664103E030ACD FOREIGN KEY (application_id) REFERENCES infos_application (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE8664103E030ACD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE facture
        SQL);
    }
}
