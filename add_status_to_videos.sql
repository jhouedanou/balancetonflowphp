-- Ajouter la colonne status à la table videos si elle n'existe pas déjà
ALTER TABLE `videos` ADD COLUMN IF NOT EXISTS `status` VARCHAR(255) DEFAULT 'draft' AFTER `url`;

-- Mettre à jour les vidéos existantes en fonction de la date de publication
UPDATE `videos` SET `status` = CASE WHEN `publish_date` IS NOT NULL THEN 'published' ELSE 'draft' END;

-- Mettre à jour la table migrations pour enregistrer cette migration
INSERT INTO `migrations` (`migration`, `batch`) 
SELECT '2025_04_14_084500_add_status_column_to_videos_table', (SELECT MAX(`batch`) + 1 FROM `migrations`)
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2025_04_14_084500_add_status_column_to_videos_table');
