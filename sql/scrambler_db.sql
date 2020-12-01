use scrambler_db

CREATE TABLE `scrambler_db`.`word_sets` ( `word_id` INT NOT NULL AUTO_INCREMENT , `word` VARCHAR(25) NOT NULL , PRIMARY KEY (`word_id`)) ENGINE = InnoDB;

CREATE TABLE `scrambler_db`.`word_sets_meta` ( `word_id` INT NOT NULL , `set_id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(50) NOT NULL , 
`subtitle` VARCHAR(50) NOT NULL , `created_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP DATE NOT NULL, `type` VARCHAR(15) NOT NULL , 
PRIMARY KEY (`set_id`, 'word_id')) ENGINE = InnoDB COMMENT = 'holds the metadata for each word in the word_sets table';