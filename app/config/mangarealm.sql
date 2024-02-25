-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Fev-2024 às 19:48
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mangarealm`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `capitulo`
--

CREATE TABLE `capitulo` (
  `id_capitulo` int(11) NOT NULL,
  `id_manga` int(11) DEFAULT NULL,
  `content` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios_capitulo`
--

CREATE TABLE `comentarios_capitulo` (
  `id_comments` int(11) NOT NULL,
  `id_capitulo` int(11) DEFAULT NULL,
  `comments_capitulo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios_manga`
--

CREATE TABLE `comentarios_manga` (
  `id_comments` int(11) NOT NULL,
  `id_manga` int(11) DEFAULT NULL,
  `comments_manga` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `favorito`
--

CREATE TABLE `favorito` (
  `id_user` int(11) DEFAULT NULL,
  `id_manga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `manga`
--

CREATE TABLE `manga` (
  `id_manga` int(11) NOT NULL,
  `autor` int(11) DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `quantidade_capitulo` int(4) DEFAULT NULL,
  `img` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `img` longtext DEFAULT NULL,
  `dark_mode` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `capitulo`
--
ALTER TABLE `capitulo`
  ADD PRIMARY KEY (`id_capitulo`),
  ADD KEY `id_manga` (`id_manga`);

--
-- Índices para tabela `comentarios_capitulo`
--
ALTER TABLE `comentarios_capitulo`
  ADD PRIMARY KEY (`id_comments`),
  ADD KEY `id_capitulo` (`id_capitulo`);

--
-- Índices para tabela `comentarios_manga`
--
ALTER TABLE `comentarios_manga`
  ADD PRIMARY KEY (`id_comments`),
  ADD KEY `id_manga` (`id_manga`);

--
-- Índices para tabela `favorito`
--
ALTER TABLE `favorito`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_manga` (`id_manga`);

--
-- Índices para tabela `manga`
--
ALTER TABLE `manga`
  ADD PRIMARY KEY (`id_manga`),
  ADD KEY `autor` (`autor`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `capitulo`
--
ALTER TABLE `capitulo`
  ADD CONSTRAINT `capitulo_ibfk_1` FOREIGN KEY (`id_manga`) REFERENCES `manga` (`id_manga`);

--
-- Limitadores para a tabela `comentarios_capitulo`
--
ALTER TABLE `comentarios_capitulo`
  ADD CONSTRAINT `comentarios_capitulo_ibfk_1` FOREIGN KEY (`id_capitulo`) REFERENCES `capitulo` (`id_capitulo`);

--
-- Limitadores para a tabela `comentarios_manga`
--
ALTER TABLE `comentarios_manga`
  ADD CONSTRAINT `comentarios_manga_ibfk_1` FOREIGN KEY (`id_manga`) REFERENCES `manga` (`id_manga`);

--
-- Limitadores para a tabela `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `favorito_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id_user`),
  ADD CONSTRAINT `favorito_ibfk_2` FOREIGN KEY (`id_manga`) REFERENCES `manga` (`id_manga`);

--
-- Limitadores para a tabela `manga`
--
ALTER TABLE `manga`
  ADD CONSTRAINT `manga_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
