<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190521224612 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE asset_topic (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , topic_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, topic_id))');
        $this->addSql('CREATE INDEX IDX_81BF05C5DA1941 ON asset_topic (asset_id)');
        $this->addSql('CREATE INDEX IDX_81BF05C1F55203D ON asset_topic (topic_id)');
        $this->addSql('DROP INDEX IDX_46E062425DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_availability AS SELECT id, asset_id, type, start_date_time, end_date_time, updated FROM asset_availability');
        $this->addSql('DROP TABLE asset_availability');
        $this->addSql('CREATE TABLE asset_availability (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , type VARCHAR(255) NOT NULL COLLATE BINARY, start_date_time DATETIME DEFAULT NULL, end_date_time DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, CONSTRAINT FK_46E062425DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_availability (id, asset_id, type, start_date_time, end_date_time, updated) SELECT id, asset_id, type, start_date_time, end_date_time, updated FROM __temp__asset_availability');
        $this->addSql('DROP TABLE __temp__asset_availability');
        $this->addSql('CREATE INDEX IDX_46E062425DA1941 ON asset_availability (asset_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__geo_availability_profile AS SELECT id, name, updated FROM geo_availability_profile');
        $this->addSql('DROP TABLE geo_availability_profile');
        $this->addSql('CREATE TABLE geo_availability_profile (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO geo_availability_profile (id, name, updated) SELECT id, name, updated FROM __temp__geo_availability_profile');
        $this->addSql('DROP TABLE __temp__geo_availability_profile');
        $this->addSql('DROP INDEX IDX_F0E45BA9D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__season AS SELECT id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links FROM season');
        $this->addSql('DROP TABLE season');
        $this->addSql('CREATE TABLE season (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, title_sortable VARCHAR(255) DEFAULT NULL COLLATE BINARY, tms_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, description_short VARCHAR(90) DEFAULT NULL COLLATE BINARY, description_long CLOB DEFAULT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, latest_asset_images CLOB DEFAULT NULL --(DC2Type:array)
        , links CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id), CONSTRAINT FK_F0E45BA9D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO season (id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links) SELECT id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links FROM __temp__season');
        $this->addSql('DROP TABLE __temp__season');
        $this->addSql('CREATE INDEX IDX_F0E45BA9D0C1FC64 ON season (show_id)');
        $this->addSql('DROP INDEX IDX_FDCD941821BDB235');
        $this->addSql('CREATE TEMPORARY TABLE __temp__audience AS SELECT id, station_id, scope FROM audience');
        $this->addSql('DROP TABLE audience');
        $this->addSql('CREATE TABLE audience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, station_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , scope VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_FDCD941821BDB235 FOREIGN KEY (station_id) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO audience (id, station_id, scope) SELECT id, station_id, scope FROM __temp__audience');
        $this->addSql('DROP TABLE __temp__audience');
        $this->addSql('CREATE INDEX IDX_FDCD941821BDB235 ON audience (station_id)');
        $this->addSql('DROP INDEX IDX_FD25C980D0C1FC64');
        $this->addSql('DROP INDEX IDX_FD25C980523CAB89');
        $this->addSql('CREATE TEMPORARY TABLE __temp__remote_asset AS SELECT id, franchise_id, show_id, title, description_short, description_long, url, image, updated FROM remote_asset');
        $this->addSql('DROP TABLE remote_asset');
        $this->addSql('CREATE TABLE remote_asset (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL COLLATE BINARY, description_short VARCHAR(90) DEFAULT NULL COLLATE BINARY, description_long CLOB DEFAULT NULL COLLATE BINARY, url VARCHAR(255) NOT NULL COLLATE BINARY, image VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_FD25C980523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FD25C980D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO remote_asset (id, franchise_id, show_id, title, description_short, description_long, url, image, updated) SELECT id, franchise_id, show_id, title, description_short, description_long, url, image, updated FROM __temp__remote_asset');
        $this->addSql('DROP TABLE __temp__remote_asset');
        $this->addSql('CREATE INDEX IDX_FD25C980D0C1FC64 ON remote_asset (show_id)');
        $this->addSql('CREATE INDEX IDX_FD25C980523CAB89 ON remote_asset (franchise_id)');
        $this->addSql('DROP INDEX IDX_62415E2B52FFEC0C');
        $this->addSql('DROP INDEX IDX_62415E2BD648660D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__remote_asset_asset_tag AS SELECT remote_asset_id, asset_tag_id FROM remote_asset_asset_tag');
        $this->addSql('DROP TABLE remote_asset_asset_tag');
        $this->addSql('CREATE TABLE remote_asset_asset_tag (remote_asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(remote_asset_id, asset_tag_id), CONSTRAINT FK_62415E2BD648660D FOREIGN KEY (remote_asset_id) REFERENCES remote_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62415E2B52FFEC0C FOREIGN KEY (asset_tag_id) REFERENCES asset_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO remote_asset_asset_tag (remote_asset_id, asset_tag_id) SELECT remote_asset_id, asset_tag_id FROM __temp__remote_asset_asset_tag');
        $this->addSql('DROP TABLE __temp__remote_asset_asset_tag');
        $this->addSql('CREATE INDEX IDX_62415E2B52FFEC0C ON remote_asset_asset_tag (asset_tag_id)');
        $this->addSql('CREATE INDEX IDX_62415E2BD648660D ON remote_asset_asset_tag (remote_asset_id)');
        $this->addSql('DROP INDEX IDX_320ED901523CAB89');
        $this->addSql('DROP INDEX IDX_320ED9014296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show AS SELECT id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, premiered, updated, links FROM show');
        $this->addSql('DROP TABLE show');
        $this->addSql('CREATE TABLE show (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL COLLATE BINARY, title_sortable VARCHAR(255) NOT NULL COLLATE BINARY, nola VARCHAR(4) DEFAULT NULL COLLATE BINARY, tms_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, description_short VARCHAR(90) NOT NULL COLLATE BINARY, description_long CLOB NOT NULL COLLATE BINARY, dfp_exclude BOOLEAN NOT NULL, funder_message CLOB DEFAULT NULL COLLATE BINARY, ga_tracking_page VARCHAR(255) DEFAULT NULL COLLATE BINARY, ga_tracking_event VARCHAR(255) DEFAULT NULL COLLATE BINARY, hashtag VARCHAR(255) DEFAULT NULL COLLATE BINARY, display_episode_number BOOLEAN NOT NULL, can_embed_player BOOLEAN NOT NULL, language VARCHAR(2) DEFAULT NULL COLLATE BINARY, ordinal_seasons BOOLEAN NOT NULL, episode_sort_desc BOOLEAN NOT NULL, episode_count INTEGER DEFAULT NULL, premiered DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id), CONSTRAINT FK_320ED9014296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_320ED901523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO show (id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, premiered, updated, links) SELECT id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, premiered, updated, links FROM __temp__show');
        $this->addSql('DROP TABLE __temp__show');
        $this->addSql('CREATE INDEX IDX_320ED901523CAB89 ON show (franchise_id)');
        $this->addSql('CREATE INDEX IDX_320ED9014296D31F ON show (genre_id)');
        $this->addSql('DROP INDEX IDX_C4A975F7848CC616');
        $this->addSql('DROP INDEX IDX_C4A975F7D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show_audience AS SELECT show_id, audience_id FROM show_audience');
        $this->addSql('DROP TABLE show_audience');
        $this->addSql('CREATE TABLE show_audience (show_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , audience_id INTEGER NOT NULL, PRIMARY KEY(show_id, audience_id), CONSTRAINT FK_C4A975F7D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C4A975F7848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO show_audience (show_id, audience_id) SELECT show_id, audience_id FROM __temp__show_audience');
        $this->addSql('DROP TABLE __temp__show_audience');
        $this->addSql('CREATE INDEX IDX_C4A975F7848CC616 ON show_audience (audience_id)');
        $this->addSql('CREATE INDEX IDX_C4A975F7D0C1FC64 ON show_audience (show_id)');
        $this->addSql('DROP INDEX IDX_363124FFE6496F');
        $this->addSql('DROP INDEX IDX_363124D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show_platform AS SELECT show_id, platform_id FROM show_platform');
        $this->addSql('DROP TABLE show_platform');
        $this->addSql('CREATE TABLE show_platform (show_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(show_id, platform_id), CONSTRAINT FK_363124D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_363124FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO show_platform (show_id, platform_id) SELECT show_id, platform_id FROM __temp__show_platform');
        $this->addSql('DROP TABLE __temp__show_platform');
        $this->addSql('CREATE INDEX IDX_363124FFE6496F ON show_platform (platform_id)');
        $this->addSql('CREATE INDEX IDX_363124D0C1FC64 ON show_platform (show_id)');
        $this->addSql('DROP INDEX IDX_CB0048D451A5BC03');
        $this->addSql('DROP INDEX IDX_CB0048D43EB8070A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__listing AS SELECT id, feed_id, program_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date FROM listing');
        $this->addSql('DROP TABLE listing');
        $this->addSql('CREATE TABLE listing (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , feed_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , program_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , package_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, taped BOOLEAN NOT NULL, duration VARCHAR(255) NOT NULL COLLATE BINARY, duration_minutes INTEGER NOT NULL, nola_episode VARCHAR(255) DEFAULT NULL COLLATE BINARY, nola_root VARCHAR(4) DEFAULT NULL COLLATE BINARY, season_premiere_finale VARCHAR(255) DEFAULT NULL COLLATE BINARY, special_warnings CLOB DEFAULT NULL COLLATE BINARY, start_time VARCHAR(4) NOT NULL COLLATE BINARY, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, animated BOOLEAN NOT NULL, closed_captions BOOLEAN NOT NULL, hd BOOLEAN NOT NULL, stereo BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, show_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, episode_title VARCHAR(255) DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, episode_description CLOB DEFAULT NULL COLLATE BINARY, date DATE NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_CB0048D43EB8070A FOREIGN KEY (program_id) REFERENCES schedule_program (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CB0048D451A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO listing (id, feed_id, program_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date) SELECT id, feed_id, program_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date FROM __temp__listing');
        $this->addSql('DROP TABLE __temp__listing');
        $this->addSql('CREATE INDEX IDX_CB0048D451A5BC03 ON listing (feed_id)');
        $this->addSql('CREATE INDEX IDX_CB0048D43EB8070A ON listing (program_id)');
        $this->addSql('DROP INDEX IDX_4C6B3FE3D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__special AS SELECT id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, full_length_asset, updated FROM special');
        $this->addSql('DROP TABLE special');
        $this->addSql('CREATE TABLE special (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, title_sortable VARCHAR(255) NOT NULL COLLATE BINARY, tms_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, description_short VARCHAR(90) NOT NULL COLLATE BINARY, description_long CLOB NOT NULL COLLATE BINARY, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(255) DEFAULT NULL COLLATE BINARY, language VARCHAR(2) DEFAULT NULL COLLATE BINARY, full_length_asset BOOLEAN DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_4C6B3FE3D0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO special (id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, full_length_asset, updated) SELECT id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, full_length_asset, updated FROM __temp__special');
        $this->addSql('DROP TABLE __temp__special');
        $this->addSql('CREATE INDEX IDX_4C6B3FE3D0C1FC64 ON special (show_id)');
        $this->addSql('DROP INDEX IDX_DDAA1CDA5F7888C2');
        $this->addSql('DROP INDEX IDX_DDAA1CDAD0C1FC64');
        $this->addSql('DROP INDEX IDX_DDAA1CDA4EC001D1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__episode AS SELECT id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated FROM episode');
        $this->addSql('DROP TABLE episode');
        $this->addSql('CREATE TABLE episode (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , season_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , full_length_asset_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, segment VARCHAR(255) DEFAULT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL COLLATE BINARY, title_sortable VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, tms_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, description_short VARCHAR(90) DEFAULT NULL COLLATE BINARY, description_long CLOB DEFAULT NULL COLLATE BINARY, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(4) DEFAULT NULL COLLATE BINARY, language VARCHAR(2) DEFAULT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DDAA1CDAD0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DDAA1CDA5F7888C2 FOREIGN KEY (full_length_asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO episode (id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated) SELECT id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated FROM __temp__episode');
        $this->addSql('DROP TABLE __temp__episode');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5F7888C2 ON episode (full_length_asset_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDAD0C1FC64 ON episode (show_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA4EC001D1 ON episode (season_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__genre AS SELECT id, slug, title, created, updated FROM genre');
        $this->addSql('DROP TABLE genre');
        $this->addSql('CREATE TABLE genre (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL COLLATE BINARY, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO genre (id, slug, title, created, updated) SELECT id, slug, title, created, updated FROM __temp__genre');
        $this->addSql('DROP TABLE __temp__genre');
        $this->addSql('DROP INDEX IDX_86FFD285C299A5BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__membership AS SELECT id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date FROM membership');
        $this->addSql('DROP TABLE membership');
        $this->addSql('CREATE TABLE membership (id VARCHAR(255) NOT NULL, pbs_profile_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , first_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, last_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, offer VARCHAR(255) DEFAULT NULL COLLATE BINARY, notes CLOB DEFAULT NULL COLLATE BINARY, status VARCHAR(255) DEFAULT NULL COLLATE BINARY, token VARCHAR(255) DEFAULT NULL COLLATE BINARY, additional_metadata VARCHAR(255) DEFAULT NULL COLLATE BINARY, provisional BOOLEAN NOT NULL, start_date DATETIME NOT NULL, expire_date DATETIME NOT NULL, activation_date DATETIME DEFAULT NULL, grace_period DATETIME NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_86FFD285C299A5BF FOREIGN KEY (pbs_profile_id) REFERENCES pbs_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO membership (id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date) SELECT id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date FROM __temp__membership');
        $this->addSql('DROP TABLE __temp__membership');
        $this->addSql('CREATE INDEX IDX_86FFD285C299A5BF ON membership (pbs_profile_id)');
        $this->addSql('DROP INDEX IDX_66F6CE2A4296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__franchise AS SELECT id, genre_id, slug, nola, title, title_sortable, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, premiered, updated, links FROM franchise');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('CREATE TABLE franchise (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , genre_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL COLLATE BINARY, nola VARCHAR(4) DEFAULT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL COLLATE BINARY, title_sortable VARCHAR(255) NOT NULL COLLATE BINARY, description_short VARCHAR(90) DEFAULT NULL COLLATE BINARY, description_long CLOB DEFAULT NULL COLLATE BINARY, dfp_exclude BOOLEAN NOT NULL, funder_message VARCHAR(255) DEFAULT NULL COLLATE BINARY, ga_tracking_page VARCHAR(255) DEFAULT NULL COLLATE BINARY, ga_tracking_event VARCHAR(255) DEFAULT NULL COLLATE BINARY, hashtag VARCHAR(255) DEFAULT NULL COLLATE BINARY, premiered DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, links CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id), CONSTRAINT FK_66F6CE2A4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO franchise (id, genre_id, slug, nola, title, title_sortable, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, premiered, updated, links) SELECT id, genre_id, slug, nola, title, title_sortable, description_short, description_long, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, hashtag, premiered, updated, links FROM __temp__franchise');
        $this->addSql('DROP TABLE __temp__franchise');
        $this->addSql('CREATE INDEX IDX_66F6CE2A4296D31F ON franchise (genre_id)');
        $this->addSql('DROP INDEX IDX_B6622B37FFE6496F');
        $this->addSql('DROP INDEX IDX_B6622B37523CAB89');
        $this->addSql('CREATE TEMPORARY TABLE __temp__franchise_platform AS SELECT franchise_id, platform_id FROM franchise_platform');
        $this->addSql('DROP TABLE franchise_platform');
        $this->addSql('CREATE TABLE franchise_platform (franchise_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(franchise_id, platform_id), CONSTRAINT FK_B6622B37523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6622B37FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO franchise_platform (franchise_id, platform_id) SELECT franchise_id, platform_id FROM __temp__franchise_platform');
        $this->addSql('DROP TABLE __temp__franchise_platform');
        $this->addSql('CREATE INDEX IDX_B6622B37FFE6496F ON franchise_platform (platform_id)');
        $this->addSql('CREATE INDEX IDX_B6622B37523CAB89 ON franchise_platform (franchise_id)');
        $this->addSql('DROP INDEX IDX_9D40DE1B727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__topic AS SELECT id, parent_id, name, updated FROM topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('CREATE TABLE topic (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , parent_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_9D40DE1B727ACA70 FOREIGN KEY (parent_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO topic (id, parent_id, name, updated) SELECT id, parent_id, name, updated FROM __temp__topic');
        $this->addSql('DROP TABLE __temp__topic');
        $this->addSql('CREATE INDEX IDX_9D40DE1B727ACA70 ON topic (parent_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__changelog_entry AS SELECT id, activity, updated_fields, type, resource_id, timestamp FROM changelog_entry');
        $this->addSql('DROP TABLE changelog_entry');
        $this->addSql('CREATE TABLE changelog_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity VARCHAR(255) NOT NULL COLLATE BINARY, updated_fields CLOB DEFAULT NULL --(DC2Type:array)
        , type VARCHAR(255) NOT NULL, resource_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , timestamp DATETIME NOT NULL)');
        $this->addSql('INSERT INTO changelog_entry (id, activity, updated_fields, type, resource_id, timestamp) SELECT id, activity, updated_fields, type, resource_id, timestamp FROM __temp__changelog_entry');
        $this->addSql('DROP TABLE __temp__changelog_entry');
        $this->addSql('DROP INDEX IDX_9F74B8987E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__setting AS SELECT id, owner_id, value, updated FROM setting');
        $this->addSql('DROP TABLE setting');
        $this->addSql('CREATE TABLE setting (id VARCHAR(255) NOT NULL COLLATE BINARY, owner_id INTEGER NOT NULL, value VARCHAR(255) NOT NULL COLLATE BINARY, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_9F74B8987E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO setting (id, owner_id, value, updated) SELECT id, owner_id, value, updated FROM __temp__setting');
        $this->addSql('DROP TABLE __temp__setting');
        $this->addSql('CREATE INDEX IDX_9F74B8987E3C61F9 ON setting (owner_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__geo_availability_country AS SELECT id, code, name, updated FROM geo_availability_country');
        $this->addSql('DROP TABLE geo_availability_country');
        $this->addSql('CREATE TABLE geo_availability_country (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , code VARCHAR(255) NOT NULL COLLATE BINARY, name VARCHAR(255) NOT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO geo_availability_country (id, code, name, updated) SELECT id, code, name, updated FROM __temp__geo_availability_country');
        $this->addSql('DROP TABLE __temp__geo_availability_country');
        $this->addSql('CREATE TEMPORARY TABLE __temp__station AS SELECT id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, pdp, passport_enabled, annual_passport_qualifying_amount, updated FROM station');
        $this->addSql('DROP TABLE station');
        $this->addSql('CREATE TABLE station (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , call_sign VARCHAR(255) NOT NULL COLLATE BINARY, full_common_name VARCHAR(255) NOT NULL COLLATE BINARY, short_common_name VARCHAR(128) NOT NULL COLLATE BINARY, tvss_url VARCHAR(255) NOT NULL COLLATE BINARY, donate_url VARCHAR(255) NOT NULL COLLATE BINARY, timezone VARCHAR(255) NOT NULL COLLATE BINARY, timezone_secondary VARCHAR(255) DEFAULT NULL COLLATE BINARY, video_portal_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, video_portal_banner_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, website_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, facebook_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, twitter_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, kids_station_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, passport_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, telephone VARCHAR(255) DEFAULT NULL COLLATE BINARY, fax VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_city VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_state VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_line1 VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_line2 VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_zip_code VARCHAR(255) DEFAULT NULL COLLATE BINARY, address_country_code VARCHAR(255) DEFAULT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, tag_line VARCHAR(255) DEFAULT NULL COLLATE BINARY, tracking_code_page VARCHAR(255) DEFAULT NULL COLLATE BINARY, tracking_code_event VARCHAR(255) DEFAULT NULL COLLATE BINARY, primary_channel VARCHAR(255) DEFAULT NULL COLLATE BINARY, primetime_start VARCHAR(255) DEFAULT NULL COLLATE BINARY, pdp BOOLEAN DEFAULT NULL, passport_enabled BOOLEAN DEFAULT NULL, annual_passport_qualifying_amount INTEGER DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO station (id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, pdp, passport_enabled, annual_passport_qualifying_amount, updated) SELECT id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, pdp, passport_enabled, annual_passport_qualifying_amount, updated FROM __temp__station');
        $this->addSql('DROP TABLE __temp__station');
        $this->addSql('DROP INDEX IDX_C53D045F21BDB235');
        $this->addSql('DROP INDEX IDX_C53D045FD0C1FC64');
        $this->addSql('DROP INDEX IDX_C53D045F523CAB89');
        $this->addSql('DROP INDEX IDX_C53D045F5DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, asset_id, franchise_id, show_id, station_id, image, profile, updated FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , station_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , image VARCHAR(255) NOT NULL COLLATE BINARY, profile VARCHAR(255) NOT NULL COLLATE BINARY, updated DATETIME DEFAULT NULL, CONSTRAINT FK_C53D045F5DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045F523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045FD0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C53D045F21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image (id, asset_id, franchise_id, show_id, station_id, image, profile, updated) SELECT id, asset_id, franchise_id, show_id, station_id, image, profile, updated FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F21BDB235 ON image (station_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FD0C1FC64 ON image (show_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F523CAB89 ON image (franchise_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F5DA1941 ON image (asset_id)');
        $this->addSql('DROP INDEX IDX_2AF5A5CB5879536');
        $this->addSql('DROP INDEX IDX_2AF5A5C523CAB89');
        $this->addSql('DROP INDEX IDX_2AF5A5CD0C1FC64');
        $this->addSql('DROP INDEX IDX_2AF5A5C4EC001D1');
        $this->addSql('DROP INDEX IDX_2AF5A5C362B62A0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset AS SELECT id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, can_embed_player, language, funder_message, player_code, has_captions, content_rating_descriptor, updated, chapters, links, captions, videos FROM asset');
        $this->addSql('DROP TABLE asset');
        $this->addSql('CREATE TABLE asset (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , episode_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , season_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , geo_profile_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL COLLATE BINARY, title_sortable VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, description_short VARCHAR(90) DEFAULT NULL COLLATE BINARY, description_long CLOB DEFAULT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, duration INTEGER DEFAULT NULL, legacy_tp_media_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, dfp_exclude BOOLEAN DEFAULT NULL, content_rating VARCHAR(255) DEFAULT NULL COLLATE BINARY, can_embed_player BOOLEAN DEFAULT NULL, language VARCHAR(2) DEFAULT NULL COLLATE BINARY, funder_message CLOB DEFAULT NULL COLLATE BINARY, player_code CLOB DEFAULT NULL COLLATE BINARY, has_captions BOOLEAN DEFAULT NULL, content_rating_descriptor CLOB DEFAULT NULL --(DC2Type:array)
        , updated DATETIME DEFAULT NULL, chapters CLOB DEFAULT NULL --(DC2Type:array)
        , links CLOB DEFAULT NULL --(DC2Type:array)
        , captions CLOB DEFAULT NULL --(DC2Type:array)
        , videos CLOB DEFAULT NULL --(DC2Type:array)
        , PRIMARY KEY(id), CONSTRAINT FK_2AF5A5C362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2AF5A5C4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2AF5A5CD0C1FC64 FOREIGN KEY (show_id) REFERENCES show (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2AF5A5C523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2AF5A5CB5879536 FOREIGN KEY (geo_profile_id) REFERENCES geo_availability_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset (id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, can_embed_player, language, funder_message, player_code, has_captions, content_rating_descriptor, updated, chapters, links, captions, videos) SELECT id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, can_embed_player, language, funder_message, player_code, has_captions, content_rating_descriptor, updated, chapters, links, captions, videos FROM __temp__asset');
        $this->addSql('DROP TABLE __temp__asset');
        $this->addSql('CREATE INDEX IDX_2AF5A5CB5879536 ON asset (geo_profile_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C523CAB89 ON asset (franchise_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CD0C1FC64 ON asset (show_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C4EC001D1 ON asset (season_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C362B62A0 ON asset (episode_id)');
        $this->addSql('DROP INDEX IDX_BB39CA4BFFE6496F');
        $this->addSql('DROP INDEX IDX_BB39CA4B5DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_platform AS SELECT asset_id, platform_id FROM asset_platform');
        $this->addSql('DROP TABLE asset_platform');
        $this->addSql('CREATE TABLE asset_platform (asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(asset_id, platform_id), CONSTRAINT FK_BB39CA4B5DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BB39CA4BFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_platform (asset_id, platform_id) SELECT asset_id, platform_id FROM __temp__asset_platform');
        $this->addSql('DROP TABLE __temp__asset_platform');
        $this->addSql('CREATE INDEX IDX_BB39CA4BFFE6496F ON asset_platform (platform_id)');
        $this->addSql('CREATE INDEX IDX_BB39CA4B5DA1941 ON asset_platform (asset_id)');
        $this->addSql('DROP INDEX IDX_14794C75FA6B6C4A');
        $this->addSql('DROP INDEX IDX_14794C755DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_geo_availability_country AS SELECT asset_id, geo_availability_country_id FROM asset_geo_availability_country');
        $this->addSql('DROP TABLE asset_geo_availability_country');
        $this->addSql('CREATE TABLE asset_geo_availability_country (asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , geo_availability_country_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(asset_id, geo_availability_country_id), CONSTRAINT FK_14794C755DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14794C75FA6B6C4A FOREIGN KEY (geo_availability_country_id) REFERENCES geo_availability_country (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_geo_availability_country (asset_id, geo_availability_country_id) SELECT asset_id, geo_availability_country_id FROM __temp__asset_geo_availability_country');
        $this->addSql('DROP TABLE __temp__asset_geo_availability_country');
        $this->addSql('CREATE INDEX IDX_14794C75FA6B6C4A ON asset_geo_availability_country (geo_availability_country_id)');
        $this->addSql('CREATE INDEX IDX_14794C755DA1941 ON asset_geo_availability_country (asset_id)');
        $this->addSql('DROP INDEX IDX_84B99C3552FFEC0C');
        $this->addSql('DROP INDEX IDX_84B99C355DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_asset_tag AS SELECT asset_id, asset_tag_id FROM asset_asset_tag');
        $this->addSql('DROP TABLE asset_asset_tag');
        $this->addSql('CREATE TABLE asset_asset_tag (asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(asset_id, asset_tag_id), CONSTRAINT FK_84B99C355DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_84B99C3552FFEC0C FOREIGN KEY (asset_tag_id) REFERENCES asset_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_asset_tag (asset_id, asset_tag_id) SELECT asset_id, asset_tag_id FROM __temp__asset_asset_tag');
        $this->addSql('DROP TABLE __temp__asset_asset_tag');
        $this->addSql('CREATE INDEX IDX_84B99C3552FFEC0C ON asset_asset_tag (asset_tag_id)');
        $this->addSql('CREATE INDEX IDX_84B99C355DA1941 ON asset_asset_tag (asset_id)');
        $this->addSql('DROP INDEX IDX_8472097DD648660D');
        $this->addSql('DROP INDEX IDX_8472097D5DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_remote_asset AS SELECT asset_id, remote_asset_id FROM asset_remote_asset');
        $this->addSql('DROP TABLE asset_remote_asset');
        $this->addSql('CREATE TABLE asset_remote_asset (asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , remote_asset_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(asset_id, remote_asset_id), CONSTRAINT FK_8472097D5DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8472097DD648660D FOREIGN KEY (remote_asset_id) REFERENCES remote_asset (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO asset_remote_asset (asset_id, remote_asset_id) SELECT asset_id, remote_asset_id FROM __temp__asset_remote_asset');
        $this->addSql('DROP TABLE __temp__asset_remote_asset');
        $this->addSql('CREATE INDEX IDX_8472097DD648660D ON asset_remote_asset (remote_asset_id)');
        $this->addSql('CREATE INDEX IDX_8472097D5DA1941 ON asset_remote_asset (asset_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE asset_topic');
        $this->addSql('DROP INDEX IDX_2AF5A5C362B62A0');
        $this->addSql('DROP INDEX IDX_2AF5A5C4EC001D1');
        $this->addSql('DROP INDEX IDX_2AF5A5CD0C1FC64');
        $this->addSql('DROP INDEX IDX_2AF5A5C523CAB89');
        $this->addSql('DROP INDEX IDX_2AF5A5CB5879536');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset AS SELECT id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, content_rating_descriptor, can_embed_player, language, funder_message, updated, player_code, has_captions, chapters, links, captions, videos FROM asset');
        $this->addSql('DROP TABLE asset');
        $this->addSql('CREATE TABLE asset (id CHAR(36) NOT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, type VARCHAR(255) NOT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, duration INTEGER DEFAULT NULL, legacy_tp_media_id VARCHAR(255) DEFAULT NULL, dfp_exclude BOOLEAN DEFAULT NULL, content_rating VARCHAR(255) DEFAULT NULL, can_embed_player BOOLEAN DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, funder_message CLOB DEFAULT NULL, player_code CLOB DEFAULT NULL, has_captions BOOLEAN DEFAULT NULL, episode_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , season_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , geo_profile_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , content_rating_descriptor CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , updated DATETIME DEFAULT NULL, chapters CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , links CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , captions CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , videos CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO asset (id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, content_rating_descriptor, can_embed_player, language, funder_message, updated, player_code, has_captions, chapters, links, captions, videos) SELECT id, episode_id, season_id, show_id, franchise_id, geo_profile_id, title, title_sortable, slug, description_short, description_long, type, premiered, encored, duration, legacy_tp_media_id, dfp_exclude, content_rating, content_rating_descriptor, can_embed_player, language, funder_message, updated, player_code, has_captions, chapters, links, captions, videos FROM __temp__asset');
        $this->addSql('DROP TABLE __temp__asset');
        $this->addSql('CREATE INDEX IDX_2AF5A5C362B62A0 ON asset (episode_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C4EC001D1 ON asset (season_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CD0C1FC64 ON asset (show_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C523CAB89 ON asset (franchise_id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CB5879536 ON asset (geo_profile_id)');
        $this->addSql('DROP INDEX IDX_84B99C355DA1941');
        $this->addSql('DROP INDEX IDX_84B99C3552FFEC0C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_asset_tag AS SELECT asset_id, asset_tag_id FROM asset_asset_tag');
        $this->addSql('DROP TABLE asset_asset_tag');
        $this->addSql('CREATE TABLE asset_asset_tag (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(asset_id, asset_tag_id))');
        $this->addSql('INSERT INTO asset_asset_tag (asset_id, asset_tag_id) SELECT asset_id, asset_tag_id FROM __temp__asset_asset_tag');
        $this->addSql('DROP TABLE __temp__asset_asset_tag');
        $this->addSql('CREATE INDEX IDX_84B99C355DA1941 ON asset_asset_tag (asset_id)');
        $this->addSql('CREATE INDEX IDX_84B99C3552FFEC0C ON asset_asset_tag (asset_tag_id)');
        $this->addSql('DROP INDEX IDX_46E062425DA1941');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_availability AS SELECT id, asset_id, type, start_date_time, end_date_time, updated FROM asset_availability');
        $this->addSql('DROP TABLE asset_availability');
        $this->addSql('CREATE TABLE asset_availability (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , type VARCHAR(255) NOT NULL, start_date_time DATETIME DEFAULT NULL, end_date_time DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO asset_availability (id, asset_id, type, start_date_time, end_date_time, updated) SELECT id, asset_id, type, start_date_time, end_date_time, updated FROM __temp__asset_availability');
        $this->addSql('DROP TABLE __temp__asset_availability');
        $this->addSql('CREATE INDEX IDX_46E062425DA1941 ON asset_availability (asset_id)');
        $this->addSql('DROP INDEX IDX_14794C755DA1941');
        $this->addSql('DROP INDEX IDX_14794C75FA6B6C4A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_geo_availability_country AS SELECT asset_id, geo_availability_country_id FROM asset_geo_availability_country');
        $this->addSql('DROP TABLE asset_geo_availability_country');
        $this->addSql('CREATE TABLE asset_geo_availability_country (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , geo_availability_country_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, geo_availability_country_id))');
        $this->addSql('INSERT INTO asset_geo_availability_country (asset_id, geo_availability_country_id) SELECT asset_id, geo_availability_country_id FROM __temp__asset_geo_availability_country');
        $this->addSql('DROP TABLE __temp__asset_geo_availability_country');
        $this->addSql('CREATE INDEX IDX_14794C755DA1941 ON asset_geo_availability_country (asset_id)');
        $this->addSql('CREATE INDEX IDX_14794C75FA6B6C4A ON asset_geo_availability_country (geo_availability_country_id)');
        $this->addSql('DROP INDEX IDX_BB39CA4B5DA1941');
        $this->addSql('DROP INDEX IDX_BB39CA4BFFE6496F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_platform AS SELECT asset_id, platform_id FROM asset_platform');
        $this->addSql('DROP TABLE asset_platform');
        $this->addSql('CREATE TABLE asset_platform (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, platform_id))');
        $this->addSql('INSERT INTO asset_platform (asset_id, platform_id) SELECT asset_id, platform_id FROM __temp__asset_platform');
        $this->addSql('DROP TABLE __temp__asset_platform');
        $this->addSql('CREATE INDEX IDX_BB39CA4B5DA1941 ON asset_platform (asset_id)');
        $this->addSql('CREATE INDEX IDX_BB39CA4BFFE6496F ON asset_platform (platform_id)');
        $this->addSql('DROP INDEX IDX_8472097D5DA1941');
        $this->addSql('DROP INDEX IDX_8472097DD648660D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__asset_remote_asset AS SELECT asset_id, remote_asset_id FROM asset_remote_asset');
        $this->addSql('DROP TABLE asset_remote_asset');
        $this->addSql('CREATE TABLE asset_remote_asset (asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , remote_asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(asset_id, remote_asset_id))');
        $this->addSql('INSERT INTO asset_remote_asset (asset_id, remote_asset_id) SELECT asset_id, remote_asset_id FROM __temp__asset_remote_asset');
        $this->addSql('DROP TABLE __temp__asset_remote_asset');
        $this->addSql('CREATE INDEX IDX_8472097D5DA1941 ON asset_remote_asset (asset_id)');
        $this->addSql('CREATE INDEX IDX_8472097DD648660D ON asset_remote_asset (remote_asset_id)');
        $this->addSql('DROP INDEX IDX_FDCD941821BDB235');
        $this->addSql('CREATE TEMPORARY TABLE __temp__audience AS SELECT id, station_id, scope FROM audience');
        $this->addSql('DROP TABLE audience');
        $this->addSql('CREATE TABLE audience (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scope VARCHAR(255) NOT NULL, station_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        )');
        $this->addSql('INSERT INTO audience (id, station_id, scope) SELECT id, station_id, scope FROM __temp__audience');
        $this->addSql('DROP TABLE __temp__audience');
        $this->addSql('CREATE INDEX IDX_FDCD941821BDB235 ON audience (station_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__changelog_entry AS SELECT id, activity, updated_fields, type, resource_id, timestamp FROM changelog_entry');
        $this->addSql('DROP TABLE changelog_entry');
        $this->addSql('CREATE TABLE changelog_entry (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, activity VARCHAR(255) NOT NULL, updated_fields CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , type CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , resource_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , timestamp DATETIME NOT NULL)');
        $this->addSql('INSERT INTO changelog_entry (id, activity, updated_fields, type, resource_id, timestamp) SELECT id, activity, updated_fields, type, resource_id, timestamp FROM __temp__changelog_entry');
        $this->addSql('DROP TABLE __temp__changelog_entry');
        $this->addSql('DROP INDEX IDX_DDAA1CDA4EC001D1');
        $this->addSql('DROP INDEX IDX_DDAA1CDAD0C1FC64');
        $this->addSql('DROP INDEX IDX_DDAA1CDA5F7888C2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__episode AS SELECT id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated FROM episode');
        $this->addSql('DROP TABLE episode');
        $this->addSql('CREATE TABLE episode (id CHAR(36) NOT NULL --(DC2Type:guid)
        , season_id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, segment VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(4) DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, full_length_asset_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO episode (id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated) SELECT id, season_id, show_id, full_length_asset_id, ordinal, segment, title, title_sortable, slug, tms_id, description_short, description_long, premiered, encored, nola, language, updated FROM __temp__episode');
        $this->addSql('DROP TABLE __temp__episode');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA4EC001D1 ON episode (season_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDAD0C1FC64 ON episode (show_id)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5F7888C2 ON episode (full_length_asset_id)');
        $this->addSql('DROP INDEX IDX_66F6CE2A4296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__franchise AS SELECT id, genre_id, slug, nola, title, title_sortable, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, links FROM franchise');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('CREATE TABLE franchise (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message VARCHAR(255) DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, genre_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , premiered DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, links CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO franchise (id, genre_id, slug, nola, title, title_sortable, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, links) SELECT id, genre_id, slug, nola, title, title_sortable, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, links FROM __temp__franchise');
        $this->addSql('DROP TABLE __temp__franchise');
        $this->addSql('CREATE INDEX IDX_66F6CE2A4296D31F ON franchise (genre_id)');
        $this->addSql('DROP INDEX IDX_B6622B37523CAB89');
        $this->addSql('DROP INDEX IDX_B6622B37FFE6496F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__franchise_platform AS SELECT franchise_id, platform_id FROM franchise_platform');
        $this->addSql('DROP TABLE franchise_platform');
        $this->addSql('CREATE TABLE franchise_platform (franchise_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(franchise_id, platform_id))');
        $this->addSql('INSERT INTO franchise_platform (franchise_id, platform_id) SELECT franchise_id, platform_id FROM __temp__franchise_platform');
        $this->addSql('DROP TABLE __temp__franchise_platform');
        $this->addSql('CREATE INDEX IDX_B6622B37523CAB89 ON franchise_platform (franchise_id)');
        $this->addSql('CREATE INDEX IDX_B6622B37FFE6496F ON franchise_platform (platform_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__genre AS SELECT id, slug, title, created, updated FROM genre');
        $this->addSql('DROP TABLE genre');
        $this->addSql('CREATE TABLE genre (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO genre (id, slug, title, created, updated) SELECT id, slug, title, created, updated FROM __temp__genre');
        $this->addSql('DROP TABLE __temp__genre');
        $this->addSql('CREATE TEMPORARY TABLE __temp__geo_availability_country AS SELECT id, code, name, updated FROM geo_availability_country');
        $this->addSql('DROP TABLE geo_availability_country');
        $this->addSql('CREATE TABLE geo_availability_country (id CHAR(36) NOT NULL --(DC2Type:guid)
        , code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO geo_availability_country (id, code, name, updated) SELECT id, code, name, updated FROM __temp__geo_availability_country');
        $this->addSql('DROP TABLE __temp__geo_availability_country');
        $this->addSql('CREATE TEMPORARY TABLE __temp__geo_availability_profile AS SELECT id, name, updated FROM geo_availability_profile');
        $this->addSql('DROP TABLE geo_availability_profile');
        $this->addSql('CREATE TABLE geo_availability_profile (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO geo_availability_profile (id, name, updated) SELECT id, name, updated FROM __temp__geo_availability_profile');
        $this->addSql('DROP TABLE __temp__geo_availability_profile');
        $this->addSql('DROP INDEX IDX_C53D045F5DA1941');
        $this->addSql('DROP INDEX IDX_C53D045F523CAB89');
        $this->addSql('DROP INDEX IDX_C53D045FD0C1FC64');
        $this->addSql('DROP INDEX IDX_C53D045F21BDB235');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, asset_id, franchise_id, show_id, station_id, image, profile, updated FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, profile VARCHAR(255) NOT NULL, asset_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , station_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , updated DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO image (id, asset_id, franchise_id, show_id, station_id, image, profile, updated) SELECT id, asset_id, franchise_id, show_id, station_id, image, profile, updated FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F5DA1941 ON image (asset_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F523CAB89 ON image (franchise_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FD0C1FC64 ON image (show_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F21BDB235 ON image (station_id)');
        $this->addSql('DROP INDEX IDX_CB0048D43EB8070A');
        $this->addSql('DROP INDEX IDX_CB0048D451A5BC03');
        $this->addSql('CREATE TEMPORARY TABLE __temp__listing AS SELECT id, program_id, feed_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date FROM listing');
        $this->addSql('DROP TABLE listing');
        $this->addSql('CREATE TABLE listing (id CHAR(36) NOT NULL --(DC2Type:guid)
        , feed_id CHAR(36) NOT NULL --(DC2Type:guid)
        , package_id VARCHAR(255) DEFAULT NULL, taped BOOLEAN NOT NULL, duration VARCHAR(255) NOT NULL, duration_minutes INTEGER NOT NULL, nola_episode VARCHAR(255) DEFAULT NULL, nola_root VARCHAR(4) DEFAULT NULL, season_premiere_finale VARCHAR(255) DEFAULT NULL, special_warnings CLOB DEFAULT NULL, start_time VARCHAR(4) NOT NULL, title VARCHAR(255) DEFAULT NULL, animated BOOLEAN NOT NULL, closed_captions BOOLEAN NOT NULL, hd BOOLEAN NOT NULL, stereo BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, show_id VARCHAR(255) DEFAULT NULL, episode_title VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, episode_description CLOB DEFAULT NULL, date DATE NOT NULL, program_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO listing (id, program_id, feed_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date) SELECT id, program_id, feed_id, package_id, taped, duration, duration_minutes, nola_episode, nola_root, season_premiere_finale, special_warnings, start_time, title, animated, closed_captions, hd, stereo, type, show_id, episode_title, description, episode_description, date FROM __temp__listing');
        $this->addSql('DROP TABLE __temp__listing');
        $this->addSql('CREATE INDEX IDX_CB0048D43EB8070A ON listing (program_id)');
        $this->addSql('CREATE INDEX IDX_CB0048D451A5BC03 ON listing (feed_id)');
        $this->addSql('DROP INDEX IDX_86FFD285C299A5BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__membership AS SELECT id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date FROM membership');
        $this->addSql('DROP TABLE membership');
        $this->addSql('CREATE TABLE membership (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, offer VARCHAR(255) DEFAULT NULL, notes CLOB DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, additional_metadata VARCHAR(255) DEFAULT NULL, provisional BOOLEAN NOT NULL, pbs_profile_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , start_date DATETIME NOT NULL, expire_date DATETIME NOT NULL, activation_date DATETIME DEFAULT NULL, grace_period DATETIME NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO membership (id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date) SELECT id, pbs_profile_id, first_name, last_name, email, offer, notes, status, token, additional_metadata, provisional, start_date, expire_date, activation_date, grace_period, create_date, update_date FROM __temp__membership');
        $this->addSql('DROP TABLE __temp__membership');
        $this->addSql('CREATE INDEX IDX_86FFD285C299A5BF ON membership (pbs_profile_id)');
        $this->addSql('DROP INDEX IDX_FD25C980523CAB89');
        $this->addSql('DROP INDEX IDX_FD25C980D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__remote_asset AS SELECT id, franchise_id, show_id, title, description_short, description_long, url, image, updated FROM remote_asset');
        $this->addSql('DROP TABLE remote_asset');
        $this->addSql('CREATE TABLE remote_asset (id CHAR(36) NOT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, url VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, franchise_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , show_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO remote_asset (id, franchise_id, show_id, title, description_short, description_long, url, image, updated) SELECT id, franchise_id, show_id, title, description_short, description_long, url, image, updated FROM __temp__remote_asset');
        $this->addSql('DROP TABLE __temp__remote_asset');
        $this->addSql('CREATE INDEX IDX_FD25C980523CAB89 ON remote_asset (franchise_id)');
        $this->addSql('CREATE INDEX IDX_FD25C980D0C1FC64 ON remote_asset (show_id)');
        $this->addSql('DROP INDEX IDX_62415E2BD648660D');
        $this->addSql('DROP INDEX IDX_62415E2B52FFEC0C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__remote_asset_asset_tag AS SELECT remote_asset_id, asset_tag_id FROM remote_asset_asset_tag');
        $this->addSql('DROP TABLE remote_asset_asset_tag');
        $this->addSql('CREATE TABLE remote_asset_asset_tag (remote_asset_id CHAR(36) NOT NULL --(DC2Type:guid)
        , asset_tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(remote_asset_id, asset_tag_id))');
        $this->addSql('INSERT INTO remote_asset_asset_tag (remote_asset_id, asset_tag_id) SELECT remote_asset_id, asset_tag_id FROM __temp__remote_asset_asset_tag');
        $this->addSql('DROP TABLE __temp__remote_asset_asset_tag');
        $this->addSql('CREATE INDEX IDX_62415E2BD648660D ON remote_asset_asset_tag (remote_asset_id)');
        $this->addSql('CREATE INDEX IDX_62415E2B52FFEC0C ON remote_asset_asset_tag (asset_tag_id)');
        $this->addSql('DROP INDEX IDX_F0E45BA9D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__season AS SELECT id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links FROM season');
        $this->addSql('DROP TABLE season');
        $this->addSql('CREATE TABLE season (id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , ordinal INTEGER NOT NULL, title VARCHAR(255) DEFAULT NULL, title_sortable VARCHAR(255) DEFAULT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) DEFAULT NULL, description_long CLOB DEFAULT NULL, updated DATETIME DEFAULT NULL, latest_asset_images CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , links CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO season (id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links) SELECT id, show_id, ordinal, title, title_sortable, tms_id, description_short, description_long, updated, latest_asset_images, links FROM __temp__season');
        $this->addSql('DROP TABLE __temp__season');
        $this->addSql('CREATE INDEX IDX_F0E45BA9D0C1FC64 ON season (show_id)');
        $this->addSql('DROP INDEX IDX_9F74B8987E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__setting AS SELECT id, owner_id, value, updated FROM setting');
        $this->addSql('DROP TABLE setting');
        $this->addSql('CREATE TABLE setting (id VARCHAR(255) NOT NULL, owner_id INTEGER NOT NULL, value VARCHAR(255) NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO setting (id, owner_id, value, updated) SELECT id, owner_id, value, updated FROM __temp__setting');
        $this->addSql('DROP TABLE __temp__setting');
        $this->addSql('CREATE INDEX IDX_9F74B8987E3C61F9 ON setting (owner_id)');
        $this->addSql('DROP INDEX IDX_320ED9014296D31F');
        $this->addSql('DROP INDEX IDX_320ED901523CAB89');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show AS SELECT id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, links FROM show');
        $this->addSql('DROP TABLE show');
        $this->addSql('CREATE TABLE show (id CHAR(36) NOT NULL --(DC2Type:guid)
        , slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, nola VARCHAR(4) DEFAULT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) NOT NULL, description_long CLOB NOT NULL, dfp_exclude BOOLEAN NOT NULL, funder_message CLOB DEFAULT NULL, ga_tracking_page VARCHAR(255) DEFAULT NULL, ga_tracking_event VARCHAR(255) DEFAULT NULL, hashtag VARCHAR(255) DEFAULT NULL, display_episode_number BOOLEAN NOT NULL, can_embed_player BOOLEAN NOT NULL, language VARCHAR(2) DEFAULT NULL, ordinal_seasons BOOLEAN NOT NULL, episode_sort_desc BOOLEAN NOT NULL, episode_count INTEGER DEFAULT NULL, genre_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , franchise_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , premiered DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, links CLOB DEFAULT \'NULL --(DC2Type:array)\' COLLATE BINARY --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO show (id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, links) SELECT id, genre_id, franchise_id, slug, title, title_sortable, nola, tms_id, description_short, description_long, premiered, dfp_exclude, funder_message, ga_tracking_page, ga_tracking_event, updated, hashtag, display_episode_number, can_embed_player, language, ordinal_seasons, episode_sort_desc, episode_count, links FROM __temp__show');
        $this->addSql('DROP TABLE __temp__show');
        $this->addSql('CREATE INDEX IDX_320ED9014296D31F ON show (genre_id)');
        $this->addSql('CREATE INDEX IDX_320ED901523CAB89 ON show (franchise_id)');
        $this->addSql('DROP INDEX IDX_C4A975F7D0C1FC64');
        $this->addSql('DROP INDEX IDX_C4A975F7848CC616');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show_audience AS SELECT show_id, audience_id FROM show_audience');
        $this->addSql('DROP TABLE show_audience');
        $this->addSql('CREATE TABLE show_audience (show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , audience_id INTEGER NOT NULL, PRIMARY KEY(show_id, audience_id))');
        $this->addSql('INSERT INTO show_audience (show_id, audience_id) SELECT show_id, audience_id FROM __temp__show_audience');
        $this->addSql('DROP TABLE __temp__show_audience');
        $this->addSql('CREATE INDEX IDX_C4A975F7D0C1FC64 ON show_audience (show_id)');
        $this->addSql('CREATE INDEX IDX_C4A975F7848CC616 ON show_audience (audience_id)');
        $this->addSql('DROP INDEX IDX_363124D0C1FC64');
        $this->addSql('DROP INDEX IDX_363124FFE6496F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__show_platform AS SELECT show_id, platform_id FROM show_platform');
        $this->addSql('DROP TABLE show_platform');
        $this->addSql('CREATE TABLE show_platform (show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , platform_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(show_id, platform_id))');
        $this->addSql('INSERT INTO show_platform (show_id, platform_id) SELECT show_id, platform_id FROM __temp__show_platform');
        $this->addSql('DROP TABLE __temp__show_platform');
        $this->addSql('CREATE INDEX IDX_363124D0C1FC64 ON show_platform (show_id)');
        $this->addSql('CREATE INDEX IDX_363124FFE6496F ON show_platform (platform_id)');
        $this->addSql('DROP INDEX IDX_4C6B3FE3D0C1FC64');
        $this->addSql('CREATE TEMPORARY TABLE __temp__special AS SELECT id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, updated, full_length_asset FROM special');
        $this->addSql('DROP TABLE special');
        $this->addSql('CREATE TABLE special (id CHAR(36) NOT NULL --(DC2Type:guid)
        , show_id CHAR(36) NOT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, title_sortable VARCHAR(255) NOT NULL, tms_id VARCHAR(255) DEFAULT NULL, description_short VARCHAR(90) NOT NULL, description_long CLOB NOT NULL, premiered DATE DEFAULT NULL, encored DATE DEFAULT NULL, nola VARCHAR(255) DEFAULT NULL, language VARCHAR(2) DEFAULT NULL, full_length_asset BOOLEAN DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO special (id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, updated, full_length_asset) SELECT id, show_id, title, slug, title_sortable, tms_id, description_short, description_long, premiered, encored, nola, language, updated, full_length_asset FROM __temp__special');
        $this->addSql('DROP TABLE __temp__special');
        $this->addSql('CREATE INDEX IDX_4C6B3FE3D0C1FC64 ON special (show_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__station AS SELECT id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, updated, pdp, passport_enabled, annual_passport_qualifying_amount FROM station');
        $this->addSql('DROP TABLE station');
        $this->addSql('CREATE TABLE station (id CHAR(36) NOT NULL --(DC2Type:guid)
        , call_sign VARCHAR(255) NOT NULL, full_common_name VARCHAR(255) NOT NULL, short_common_name VARCHAR(128) NOT NULL, tvss_url VARCHAR(255) NOT NULL, donate_url VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, timezone_secondary VARCHAR(255) DEFAULT NULL, video_portal_url VARCHAR(255) DEFAULT NULL, video_portal_banner_url VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, facebook_url VARCHAR(255) DEFAULT NULL, twitter_url VARCHAR(255) DEFAULT NULL, kids_station_url VARCHAR(255) DEFAULT NULL, passport_url VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_line1 VARCHAR(255) DEFAULT NULL, address_line2 VARCHAR(255) DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_country_code VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, tag_line VARCHAR(255) DEFAULT NULL, tracking_code_page VARCHAR(255) DEFAULT NULL, tracking_code_event VARCHAR(255) DEFAULT NULL, primary_channel VARCHAR(255) DEFAULT NULL, primetime_start VARCHAR(255) DEFAULT NULL, pdp BOOLEAN DEFAULT NULL, passport_enabled BOOLEAN DEFAULT NULL, annual_passport_qualifying_amount INTEGER DEFAULT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO station (id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, updated, pdp, passport_enabled, annual_passport_qualifying_amount) SELECT id, call_sign, full_common_name, short_common_name, tvss_url, donate_url, timezone, timezone_secondary, video_portal_url, video_portal_banner_url, website_url, facebook_url, twitter_url, kids_station_url, passport_url, telephone, fax, address_city, address_state, address_line1, address_line2, address_zip_code, address_country_code, email, tag_line, tracking_code_page, tracking_code_event, primary_channel, primetime_start, updated, pdp, passport_enabled, annual_passport_qualifying_amount FROM __temp__station');
        $this->addSql('DROP TABLE __temp__station');
        $this->addSql('DROP INDEX IDX_9D40DE1B727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__topic AS SELECT id, parent_id, name, updated FROM topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('CREATE TABLE topic (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, parent_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , updated DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO topic (id, parent_id, name, updated) SELECT id, parent_id, name, updated FROM __temp__topic');
        $this->addSql('DROP TABLE __temp__topic');
        $this->addSql('CREATE INDEX IDX_9D40DE1B727ACA70 ON topic (parent_id)');
    }
}
