UPDATE psl_variable SET value="0.7.1.2" WHERE variable_id='100';

# Add question/answer user recovery - Ian Clysdale, August 20
ALTER TABLE `psl_author` ADD `question` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `psl_author` ADD `answer` VARCHAR(255) DEFAULT NULL;
