# Change the primary key of the CONTACT table to "id" to be consistent with the new norm.
ALTER TABLE `contact` CHANGE `contact_no` `id` INT(10) NOT NULL AUTO_INCREMENT;