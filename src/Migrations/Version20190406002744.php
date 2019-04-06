<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190406002744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE audience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, station_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , scope VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FDCD941821BDB235 ON audience (station_id)');
        $this->addSql('CREATE TABLE show (id CHAR(36) NOT NULL --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) NOT NULL, description_long CLOB NOT NULL, premiered DATETIME DEFAULT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message CLOB DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, display_episode_number BOOLEAN NOT NULL, can_embed_player BOOLEAN NOT NULL, language VARCHAR(2) DEFAULT NULL, ordinal_seasons BOOLEAN NOT NULL, episode_sort_desc BOOLEAN NOT NULL, episode_count INTEGER DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
        , images CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_320ED9014296D31F ON show (genre_id)');
        $this->addSql('CREATE INDEX IDX_320ED901523CAB89 ON show (franchise_id)');
        $this->addSql('CREATE TABLE show_audience (show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , audience_id INTEGER NOT NULL, PRIMARY KEY(show_id, audience_id))');
        $this->addSql('CREATE INDEX IDX_C4A975F7D0C1FC64 ON show_audience (show_id)');
        $this->addSql('CREATE INDEX IDX_C4A975F7848CC616 ON show_audience (audience_id)');
        $this->addSql('CREATE TABLE show_platform (show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(show_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_363124D0C1FC64 ON show_platform (show_id)');
        $this->addSql('CREATE INDEX IDX_363124FFE6496F ON show_platform (platform_id)');
        $this->addSql('CREATE TABLE platform (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created DATETIME NOT NULL --(DC2Type:datetimetz_immutable)
        , updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE franchise (id CHAR(36) NOT NULL --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, premiered DATETIME DEFAULT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message VARCHAR(255) DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
        , images CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_66F6CE2A4296D31F ON franchise (genre_id)');
        $this->addSql('CREATE TABLE franchise_platform (franchise_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(franchise_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_B6622B37523CAB89 ON franchise_platform (franchise_id)');
        $this->addSql('CREATE INDEX IDX_B6622B37FFE6496F ON franchise_platform (platform_id)');
        $this->addSql('CREATE TABLE station (id CHAR(36) NOT NULL --(DC2Type:guid)
        , call_sign VARCHAR(255) NOT NULL, full_common_name VARCHAR(255) NOT NULL, short_common_name VARCHAR(128) NOT NULL, tvss_url VARCHAR(255) NOT NULL, donate_url VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, timezone_secondary VARCHAR(255) DEFAULT NULL, video_portal_url VARCHAR(255) DEFAULT NULL, video_portal_banner_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, facebook_url VARCHAR(255) DEFAULT NULL, twitter_url VARCHAR(255) DEFAULT NULL, kids_station_url VARCHAR(255) DEFAULT NULL, passport_url VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_line1 VARCHAR(255) DEFAULT NULL, address_line2 VARCHAR(255) DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_country_code VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, tag_line VARCHAR(255) DEFAULT NULL, tracking_code_page VARCHAR(255) DEFAULT NULL, tracking_code_event VARCHAR(255) DEFAULT NULL, primary_channel VARCHAR(255) DEFAULT NULL, primetime_start VARCHAR(255) DEFAULT NULL, images CLOB DEFAULT NULL --(DC2Type:array)
        , updated DATETIME DEFAULT NULL, pdp BOOLEAN DEFAULT NULL, passport_enabled BOOLEAN DEFAULT NULL, annual_passport_qualifying_amount INTEGER DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE show');
        $this->addSql('DROP TABLE show_audience');
        $this->addSql('DROP TABLE show_platform');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE franchise_platform');
        $this->addSql('DROP TABLE station');
    }
}
