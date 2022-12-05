<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205125609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_cours (promo_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_14E3B00AD0C07AFF (promo_id), INDEX IDX_14E3B00A7ECF78B0 (cours_id), PRIMARY KEY(promo_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promo_cours ADD CONSTRAINT FK_14E3B00AD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_cours ADD CONSTRAINT FK_14E3B00A7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_cours DROP FOREIGN KEY FK_14E3B00AD0C07AFF');
        $this->addSql('ALTER TABLE promo_cours DROP FOREIGN KEY FK_14E3B00A7ECF78B0');
        $this->addSql('DROP TABLE promo_cours');
    }
}
