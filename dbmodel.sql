
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
--
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

CREATE TABLE IF NOT EXISTS `card` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_location` varchar(32) NOT NULL,
  `card_state` int(10),
  `class` int(10),
  `grade` varchar(255),
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `coin` (
  `coin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coin_location` varchar(32) NOT NULL,
  `coin_state` int(10),
  `value` int(10),
  `type` int(10),
  PRIMARY KEY (`coin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
