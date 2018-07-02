-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2018 at 06:06 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment02`
--

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` int(10) NOT NULL,
  `phoneNumber` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `phoneNumber`, `password`, `firstname`, `lastname`, `dob`) VALUES
(1, '1231231234', 'e99a18c428cb38d5f260853678922e03', 'Thiago', 'Santos', 'MAY-01-1983');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `priority` int(1) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `firstname`, `lastname`, `title`, `comment`, `priority`, `filename`, `time`) VALUES
(1, 'Gary', 'Tong', 'Cras nisl ligula', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac aliquet metus, id congue tortor. Pellentesque blandit ex in bibendum cursus. Phasellus dapibus augue nisl, feugiat scelerisque nisi finibus non. Curabitur hendrerit nisi nec urna sollicitudin, eu ultricies ipsum maximus. Ut ac nibh sit amet turpis malesuada dapibus eget a turpis. Sed gravida ultrices tortor at consequat. Aenean molestie tincidunt orci, dignissim luctus libero ullamcorper et. Pellentesque blandit odio vitae ultricies varius. Aenean laoreet quam lectus, eget convallis sapien dictum sed. Duis nec sodales leo, et sollicitudin arcu. Donec varius risus in ex efficitur, id interdum lorem bibendum. Pellentesque sed eros rhoncus, facilisis urna eget, lobortis nibh.', 3, 'nyc.jpg', '1481808630'),
(2, 'President', 'Obama', 'Another Justo', 'Duis ut commodo libero. Etiam luctus vestibulum mauris, in scelerisque erat tincidunt sed. Proin elit massa, rutrum ut lacus a, congue mattis turpis. Nunc dui lorem, lobortis sit amet ullamcorper ut, volutpat non metus. Morbi tristique ex eget interdum convallis. Proin et venenatis arcu. Phasellus vitae efficitur neque. Nam leo enim, efficitur in ipsum ut, facilisis egestas urna. In arcu lorem, eleifend vel tortor ac, eleifend fringilla leo.', 1, 'nyc.jpg   ', '1481808630'),
(3, 'John', 'Doe', 'Etiam dolor ipsum', 'Nunc malesuada sapien et tincidunt sagittis. Nunc luctus purus augue, sed efficitur enim vulputate quis. ', 1, ' nyc.jpg', '1481808630'),
(4, 'Jane', 'Doe', 'A title', 'Suspendisse id eleifend mi. Nulla mi justo, consequat sed est a, sollicitudin mattis nibh. Suspendisse sodales aliquam lectus a ullamcorper. ', 1, NULL, '1481808630'),
(5, 'Doe', NULL, 'A title', 'Nunc dignissim erat ac aliquet condimentum. Cras nisl ligula, viverra et massa non, vulputate dapibus velit.', 1, 'nyc.jpg', '1481808630'),
(6, 'Miller', NULL, 'ipsum', 'Cras nec lectus risus. Etiam felis lectus, hendrerit nec est a, vehicula finibus mauris. Phasellus rutrum nibh sit amet tempor consectetur. Nullam mattis, mauris ac iaculis sagittis, felis eros finibus nibh, non feugiat mauris nibh in diam. In euismod massa quam, vel tristique nisi bibendum vel.', 1, 'nyc.jpg', NULL),
(7, 'Donald', 'Trump', 'A bad person', 'In commodo odio id ipsum lobortis luctus. Duis vitae metus et lacus imperdiet iaculis non ultrices erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ultricies risus nec sagittis rhoncus. Mauris porta mauris quis magna placerat, ac dapibus arcu consequat. Sed sit amet turpis convallis tortor euismod luctus sit amet eu lorem. Sed at tellus ac arcu mattis molestie. Ut finibus justo et ligula porta elementum. Sed faucibus nisi quis orci cursus, congue iaculis arcu gravida. Nullam sit amet condimentum dolor, ut rhoncus nulla. Nullam eget varius augue, quis viverra felis. Donec varius dolor ut nibh mattis facilisis. Aenean pulvinar, mi ut vulputate sodales, felis ipsum blandit libero, eget tempus velit neque et tortor. Proin laoreet ipsum eu nisl aliquet pulvinar.', 1, 'nyc.jpg', '1481808630'),
(8, 'Harry', 'Potter', 'Wizard', 'Sed sit amet turpis convallis tortor euismod luctus sit amet eu lorem. Sed at tellus ac arcu mattis molestie. Ut finibus justo et ligula porta el Donec varius dolor ut nibh mattis facilisis. Aenean pulvinar, mi ut vulputate sodales, felis ipsum blandit libero, eget tempus velit neque et tortor. Proin laoreet ipsum eu nisl aliquet pulvinar.', 2, 'nyc.jpg', '1481808630'),
(9, 'Jane', 'Smith', 'Simple', 'Just a simple sentence.', 2, '7e23dbd93ba9f0a206f029cd4adc1bfd.jpg', '1488830741'),
(10, 'Thiago', 'Santos', 'Brave', 'If I said I will fix it, I will fix it. There is no need to nag me every 6 months about it.', 1, 'a0483079a9a085ddb3bd43233d87c655.jpg', '1530504216');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
