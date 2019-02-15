
CREATE TABLE IF NOT EXISTS `my_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(60) NOT NULL,
  `transaction_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `transaction_currency` varchar(4) NOT NULL,
  `transaction_amount` decimal(9,2) NOT NULL,
  `transaction_method` varchar(40) NOT NULL,
  `transaction_state` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
)AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `my_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_price` decimal(19,2) NOT NULL,
  `image_name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code` (`product_code`)
) AUTO_INCREMENT=4 ;

--
-- Dumping data for table `my_products`
--

INSERT INTO `my_products` (`id`, `product_code`, `product_name`, `product_price`, `image_name`) VALUES
(1, 'ABC100', 'Star mini S5', 150.00, 'cell_phone1.jpg'),
(2, 'XBD200', 'Star mini S6', 160.00, 'cell_phone2.jpg'),
(3, 'DDE120', 'Mpie MP707', 300.00, 'cell_phone3.jpg');

