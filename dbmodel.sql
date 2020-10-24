
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
--
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

CREATE TABLE IF NOT EXISTS `card` (
  `card_key` varchar(32) NOT NULL,
  `card_location` varchar(32) NOT NULL,
  `card_state` int(10),
  PRIMARY KEY (`card_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
