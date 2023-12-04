-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2023 at 10:30 AM
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
-- Database: `valcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(10) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `icon_url` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `role_id` varchar(100) NOT NULL,
  `ability1` text NOT NULL,
  `ability2` text NOT NULL,
  `ability3` text NOT NULL,
  `ability4` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `agent_name`, `icon_url`, `bio`, `role_id`, `ability1`, `ability2`, `ability3`, `ability4`) VALUES
(6, 'Gekko', '', 'Gekko the Angeleno leads a tight-knit crew of calamitous creatures. His buddies bound forward, scattering enemies out of the way, with Gekko chasing them down to regroup and go again.', '1', 'Wingman', 'Dizzy', 'Mosh Pit', 'Thrash'),
(7, 'Fade', '', 'Turkish bounty hunter Fade unleashes the power of raw nightmare to seize enemy secrets. Attuned with terror itself, she hunts down targets and reveals their deepest fears - before crushing them in the dark.', '1', 'Seize', 'Haunt', 'Prowler', 'Nightfall'),
(8, 'Breach', '', 'The bionic Swede Breach fires powerful, targeted kinetic blasts to aggressively clear a path through enemy ground. The damage and disruption he inflicts ensures no fight is ever fair.', '1', 'Flashpoint', 'Fault Line', 'Aftershock', 'Rolling Thunder'),
(9, 'Deadlock', '', 'Norwegian operative Deadlock deploys an array of cutting-edge nanowire to secure the battlefield from even the most lethal assault. No one escapes her vigilant watch, nor survives her unyielding ferocity.', '2', 'Sonic Sensor', 'Barrier Mesh', 'GravNet', 'Annihilation'),
(10, 'Raze', '', 'Raze explodes out of Brazil with her big personality and big guns. With her blunt-force-trauma playstyle, she excels at flushing entrenched enemies and clearing tight spaces with a generous dose of \"boom.\"', '3', 'Blast Pack', 'Paint Shells', 'Boom Bot', 'Showstopper'),
(11, 'Chamber', '', 'Well-dressed and well-armed, French weapons designer Chamber expels aggressors with deadly precision. He leverages his custom arsenal to hold the line and pick off enemies from afar, with a contingency built for every plan.', '2', 'Rendezvous', 'Trademark', 'Headhunter', 'Tour De Force'),
(12, 'KAY/O', '', 'KAY/O is a machine of war built for a single purpose: neutralizing radiants. His power to Suppress enemy abilities dismantles his opponents\' capacity to fight back, securing him and his allies the ultimate edge.', '1', 'FRAG/ment', 'FLASH/drive', 'ZERO/point', 'NULL/cmd'),
(13, 'Skye', '', 'Hailing from Australia, Skye and her band of beasts trailblaze the way through hostile territory. With her creations hampering the enemy, and her power to heal others, the team is strongest and safest by Skye\'s side.', '1', 'Trailblazer', 'Guiding Light', 'Regrowth', 'Seekers'),
(14, 'Cypher', '', 'The Moroccan information broker, Cypher is a one-man surveillance network who keeps tabs on the enemy\'s every move. No secret is safe. No maneuver goes unseen. Cypher is always watching.', '2', 'Cyber Cage', 'Spycam', 'Trapwire', 'Neural Theft'),
(15, 'Sova', '', 'Born from the eternal winter of Russia\'s tundra, Sova tracks, finds, and eliminates enemies with ruthless efficiency and precision. His custom bow and incredible scouting abilities ensure that even if you run, you cannot hide. ', '1', 'Shock Bolt', 'Recon Bolt', 'Owl Drone', 'Hunter\'s Fury'),
(16, 'Killjoy', '', 'The genius of Germany, Killjoy effortlessly secures key battlefield positions with her arsenal of inventions. If their damage doesn\'t take her enemies out, the debuff her robots provide will make short work of them.', '2', 'Nanoswarm', 'ALARMBOT', 'TURRET', 'Lockdown'),
(17, 'Harbor', '', 'Hailing from India’s coast, Harbor storms the field wielding ancient technology with dominion over water. He unleashes frothing rapids and crushing waves to shield his allies, or pummel those that oppose him.', '4', 'Cove', 'Cascade', 'High Tide', 'Reckoning'),
(18, 'Viper', '', 'The American Chemist, Viper deploys an array of poisonous chemical devices to control the battlefield and choke the enemy\'s vision. If the toxins don\'t kill her prey, her mindgames surely will.', '4', 'Poison Cloud', 'Toxic Screen', 'Snake Bite', 'Viper\'s Pit'),
(19, 'Phoenix', '', 'Hailing from the U.K., Phoenix\'s star power shines through in his fighting style, igniting the battlefield with flash and flare. Whether he\'s got backup or not, he\'s rushing in to fight on his own terms.', '3', 'Blaze', 'Curveball', 'Hot Hands', 'Run it Back'),
(20, 'Astra', '', 'Ghanaian Agent Astra harnesses the energies of the cosmos to reshape battlefields to her whim. With full command of her astral form and a talent for deep strategic foresight, she\'s always eons ahead of her enemy\'s next move.', '4', 'Nova Pulse', 'Nebula  / Dissipate', 'Gravity Well', 'Astral Form / Cosmic Divide'),
(21, 'Brimstone', '', 'Joining from the U.S.A., Brimstone\'s orbital arsenal ensures his squad always has the advantage. His ability to deliver utility precisely and safely make him the unmatched boots-on-the-ground commander.', '4', 'Stim Beacon', 'Incendiary', 'Sky Smoke', 'Orbital Strike'),
(22, 'Iso', '', 'Chinese fixer for hire Iso falls into a flow state to dismantle the opposition. Reconfiguring ambient energy into bulletproof protection, he advances with focus towards his next duel to the death.', '3', 'Undercut', 'Kill Contract', 'Double Tap', 'Contingency'),
(23, 'Neon', '', 'Filipino Agent Neon surges forward at shocking speeds, discharging bursts of bioelectric radiance as fast as her body generates it. She races ahead to catch enemies off guard then strikes them down quicker than lightning.', '3', 'High Gear', 'Relay Bolt', 'Fast Lane', 'Overdrive'),
(24, 'Yoru', '', 'Japanese native Yoru rips holes straight through reality to infiltrate enemy lines unseen. Using deception and aggression in equal measure, he gets the drop on each target before they know where to look.', '3', 'FAKEOUT', 'BLINDSIDE', 'GATECRASH', 'DIMENSIONAL DRIFT'),
(25, 'Sage', '', 'The bastion of China, Sage creates safety for herself and her team wherever she goes. Able to revive fallen friends and stave off forceful assaults, she provides a calm center to a hellish battlefield.', '2', 'Slow Orb', 'Healing Orb', 'Barrier Orb', 'Resurrection'),
(26, 'Reyna', '', 'Forged in the heart of Mexico, Reyna dominates single combat, popping off with each kill she scores. Her capability is only limited by her raw skill, making her sharply dependant on performance. ', '3', 'Devour', 'Dismiss', 'Leer', 'Empress'),
(27, 'Omen', '', 'A phantom of a memory, Omen hunts in the shadows. He renders enemies blind, teleports across the field, then lets paranoia take hold as his foe scrambles to uncover where he might strike next.', '4', 'Paranoia', 'Dark Cover', 'Shrouded Step', 'From the Shadows'),
(28, 'Jett', '', 'Representing her home country of South Korea, Jett\'s agile and evasive fighting style lets her take risks no one else can. She runs circles around every skirmish, cutting enemies up before they even know what hit them.', '3', 'Updraft', 'Tailwind', 'Cloudburst', 'Blade Storm'),
(30, 'Franz', '', 'Originated from the Philippines and moved to Canada where he learned how to hack.', '2', 'Hack', 'Caffeinated', 'No Sleep', 'SUPER CODER');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(4, 'Controller'),
(3, 'Duelist'),
(1, 'Initiator'),
(2, 'Sentinel');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `added` datetime NOT NULL DEFAULT current_timestamp(),
  `user_type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `active`, `added`, `user_type`) VALUES
(10, 'OnlyFranz', '$2y$10$ac2jl62c7H3c0okdlu0FNOwl8UCl5.t5fi1M6XhHb.8m0Z./T8IRW', 1, '2023-11-13 21:43:38', 0),
(11, 'asdf', '$2y$10$UMt.Xltzw9U7cOes3l8T2uEMJrH717mx9EiK5zLSpqzBZSN8xHJIC', 1, '2023-11-13 21:44:51', 0),
(12, 'admin', '$2y$10$PgvdbrjCn23YD57U0s7M2OadodhxPQ2FWdjc4NXjYIS/nUIBgVK1e', 1, '2023-11-13 22:05:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
