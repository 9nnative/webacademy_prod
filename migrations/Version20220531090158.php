<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531090158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_6692B54A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, section_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_F981B52E591CC992 (course_id), INDEX IDX_F981B52ED823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, autosaved_section_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', state VARCHAR(25) NOT NULL, slug VARCHAR(255) NOT NULL, is_paying TINYINT(1) NOT NULL, is_certifying TINYINT(1) DEFAULT NULL, brochure_filename VARCHAR(255) DEFAULT NULL, duration VARCHAR(255) DEFAULT NULL, views INT DEFAULT NULL, description LONGTEXT NOT NULL, step INT DEFAULT NULL, date_end_bool TINYINT(1) NOT NULL, date_start DATETIME DEFAULT NULL, date_end DATETIME DEFAULT NULL, prerequis VARCHAR(255) DEFAULT NULL, objectifs_pedago VARCHAR(255) DEFAULT NULL, date_start_str VARCHAR(255) DEFAULT NULL, date_end_str VARCHAR(255) DEFAULT NULL, autosave TINYINT(1) DEFAULT NULL, targets LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_169E6FB9989D9B62 (slug), INDEX IDX_169E6FB9B03A8386 (created_by_id), UNIQUE INDEX UNIQ_169E6FB983F4A608 (autosaved_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_user_group (course_id INT NOT NULL, user_group_id INT NOT NULL, INDEX IDX_50E0F11E591CC992 (course_id), INDEX IDX_50E0F11E1ED93D47 (user_group_id), PRIMARY KEY(course_id, user_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_category (course_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_AFF87497591CC992 (course_id), INDEX IDX_AFF8749712469DE2 (category_id), PRIMARY KEY(course_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_file (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_files (id INT AUTO_INCREMENT NOT NULL, section_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, is_readable TINYINT(1) DEFAULT NULL, INDEX IDX_EF9E47B1D823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_naviguation (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, user_id INT NOT NULL, section_id INT DEFAULT NULL, step VARCHAR(255) NOT NULL, started TINYINT(1) DEFAULT NULL, INDEX IDX_35EB1E30591CC992 (course_id), INDEX IDX_35EB1E30A76ED395 (user_id), INDEX IDX_35EB1E30D823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, actionby_id INT DEFAULT NULL, wgroup_id INT DEFAULT NULL, type VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, INDEX IDX_3BAE0AA78123EB11 (actionby_id), INDEX IDX_3BAE0AA7E9BDBC9C (wgroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_course (event_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_240718EB71F7E88B (event_id), INDEX IDX_240718EB591CC992 (course_id), PRIMARY KEY(event_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_event (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, post_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CE21591BB03A8386 (created_by_id), UNIQUE INDEX UNIQ_CE21591B4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_event_course (feed_event_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_CBC686B051227F2C (feed_event_id), INDEX IDX_CBC686B0591CC992 (course_id), PRIMARY KEY(feed_event_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_event_user (feed_event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1D7596FE51227F2C (feed_event_id), INDEX IDX_1D7596FEA76ED395 (user_id), PRIMARY KEY(feed_event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_prompt (id INT AUTO_INCREMENT NOT NULL, wgroup_id INT NOT NULL, content VARCHAR(255) NOT NULL, brochure_filename VARCHAR(255) NOT NULL, INDEX IDX_B169B4E8E9BDBC9C (wgroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invite_to_group (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, wgroup_id INT DEFAULT NULL, course_id INT DEFAULT NULL, navigation_id INT DEFAULT NULL, status TINYINT(1) NOT NULL, INDEX IDX_DC33B47CA76ED395 (user_id), INDEX IDX_DC33B47CE9BDBC9C (wgroup_id), INDEX IDX_DC33B47C591CC992 (course_id), UNIQUE INDEX UNIQ_DC33B47C39F79D6D (navigation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links (id INT AUTO_INCREMENT NOT NULL, wgroup_id INT DEFAULT NULL, user_id INT DEFAULT NULL, section_id INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, isavideo TINYINT(1) DEFAULT NULL, state VARCHAR(30) DEFAULT NULL, INDEX IDX_D182A118E9BDBC9C (wgroup_id), INDEX IDX_D182A118A76ED395 (user_id), INDEX IDX_D182A118D823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, wroteby_id INT NOT NULL, wgroup_id INT NOT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, brochure_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_B6BD307FC5A121B (wroteby_id), INDEX IDX_B6BD307FE9BDBC9C (wgroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, wgroup_id INT DEFAULT NULL, invite_to_group_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, link VARCHAR(255) DEFAULT NULL, header VARCHAR(255) NOT NULL, status TINYINT(1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_BF5476CAE9BDBC9C (wgroup_id), UNIQUE INDEX UNIQ_BF5476CA4D35E72 (invite_to_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_user (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_35AF9D73EF1A9D84 (notification_id), INDEX IDX_35AF9D73A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, state VARCHAR(20) DEFAULT NULL, is_hidden TINYINT(1) DEFAULT NULL, INDEX IDX_2D737AEF591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subchapter (id INT AUTO_INCREMENT NOT NULL, chapter_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B2E31DE1579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, priority INT DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, INDEX IDX_97A0ADA3B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(30) DEFAULT NULL, forename VARCHAR(30) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, birthdate DATETIME DEFAULT NULL, brochure_filename VARCHAR(255) DEFAULT NULL, inscription_date DATETIME DEFAULT NULL, lasttimeseen DATETIME DEFAULT NULL, notifstate TINYINT(1) DEFAULT NULL, darkmode TINYINT(1) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, tutorial TINYINT(1) DEFAULT NULL, cheatcode VARCHAR(11) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_course (user_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_73CC7484A76ED395 (user_id), INDEX IDX_73CC7484591CC992 (course_id), PRIMARY KEY(user_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_8F02BF9DB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_user (user_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3AE4BD51ED93D47 (user_group_id), INDEX IDX_3AE4BD5A76ED395 (user_id), PRIMARY KEY(user_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_course (user_group_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_45D4CB6B1ED93D47 (user_group_id), INDEX IDX_45D4CB6B591CC992 (course_id), PRIMARY KEY(user_group_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access ADD CONSTRAINT FK_6692B54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52ED823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB983F4A608 FOREIGN KEY (autosaved_section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE course_user_group ADD CONSTRAINT FK_50E0F11E591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_user_group ADD CONSTRAINT FK_50E0F11E1ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_category ADD CONSTRAINT FK_AFF87497591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_category ADD CONSTRAINT FK_AFF8749712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_files ADD CONSTRAINT FK_EF9E47B1D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE course_naviguation ADD CONSTRAINT FK_35EB1E30591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE course_naviguation ADD CONSTRAINT FK_35EB1E30A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course_naviguation ADD CONSTRAINT FK_35EB1E30D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA78123EB11 FOREIGN KEY (actionby_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE event_course ADD CONSTRAINT FK_240718EB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_course ADD CONSTRAINT FK_240718EB591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feed_event ADD CONSTRAINT FK_CE21591BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feed_event ADD CONSTRAINT FK_CE21591B4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE feed_event_course ADD CONSTRAINT FK_CBC686B051227F2C FOREIGN KEY (feed_event_id) REFERENCES feed_event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feed_event_course ADD CONSTRAINT FK_CBC686B0591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feed_event_user ADD CONSTRAINT FK_1D7596FE51227F2C FOREIGN KEY (feed_event_id) REFERENCES feed_event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feed_event_user ADD CONSTRAINT FK_1D7596FEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_prompt ADD CONSTRAINT FK_B169B4E8E9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE invite_to_group ADD CONSTRAINT FK_DC33B47CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invite_to_group ADD CONSTRAINT FK_DC33B47CE9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE invite_to_group ADD CONSTRAINT FK_DC33B47C591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE invite_to_group ADD CONSTRAINT FK_DC33B47C39F79D6D FOREIGN KEY (navigation_id) REFERENCES course_naviguation (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118E9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC5A121B FOREIGN KEY (wroteby_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAE9BDBC9C FOREIGN KEY (wgroup_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA4D35E72 FOREIGN KEY (invite_to_group_id) REFERENCES invite_to_group (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE subchapter ADD CONSTRAINT FK_B2E31DE1579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD51ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_user ADD CONSTRAINT FK_3AE4BD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_course ADD CONSTRAINT FK_45D4CB6B1ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_course ADD CONSTRAINT FK_45D4CB6B591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_category DROP FOREIGN KEY FK_AFF8749712469DE2');
        $this->addSql('ALTER TABLE subchapter DROP FOREIGN KEY FK_B2E31DE1579F4768');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E591CC992');
        $this->addSql('ALTER TABLE course_user_group DROP FOREIGN KEY FK_50E0F11E591CC992');
        $this->addSql('ALTER TABLE course_category DROP FOREIGN KEY FK_AFF87497591CC992');
        $this->addSql('ALTER TABLE course_naviguation DROP FOREIGN KEY FK_35EB1E30591CC992');
        $this->addSql('ALTER TABLE event_course DROP FOREIGN KEY FK_240718EB591CC992');
        $this->addSql('ALTER TABLE feed_event_course DROP FOREIGN KEY FK_CBC686B0591CC992');
        $this->addSql('ALTER TABLE invite_to_group DROP FOREIGN KEY FK_DC33B47C591CC992');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF591CC992');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484591CC992');
        $this->addSql('ALTER TABLE user_group_course DROP FOREIGN KEY FK_45D4CB6B591CC992');
        $this->addSql('ALTER TABLE invite_to_group DROP FOREIGN KEY FK_DC33B47C39F79D6D');
        $this->addSql('ALTER TABLE event_course DROP FOREIGN KEY FK_240718EB71F7E88B');
        $this->addSql('ALTER TABLE feed_event_course DROP FOREIGN KEY FK_CBC686B051227F2C');
        $this->addSql('ALTER TABLE feed_event_user DROP FOREIGN KEY FK_1D7596FE51227F2C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA4D35E72');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE feed_event DROP FOREIGN KEY FK_CE21591B4B89032C');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52ED823E37A');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB983F4A608');
        $this->addSql('ALTER TABLE course_files DROP FOREIGN KEY FK_EF9E47B1D823E37A');
        $this->addSql('ALTER TABLE course_naviguation DROP FOREIGN KEY FK_35EB1E30D823E37A');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A118D823E37A');
        $this->addSql('ALTER TABLE access DROP FOREIGN KEY FK_6692B54A76ED395');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9B03A8386');
        $this->addSql('ALTER TABLE course_naviguation DROP FOREIGN KEY FK_35EB1E30A76ED395');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA78123EB11');
        $this->addSql('ALTER TABLE feed_event DROP FOREIGN KEY FK_CE21591BB03A8386');
        $this->addSql('ALTER TABLE feed_event_user DROP FOREIGN KEY FK_1D7596FEA76ED395');
        $this->addSql('ALTER TABLE invite_to_group DROP FOREIGN KEY FK_DC33B47CA76ED395');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A118A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC5A121B');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3B03A8386');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484A76ED395');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DB03A8386');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_3AE4BD5A76ED395');
        $this->addSql('ALTER TABLE course_user_group DROP FOREIGN KEY FK_50E0F11E1ED93D47');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E9BDBC9C');
        $this->addSql('ALTER TABLE group_prompt DROP FOREIGN KEY FK_B169B4E8E9BDBC9C');
        $this->addSql('ALTER TABLE invite_to_group DROP FOREIGN KEY FK_DC33B47CE9BDBC9C');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A118E9BDBC9C');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE9BDBC9C');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAE9BDBC9C');
        $this->addSql('ALTER TABLE user_group_user DROP FOREIGN KEY FK_3AE4BD51ED93D47');
        $this->addSql('ALTER TABLE user_group_course DROP FOREIGN KEY FK_45D4CB6B1ED93D47');
        $this->addSql('DROP TABLE access');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_user_group');
        $this->addSql('DROP TABLE course_category');
        $this->addSql('DROP TABLE course_file');
        $this->addSql('DROP TABLE course_files');
        $this->addSql('DROP TABLE course_naviguation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_course');
        $this->addSql('DROP TABLE feed_event');
        $this->addSql('DROP TABLE feed_event_course');
        $this->addSql('DROP TABLE feed_event_user');
        $this->addSql('DROP TABLE group_prompt');
        $this->addSql('DROP TABLE invite_to_group');
        $this->addSql('DROP TABLE links');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE subchapter');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_course');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_group_user');
        $this->addSql('DROP TABLE user_group_course');
    }
}
