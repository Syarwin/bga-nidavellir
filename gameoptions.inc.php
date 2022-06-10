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
        'id' => OPTION_THINGVELLIR,
        'value' => THINGVELLIR,
      ],
      [
        'type' => 'otheroptionisnot',
        'id' => OPTION_IDAVOLL,
        'value' => IDAVOLL,
      ]
    ],
  ],
  OPTION_THINGVELLIR => [
    'name' => totranslate("Thingvellir expansion"),
    'values' => [
      NONE => [
        'name' => totranslate("No"),
        'description' => totranslate("Thingvellir expansion excluded"),
      ],
      THINGVELLIR => [
        'name' => totranslate("Yes"),
        'tmdisplay' => totranslate("Thingvellir"),
        'description' => totranslate("Thingvellir expansion included"),
        'nobeginner' => true,
      ]
    ],
    "default" => NONE,
  ],
  OPTION_IDAVOLL => [
    'name' => totranslate("Idavoll expansion"),
    'values' => [
      NONE => [
        'name' => totranslate("No"),
        'description' => totranslate("Idavoll expansion excluded"),
      ],
      IDAVOLL => [
        'name' => totranslate("Yes"),
        'tmdisplay' => totranslate("Idavoll"),
        'description' => totranslate("Idavoll expansion included"),
        'nobeginner' => true,
      ]
    ],
    "default" => NONE,
  ]
];


$game_preferences = [
];
