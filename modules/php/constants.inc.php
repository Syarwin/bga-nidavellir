<?php

/*
 * State constants
 */
define('ST_GAME_SETUP', 1);

define('ST_START_OF_AGE', 3);
define('ST_START_OF_TURN', 4);
define('ST_END_OF_TURN', 5);
define('ST_RESOLVE_STACK', 80);

define('ST_BIDS', 10);

define('ST_NEXT_RESOLUTION', 11);
define('ST_REVEAL_BIDS', 12);
define('ST_ULINE_BID', 13);
define('ST_RESOLVE_BIDS', 14);

define('ST_NEXT_PLAYER', 20);
define('ST_RECRUIT_DWARF', 21);
define('ST_RECRUIT_HERO', 22);
define('ST_RECRUIT_CAMP', 37);
define('ST_CHOOSE_THRUD_COLUMN', 34);
define('ST_DISCARD_TAVERN_CARD', 36);
define('ST_ANDUMIA', 43);
define('ST_OLWYN', 44);
define('ST_KHRAD_TRANSFORM', 49);

define('ST_PRE_HOFUD', 45);
define('ST_HOFUD', 46);

define('ST_PRE_BRISINGAMENS', 47);
define('ST_BRISINGAMENS', 48);


define('ST_TRADE_COIN', 23);
define('ST_ULINE_TRADE_COIN', 33);

define('ST_TRANSFORM_COIN', 24);
define('ST_DISCARD_CARD', 25);
define('ST_VIDOFNIR', 42);

define('ST_PRE_END_OF_AGE', 29);
define('ST_END_OF_AGE', 30);

define('ST_START_MERCENARY_ENLISTMENT', 38);
define('ST_NEXT_PLAYER_ENLIST', 39);
define('ST_ENLIST_CHOOSE_ORDER', 41);
define('ST_ENLIST_MERCENARY', 40);


define('ST_CHOOSE_YLUD_COLUMN', 35);
define('ST_NEXT_DISTINCTION', 31);
define('ST_DISTINCTION_EXPLORER', 32);

define('ST_PRE_END_OF_GAME', 90);
define('ST_BRISINGAMENS_DISCARD', 91);
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
define('MERCENARY', 9);
define('ARTIFACT', 10);


define('ZOLKUR_ZONE', 80);

// Score
define('ARTIFACT_SCORE', 6);
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


define('OPTION_THINGVELLIR', 103);
define('NONE', 0);
define('THINGVELLIR', 1);

define('OPTION_IDAVOLL', 104);
define('IDAVOLL', 2);


/*
 * User preference option
 */
define('AUTOPICK', 102);
define('OFF', 1);
define('ON', 2);


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

define('ANDUMIA', 221);
define('HOLDA', 222);
define('KHRAD', 223);
define('JARIKA', 224);
define('OLWYN', 225);
define('OLWYN_DOUBLE1', 226);
define('OLWYN_DOUBLE2', 227);
define('ZOLKUR', 228);


/*
 * DISTINCTIONS
 */
define('DISTINCTION_WARRIOR', 300);
define('DISTINCTION_HUNTER', 301);
define('DISTINCTION_MINER', 302);
define('DISTINCTION_BLACKSMITH', 303);
define('DISTINCTION_EXPLORER', 304);
define('DISTINCTION_BLACKSMITH_SPECIAL', 305);


/*
 * ARTIFACTS
 */
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
 * MERCERNARIES
 */
define('MERCENARY_0', 400);
define('MERCENARY_1', 401);
define('MERCENARY_2', 402);
define('MERCENARY_3', 403);
define('MERCENARY_4', 404);
define('MERCENARY_5', 405);

define('MERCENARY_6', 406);
define('MERCENARY_7', 407);
define('MERCENARY_8', 408);
define('MERCENARY_9', 409);
define('MERCENARY_10', 410);
define('MERCENARY_11', 411);




/*
 * STATISTICS
 */
define('STAT_COINS_BP', 31);
define('STAT_BLACKSMITH_BP', 32);
define('STAT_HUNTER_BP', 33);
define('STAT_EXPLORER_BP', 34);
define('STAT_MINER_BP', 35);
define('STAT_WARRIOR_BP', 36);
define('STAT_NEUTRAL_BP', 37);
define('STAT_UPGRADE', 38);
define('STAT_TIES', 39);
define('STAT_HEROES', 40);
