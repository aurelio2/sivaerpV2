-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2025 at 06:25 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oziva`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Nui` varchar(50) NOT NULL,
  `contacto` varchar(12) NOT NULL,
  `endereco` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client_order_detalhes`
--

CREATE TABLE `client_order_detalhes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `id_order` int(11) NOT NULL,
  `invoice_id` int(5) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `client_order_detalhes`
--

INSERT INTO `client_order_detalhes` (`id`, `nome`, `id_order`, `invoice_id`) VALUES
(5539, 'Silvestre', 1, 05119),
(5540, 'Norte Firmino', 1, 05120),
(5541, 'Aurelio', 1, 05121),
(5542, '2', 2, 05122),
(5543, 'nade', 1, 05123),
(5544, '55', 55, 05124),
(5545, '', 1, 05125),
(5546, '55', 55, 05126),
(5547, 'Leonel', 1, 05127),
(5548, '', 1, 05128),
(5549, 'TesteCliente', 1, 05129),
(5550, 'Teste', 1, 05130),
(5551, 'Nelson', 1, 05131),
(5552, '55', 55, 00000);

-- --------------------------------------------------------

--
-- Table structure for table `detalhe_tipo_dispensa`
--

CREATE TABLE `detalhe_tipo_dispensa` (
  `id` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `descricao` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `valor` decimal(11,2) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `nuit` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `contacto` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `nuit`, `contacto`, `address`) VALUES
(1, 'OZIVA PUP ', '100158957', '878181020/856534466', 'Quelimane');

-- --------------------------------------------------------

--
-- Table structure for table `meio_payment`
--

CREATE TABLE `meio_payment` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `meio_payment`
--

INSERT INTO `meio_payment` (`id`, `descricao`) VALUES
(1, 'M-pesa'),
(2, 'E-mola'),
(3, 'Conta movel'),
(4, 'BIM'),
(5, 'BCI'),
(6, 'Dinheiro');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_armazem`
--

CREATE TABLE `tbl_armazem` (
  `pid` int(5) UNSIGNED ZEROFILL NOT NULL COMMENT '00F',
  `pname` varchar(200) DEFAULT NULL,
  `pcategory` varchar(200) DEFAULT NULL,
  `purchaseprice` float NOT NULL,
  `saleprice` float NOT NULL,
  `pstock` int(11) DEFAULT NULL,
  `fornecedor` varchar(30) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_armazem`
--

