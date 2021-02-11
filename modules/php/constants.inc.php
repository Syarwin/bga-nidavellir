<?php

/*
 * State constants
 */
define('ST_GAME_SETUP', 1);

define('ST_START_OF_AGE', 3);
define('ST_START_OF_TURN', 4);
define('ST_END_OF_TURN', 5);

define('ST_BIDS', 10);

define('ST_NEXT_RESOLUTION', 11);
define('ST_REVEAL_BIDS', 12);
define('ST_ULINE_BID', 13);
define('ST_RESOLVE_BIDS', 14);

define('ST_NEXT_PLAYER', 20);
define('ST_RECRUIT_DWARF', 21);
define('ST_RECRUIT_HERO', 22);

define('ST_TRADE_COIN', 23);

define('ST_END_OF_AGE', 30);

define('ST_GAME_END', 99);


/*
 * Globals
 */
define('CURRENT_TAVERN', 10);
define('GOBLIN_TAVERN', 0);
define('DRAGON_TAVERN', 1);
define('HORSE_TAVERN', 2);


define('CURRENT_PLAYER_INDEX', 11);


/*
 * Type of cards
 */
define('BLACKSMITH', 1);
define('HUNTER', 2);
define('EXPLORER', 3);
define('MINER', 4);
define('WARRIOR', 5);
define('ROYAL_OFFER', 6);

define('HERO', 7);


/*
 * Type of coins
 */
define('COIN_PLAYER', 0);
define('COIN_TREASURE', 1);
define('COIN_DISTINCTION', 2);
