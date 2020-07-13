CREATE DATABASE IF NOT EXISTS todo;

CREATE TABLE IF NOT EXISTS todo.users (
  `id` BIGINT(20) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `uid` VARCHAR(120),
  `name` VARCHAR(120),
  `email` VARCHAR(120),
  `password` VARCHAR(255),
  `status` INT DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp,
  `updated_at` timestamp DEFAULT current_timestamp ON UPDATE current_timestamp
);

CREATE TABLE IF NOT EXISTS todo.posts (
  `id` BIGINT(20) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `uid` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `status` INT DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp,
  `updated_at` timestamp DEFAULT current_timestamp ON UPDATE current_timestamp
);

CREATE TABLE IF NOT EXISTS todo.tags (
  `id` BIGINT(20) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `name` VARCHAR (255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp,
  `updated_at` timestamp DEFAULT current_timestamp ON UPDATE current_timestamp
);

CREATE TABLE IF NOT EXISTS todo.post_tag_relationships (
  `id` BIGINT(20) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `pid` INT NOT NULL,
  `tid` INT NOT NULL,
  `created_at` datetime DEFAULT current_timestamp
);
