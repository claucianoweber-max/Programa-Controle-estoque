-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 25/05/2026 às 19:18
-- Versão do servidor: 8.4.7
-- Versão do PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `eestoque`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `created_at`) VALUES
(1, 'Eletrônicos', '2026-05-23 13:31:33'),
(2, 'Alimentos', '2026-05-23 13:31:33'),
(3, 'Peças', '2026-05-23 13:31:33'),
(4, 'Frutas', '2026-05-23 13:31:33'),
(5, 'Escolar', '2026-05-23 13:31:33'),
(6, 'Hardware e Componentes', '2026-05-23 13:31:33'),
(7, 'Periféricos de Informática', '2026-05-23 13:31:33'),
(8, 'Materiais de Escritório', '2026-05-23 13:31:33'),
(9, 'Equipamentos de Proteção (EPI)', '2026-05-23 13:31:33'),
(10, 'Iluminação e Elétrica', '2026-05-23 13:31:33'),
(11, 'Ferramentas Manuais', '2026-05-23 13:31:33'),
(12, 'Limpeza e Conservação', '2026-05-23 13:31:33'),
(13, 'Papelaria e Suprimentos', '2026-05-23 13:31:33'),
(14, 'Móveis Corporativos', '2026-05-23 13:31:33'),
(15, 'Conectividade e Redes', '2026-05-23 13:31:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contato` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `nome`, `contato`) VALUES
(1, 'Fornecedor A', NULL),
(2, 'Fornecedor B', NULL),
(3, 'Fornecedor C', '54999996666'),
(4, 'Móveis', '54999999999'),
(5, 'Casa Porto 2', '54999999999'),
(6, 'Distribuidora ferro', '5433321855'),
(7, 'TechLogística Brasil Ltda', 'contato@techlogistica.com.br'),
(8, 'Distribuidora Global Mix', '(11) 98877-6655'),
(9, 'Soluções em Embalagens Norte', 'vendas@solucoesnorte.com'),
(10, 'Impacto Suprimentos de Escritório', 'comercial@impactosup.com.br'),
(11, 'WF Conexões e Tubos', '(21) 3344-5566'),
(12, 'Mega Atacado Alvorada', 'atendimento@megaalvorada.com.br'),
(13, 'Prime Peças Hidráulicas', 'suporte@primepecas.com'),
(14, 'Fênix Elétrica e Iluminação', '(31) 97766-4433'),
(15, 'Vale Transportes & Cargas', 'logistica@valetransportes.com'),
(16, 'Nexus Importados Especializados', 'contato@nexusimport.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes`
--

DROP TABLE IF EXISTS `movimentacoes`;
CREATE TABLE IF NOT EXISTS `movimentacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produto_id` int DEFAULT NULL,
  `tipo` enum('entrada','saida') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `data` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuarioid` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produto_id` (`produto_id`),
  KEY `fk_movimentacoes_usuarios` (`usuarioid`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `movimentacoes`
--

INSERT INTO `movimentacoes` (`id`, `produto_id`, `tipo`, `quantidade`, `data`, `usuarioid`) VALUES
(1, 1, 'entrada', 50, '2026-03-27 00:00:00', 1),
(2, 2, 'saida', 25, '2026-03-26 00:00:00', 1),
(3, 3, 'entrada', 12, '2026-03-20 00:00:00', 1),
(4, 9, 'saida', 10, '2026-03-27 23:00:58', 3),
(5, 2, 'saida', 45, '2026-03-27 23:02:03', 3),
(6, 6, 'saida', 5, '2026-03-27 23:04:18', 3),
(7, 3, 'saida', 25, '2026-03-27 23:05:34', 3),
(8, 9, 'entrada', 2, '2026-03-27 23:07:47', 4),
(9, 4, 'saida', 2, '2026-03-27 23:08:11', 4),
(10, 1, 'saida', 1, '2026-03-27 23:10:42', 4),
(11, 4, 'saida', 1, '2026-03-27 23:10:57', 4),
(12, 6, 'saida', 1, '2026-03-27 23:11:15', 4),
(13, 10, 'entrada', 3, '2026-03-27 23:30:04', 5),
(14, 2, 'entrada', 1, '2026-03-27 23:30:17', 5),
(15, 14, 'entrada', 5, '2026-03-27 23:30:31', 5),
(16, 111, 'saida', 20, '2026-03-27 23:49:14', 5),
(17, 63, 'saida', 100, '2026-03-29 16:57:07', 2),
(18, 63, 'saida', 45, '2026-03-29 16:58:14', 2),
(19, 114, 'saida', 135, '2026-03-29 17:21:51', 2),
(20, 114, 'entrada', 5, '2026-03-29 17:22:51', 2),
(21, 63, 'entrada', 3, '2026-03-29 18:26:30', 1),
(22, 55, 'entrada', 3, '2026-03-29 18:26:51', 1),
(23, 11, 'entrada', 1, '2026-03-29 18:27:16', 1),
(24, 9, 'entrada', 1, '2026-03-29 18:27:34', 1),
(25, 63, 'entrada', 1, '2026-03-29 18:27:48', 1),
(26, 20, 'entrada', 1, '2026-03-29 18:28:13', 1),
(27, 7, 'entrada', 1, '2026-03-29 18:28:36', 1),
(28, 114, 'saida', 1, '2026-03-31 00:14:49', 1),
(29, 104, 'entrada', 1, '2026-03-31 00:19:59', 1),
(30, 114, 'entrada', 1, '2026-03-31 00:20:15', 1),
(31, 114, 'saida', 5, '2026-03-31 00:20:40', 1),
(32, 114, 'entrada', 15, '2026-04-26 15:31:32', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `quantidade` int DEFAULT '0',
  `preco` decimal(10,2) DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `fornecedor_id` (`fornecedor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `categoria_id`, `fornecedor_id`, `quantidade`, `preco`, `descricao`, `created_at`) VALUES
(1, 'Notebook Dell Inspiron', 1, 1, 9, 3500.00, NULL, '2026-05-23 12:49:23'),
(2, 'Mouse Logitech', 2, 2, 6, 120.00, NULL, '2026-05-23 12:49:23'),
(3, 'Teclado Mecânico Redragon', 2, 3, 5, 250.00, NULL, '2026-05-23 12:49:23'),
(4, 'Monitor LG 24\"', 1, 1, 12, 900.00, NULL, '2026-05-23 12:49:23'),
(5, 'Impressora HP Deskjet', 3, 4, 8, 600.00, NULL, '2026-05-23 12:49:23'),
(6, 'Headset Gamer HyperX', 2, 5, 14, 320.00, NULL, '2026-05-23 12:49:23'),
(7, 'SSD Kingston 480GB', 1, 3, 6, 280.00, NULL, '2026-05-23 12:49:23'),
(8, 'HD Externo Seagate 1TB', 1, 2, 12, 400.00, NULL, '2026-05-23 12:49:23'),
(9, 'Webcam Logitech HD', 2, 2, 5, 220.00, NULL, '2026-05-23 12:49:23'),
(10, 'Cadeira Gamer', 3, 1, 6, 1300.00, NULL, '2026-05-23 12:49:23'),
(11, 'NOVO PRODUTO', 1, 1, 6, 25.00, NULL, '2026-05-23 12:49:23'),
(13, 'tomate', 4, 1, 4, 10.00, NULL, '2026-05-23 12:49:23'),
(14, 'web cam', 1, 1, 6, 10.00, NULL, '2026-05-23 12:49:23'),
(15, 'Mouse Sem Fio Optico', 2, 5, 150, 45.90, NULL, '2026-05-23 12:49:23'),
(16, 'Teclado Mecanico RGB', 2, 1, 45, 289.00, NULL, '2026-05-23 12:49:23'),
(17, 'Monitor 24 Polegadas Full HD', 2, 10, 12, 899.00, NULL, '2026-05-23 12:49:23'),
(18, 'Cadeira de Escritorio Ergonomica', 9, 4, 8, 1250.00, NULL, '2026-05-23 12:49:23'),
(19, 'SSD 480GB SATA III', 1, 1, 85, 210.00, NULL, '2026-05-23 12:49:23'),
(20, 'Placa de Video RTX 3060', 1, 15, 6, 2450.00, NULL, '2026-05-23 12:49:23'),
(21, 'Processador Intel i5 12th Gen', 1, 15, 14, 1100.00, NULL, '2026-05-23 12:49:23'),
(22, 'Memoria RAM 8GB DDR4', 1, 1, 120, 185.00, NULL, '2026-05-23 12:49:23'),
(23, 'Roteador Wi-Fi 6 Dual Band', 10, 9, 30, 450.00, NULL, '2026-05-23 12:49:23'),
(24, 'Cabo HDMI 2.0 3 Metros', 10, 2, 250, 35.00, NULL, '2026-05-23 12:49:23'),
(25, 'Webcam Full HD 1080p', 2, 10, 40, 199.00, NULL, '2026-05-23 12:49:23'),
(26, 'Headset Gamer com Microfone', 2, 1, 65, 159.00, NULL, '2026-05-23 12:49:23'),
(27, 'Notebook 15.6 polegadas 8GB', 2, 15, 10, 3200.00, NULL, '2026-05-23 12:49:23'),
(28, 'Impressora Multifuncional Jato', 8, 2, 15, 750.00, NULL, '2026-05-23 12:49:23'),
(29, 'Cartucho de Tinta Preto XL', 8, 2, 200, 89.00, NULL, '2026-05-23 12:49:23'),
(30, 'Resma de Papel A4 500fls', 8, 6, 300, 28.50, NULL, '2026-05-23 12:49:23'),
(31, 'Grampeador de Metal Grande', 3, 4, 110, 42.00, NULL, '2026-05-23 12:49:23'),
(32, 'Caixa de Canetas Azuis 50un', 3, 6, 90, 35.00, NULL, '2026-05-23 12:49:23'),
(33, 'Pasta Suspensa Kraft 20un', 3, 6, 150, 55.00, NULL, '2026-05-23 12:49:23'),
(34, 'Calculadora Cientifica', 3, 10, 40, 85.00, NULL, '2026-05-23 12:49:23'),
(35, 'Furadeira de Impacto 500W', 6, 7, 25, 245.00, NULL, '2026-05-23 12:49:23'),
(36, 'Jogo de Chaves de Fenda 10un', 6, 7, 60, 115.00, NULL, '2026-05-23 12:49:23'),
(37, 'Martelo de Unha 20mm', 6, 7, 80, 45.00, NULL, '2026-05-23 12:49:23'),
(38, 'Alicate Universal Isolado', 6, 7, 120, 38.00, NULL, '2026-05-23 12:49:23'),
(39, 'Trena Metrica 5 Metros', 6, 7, 180, 19.90, NULL, '2026-05-23 12:49:23'),
(40, 'Lanterna LED Recarregavel', 5, 8, 55, 65.00, NULL, '2026-05-23 12:49:23'),
(41, 'Lâmpada LED 9W Branca 10un', 5, 8, 200, 75.00, NULL, '2026-05-23 12:49:23'),
(42, 'Multimetro Digital Portatil', 5, 10, 35, 125.00, NULL, '2026-05-23 12:49:23'),
(43, 'Fita Isolante Preta 20m', 5, 8, 280, 8.50, NULL, '2026-05-23 12:49:23'),
(44, 'Extensao Eletrica 5 Metros', 5, 8, 95, 45.00, NULL, '2026-05-23 12:49:23'),
(45, 'Capacete de Seguranca Branco', 4, 3, 50, 32.00, NULL, '2026-05-23 12:49:23'),
(46, 'Luva de Vaqueta Cano Curto', 4, 3, 150, 24.00, NULL, '2026-05-23 12:49:23'),
(47, 'Oculos de Protecao Incolor', 4, 3, 220, 12.50, NULL, '2026-05-23 12:49:23'),
(48, 'Protetor Auricular Plug', 4, 3, 300, 3.50, NULL, '2026-05-23 12:49:23'),
(49, 'Bota de Seguranca com Bico', 4, 3, 40, 110.00, NULL, '2026-05-23 12:49:23'),
(50, 'Detergente Multiuso 5L', 7, 6, 60, 25.00, NULL, '2026-05-23 12:49:23'),
(51, 'Vassoura de Nylon com Cabo', 7, 6, 100, 18.00, NULL, '2026-05-23 12:49:23'),
(52, 'Saco de Lixo 100L 20un', 7, 6, 250, 22.00, NULL, '2026-05-23 12:49:23'),
(53, 'Papel Toalha Interfolha', 7, 6, 180, 15.50, NULL, '2026-05-23 12:49:23'),
(54, 'Dispenser de Sabonete Liquido', 7, 4, 35, 85.00, NULL, '2026-05-23 12:49:23'),
(55, 'Mesa de Reuniao 2 Metros', 9, 4, 6, 1850.00, NULL, '2026-05-23 12:49:23'),
(56, 'Armario de Aco 2 Portas', 9, 4, 7, 950.00, NULL, '2026-05-23 12:49:23'),
(57, 'Gaveteiro com Rodizios', 9, 4, 20, 450.00, NULL, '2026-05-23 12:49:23'),
(58, 'Suporte para Monitor Articulado', 9, 2, 45, 185.00, NULL, '2026-05-23 12:49:23'),
(59, 'Quadro Branco 120x90cm', 9, 6, 12, 220.00, NULL, '2026-05-23 12:49:23'),
(60, 'HD Externo 1TB USB 3.0', 10, 1, 28, 380.00, NULL, '2026-05-23 12:49:23'),
(61, 'Switch 8 Portas Gigabit', 10, 9, 40, 210.00, NULL, '2026-05-23 12:49:23'),
(62, 'Cabo de Rede Cat5e 305m', 10, 9, 10, 450.00, NULL, '2026-05-23 12:49:23'),
(63, 'Adaptador Bluetooth USB', 10, 10, 9, 35.00, NULL, '2026-05-23 12:49:23'),
(64, 'Placa de Som USB Externa', 10, 10, 60, 45.00, NULL, '2026-05-23 12:49:23'),
(65, 'Estabilizador 1000VA', 5, 8, 15, 350.00, NULL, '2026-05-23 12:49:23'),
(66, 'Nobreak 1200VA Mono', 5, 8, 8, 980.00, NULL, '2026-05-23 12:49:23'),
(67, 'Caixa de Som 2.1 Bluetooth', 2, 1, 35, 280.00, NULL, '2026-05-23 12:49:23'),
(68, 'Microfone Condensador USB', 2, 10, 20, 350.00, NULL, '2026-05-23 12:49:23'),
(69, 'Tablet 10 Polegadas 64GB', 2, 15, 12, 1450.00, NULL, '2026-05-23 12:49:23'),
(70, 'Smartphone de Entrada 128GB', 2, 15, 18, 1100.00, NULL, '2026-05-23 12:49:23'),
(71, 'Carregador Portatil 10000mAh', 10, 15, 85, 125.00, NULL, '2026-05-23 12:49:23'),
(72, 'Hub USB-C 7 em 1', 10, 10, 50, 195.00, NULL, '2026-05-23 12:49:23'),
(73, 'Mochila para Notebook 15.6', 3, 4, 45, 185.00, NULL, '2026-05-23 12:49:23'),
(74, 'Organizador de Cabos Velcro', 3, 4, 200, 12.00, NULL, '2026-05-23 12:49:23'),
(75, 'Fragmentadora de Papel', 3, 2, 8, 450.00, NULL, '2026-05-23 12:49:23'),
(76, 'Clips de Papel n2 100un', 3, 6, 300, 4.50, NULL, '2026-05-23 12:49:23'),
(77, 'Fita Adesiva Larga Transp.', 3, 6, 150, 7.50, NULL, '2026-05-23 12:49:23'),
(78, 'Envelopes Oficio 100un', 3, 6, 120, 35.00, NULL, '2026-05-23 12:49:23'),
(79, 'Bloco Autoadesivo 76x76mm', 3, 6, 250, 5.50, NULL, '2026-05-23 12:49:23'),
(80, 'Tesoura de Escritorio 20cm', 3, 4, 100, 15.00, NULL, '2026-05-23 12:49:23'),
(81, 'Regua de Aco 30cm', 6, 7, 90, 12.50, NULL, '2026-05-23 12:49:23'),
(82, 'Nivel de Bolha Magnetico', 6, 7, 40, 55.00, NULL, '2026-05-23 12:49:23'),
(83, 'Serrote para Madeira 18p', 6, 7, 30, 65.00, NULL, '2026-05-23 12:49:23'),
(84, 'Chave Inglesa 10 Polegadas', 6, 7, 45, 75.00, NULL, '2026-05-23 12:49:23'),
(85, 'Alicate de Corte Diagonal', 6, 7, 65, 42.00, NULL, '2026-05-23 12:49:23'),
(86, 'Parafusadeira Sem Fio 12V', 6, 7, 15, 380.00, NULL, '2026-05-23 12:49:23'),
(87, 'Brocas para Concreto 5un', 6, 7, 100, 45.00, NULL, '2026-05-23 12:49:23'),
(88, 'Pincel Atomico Preto', 8, 6, 200, 6.50, NULL, '2026-05-23 12:49:23'),
(89, 'Quadro de Cortisa 60x40cm', 9, 6, 25, 45.00, NULL, '2026-05-23 12:49:23'),
(90, 'Lixeira de Escritorio 10L', 7, 4, 80, 35.00, NULL, '2026-05-23 12:49:23'),
(91, 'Desinfetante Eucalipto 5L', 7, 6, 50, 22.00, NULL, '2026-05-23 12:49:23'),
(92, 'Luva Latex Multiuso M', 7, 6, 150, 8.50, NULL, '2026-05-23 12:49:23'),
(93, 'Escada de Aluminio 5 Degraus', 6, 7, 10, 290.00, NULL, '2026-05-23 12:49:23'),
(94, 'Caixa Termica 32 Litros', 7, 2, 15, 145.00, NULL, '2026-05-23 12:49:23'),
(95, 'Suporte para Celular Mesa', 10, 10, 120, 25.00, NULL, '2026-05-23 12:49:23'),
(96, 'Mouse Pad Speed Grande', 2, 1, 95, 55.00, NULL, '2026-05-23 12:49:23'),
(97, 'Filtro de Linha 6 Tomadas', 5, 8, 110, 38.00, NULL, '2026-05-23 12:49:23'),
(98, 'Disjuntor Termomagnetico 20A', 5, 8, 180, 22.00, NULL, '2026-05-23 12:49:23'),
(99, 'Caneta Laser para Apresentacao', 3, 10, 30, 65.00, NULL, '2026-05-23 12:49:23'),
(100, 'Pilhas Alcalinas AA 4un', 10, 2, 280, 18.00, NULL, '2026-05-23 12:49:23'),
(101, 'Baterias de Litio CR2032 5un', 10, 2, 150, 25.00, NULL, '2026-05-23 12:49:23'),
(102, 'Pasta Catalogo 50 Plasticos', 3, 6, 70, 42.00, NULL, '2026-05-23 12:49:23'),
(103, 'Grampo para Grampeador 26/6', 3, 6, 200, 9.50, NULL, '2026-05-23 12:49:23'),
(104, 'Perfurador de Papel 2 Furos', 3, 4, 56, 38.00, NULL, '2026-05-23 12:49:23'),
(105, 'Estilete Profissional 18mm', 6, 7, 130, 14.00, NULL, '2026-05-23 12:49:23'),
(106, 'Lamina para Estilete 10un', 6, 7, 100, 18.00, NULL, '2026-05-23 12:49:23'),
(107, 'Cooler para Processador RGB', 1, 1, 35, 125.00, NULL, '2026-05-23 12:49:23'),
(108, 'Pasta Termica de Prata 5g', 1, 1, 90, 45.00, NULL, '2026-05-23 12:49:23'),
(109, 'Fonte ATX 600W 80 Plus', 1, 15, 20, 450.00, NULL, '2026-05-23 12:49:23'),
(110, 'Gabinete Gamer Lateral Vidro', 1, 15, 12, 380.00, NULL, '2026-05-23 12:49:23'),
(111, 'Cabo de Forca Tripolar 1.5m', 5, 8, 180, 15.00, NULL, '2026-05-23 12:49:23'),
(112, 'Adaptador Wi-Fi USB 600Mbps', 10, 9, 75, 85.00, NULL, '2026-05-23 12:49:23'),
(113, 'Dock Station para HD SATA', 10, 10, 15, 220.00, NULL, '2026-05-23 12:49:23'),
(114, 'Pendrive 64GB USB 3.0', 10, 1, 20, 55.00, NULL, '2026-05-23 12:49:23'),
(115, 'livre', 5, 1, 1, 10.00, NULL, '2026-05-23 12:49:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeusuario` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_attempts` int DEFAULT '0',
  `lock_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fkusuarioid` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nomeusuario`, `email`, `senha`, `failed_attempts`, `lock_until`) VALUES
(1, 'Meu nome', 'usuario@exemplo.com', '$2y$10$BDW1p27UFojpVYK0jrY4seceP73fnc1gM44ozHMltLB1VoFixgMSi', 0, NULL),
(2, 'usuario1', 'usuario1@exemplo.com', '$2y$10$/DJh7Njl6OZzMiJ6RC6jken2FXhahRarLVfDFmyweGaH0N.vBdNiK', 0, NULL),
(3, 'usuario2', 'usuario2@exemplo.com', '$2y$10$BoddOdzfHgW1cts/7F8fLu2zP.EoG5/IpjTvtBAzD1oDsbgqL7KW2', 0, NULL),
(4, 'usuario3', 'usuario3@exemplo.com', '$2y$10$tntNkxk82pA.eP8/nOB5UuORwxpwIvIBmBgPwEZ8fpZnGZBP7ODhu', 1, NULL),
(5, 'usuario4', 'usuario4@exemplo.com', '$2y$10$mNOBBzCK.UtJW644P6NmlevJhoYTURLO12ANfyw8HRoU02TyRJlqS', 2, NULL),
(6, 'admin.central', 'admin@sistema.com.br', '$2y$10$98YXX5hOlUn5ShbvayFg0u8BtfkqzSVQBFlZCOVweFtuYnrO0yy0i', 0, NULL),
(7, 'roberto.estoque', 'roberto.silva@empresa.com', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(8, 'ana.vendas', 'ana.paula@provedor.net', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(9, 'marcos.gerente', 'marcos.souza@estoquemix.com', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(10, 'luciana.alfa', 'luciana.ti@tecnologia.com.br', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(11, 'felipe.log', 'felipe.logistica@empresa.com', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(12, 'suporte.tecnico', 'suporte@sistema.com.br', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(13, 'carla.compras', 'carla.compras@estoquemix.com', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(14, 'diego.inv', 'diego.inventario@empresa.com', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(15, 'visitante.demo', 'demo@sistema.com.br', '$2y$10$8K0M7A8pX7E/GvF7Y0uGv.XmZlR1f6WfH6G.8U9/2fH6G.8U9/2f.', 0, NULL),
(16, 'Admin', 'admin@email.com', '$2y$10$Xl0XhMvheFXoqg2PEYR.neSR6KTuV68N1UwvNnbcgilFTo06MFeJe', 0, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
