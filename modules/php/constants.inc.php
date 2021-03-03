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
define('ST_CHOOSE_THRUD_COLUMN', 34);

define('ST_TRADE_COIN', 23);
define('ST_ULINE_TRADE_COIN', 33);

define('ST_TRANSFORM_COIN', 24);
define('ST_DISCARD_CARD', 25);

define('ST_END_OF_AGE', 30);

define('ST_NEXT_DISTINCTION', 31);
define('ST_DISTINCTION_EXPLORER', 32);

define('ST_PRE_END_OF_GAME', 90);
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
define('NEUTRAL', 0);
define('BLACKSMITH', 1);
define('HUNTER', 2);
define('EXPLORER', 3);
define('MINER', 4);
define('WARRIOR', 5);
define('ROYAL_OFFER', 6);

define('HERO', 7);
define('DISTINCTION', 8);


define('EXTRA_SCORE', 7);


/*
 * Type of coins
 */
define('COIN_PLAYER', 0);
define('COIN_TREASURE', 1);
define('COIN_DISTINCTION', 2);


/*
 * Game option
 */
define('OPTION_SETUP', 102);
define('BASIC', 0);
define('NORMAL', 1);


/*
 * HEROES
 */
define('KRAAL', 200);
define('TARAH', 201);

define('ARAL', 202);
define('DAGDA', 203);

define('AEGUR', 204);
define('BONFUR', 205);

define('ZORAL', 206);
define('LOKDUR', 207);

define('HOURYA', 208);
define('IDUNN', 209);

define('DWERG1', 210);
define('DWERG2', 211);
define('DWERG3', 212);
define('DWERG4', 213);
define('DWERG5', 214);

define('SKAA', 215);
define('ASTRID', 216);
define('GRID', 217);
define('ULINE', 218);
define('YLUD', 219);
define('THRUD', 220);


define('FAFNIR', 250);
define('DRAUPNIR', 251);
define('VEGVISIR', 252);
define('SVALINN', 253);
define('MEGINGJORD', 254);
define('VIDOFNIR', 255);
define('BRISINGAMENS', 256);
define('MJOLLNIR', 257);
define('HOFUD', 258);
define('HRAFNSMERKI', 259);
define('JARNGLOFI', 260);
define('GJALLARHORN', 261);


/*
 * DISTINCTIONS
 */
define('DISTINCTION_WARRIOR', 300);
define('DISTINCTION_HUNTER', 301);
define('DISTINCTION_MINER', 302);
define('DISTINCTION_BLACKSMITH', 303);
define('DISTINCTION_BLACKSMITH_SPECIAL', 304);
define('DISTINCTION_EXPLORER', 305);
