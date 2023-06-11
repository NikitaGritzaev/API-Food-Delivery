DROP DATABASE IF EXISTS food;

CREATE DATABASE food;

USE food;
SET GLOBAL time_zone = 'Asia/Novosibirsk';

CREATE TABLE `user`(
    `id` varchar(36),
    /*uuid NOT NULL? uuid=PK? Эффективно ли? может суррогатный? как генерировать?*/
    `fullName` varchar(60) NOT NULL,
    `email` varchar(30) NOT NULL,
    `gender` enum("Male", "Female") NOT NULL,
    `password` varchar(255) NOT NULL,
    `salt` varchar(255) NOT NULL,
    `birthDate` datetime(3),
    `address` varchar(100),
    `phoneNumber` varchar(11),
    PRIMARY KEY(`id`),
    UNIQUE(`email`),
    CONSTRAINT check_user_id CHECK(
        IS_UUID(`id`)
        AND LENGTH(`id`) = 36
    ),
    CONSTRAINT check_email_user CHECK(`email` LIKE "%_@__%.__%"),
    CONSTRAINT check_fullName_user CHECK(
        `fullName` NOT LIKE "%[^a-zA-Zа-яА-ЯёЁ]%"
        AND LENGTH(`fullName`) > 4
    ),
    CONSTRAINT check_birthDate_user CHECK(`birthDate` > "1900-01-01"),
    CONSTRAINT check_address_user CHECK(LENGTH(`address`) > 5),
    CONSTRAINT check_phoneNumber_user CHECK(
        `phoneNumber` NOT LIKE "%[^0-9]%"
        AND `phoneNumber` LIKE "7%"
        AND LENGTH(`phoneNumber`) = 11
    )
);

CREATE TABLE `dish`(
    `id` varchar(36) NOT NULL,
    `name` varchar(50) NOT NULL,
    `price` float NOT NULL,
    `description` text,
    `image` varchar(255),
    `vegetarian` boolean,
    `category` enum("Wok", "Pizza", "Soup", "Dessert", "Drink"),
    PRIMARY KEY(`id`),
    CONSTRAINT check_dish_id CHECK(
        IS_UUID(`id`)
        AND LENGTH(`id`) = 36
    ),
    CONSTRAINT check_name_dish CHECK(
        `name` NOT LIKE "%[^a-zA-Zа-яА-ЯёЁ0-9]%"
        AND LENGTH(`name`) > 1
    ),
    CONSTRAINT check_price_dish CHECK(
        `price` > 0
        AND `price` <= 10000
    )
);

CREATE TABLE `user_dish`(
    `user` varchar(36) NOT NULL,
    `dish` varchar(36) NOT NULL,
    `rating` int,
    PRIMARY KEY(`user`, `dish`),
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE,
    FOREIGN KEY(`dish`) REFERENCES `dish`(`id`) ON UPDATE CASCADE,
    CONSTRAINT check_rating CHECK(
        `rating` BETWEEN 0
        AND 10
    )
);

CREATE TABLE `order`(
    `id` varchar(36) NOT NULL,
    `user` varchar(36) NOT NULL,
    `deliveryTime` datetime(3) NOT NULL,
    /*когда довести?*/
    `orderTime` datetime(3) NOT NULL,
    /*время, когда заказали????*/
    `status` enum("InProcess", "Delivered") NOT NULL,
    `price` float NOT NULL,
    `address` varchar(100) NOT NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT check_order_id CHECK(
        IS_UUID(`id`)
        AND LENGTH(`id`) = 36
    ),
    CONSTRAINT check_time_order CHECK(
        `orderTime` < `deliveryTime`
        /*orderTime + n???*/
    ),
    CONSTRAINT check_price_order CHECK(
        `price` > 0
        AND `price` <= 1000000
    ),
    CONSTRAINT check_address_order CHECK(LENGTH(`address`) > 5),
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE
);

CREATE TABLE `basket`(
    `id` varchar(36) NOT NULL,
    `dish` varchar(36) NOT NULL,
    `amount` int NOT NULL,
    `user` varchar(36),
    `order` varchar(36),
    PRIMARY KEY(`id`),
    UNIQUE(`user`, `dish`),
    UNIQUE(`order`, `dish`),
    CONSTRAINT check_amount CHECK(
        `amount` > 0
        AND `amount` <= 10000
    ),
    FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE,
    FOREIGN KEY(`dish`) REFERENCES `dish`(`id`) ON UPDATE CASCADE,
    FOREIGN KEY(`order`) REFERENCES `order`(`id`) ON UPDATE CASCADE
);

