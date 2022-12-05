<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205125113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_promo DROP FOREIGN KEY FK_8075132D7ECF78B0');
        $this->addSql('ALTER TABLE cours_promo DROP FOREIGN KEY FK_8075132DD0C07AFF');
        $this->addSql('DROP TABLE cours_promo');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CD0C07AFF');
        $this->addSql('DROP INDEX IDX_FDCA8C9CD0C07AFF ON cours');
        $this->addSql('ALTER TABLE cours DROP promo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_promo (cours_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_8075132D7ECF78B0 (cours_id), INDEX IDX_8075132DD0C07AFF (promo_id), PRIMARY KEY(cours_id, promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cours_promo ADD CONSTRAINT FK_8075132D7ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours_promo ADD CONSTRAINT FK_8075132DD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CD0C07AFF ON cours (promo_id)');
    }
}
