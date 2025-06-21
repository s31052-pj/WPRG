-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 22, 2025 at 12:20 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_name` varchar(50) DEFAULT 'Gość',
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `guest_name`, `content`, `created_at`) VALUES
(9, 2, NULL, '', 'Sztos!', '2025-06-20 20:57:48'),
(10, 2, 4, NULL, 'Fajne!', '2025-06-20 21:11:02'),
(11, 3, 5, NULL, 'JD', '2025-06-21 22:19:09'),
(12, 4, 5, NULL, 'FIRE', '2025-06-21 22:19:15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `contact_info`, `message`, `created_at`) VALUES
(1, 'Luke', '123456789', 'asd', '2025-06-21 22:07:24');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `created_at`) VALUES
(19, NULL, 'Zarejestrowano użytkownika: root', '2025-06-20 21:10:33'),
(20, NULL, 'Zarejestrowano użytkownika: root', '2025-06-20 21:10:51'),
(21, 4, 'Zalogowano użytkownika: root', '2025-06-20 21:10:56'),
(22, 4, 'Wylogowano użytkownika: root', '2025-06-20 21:11:06'),
(23, 1, 'Zalogowano użytkownika: admin', '2025-06-20 21:11:12'),
(24, NULL, 'Wysłano wiadomość kontaktową', '2025-06-21 22:07:24'),
(25, 1, 'Zalogowano użytkownika: admin', '2025-06-21 22:07:44'),
(26, 1, 'Dodano wpis: (⌐■_■)', '2025-06-21 22:09:41'),
(27, 1, 'Wylogowano użytkownika: admin', '2025-06-21 22:10:44'),
(28, 5, 'Zalogowano użytkownika: user', '2025-06-21 22:10:49'),
(29, 5, 'Dodano wpis: 2077', '2025-06-21 22:11:42'),
(30, 5, 'Wylogowano użytkownika: user', '2025-06-21 22:18:45'),
(31, 5, 'Zalogowano użytkownika: user', '2025-06-21 22:18:50');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(200) DEFAULT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image_path`, `published_at`, `author_id`) VALUES
(2, 'Vader vs Padawan', 'Vader vs Padawan', 'uploads/DVvsAT.jpg', '2025-06-20 19:33:40', 1),
(3, '(⌐■_■)', '.', 'uploads/john-wick-1.jpg', '2025-06-21 22:09:41', 1),
(4, '2077', 'Silverhand', 'uploads/Cyberpunk.jpg', '2025-06-21 22:11:42', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','author','user') NOT NULL DEFAULT 'user',
  `reset_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `reset_token`, `created_at`) VALUES
(1, 'admin', '$2y$10$e.9YFR8XwqTJdlT545yf6eM3qPb3Zjv5XKpHEvA.roYD6MBhNf.6a', 'admin', NULL, '2025-06-20 19:00:19'),
(4, 'root', '$2y$10$/yUqrd1hGnAUfWxTnbM67.XglGXQgnyju/blQGl6z6EM08o7MT63y', 'user', NULL, '2025-06-20 21:10:51'),
(5, 'user', '$2y$10$GqIUmEaBW97SYaR1LawTPOHSQAMDLIHv2HvQmIE0QJG9L.WmkLEZm', 'user', NULL, '2025-06-21 22:07:02');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
