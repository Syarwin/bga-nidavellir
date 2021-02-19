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
 * gameoptions.inc.php
 *
 * Nidavellir game options description
 */

$game_options = [
  OPTION_SETUP => [
    'name' => totranslate('Heroes'),
    'values' => [
      BASIC => [
        'name' => totranslate('First game'),
        'tmdisplay' => totranslate('First game'),
        'description' => totranslate('Thrud, Ylud and Uline removed'),
      ],
      NORMAL => [
        'name' => totranslate('All heroes'),
        'tmdisplay' => totranslate('All heroes'),
        'description' => totranslate('All heroes'),
        'nobeginner' => true,
      ],
    ],
    "default" => NORMAL,
  ],
];
