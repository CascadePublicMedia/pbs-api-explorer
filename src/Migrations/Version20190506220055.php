<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506220055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE asset_availability (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , type VARCHAR(255) NOT NULL, start_date_time DATETIME DEFAULT NULL, end_date_time DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_46E062425DA1941 ON asset_availability (asset_id)');
        $this->addSql('CREATE TABLE asset_tag (id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE geo_availability_profile (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE season (id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, title VARCHAR(255) DEFAULT NULL, title_sortable VARCHAR(255) DEFAULT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, updated DATETIME DEFAULT NULL, latest_asset_images CLOB DEFAULT NULL --(DC2Type:array)
        , links CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E45BA9D0C1FC64 ON season (show_id)');
        $this->addSql('CREATE TABLE audience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, station_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , scope VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FDCD941821BDB235 ON audience (station_id)');
        $this->addSql('CREATE TABLE remote_asset (id CHAR(36) NOT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, url VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FD25C980523CAB89 ON remote_asset (franchise_id)');
        $this->addSql('CREATE INDEX IDX_FD25C980D0C1FC64 ON remote_asset (show_id)');
        $this->addSql('CREATE TABLE remote_asset_asset_tag (remote_asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(remote_asset_id, asset_tag_id))');
        $this->addSql('CREATE INDEX IDX_62415E2BD648660D ON remote_asset_asset_tag (remote_asset_id)');
        $this->addSql('CREATE INDEX IDX_62415E2B52FFEC0C ON remote_asset_asset_tag (asset_tag_id)');
        $this->addSql('CREATE TABLE show (id CHAR(36) NOT NULL --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) NOT NULL, description_long CLOB NOT NULL, premiered DATETIME DEFAULT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message CLOB DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, display_episode_number BOOLEAN NOT NULL, can_embed_player BOOLEAN NOT NULL, language VARCHAR(2) DEFAULT NULL, ordinal_seasons BOOLEAN NOT NULL, episode_sort_desc BOOLEAN NOT NULL, episode_count INTEGER DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
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
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE platform (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE special (id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) NOT NULL, description_long CLOB NOT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(255) DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, updated DATETIME DEFAULT NULL, full_length_asset BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C6B3FE3D0C1FC64 ON special (show_id)');
        $this->addSql('CREATE TABLE episode (id CHAR(36) NOT NULL --(DC2Type:guid)
        , season_id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , full_length_asset_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, segment VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(4) DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA4EC001D1 ON episode (season_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDAD0C1FC64 ON episode (show_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5F7888C2 ON episode (full_length_asset_id)');
        $this->addSql('CREATE TABLE genre (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE franchise (id CHAR(36) NOT NULL --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, premiered DATETIME DEFAULT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message VARCHAR(255) DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_66F6CE2A4296D31F ON franchise (genre_id)');
        $this->addSql('CREATE TABLE franchise_platform (franchise_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(franchise_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_B6622B37523CAB89 ON franchise_platform (franchise_id)');
        $this->addSql('CREATE INDEX IDX_B6622B37FFE6496F ON franchise_platform (platform_id)');
        $this->addSql('CREATE TABLE topic (id CHAR(36) NOT NULL --(DC2Type:guid)
        , parent_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D40DE1B727ACA70 ON topic (parent_id)');
        $this->addSql('CREATE TABLE setting (id VARCHAR(255) NOT NULL, owner_id INTEGER NOT NULL, value VARCHAR(255) NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F74B8987E3C61F9 ON setting (owner_id)');
        $this->addSql('CREATE TABLE geo_availability_country (id CHAR(36) NOT NULL --(DC2Type:guid)
        , code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE station (id CHAR(36) NOT NULL --(DC2Type:guid)
        , call_sign VARCHAR(255) NOT NULL, full_common_name VARCHAR(255) NOT NULL, short_common_name VARCHAR(128) NOT NULL, tvss_url VARCHAR(255) NOT NULL, donate_url VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, timezone_secondary VARCHAR(255) DEFAULT NULL, video_portal_url VARCHAR(255) DEFAULT NULL, video_portal_banner_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, facebook_url VARCHAR(255) DEFAULT NULL, twitter_url VARCHAR(255) DEFAULT NULL, kids_station_url VARCHAR(255) DEFAULT NULL, passport_url VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_line1 VARCHAR(255) DEFAULT NULL, address_line2 VARCHAR(255) DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_country_code VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, tag_line VARCHAR(255) DEFAULT NULL, tracking_code_page VARCHAR(255) DEFAULT NULL, tracking_code_event VARCHAR(255) DEFAULT NULL, primary_channel VARCHAR(255) DEFAULT NULL, primetime_start VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, pdp BOOLEAN DEFAULT NULL, passport_enabled BOOLEAN DEFAULT NULL, annual_passport_qualifying_amount INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , station_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , image VARCHAR(255) NOT NULL, profile VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_C53D045F5DA1941 ON image (asset_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F523CAB89 ON image (franchise_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FD0C1FC64 ON image (show_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F21BDB235 ON image (station_id)');
        $this->addSql('CREATE TABLE asset (id CHAR(36) NOT NULL --(DC2Type:guid)
        , episode_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , season_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , geo_profile_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, type VARCHAR(255) NOT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, duration INTEGER DEFAULT NULL, legacy_tp_media_id VARCHAR(255) DEFAULT NULL, dfp_exclude BOOLEAN DEFAULT NULL, content_rating VARCHAR(255) DEFAULT NULL, content_rating_descriptor CLOB DEFAULT NULL --(DC2Type:array)
        , can_embed_player BOOLEAN DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, funder_message CLOB DEFAULT NULL, updated DATETIME DEFAULT NULL, player_code CLOB DEFAULT NULL, has_captions BOOLEAN DEFAULT NULL, chapters CLOB DEFAULT NULL --(DC2Type:array)
        , links CLOB DEFAULT NULL --(DC2Type:array)
        , captions CLOB DEFAULT NULL --(DC2Type:array)
        , videos CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2AF5A5C362B62A0 ON asset (episode_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C4EC001D1 ON asset (season_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CD0C1FC64 ON asset (show_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C523CAB89 ON asset (franchise_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CB5879536 ON asset (geo_profile_id)');
        $this->addSql('CREATE TABLE asset_platform (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, platform_id))');
        $this->addSql('CREATE INDEX IDX_BB39CA4B5DA1941 ON asset_platform (asset_id)');
        $this->addSql('CREATE INDEX IDX_BB39CA4BFFE6496F ON asset_platform (platform_id)');
        $this->addSql('CREATE TABLE asset_geo_availability_country (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , geo_availability_country_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, geo_availability_country_id))');
        $this->addSql('CREATE INDEX IDX_14794C755DA1941 ON asset_geo_availability_country (asset_id)');
        $this->addSql('CREATE INDEX IDX_14794C75FA6B6C4A ON asset_geo_availability_country (geo_availability_country_id)');
        $this->addSql('CREATE TABLE asset_asset_tag (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(asset_id, asset_tag_id))');
        $this->addSql('CREATE INDEX IDX_84B99C355DA1941 ON asset_asset_tag (asset_id)');
        $this->addSql('CREATE INDEX IDX_84B99C3552FFEC0C ON asset_asset_tag (asset_tag_id)');
        $this->addSql('CREATE TABLE asset_remote_asset (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , remote_asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, remote_asset_id))');
        $this->addSql('CREATE INDEX IDX_8472097D5DA1941 ON asset_remote_asset (asset_id)');
        $this->addSql('CREATE INDEX IDX_8472097DD648660D ON asset_remote_asset (remote_asset_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE asset_availability');
        $this->addSql('DROP TABLE asset_tag');
        $this->addSql('DROP TABLE geo_availability_profile');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE remote_asset');
        $this->addSql('DROP TABLE remote_asset_asset_tag');
        $this->addSql('DROP TABLE show');
        $this->addSql('DROP TABLE show_audience');
        $this->addSql('DROP TABLE show_platform');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE special');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE franchise_platform');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE geo_availability_country');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE asset');
        $this->addSql('DROP TABLE asset_platform');
        $this->addSql('DROP TABLE asset_geo_availability_country');
        $this->addSql('DROP TABLE asset_asset_tag');
        $this->addSql('DROP TABLE asset_remote_asset');
    }
}
