<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261101191432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, publish_year INT DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_CBE5A331F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book_return (id INT AUTO_INCREMENT NOT NULL, return_date DATETIME NOT NULL, book_id INT NOT NULL, reader_id INT NOT NULL, INDEX IDX_8E49348216A2B381 (book_id), INDEX IDX_8E4934821717D737 (reader_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE giving (id INT AUTO_INCREMENT NOT NULL, giving_date DATETIME NOT NULL, book_id INT NOT NULL, reader_id INT NOT NULL, INDEX IDX_11EB364016A2B381 (book_id), INDEX IDX_11EB36401717D737 (reader_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reader (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE book_return ADD CONSTRAINT FK_8E49348216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book_return ADD CONSTRAINT FK_8E4934821717D737 FOREIGN KEY (reader_id) REFERENCES reader (id)');
        $this->addSql('ALTER TABLE giving ADD CONSTRAINT FK_11EB364016A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE giving ADD CONSTRAINT FK_11EB36401717D737 FOREIGN KEY (reader_id) REFERENCES reader (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book_return DROP FOREIGN KEY FK_8E49348216A2B381');
        $this->addSql('ALTER TABLE book_return DROP FOREIGN KEY FK_8E4934821717D737');
        $this->addSql('ALTER TABLE giving DROP FOREIGN KEY FK_11EB364016A2B381');
        $this->addSql('ALTER TABLE giving DROP FOREIGN KEY FK_11EB36401717D737');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_return');
        $this->addSql('DROP TABLE giving');
        $this->addSql('DROP TABLE reader');
    }
}