INSERT INTO `tbl_armazem` (`pid`, `pname`, `pcategory`, `purchaseprice`, `saleprice`, `pstock`, `fornecedor`, `status`) VALUES
(00486, 'Manica 600ml', 'Bar 1', 60, 70, 18, 'Handling 1', 0),
(00487, 'Cerveja', 'Bar 1', 50, 60, 6, 'HANDLING', 0),
(00488, 'lite curta 1', 'Bar 1', 40, 55, 52, 'Handling 1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_caixa`
--

CREATE TABLE `tbl_caixa` (
  `id` int(11) NOT NULL,
  `valor_inicial` decimal(11,2) NOT NULL,
  `valor_final` decimal(11,2) NOT NULL,
  `id_user` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `hora_final` time NOT NULL,
  `data_final` date NOT NULL,
  `estado` int(11) NOT NULL,
  `closed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_caixa`
--

INSERT INTO `tbl_caixa` (`id`, `valor_inicial`, `valor_final`, `id_user`, `data`, `hora`, `hora_final`, `data_final`, `estado`, `closed_by`) VALUES
(287, '10.00', '70.00', 45, '2025-01-19', '18:03:13', '18:03:13', '2025-01-20', 2, 45),
(288, '100.00', '370.00', 45, '2025-01-20', '15:12:37', '15:12:37', '2025-01-22', 2, 45),
(289, '100.00', '715.00', 45, '2025-01-22', '10:47:20', '10:47:20', '2025-01-23', 2, 45),
(290, '0.00', '170.00', 45, '2025-01-28', '11:59:11', '11:59:11', '2025-01-28', 2, 45),
(291, '10.00', '170.00', 45, '2025-01-28', '21:22:13', '21:22:13', '2025-01-28', 2, 45),
(292, '111.00', '170.00', 45, '2025-01-28', '21:47:03', '00:00:00', '2025-01-28', 1, 45),
(293, '11111.00', '170.00', 45, '2025-01-28', '21:50:22', '22:01:51', '2025-01-28', 2, 45),
(294, '10.00', '225.00', 45, '2025-02-03', '10:59:32', '21:41:48', '2025-02-09', 2, 45),
(295, '10.00', '55.00', 45, '2025-02-09', '21:42:01', '15:48:36', '2025-02-11', 2, 45),
(296, '102.00', '0.00', 45, '2025-02-11', '15:48:44', '00:00:00', '0000-00-00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catid`, `category`) VALUES
(58, 'CUZINHA'),
(59, 'BAR'),
(63, 'A4'),
(64, 'Bar 1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_control_payment`
--

CREATE TABLE `tbl_control_payment` (
  `id` int(11) NOT NULL,
  `id_invoice` int(5) UNSIGNED ZEROFILL NOT NULL,
  `mpesa` decimal(11,2) NOT NULL,
  `emola` decimal(11,2) NOT NULL,
  `cash` decimal(11,2) NOT NULL,
  `pos` decimal(11,2) NOT NULL,
  `valor_recebido` int(11) NOT NULL,
  `troco` int(11) NOT NULL,
  `data` date NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_control_payment`
--

INSERT INTO `tbl_control_payment` (`id`, `id_invoice`, `mpesa`, `emola`, `cash`, `pos`, `valor_recebido`, `troco`, `data`, `user`) VALUES
(5172, 05118, '0.00', '0.00', '0.00', '0.00', 100, 30, '2025-01-07', 49),
(5173, 05120, '0.00', '0.00', '0.00', '0.00', 500, 130, '2025-01-20', 45),
(5174, 05121, '0.00', '0.00', '0.00', '0.00', 200, 50, '2025-01-22', 45),
(5175, 05122, '0.00', '0.00', '0.00', '0.00', 60, 0, '2025-01-22', 45),
(5176, 05123, '0.00', '0.00', '0.00', '0.00', 500, 50, '2025-01-22', 45),
(5177, 05124, '0.00', '0.00', '0.00', '0.00', 100, 45, '2025-01-23', 45),
(5178, 05126, '0.00', '0.00', '0.00', '0.00', 115, 0, '2025-01-28', 45),
(5179, 05127, '0.00', '0.00', '0.00', '0.00', 100, 45, '2025-02-03', 45),
(5180, 05128, '0.00', '70.00', '100.00', '0.00', 0, 0, '2025-02-03', 45),
(5181, 05129, '0.00', '0.00', '0.00', '0.00', 100, 45, '2025-02-09', 45),
(5182, 05131, '0.00', '0.00', '0.00', '0.00', 200, 35, '2025-02-11', 45);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_devida`
--

CREATE TABLE `tbl_devida` (
  `id` int(11) NOT NULL,
  `id_invoice` int(5) UNSIGNED ZEROFILL NOT NULL,
  `celular` int(9) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `estado` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_devida`
--

INSERT INTO `tbl_devida` (`id`, `id_invoice`, `celular`, `valor`, `data`, `estado`, `user`) VALUES
(5013, 05118, 0, '0', '2025-01-07', 1, 49),
(5014, 05119, 841101674, '70', '2025-01-19', 0, 45),
(5015, 05120, 0, '0', '2025-01-20', 1, 45),
(5016, 05121, 0, '0', '2025-01-22', 1, 45),
(5017, 05122, 0, '0', '2025-01-22', 1, 45),
(5018, 05123, 0, '0', '2025-01-22', 1, 45),
(5019, 05124, 0, '0', '2025-01-23', 1, 45),
(5020, 05125, 844334559, '55', '2025-01-28', 0, 45),
(5021, 05126, 0, '0', '2025-01-28', 1, 45),
(5022, 05127, 0, '0', '2025-02-03', 1, 45),
(5023, 05128, 0, '0', '2025-02-03', 1, 45),
(5024, 05129, 0, '0', '2025-02-09', 1, 45),
(5025, 05130, 841101674, '110', '2025-02-11', 0, 45),
(5026, 05131, 0, '0', '2025-02-11', 1, 45);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_entradas`
--

CREATE TABLE `tbl_entradas` (
  `id` int(11) NOT NULL,
  `idproduto` int(5) UNSIGNED ZEROFILL NOT NULL,
  `quantidade` int(11) NOT NULL,
  `qtd_anterior` int(11) NOT NULL,
  `produto` varchar(200) NOT NULL,
  `data` date NOT NULL,
  `ajust` varchar(250) NOT NULL,
  `data_ajust` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fornecedor`
--

CREATE TABLE `tbl_fornecedor` (
  `id` int(11) NOT NULL,
  `marca` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `produto` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_fornecedor`
--

INSERT INTO `tbl_fornecedor` (`id`, `marca`, `produto`) VALUES
(14, 'HANDLING', ''),
(17, 'NATHOOBAY', ''),
(18, 'NUMBER ONE', ''),
(19, 'Nº1', 'Nº1'),
(20, 'Handling 1', 'cervejas');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `invoice_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `mesa` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`invoice_id`, `mesa`, `customer_name`, `order_date`, `subtotal`, `total`, `user`) VALUES
(05119, '01', 'DORIVAL BERTINHO', '2025-01-19', '70.00', '70.00', 45),
(05120, '01', 'DORIVAL BERTINHO', '2025-01-20', '370.00', '370.00', 45),
(05121, '01', 'DORIVAL BERTINHO', '2025-01-22', '150.00', '150.00', 45),
(05122, '02', 'DORIVAL BERTINHO', '2025-01-22', '60.00', '60.00', 45),
(05123, '01', 'DORIVAL BERTINHO', '2025-01-22', '450.00', '450.00', 45),
(05124, '55', 'DORIVAL BERTINHO', '2025-01-23', '55.00', '55.00', 45),
(05125, '01', 'DORIVAL BERTINHO', '2025-01-28', '55.00', '55.00', 45),
(05126, '55', 'DORIVAL BERTINHO', '2025-01-28', '115.00', '115.00', 45),
(05127, '01', 'DORIVAL BERTINHO', '2025-02-03', '55.00', '55.00', 45),
(05128, '01', 'DORIVAL BERTINHO', '2025-02-03', '170.00', '170.00', 45),
(05129, '01', 'DORIVAL BERTINHO', '2025-02-09', '55.00', '55.00', 45),
(05130, '01', 'DORIVAL BERTINHO', '2025-02-11', '110.00', '110.00', 45),
(05131, '01', 'DORIVAL BERTINHO', '2025-02-11', '165.00', '165.00', 45);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_details`
--

CREATE TABLE `tbl_invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` double NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `t_iva` decimal(11,2) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice_details`
--

INSERT INTO `tbl_invoice_details` (`id`, `invoice_id`, `product_id`, `product_name`, `qty`, `price`, `total`, `t_iva`, `order_date`) VALUES
(41880, 5119, 00486, 'Manica 600ml', 1, 70, '70.00', '0.00', '2025-01-19'),
(41881, 5120, 00486, 'Manica 600ml', 1, 70, '70.00', '0.00', '2025-01-20'),
(41882, 5120, 00485, 'Arroz ', 2, 150, '300.00', '0.00', '2025-01-20'),
(41883, 5121, 00485, 'Arroz ', 1, 150, '150.00', '0.00', '2025-01-22'),
(41884, 5122, 00487, 'Cerveja', 1, 60, '60.00', '0.00', '2025-01-22'),
(41885, 5123, 00488, 'lite curta', 6, 55, '330.00', '0.00', '2025-01-22'),
(41886, 5123, 00487, 'Cerveja', 2, 60, '120.00', '0.00', '2025-01-22'),
(41887, 5124, 00488, 'lite curta', 1, 55, '55.00', '0.00', '2025-01-23'),
(41888, 5125, 00488, 'lite curta', 1, 55, '55.00', '0.00', '2025-01-28'),
(41889, 5126, 00487, 'Cerveja', 1, 60, '60.00', '0.00', '2025-01-28'),
(41890, 5126, 00488, 'lite curta', 1, 55, '55.00', '0.00', '2025-01-28'),
(41891, 5127, 00488, 'lite curta', 1, 55, '55.00', '0.00', '2025-02-03'),
(41892, 5128, 00487, 'Cerveja', 1, 60, '60.00', '0.00', '2025-02-03'),
(41893, 5128, 00488, 'lite curta', 2, 55, '110.00', '0.00', '2025-02-03'),
(41894, 5129, 00488, 'lite curta', 1, 55, '55.00', '0.00', '2025-02-09'),
(41895, 5130, 00488, 'lite curta', 2, 55, '110.00', '0.00', '2025-02-11'),
(41896, 5131, 00488, 'lite curta', 3, 55, '165.00', '0.00', '2025-02-11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_iva`
--

CREATE TABLE `tbl_iva` (
  `id` int(11) NOT NULL,
  `iva` varchar(20) NOT NULL,
  `valor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_iva`
--

INSERT INTO `tbl_iva` (`id`, `iva`, `valor`) VALUES
(1, 'Iva', '0.17'),
(2, 'Sem iva', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mesa`
--

CREATE TABLE `tbl_mesa` (
  `id` int(11) NOT NULL,
  `cod_mesa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-livre 1-Ocupante 2-Reservado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_mesa`
--

INSERT INTO `tbl_mesa` (`id`, `cod_mesa`, `descricao`, `status`) VALUES
(25, '01', 'Pedido 01', 0),
(26, '02', 'Pedido 02', 0),
(27, '03', 'Pedido 03', 0),
(28, '04', 'Pedido 04', 0),
(29, '05', 'Pedido 05', 0),
(30, '06', 'Pedido 06', 0),
(31, '07', 'Pedido 07', 0),
(32, '08', 'Pedido 08', 0),
(33, '09', 'Pedido 09', 0),
(34, '10', 'Pedido 10', 0),
(35, '11', 'Pedido 11', 0),
(36, '12', 'Pedido 12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `pid` int(5) UNSIGNED ZEROFILL NOT NULL COMMENT '00F',
  `pname` varchar(200) DEFAULT NULL,
  `pcategory` varchar(200) DEFAULT NULL,
  `purchaseprice` float NOT NULL,
  `saleprice` float NOT NULL,
  `pstock` int(11) DEFAULT NULL,
  `pdescription` varchar(250) NOT NULL,
  `fornecedor` varchar(30) NOT NULL,
  `iva` varchar(10) NOT NULL,
  `codebar` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`pid`, `pname`, `pcategory`, `purchaseprice`, `saleprice`, `pstock`, `pdescription`, `fornecedor`, `iva`, `codebar`) VALUES
(00485, 'Arroz ', 'BAR', 100, 150, 0, '', '', '', ''),
(00486, 'Manica 600ml', 'Bar 1', 60, 70, 0, '', '', '', ''),
(00487, 'Cerveja', 'Bar 1', 50, 60, 1, '', '', '', ''),
(00488, 'lite curta', 'Bar 1', 40, 55, 32, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quebra_stock`
--

CREATE TABLE `tbl_quebra_stock` (
  `id` int(11) NOT NULL,
  `id_produto` int(5) UNSIGNED ZEROFILL NOT NULL,
  `stock_anterior` int(11) NOT NULL,
  `stock_quebra` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_quebra_stock`
--

INSERT INTO `tbl_quebra_stock` (`id`, `id_produto`, `stock_anterior`, `stock_quebra`, `id_user`, `data`) VALUES
(1, 00485, 3, 1, 45, '2025-01-20'),
(2, 00485, 2, 1, 45, '2025-01-21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tipo`
--

CREATE TABLE `tbl_tipo` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_tipo`
--

INSERT INTO `tbl_tipo` (`id`, `tipo`) VALUES
(2, 'Capsola'),
(3, 'Ampola');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tipo_dispensa`
--

CREATE TABLE `tbl_tipo_dispensa` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_tipo_dispensa`
--

INSERT INTO `tbl_tipo_dispensa` (`id`, `descricao`) VALUES
(1, 'Energia'),
(2, 'Produto'),
(3, 'Outro');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transfer_history`
--

CREATE TABLE `tbl_transfer_history` (
  `id` int(11) NOT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_transfer` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_transfer_history`
--

INSERT INTO `tbl_transfer_history` (`id`, `product_id`, `quantity`, `date_transfer`) VALUES
(1, '00486', 1, '2025-01-08'),
(2, '00486', 1, '2025-01-08'),
(3, '00485', 4, '2025-01-08'),
(4, '00487', 6, '2025-01-22'),
(5, '00488', 50, '2025-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `useremail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `password`, `role`) VALUES
(40, 'admin', 'admin', 'admin', 'admin'),
(45, 'DORIVAL BERTINHO', 'caixa', 'caixa', 'Caixa'),
(46, 'ASSANE DAUD', 'ASSANE', '10042024', 'Caixa'),
(47, 'Leonel Walusa', 'leonel', '12345', 'admin'),
(48, 'Juliao', 'Walussa', '13456', 'Caixa'),
(49, 'Gomes', 'Gomes', '12345', 'Caixa');

-- --------------------------------------------------------

--
-- Table structure for table `tlb_cotacao`
--

CREATE TABLE `tlb_cotacao` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `preco` decimal(11,2) NOT NULL,
  `qtd` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_order_detalhes`
--
ALTER TABLE `client_order_detalhes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detalhe_tipo_dispensa`
--
ALTER TABLE `detalhe_tipo_dispensa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meio_payment`
--
ALTER TABLE `meio_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_armazem`
--
ALTER TABLE `tbl_armazem`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_caixa`
--
ALTER TABLE `tbl_caixa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_control_payment`
--
ALTER TABLE `tbl_control_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_devida`
--
ALTER TABLE `tbl_devida`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_entradas`
--
ALTER TABLE `tbl_entradas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_fornecedor`
--
ALTER TABLE `tbl_fornecedor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_iva`
--
ALTER TABLE `tbl_iva`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mesa`
--
ALTER TABLE `tbl_mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_quebra_stock`
--
ALTER TABLE `tbl_quebra_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tipo`
--
ALTER TABLE `tbl_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tipo_dispensa`
--
ALTER TABLE `tbl_tipo_dispensa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_transfer_history`
--
ALTER TABLE `tbl_transfer_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `tlb_cotacao`
--
ALTER TABLE `tlb_cotacao`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_order_detalhes`
--
ALTER TABLE `client_order_detalhes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5553;

--
-- AUTO_INCREMENT for table `detalhe_tipo_dispensa`
--
ALTER TABLE `detalhe_tipo_dispensa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meio_payment`
--
ALTER TABLE `meio_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_armazem`
--
ALTER TABLE `tbl_armazem`
  MODIFY `pid` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT '00F', AUTO_INCREMENT=489;

--
-- AUTO_INCREMENT for table `tbl_caixa`
--
ALTER TABLE `tbl_caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_control_payment`
--
ALTER TABLE `tbl_control_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5183;

--
-- AUTO_INCREMENT for table `tbl_devida`
--
ALTER TABLE `tbl_devida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5027;

--
-- AUTO_INCREMENT for table `tbl_entradas`
--
ALTER TABLE `tbl_entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1517;

--
-- AUTO_INCREMENT for table `tbl_fornecedor`
--
ALTER TABLE `tbl_fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5132;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41897;

--
-- AUTO_INCREMENT for table `tbl_iva`
--
ALTER TABLE `tbl_iva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_mesa`
--
ALTER TABLE `tbl_mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `pid` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT '00F', AUTO_INCREMENT=489;

--
-- AUTO_INCREMENT for table `tbl_quebra_stock`
--
ALTER TABLE `tbl_quebra_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_tipo`
--
ALTER TABLE `tbl_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_tipo_dispensa`
--
ALTER TABLE `tbl_tipo_dispensa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_transfer_history`
--
ALTER TABLE `tbl_transfer_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tlb_cotacao`
--
ALTER TABLE `tlb_cotacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