DELIMITER $$
CREATE FUNCTION get_rating(dish_uuid varchar(36))
RETURNS FLOAT DETERMINISTIC
BEGIN
DECLARE score float;
SELECT AVG(rating) INTO score FROM user_dish WHERE dish=dish_uuid;
RETURN score;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `basket_insert` BEFORE
INSERT
    ON `basket` FOR EACH ROW IF (
        NEW.`order` IS NOT NULL
        OR NEW.`user` IS NULL
    ) THEN SIGNAL SQLSTATE "45000"
SET
    MESSAGE_TEXT = "Incorrect basket insert!";

END IF$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `order_update` BEFORE
UPDATE
    ON `order` FOR EACH ROW IF (NEW.`user` != OLD.`user`) THEN SIGNAL SQLSTATE "45000"
SET
    MESSAGE_TEXT = "Impossible to change order's owner!";

END IF$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `basket_update` BEFORE
UPDATE
    ON `basket` FOR EACH ROW IF (NEW.`user` != OLD.`user`)
    OR (
        NEW.`user` IS NOT NULL
        AND OLD.`user` IS NULL
    )
    OR (
        NEW.`amount` != OLD.`amount`
        AND NEW.`user` IS NULL
    )
    OR (
        NEW.`order` IS NULL
        AND NEW.`user` IS NULL
    )
    OR (
        NEW.`order` IS NULL
        AND OLD.`order` IS NOT NULL
    )
    OR (
        NEW.`order` IS NOT NULL
        AND EXISTS(
            SELECT
                1
            FROM
                `order`
            WHERE
                `user` != NEW.`user`
                AND `id` = NEW.`order`
        )
    ) THEN SIGNAL SQLSTATE "45000"
SET
    MESSAGE_TEXT = "Incorrect basket update!";

END IF$$

DELIMITER ;

/*
 CREATE TABLE `rating`(
 `user` varchar(36) NOT NULL,
 `dish` varchar(36) NOT NULL,
 `rating` int NOT NULL,
 PRIMARY KEY(`user`, `dish`),
 CONSTRAINT check_rating CHECK(
 `rating` BETWEEN 0 AND 10
 ),
 FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE,
 FOREIGN KEY(`dish`) REFERENCES `dish`(`id`) ON UPDATE CASCADE
 );
 
 CREATE TABLE `user_dish`(
 `user` varchar(36) NOT NULL,
 `dish` varchar(36) NOT NULL,
 FOREIGN KEY(`user`) REFERENCES `user`(`id`) ON UPDATE CASCADE,
 FOREIGN KEY(`dish`) REFERENCES `dish`(`id`) ON UPDATE CASCADE
 );
 
 
 
 
 
 DELIMITER $$
 CREATE TRIGGER `basket_insert` BEFORE INSERT ON `basket` 
 FOR EACH ROW
 IF (NEW.`order` IS NOT NULL) AND
 EXISTS(SELECT 1 FROM `order` WHERE `id`=NEW.`order` AND `user`!=NEW.`user`) THEN 
 SIGNAL SQLSTATE "45000"
 SET MESSAGE_TEXT = "Incorrect order!";
 END IF
 $$
 DELIMITER ;
 
 
 
 
 
 DELIMITER $$
 CREATE TRIGGER `order_update` BEFORE UPDATE ON `order` 
 FOR EACH ROW
 IF (NEW.`user` != OLD.`user`) THEN 
 SIGNAL SQLSTATE "45000"
 SET MESSAGE_TEXT = "Impossible to change order's owner!";
 END IF
 $$
 DELIMITER ;
 
 DELIMITER $$
 CREATE TRIGGER `basket_update_01` BEFORE UPDATE ON `basket` 
 FOR EACH ROW
 IF (NEW.`user` != OLD.`user` OR NEW.`dish` != OLD.`dish` OR (OLD.`order` IS NOT NULL AND NEW.`order` != OLD.`order`)) THEN 
 SIGNAL SQLSTATE "45000"
 SET MESSAGE_TEXT = "Impossible to change basket item!";
 END IF
 $$
 DELIMITER ;
 
 DELIMITER $$
 CREATE TRIGGER `basket_update_02` BEFORE UPDATE ON `basket`
 FOR EACH ROW
 IF (OLD.`order` IS NULL AND NEW.`order` IS NOT NULL) AND
 EXISTS(SELECT 1 FROM `order` WHERE `id`=NEW.`order` AND `user`!=NEW.`user`) THEN 
 SIGNAL SQLSTATE "45000"
 SET MESSAGE_TEXT = "Incorrect order!";
 END IF
 $$
 DELIMITER ;
 */