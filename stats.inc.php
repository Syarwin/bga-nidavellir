<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * stats.inc.php
 *
 * Nidavellir game statistics description
 *
 */

require_once("modules/php/constants.inc.php");

$stats_type = [
  "table" => [],
  "player" => [
    "coins_upgrades" => [
      "id" => STAT_UPGRADE,
      "name" => totranslate("Number of coins trades/transformations"),
      "type" => "int"
    ],

    "player_ties" => [
      "id" => STAT_TIES,
      "name" => totranslate("Number of ties"),
      "type" => "int"
    ],

    "player_heroes" => [
      "id" => STAT_HEROES,
      "name" => totranslate("Number of heroes"),
      "type" => "int"
    ],


    "score_coins" => [
      "id" => STAT_COINS_BP,
      "name" => totranslate("Number of Bravery Points earned by coins"),
      "type" => "int"
    ],

    "score_blacksmith" => [
      "id" => STAT_BLACKSMITH_BP,
      "name" => totranslate("Number of Bravery Points earned by blacksmiths"),
      "type" => "int"
    ],
    "score_hunter" => [
      "id" => STAT_HUNTER_BP,
      "name" => totranslate("Number of Bravery Points earned by hunters"),
      "type" => "int"
    ],
    "score_explorer" => [
      "id" => STAT_EXPLORER_BP,
      "name" => totranslate("Number of Bravery Points earned by explorers"),
      "type" => "int"
    ],
    "score_miner" => [
      "id" => STAT_MINER_BP,
      "name" => totranslate("Number of Bravery Points earned by miners"),
      "type" => "int"
    ],
    "score_warrior" => [
      "id" => STAT_WARRIOR_BP,
      "name" => totranslate("Number of Bravery Points earned by warriors"),
      "type" => "int"
    ],
    "score_neutral" => [
      "id" => STAT_NEUTRAL_BP,
      "name" => totranslate("Number of Bravery Points earned by neutral heroes"),
      "type" => "int"
    ],

  ],
];
