
-- For Use this Schema in Phpmyadmin remove All The Comments frome this And Then Imports It In to The phpmy Admin
-- this and pg_management.sql file are used to create the database and tables for the PG Management System. that files is exported from the phpmyadmin.

--User tabel
CREATE TABLE `pg_management`.`users` (`sno` INT NOT NULL AUTO_INCREMENT , `uname` VARCHAR(40) NOT NULL , `uemail` VARCHAR(40) NOT NULL , `upass` VARCHAR(255) NOT NULL , PRIMARY KEY (`sno`)) ENGINE = InnoDB;
ALTER TABLE `users` ADD UNIQUE(`uname`);
ALTER TABLE `users` ADD INDEX(`sno`);

--admin tabel
CREATE TABLE `pg_management`.`admin` (`aid` INT(10) NOT NULL , `aname` VARCHAR(20) NOT NULL , `aemail` VARCHAR(30) NOT NULL , `apass` VARCHAR(255) NOT NULL , PRIMARY KEY (`aid`)) ENGINE = InnoDB;
ALTER TABLE `admin` ADD UNIQUE(`aname`);
ALTER TABLE `admin` ADD INDEX(`aid`);


--listing tabel
CREATE TABLE `pg_management`.`listing` (`lid` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(100) NOT NULL , `price` DECIMAL(20) NOT NULL , `ladd` VARCHAR(50) NOT NULL , `stype` VARCHAR(20) NOT NULL , `feature` VARCHAR(40) NOT NULL , `limage` VARCHAR(255) NOT NULL , `ldesc` VARCHAR(255) NOT NULL , `aid` INT(10) NOT NULL , PRIMARY KEY (`lid`)) ENGINE = InnoDB;
ALTER TABLE `listing` ADD INDEX(`lid`);
ALTER TABLE `listing` ADD CONSTRAINT `aid` FOREIGN KEY (`aid`) REFERENCES `admin`(`aid`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `listing` CHANGE `feature` `wifi` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `listing` ADD `ac` VARCHAR(20) NOT NULL AFTER `wifi`, ADD `food` VARCHAR(20) NOT NULL AFTER `ac`, ADD `parking` VARCHAR(20) NOT NULL AFTER `food`, ADD `gym` VARCHAR(20) NOT NULL AFTER `parking`, ADD `securitry` VARCHAR(20) NOT NULL AFTER `gym`;



--inquire tabel
CREATE TABLE `pg_management`.`inquire` (`iid` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `listing_id` INT NOT NULL , `admin_id` INT NOT NULL , `subject` VARCHAR(255) NOT NULL , `message` VARCHAR(255) NOT NULL , `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `a_respone` TEXT NOT NULL , `status` ENUM('Pending','Cancelled','Resolved') NOT NULL DEFAULT 'Pending', `resolved_at` DATETIME NULL , PRIMARY KEY (`iid`)) ENGINE = InnoDB;
ALTER TABLE `inquire` ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users`(`sno`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `inquire` ADD CONSTRAINT `listing_id` FOREIGN KEY (`listing_id`) REFERENCES `listing`(`lid`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `inquire` ADD CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin`(`aid`) ON DELETE CASCADE ON UPDATE RESTRICT;


--insert of admin data
INSERT INTO `admin` (`aid`, `aname`, `aemail`, `apass`) VALUES ('101', 'mayur', 'mayur09@gmail.com', '$2y$10$K02rWmZ/NloBBSCld3NGjuADYLBfNh7T7730KvSJgiGuaXgidENzm'), ('102', 'atul', 'atul998@gmail.com', '$2y$10$k66DlgC1cEAQZMqvbVHQI.c/sNm5vQAKGQU4p9j1QKwqMHy2vMyhm')

--insert of listings data
INSERT INTO `listing` (`lid`, `title`, `price`, `ladd`, `stype`, `wifi`, `ac`, `food`, `parking`, `gym`, `securitry`, `limage`, `ldesc`, `aid`) VALUES (NULL, 'Widwlare Pgs', '8700', 'Surat Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', '', '', '2e2e2f75706c6f6164732f67656e6572617465642d696d6167652e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '101'), (NULL, 'Guvaliar Pgs', '12000', 'Ahemdabad , Gujarat', 'Double', 'WiFi', 'AC', 'Food', 'Parking', '', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202832292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '101'), (NULL, 'Patel Pgs', '9000', 'Vadorda , Gujarat', 'Single', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d61676520283130292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '101'), (NULL, 'Adani Pgs', '18000', 'Rajkot , Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', '', '', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202839292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '101'), (NULL, 'Gulati Pgs', '25000', 'Gadhinagar , Gujarat', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202836292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '101'), (NULL, 'Spicesnce Pgs', '7000', 'Nasik , Maharashta', 'Single', 'WiFi', '', 'Food', 'Parking', '', '', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202837292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '102'), (NULL, 'Sharmas Pgs', '12000', 'Pune , Maharashta', 'Double', 'WiFi', 'AC', 'Food', 'Parking', '', '', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202838292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '102'), (NULL, 'Welbiers Pgs', '19000', 'Mumbai , Maharastha', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202835292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '102'), (NULL, 'Guatms Pgs', '8000', 'Pune , Maharashta', 'Double', 'WiFi', '', 'Food', 'Parking', '', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202834292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '102'), (NULL, 'The Maharaja Pgs', '30000', 'Mumbai , Maharastha', 'Triple', 'WiFi', 'AC', 'Food', 'Parking', 'Gym', 'Security', '2e2e2f75706c6f6164732f67656e6572617465642d696d616765202833292e706e67', 'Spacious PG Property with Premium Amenities\r\nWelcome to your perfect home away from home! This paying guest accommodation offers an ideal blend of comfort, convenience, and community, making it perfect for students and working professionals.\r\n\r\nProperty H', '102')

--insert of user data
INSERT INTO `users` (`sno`, `uname`, `uemail`, `upass`) VALUES (NULL, 'mayur', 'mayurprjpati03@gmail.com', '$2y$10$ZoCG9H1IPf3YhDgG7lpqJ.hu1h7qZcEj8Gz8Q.iCYeC8Kk2LkzNq.')

