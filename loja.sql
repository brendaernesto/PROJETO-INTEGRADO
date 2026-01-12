-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 02:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loja`
--

-- --------------------------------------------------------

--
-- Table structure for table `novidades`
--

CREATE TABLE `novidades` (
  `codigo` int(11) NOT NULL,
  `itens` varchar(50) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `novidades`
--

INSERT INTO `novidades` (`codigo`, `itens`, `quantidade`) VALUES
(1, 'Mousse Kids Verão', 2),
(2, 'Creme de Cabelo Kids Verão', 2),
(3, 'Shampoo Kids Verão', 1),
(4, 'Condicionador Kids Verão', 1),
(5, 'Máscara Kids Verão ', 3),
(123, 'teste', 2);

-- --------------------------------------------------------

--
-- Table structure for table `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT 0.00,
  `quantidade` int(11) DEFAULT 0,
  `imagem` varchar(255) DEFAULT 'produto_default.png',
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `novidade` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produto`
--

INSERT INTO `produto` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `imagem`, `data_criacao`, `data_atualizacao`, `novidade`) VALUES
(1, 'Shampoo Hidratante', 'Shampoo para cabelos secos e danificados', 25.90, 15, 'produtoamarelo_redondo.png', '2025-07-02 21:11:34', '2025-07-02 21:11:34', 0),
(2, 'Condicionador Nutritivo', 'Condicionador para nutrição profunda', 28.50, 12, 'produtoinfantil_redondo.png', '2025-07-02 21:11:34', '2025-07-02 21:11:34', 0),
(3, 'Máscara Capilar', 'Máscara de tratamento intensivo', 45.00, 8, 'produtoverde_redondo.png', '2025-07-02 21:11:34', '2025-07-02 21:11:34', 0),
(4, 'Óleo Capilar', 'Óleo para finalização e brilho', 35.90, 20, 'belezahair_round.png', '2025-07-02 21:11:34', '2025-07-02 21:11:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quantidade` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `novidade` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `quantidade`, `imagem`, `novidade`) VALUES
(1, 'Kit para Cabelos Coloridos', NULL, 0.00, 100, 'produtoinfantil_redondo.png', 0),
(2, 'Kit Anticaspa', NULL, 0.00, 200, 'produtoverde_redondo.png', 0),
(3, 'Kits para Cabelos Danificados', NULL, 0.00, 100, 'produtoamarelo_redondo.png', 0),
(5, 'Kits para Cabelos Oleosos', NULL, 0.00, 250, 'belezahair_round.png', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `novidades`
--
ALTER TABLE `novidades`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
