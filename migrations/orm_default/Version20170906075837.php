<?php

namespace OrmDefaultMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170906075837 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE user_remember_me_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE permissions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE confirmations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_remember_me (id INT NOT NULL, sid VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, user_id INT NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3E96EBDBF396750 ON user_remember_me (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3E96EBD57167AB4 ON user_remember_me (sid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3E96EBD5F37A13B ON user_remember_me (token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3E96EBDA76ED395 ON user_remember_me (user_id)');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, name VARCHAR(48) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC75E237E06 ON roles (name)');
        $this->addSql('CREATE TABLE hierarchicalrole_hierarchicalrole (hierarchicalrole_source INT NOT NULL, hierarchicalrole_target INT NOT NULL, PRIMARY KEY(hierarchicalrole_source, hierarchicalrole_target))');
        $this->addSql('CREATE INDEX IDX_5707BC75CD934D59 ON hierarchicalrole_hierarchicalrole (hierarchicalrole_source)');
        $this->addSql('CREATE INDEX IDX_5707BC75D4761DD6 ON hierarchicalrole_hierarchicalrole (hierarchicalrole_target)');
        $this->addSql('CREATE TABLE hierarchicalrole_permission (hierarchicalrole_id INT NOT NULL, permission_id INT NOT NULL, PRIMARY KEY(hierarchicalrole_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_8D28B77E83B93C19 ON hierarchicalrole_permission (hierarchicalrole_id)');
        $this->addSql('CREATE INDEX IDX_8D28B77EFED90CCA ON hierarchicalrole_permission (permission_id)');
        $this->addSql('CREATE TABLE permissions (id INT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DEDCC6F5E237E06 ON permissions (name)');
        $this->addSql('CREATE TABLE confirmations (id INT NOT NULL, users_id INT NOT NULL, code VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34298E9EBF396750 ON confirmations (id)');
        $this->addSql('CREATE INDEX IDX_34298E9E67B3B43D ON confirmations (users_id)');
        $this->addSql('CREATE TABLE users (user_id INT NOT NULL, username VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, display_name VARCHAR(50) DEFAULT NULL, password VARCHAR(128) NOT NULL, state SMALLINT DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A76ED395 ON users (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE users_hierarchicalrole (users_id INT NOT NULL, hierarchicalrole_id INT NOT NULL, PRIMARY KEY(users_id, hierarchicalrole_id))');
        $this->addSql('CREATE INDEX IDX_47D858867B3B43D ON users_hierarchicalrole (users_id)');
        $this->addSql('CREATE INDEX IDX_47D858883B93C19 ON users_hierarchicalrole (hierarchicalrole_id)');
        $this->addSql('CREATE TABLE user_provider (user_id INT NOT NULL, provider_id VARCHAR(50) NOT NULL, provider VARCHAR(255) NOT NULL, PRIMARY KEY(user_id, provider_id))');
        $this->addSql('ALTER TABLE hierarchicalrole_hierarchicalrole ADD CONSTRAINT FK_5707BC75CD934D59 FOREIGN KEY (hierarchicalrole_source) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hierarchicalrole_hierarchicalrole ADD CONSTRAINT FK_5707BC75D4761DD6 FOREIGN KEY (hierarchicalrole_target) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hierarchicalrole_permission ADD CONSTRAINT FK_8D28B77E83B93C19 FOREIGN KEY (hierarchicalrole_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hierarchicalrole_permission ADD CONSTRAINT FK_8D28B77EFED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE confirmations ADD CONSTRAINT FK_34298E9E67B3B43D FOREIGN KEY (users_id) REFERENCES users (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_hierarchicalrole ADD CONSTRAINT FK_47D858867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_hierarchicalrole ADD CONSTRAINT FK_47D858883B93C19 FOREIGN KEY (hierarchicalrole_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE hierarchicalrole_hierarchicalrole DROP CONSTRAINT FK_5707BC75CD934D59');
        $this->addSql('ALTER TABLE hierarchicalrole_hierarchicalrole DROP CONSTRAINT FK_5707BC75D4761DD6');
        $this->addSql('ALTER TABLE hierarchicalrole_permission DROP CONSTRAINT FK_8D28B77E83B93C19');
        $this->addSql('ALTER TABLE users_hierarchicalrole DROP CONSTRAINT FK_47D858883B93C19');
        $this->addSql('ALTER TABLE hierarchicalrole_permission DROP CONSTRAINT FK_8D28B77EFED90CCA');
        $this->addSql('ALTER TABLE confirmations DROP CONSTRAINT FK_34298E9E67B3B43D');
        $this->addSql('ALTER TABLE users_hierarchicalrole DROP CONSTRAINT FK_47D858867B3B43D');
        $this->addSql('DROP SEQUENCE user_remember_me_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE permissions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE confirmations_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_user_id_seq CASCADE');
        $this->addSql('DROP TABLE user_remember_me');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE hierarchicalrole_hierarchicalrole');
        $this->addSql('DROP TABLE hierarchicalrole_permission');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE confirmations');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_hierarchicalrole');
        $this->addSql('DROP TABLE user_provider');
    }
}
