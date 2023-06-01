CREATE TABLE register (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `user_register` (
  `username` varchar(40) NOT NULL,
  `cardID` varchar(40) NOT NULL,
  `email` varchar(30) NOT NULL,
  `earned` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`cardID`)
) ;
