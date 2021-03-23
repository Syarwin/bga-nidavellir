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

require_once('modules/php/constants.inc.php');

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
    'displaycondition' => [
      [
        'type' => 'otheroptionisnot',
        'id' => OPTION_EXPANSION,
        'value' => THINGVELLIR,
      ]
    ],
  ],
  OPTION_EXPANSION => [
    'name' => totranslate("Expansion"),
    'values' => [
      NONE => [
        'name' => totranslate("None"),
        'description' => totranslate("Only the base game"),
      ],
      /*
      THINGVELLIR => [
        'name' => totranslate("Thingvellir"),
        'tmdisplay' => totranslate("Thingvellir"),
        'description' => totranslate("Thingvellir expansion"),
        'nobeginner' => true,
      ]
      */
    ],
    "default" => NONE,
  ]
];


$game_preferences = [
];
