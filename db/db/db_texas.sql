-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 01, 2025 at 08:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_texas`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_order_detalhes`
--

CREATE TABLE `client_order_detalhes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `id_order` int(11) NOT NULL,
  `invoice_id` int(5) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalhe_tipo_dispensa`
--

CREATE TABLE `detalhe_tipo_dispensa` (
  `id` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `valor` decimal(11,2) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `nuit` varchar(20) NOT NULL,
  `contacto` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `nuit`, `contacto`, `address`) VALUES
(1, 'Texas Botle Store', '000000', '847588897', 'Nacala');

-- --------------------------------------------------------

--
-- Table structure for table `meio_payment`
--

CREATE TABLE `meio_payment` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fornecedor`
--

CREATE TABLE `tbl_fornecedor` (
  `id` int(11) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `produto` varchar(20) NOT NULL
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
  `invoice_id` int(5) NOT NULL,
  `mesa` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_iva`
--

CREATE TABLE `tbl_iva` (
  `id` int(11) NOT NULL,
  `iva` varchar(20) NOT NULL,
  `valor` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `cod_mesa` varchar(20) NOT NULL,
  `descricao` text NOT NULL,
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
  `pid` int(5) NOT NULL COMMENT '00F',
  `pname` varchar(200) DEFAULT NULL,
  `pcategory` varchar(200) DEFAULT NULL,
  `purchaseprice` float NOT NULL,
  `saleprice` float NOT NULL,
  `pstock` int(11) DEFAULT NULL,
  `pdescription` varchar(250) NOT NULL,
  `fornecedor` varchar(30) NOT NULL,
  `iva` varchar(10) NOT NULL,
  `codebar` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tipo`
--

CREATE TABLE `tbl_tipo` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `descricao` varchar(100) NOT NULL
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
  `product_id` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_transfer` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin', 'admin'),
(2, 'SIVAERP', 'caixa', 'caixa', 'Caixa');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `pid` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT '00F', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_caixa`
--
ALTER TABLE `tbl_caixa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_control_payment`
--
ALTER TABLE `tbl_control_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_devida`
--
ALTER TABLE `tbl_devida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_entradas`
--
ALTER TABLE `tbl_entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_fornecedor`
--
ALTER TABLE `tbl_fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `pid` int(5) NOT NULL AUTO_INCREMENT COMMENT '00F', AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tlb_cotacao`
--
ALTER TABLE `tlb_cotacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
