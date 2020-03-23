CREATE TABLE user
(
    id           INT AUTO_INCREMENT NOT NULL,
    pipe_uid     INT                NOT NULL,
    telegram_uid INT                NOT NULL,
    username     VARCHAR(130)       NOT NULL,
    first_name   VARCHAR(255)       NOT NULL,
    last_name    VARCHAR(255)       NOT NULL,
    role         INT                NOT NULL,
    description  VARCHAR(255) DEFAULT NULL,
    city_id      INT                NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649B21872D3 (pipe_uid),
    UNIQUE INDEX UNIQ_8D93D6499C52A1C8 (telegram_uid),
    UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
    CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

CREATE TABLE city
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255)       NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

INSERT INTO city (name) VALUES ('Київ');
INSERT INTO city (name) VALUES ('Дніпро');
INSERT INTO city (name) VALUES ('Харьків');
INSERT INTO city (name) VALUES ('Одесса');
