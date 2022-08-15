CREATE USER 'task3_alexb'@'%' IDENTIFIED WITH mysql_native_password BY '8IhXvC1w7F3KvgDJ';
GRANT USAGE ON *.* TO 'task3_alexb'@'%';
ALTER USER 'task3_alexb'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
CREATE DATABASE IF NOT EXISTS `task3_alexb`;
GRANT ALL PRIVILEGES ON `task3\_alexb`.* TO 'task3_alexb'@'%';
USE `task3_alexb`;

CREATE TABLE posts
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  content TEXT,
  user_name VARCHAR(50),
  rate FLOAT DEFAULT 0,
  rates_total INT DEFAULT 0,
  created_at DATETIME
);

CREATE TABLE comments
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  post_id INT,
  content TEXT,
  user_name VARCHAR(50),
  created_at DATETIME,
  FOREIGN KEY (post_id)  REFERENCES posts (id)
);

INSERT INTO posts (`id`, `content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
(1, 'Hello, folks. Recently I found a dish called "puding". I recomend you to try it. It\'s tasty and fast to cook.', 'Alex', 4.6336, 11, "2022-08-10 15:38:18");

INSERT INTO comments (`id`, `post_id`, `content`, `user_name`, `created_at`) 
VALUES
(1, 1, 'Thanks for sharing', 'Hanna',  "2022-08-10 17:22:10");

INSERT INTO comments (`id`, `post_id`, `content`, `user_name`, `created_at`) 
VALUES
(2, 1, 'I love with solted caramel', 'Sofia',  "2022-08-10 19:57:02");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Anybody visited sand quarry in Vil\'nogirsk town?', 'Sarrah', 0, 0, "2022-08-12 13:12:43");

INSERT INTO comments (`post_id`, `content`, `user_name`, `created_at`) 
VALUES
(2, 'Me visited :) Higly reccomend this place.', 'Dmitro',  "2022-08-10 19:57:02");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Be — don\'t try to become', 'Osho', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Whenever a person tries to abuse us, or to unload their anger on us, we can each choose to decline or to accept the abuse; whether to make it ours or not. By our personal response to the abuse from another, we can choose who owns and keeps the bad (unsafe) feelings', 'Buddha', 0, 0, "2022-08-12 20:35:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('If you\'re afraid - don\'t do it, - if you\'re doing it - don\'t be afraid!', 'Genghis Khan', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Love thy neighbor as thyself', 'Jesus Christ', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Veni, vidi, vici', 'Gaius Julius Caesar', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Life is a journey between the letter B and D. B stands for birth and D for death. Between B and D is the letter C which stands for choice.', 'Mustansir Meccawala', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Always give without remembering and always receive without forgetting.', 'Brian Tracy', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('It’s easier to take than to give. It’s nobler to give than to take. The thrill of taking lasts a day. The thrill of giving lasts a lifetime.', 'Joan Marques', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('An eye for an eye makes the whole world blind', 'Mohandas Karamchand Gandhi', 0, 0, "2022-08-12 13:12:43");

INSERT INTO posts (`content`, `user_name`, `rate`, `rates_total`, `created_at`) 
VALUES
('Yesterday is history. Tomorrow is a mystery. Today is a gift.', 'Alice Morse Earle', 0, 0, "2022-08-12 13:12:43");