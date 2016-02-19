#Clinical Research Tracker

This repo contains the front-end and back-end source code for the Clinical Research Tracker. Made by UCL Comp Sci students in 2016. 

## Note about `apollo-app`

To decrease the amount of files transfered to the repository the Composer folder `vendor` is ignored by Git, hence the app will not run out of the box. Make sure to run `composer install` before trying to run the app.

Additionally, make sure that the `fileinfo` PHP module is enabled. Usually this just means uncommenting `extension=fileinfo.so` or `extension=php_fileinfo.dll` in your `php.ini`.

## Some notes about setting up the app for the first time

After cloning the repository and running `composer install` in the directory where `composer.json` is located, you will have to setup the MySQL database. This is the most recent version of the database: (just paste the whole thing into the text area in the SQL section)

	-- phpMyAdmin SQL Dump
	-- version 4.5.1
	-- http://www.phpmyadmin.net
	--
	-- Host: 127.0.0.1
	-- Generation Time: Feb 19, 2016 at 04:14 AM
	-- Server version: 10.1.9-MariaDB
	-- PHP Version: 5.6.15

	SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
	SET time_zone = "+00:00";


	/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
	/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
	/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
	/*!40101 SET NAMES utf8mb4 */;

	--
	-- Database: `apollo`
	--

	-- --------------------------------------------------------

	--
	-- Table structure for table `organisations`
	--

	CREATE TABLE `organisations` (
	  `id` int(11) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `timezone` varchar(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

	-- --------------------------------------------------------

	--
	-- Table structure for table `users`
	--

	CREATE TABLE `users` (
	  `id` int(11) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `password` varchar(255) NOT NULL,
	  `org_id` int(11) NOT NULL,
	  `is_admin` tinyint(1) NOT NULL,
	  `registered_on` datetime NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

	--
	-- Indexes for dumped tables
	--

	--
	-- Indexes for table `organisations`
	--
	ALTER TABLE `organisations`
	  ADD PRIMARY KEY (`id`),
	  ADD KEY `id` (`id`);

	--
	-- Indexes for table `users`
	--
	ALTER TABLE `users`
	  ADD PRIMARY KEY (`id`);

	--
	-- AUTO_INCREMENT for dumped tables
	--

	--
	-- AUTO_INCREMENT for table `organisations`
	--
	ALTER TABLE `organisations`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
	--
	-- AUTO_INCREMENT for table `users`
	--
	ALTER TABLE `users`
	  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
	/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
	/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
	/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

To sign in you will have to insert user details into the `users` table. The only field that is not self-explanatory is the `password` field. It should contain the hash of your password generated using PHP's `hash_password()` function. So, to get one of said hashes create a new PHP file somewhere (or use an online interpreter) and execute the following command:

	echo hash_password('your-password-here');

The output will be your desired hash, just set the `password` field in your record in the `users` table to the hash you got and you should be able to login using your password (`your-password-here` in my case).

Additionally, you might have to do some Doctrine setup, but it will take forever to explain. Try googling `Doctrine getting started`.