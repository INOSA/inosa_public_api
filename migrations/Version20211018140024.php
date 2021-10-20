<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018140024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_additional_info (id NVARCHAR(36) NOT NULL, client_id NVARCHAR(32), inosa_site_id NVARCHAR(36) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_983BEA6A19EB6921 ON client_additional_info (client_id) WHERE client_id IS NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX inosaSiteId ON client_additional_info (inosa_site_id) WHERE inosa_site_id IS NOT NULL');
        $this->addSql('CREATE TABLE oauth2_access_token (identifier NCHAR(80) NOT NULL, client NVARCHAR(32) NOT NULL, expiry DATETIME2(6) NOT NULL, user_identifier NVARCHAR(128), scopes VARCHAR(MAX), revoked BIT NOT NULL, PRIMARY KEY (identifier))');
        $this->addSql('CREATE INDEX IDX_454D9673C7440455 ON oauth2_access_token (client)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_access_token\', N\'COLUMN\', expiry');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:oauth2_scope)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_access_token\', N\'COLUMN\', scopes');
        $this->addSql('CREATE TABLE oauth2_authorization_code (identifier NCHAR(80) NOT NULL, client NVARCHAR(32) NOT NULL, expiry DATETIME2(6) NOT NULL, user_identifier NVARCHAR(128), scopes VARCHAR(MAX), revoked BIT NOT NULL, PRIMARY KEY (identifier))');
        $this->addSql('CREATE INDEX IDX_509FEF5FC7440455 ON oauth2_authorization_code (client)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_authorization_code\', N\'COLUMN\', expiry');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:oauth2_scope)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_authorization_code\', N\'COLUMN\', scopes');
        $this->addSql('CREATE TABLE oauth2_client (identifier NVARCHAR(32) NOT NULL, secret NVARCHAR(128), redirect_uris VARCHAR(MAX), grants VARCHAR(MAX), scopes VARCHAR(MAX), active BIT NOT NULL, allow_plain_text_pkce BIT NOT NULL, PRIMARY KEY (identifier))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:oauth2_redirect_uri)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_client\', N\'COLUMN\', redirect_uris');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:oauth2_grant)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_client\', N\'COLUMN\', grants');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:oauth2_scope)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_client\', N\'COLUMN\', scopes');
        $this->addSql('ALTER TABLE oauth2_client ADD CONSTRAINT DF_669FF9C9_C97075B7 DEFAULT \'0\' FOR allow_plain_text_pkce');
        $this->addSql('CREATE TABLE oauth2_refresh_token (identifier NCHAR(80) NOT NULL, access_token NCHAR(80), expiry DATETIME2(6) NOT NULL, revoked BIT NOT NULL, PRIMARY KEY (identifier))');
        $this->addSql('CREATE INDEX IDX_4DD90732B6A2DD68 ON oauth2_refresh_token (access_token)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:datetime_immutable)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'oauth2_refresh_token\', N\'COLUMN\', expiry');
        $this->addSql('ALTER TABLE client_additional_info ADD CONSTRAINT FK_983BEA6A19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_client (identifier) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth2_access_token ADD CONSTRAINT FK_454D9673C7440455 FOREIGN KEY (client) REFERENCES oauth2_client (identifier) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth2_authorization_code ADD CONSTRAINT FK_509FEF5FC7440455 FOREIGN KEY (client) REFERENCES oauth2_client (identifier) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oauth2_refresh_token ADD CONSTRAINT FK_4DD90732B6A2DD68 FOREIGN KEY (access_token) REFERENCES oauth2_access_token (identifier) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE oauth2_refresh_token DROP CONSTRAINT FK_4DD90732B6A2DD68');
        $this->addSql('ALTER TABLE client_additional_info DROP CONSTRAINT FK_983BEA6A19EB6921');
        $this->addSql('ALTER TABLE oauth2_access_token DROP CONSTRAINT FK_454D9673C7440455');
        $this->addSql('ALTER TABLE oauth2_authorization_code DROP CONSTRAINT FK_509FEF5FC7440455');
        $this->addSql('DROP TABLE client_additional_info');
        $this->addSql('DROP TABLE oauth2_access_token');
        $this->addSql('DROP TABLE oauth2_authorization_code');
        $this->addSql('DROP TABLE oauth2_client');
        $this->addSql('DROP TABLE oauth2_refresh_token');
    }
}
