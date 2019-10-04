DELETE FROM `Module` WHERE `Keyword` = 'notice';
SET foreign_key_checks = 0;
DROP TABLE `Notice_Cron`, `Notice_History`, `Notice_Message`, `Notice_Rule`;
DELETE FROM `Settings` WHERE `Module` = 'notice';