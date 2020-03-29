DROP TABLE IF EXISTS city;
CREATE TABLE city
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255)       NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

DROP TABLE IF EXISTS district;
CREATE TABLE district
(
    id      INT(11)      NOT NULL AUTO_INCREMENT,
    city_id INT(11)      NOT NULL,
    name    VARCHAR(160) NOT NULL,
    INDEX IDX_31C154878BAC62AF (city_id),
    CONSTRAINT FK_31C154878BAC62AF FOREIGN KEY (city_id) REFERENCES city (id),
    PRIMARY KEY (id)
)
    ENGINE = INNODB,
    CHARACTER SET utf8mb4,
    COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS user;
CREATE TABLE user
(
    id          INT AUTO_INCREMENT NOT NULL,
    pipe_uid    INT                NOT NULL,
    role        INT                NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    city_id     INT                NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649B21872D3 (pipe_uid),
    CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

DROP TABLE IF EXISTS route;
CREATE TABLE route
(
    id               INT(11)      NOT NULL AUTO_INCREMENT,
    from_district    INT(11)      NOT NULL,
    to_district      INT(11)      NOT NULL,
    city_id          INT(11)      NOT NULL,
    user_id          INT(11)      NOT NULL,
    from_comment     VARCHAR(255) NOT NULL,
    to_comment       VARCHAR(255) NOT NULL,
    time             VARCHAR(255) NOT NULL,
    date             VARCHAR(255) NOT NULL,
    passengers_count INT(11)      NOT NULL,
    INDEX IDX_2C42079772EB41D (from_district),
    INDEX IDX_2C420798BAC62AF (city_id),
    INDEX IDX_2C42079A76ED395 (user_id),
    INDEX IDX_2C42079FC98CA29 (to_district),
    CONSTRAINT FK_2C42079772EB41D FOREIGN KEY (from_district) REFERENCES district (id),
    CONSTRAINT FK_2C420798BAC62AF FOREIGN KEY (city_id) REFERENCES city (id),
    CONSTRAINT FK_2C42079A76ED395 FOREIGN KEY (user_id) REFERENCES user (id),
    CONSTRAINT FK_2C42079FC98CA29 FOREIGN KEY (to_district) REFERENCES district (id),
    PRIMARY KEY (id)
)
    ENGINE = INNODB,
    CHARACTER SET utf8mb4,
    COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS auth_token;
CREATE TABLE auth_token
(
    id       INT(11)      NOT NULL AUTO_INCREMENT,
    uuid     VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles    TEXT         NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX UNIQ_B6A2DD68D17F50A6 (uuid)
)
    ENGINE = INNODB,
    CHARACTER SET utf8mb4,
    COLLATE utf8mb4_unicode_ci;

########################################################

INSERT INTO auth_token (id, uuid, password, roles)
VALUES (1, 'X-AUTH-TOKEN', '#AUTH_TOKEN#', '{}');

########################################################

INSERT INTO city (id, name)
VALUES (1, 'Київ');
INSERT INTO city (id, name)
VALUES (2, 'Дніпро');
INSERT INTO city (id, name)
VALUES (3, 'Харьків');
INSERT INTO city (id, name)
VALUES (4, 'Одесса');

#Kiev
INSERT INTO district (id, city_id, name)
VALUES (101, 1, 'Голосіївський');
INSERT INTO district (id, city_id, name)
VALUES (102, 1, 'Святошинський');
INSERT INTO district (id, city_id, name)
VALUES (103, 1, 'Солом''янський');
INSERT INTO district (id, city_id, name)
VALUES (104, 1, 'Оболонський');
INSERT INTO district (id, city_id, name)
VALUES (105, 1, 'Подільський');
INSERT INTO district (id, city_id, name)
VALUES (106, 1, 'Печерський');
INSERT INTO district (id, city_id, name)
VALUES (107, 1, 'Шевченківський');
INSERT INTO district (id, city_id, name)
VALUES (108, 1, 'Дарницький');
INSERT INTO district (id, city_id, name)
VALUES (109, 1, 'Дніпровський');
INSERT INTO district (id, city_id, name)
VALUES (110, 1, 'Деснянський');

#Dnipro
INSERT INTO district (id, city_id, name)
VALUES (201, 2, 'Амур-Нижньодніпровський');
INSERT INTO district (id, city_id, name)
VALUES (202, 2, 'Шевченківський');
INSERT INTO district (id, city_id, name)
VALUES (203, 2, 'Соборний');
INSERT INTO district (id, city_id, name)
VALUES (204, 2, 'Індустріальний');
INSERT INTO district (id, city_id, name)
VALUES (205, 2, 'Центральний');
INSERT INTO district (id, city_id, name)
VALUES (206, 2, 'Чечелівський');
INSERT INTO district (id, city_id, name)
VALUES (207, 2, 'Новокодацький');
INSERT INTO district (id, city_id, name)
VALUES (208, 2, 'Самарський');

#Kharkiv
INSERT INTO district (id, city_id, name)
VALUES (301, 3, 'Шевченківський');
INSERT INTO district (id, city_id, name)
VALUES (302, 3, 'Київський');
INSERT INTO district (id, city_id, name)
VALUES (303, 3, 'Слобідської');
INSERT INTO district (id, city_id, name)
VALUES (304, 3, 'Холодногорский');
INSERT INTO district (id, city_id, name)
VALUES (305, 3, 'Московський');
INSERT INTO district (id, city_id, name)
VALUES (306, 3, 'Новобаварський');
INSERT INTO district (id, city_id, name)
VALUES (307, 3, 'Індустріальний');
INSERT INTO district (id, city_id, name)
VALUES (308, 3, 'Немишлянська');
INSERT INTO district (id, city_id, name)
VALUES (309, 3, 'Основ''янський');

#Odessa
INSERT INTO district (id, city_id, name)
VALUES (401, 4, 'Суворовський');
INSERT INTO district (id, city_id, name)
VALUES (402, 4, 'Приморський');
INSERT INTO district (id, city_id, name)
VALUES (403, 4, 'Маліновський');
INSERT INTO district (id, city_id, name)
VALUES (404, 4, 'Киевский');
