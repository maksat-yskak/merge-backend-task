-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Авг 19 2022 г., 15:17
-- Версия сервера: 10.4.24-MariaDB
-- Версия PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mergy-backend-task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `cv` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `experience` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `job`, `cv`, `user_image`, `experience`) VALUES
('ahd123@d', 'username', 'username@gmail.com', 'usejob', 'http://www.google.com/asda.docx', 'http://www.google.com/asd.jpeg', '[{\"job_title\":\"Dev\",\"location\":\"KZ\",\"start_date\":\"11\\/11\\/2011\",\"end_date\":\"11\\/11\\/2011\"},{\"job_title\":\"DevOps\",\"location\":\"KZ\",\"start_date\":\"12\\/12\\/2012\",\"end_date\":\"12\\/12\\/2012\"}]'),
('ahd123@zod', 'username', 'username@gmail.com', 'usejob', 'http://www.google.com/asda.docx', 'http://www.google.com/asd.jpeg', '[{\"job_title\":\"Dev\",\"location\":\"KZ\",\"start_date\":\"11\\/11\\/2011\",\"end_date\":\"11\\/11\\/2011\"},{\"job_title\":\"DevOps\",\"location\":\"KZ\",\"start_date\":\"12\\/12\\/2012\",\"end_date\":\"12\\/12\\/2012\"}]');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
