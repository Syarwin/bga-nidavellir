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
 * states.inc.php
 *
 * Nidavellir game states description
 *
 */

$machinestates = [
  // The initial state. Please do not modify.
  ST_GAME_SETUP => [
    "name" => "gameSetup",
    "description" => "",
    "type" => "manager",
    "action" => "stGameSetup",
    "transitions" => [ "" => ST_START_OF_AGE ]
  ],

  // Start of an age or distinction
  ST_START_OF_AGE => [
    "name" => "startOfAge",
    "description" => "",
    "type" => "game",
    "action" => "stStartOfAge",
    "transitions" => [
      "turn" => ST_START_OF_TURN,
    ]
  ],


  // Start of turn : draw the cards
  ST_START_OF_TURN => [
    "name" => "startOfTurn",
    "description" => "",
    "type" => "game",
    "action" => "stStartOfTurn",
    "transitions" => ['' => ST_BIDS]
  ],


  ST_BIDS => [
    "name" => "playerBids",
    'description' => clienttranslate('Waiting for other players to bid'),
    'descriptionmyturn' => clienttranslate('${you} must bid for the three taverns'),
    'descriptionmyturngeneric' => clienttranslate('${you} must bid for the three taverns'),
    'descriptionmyturnplacecoin' => clienttranslate('${you} must select a tavern to place the coin'),
    'type' => 'multipleactiveplayer',
    'args' => 'argPlayerBids',
    'action' => 'stPlayersBids',
    'possibleactions' => ['bid'],
    'transitions' => [
      'done' => ST_NEXT_RESOLUTION,
    ]
  ],


  ST_NEXT_RESOLUTION => [
    "name" => "nextResolution",
    "description" => "",
    "type" => "game",
    "action" => "stNextResolution",
    "transitions" => [
      'reveal' => ST_REVEAL_BIDS,
      'finished' => ST_END_OF_TURN,
    ]
  ],


  ST_REVEAL_BIDS => [
    "name" => "resolveBids",
    "description" => '',
    "type" => "game",
    "action" => "stRevealBids",
    "updateGameProgression" => true,
    "transitions" => [
      'revealed' => ST_RESOLVE_BIDS,
      'uline' => ST_ULINE_BID,
    ]
  ],


  ST_ULINE_BID => [
    "name" => "ulineBid",
    'description' => clienttranslate('${activeplayer} must use Uline\'s power'),
    'descriptionmyturn' => clienttranslate('${you} must use Uline\'s power'),
    'type' => 'activeplayer',
    'args' => 'argUlineBid',
    'possibleactions' => [],
    'transitions' => []
  ],




  ST_RESOLVE_BIDS => [
    "name" => "resolveBids",
    "description" => '',
    "type" => "game",
    "action" => "stResolveBids",
    "updateGameProgression" => true,
    "transitions" => [
      "resolved" => ST_NEXT_PLAYER,
    ]
  ],


  ST_NEXT_PLAYER => [
		'name' => 'nextPlayer',
		'description' => '',
		'type' => 'game',
		'action' => 'stNextPlayer',
		'transitions' => [
      'recruit' => ST_RECRUIT_DWARF,
      'done' => ST_NEXT_RESOLUTION,
    ],
	],


  ST_RECRUIT_DWARF => [
    "name" => "recruitDwarf",
    'description' => clienttranslate('${actplayer} must recruit a dwarf at the ${tavern_name}'),
    'descriptionmyturn' => clienttranslate('${you} must recruit a dwarf at the ${tavern_name}'),
    'type' => 'activeplayer',
    'args' => 'argRecruitDwarf',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
    ]
  ],


  ST_RECRUIT_HERO => [
    "name" => "recruitHero",
    'description' => clienttranslate('${actplayer} must recruit a hero'),
    'descriptionmyturn' => clienttranslate('${you} must recruit a hero'),
    'type' => 'activeplayer',
    'args' => 'argRecruitHero',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
    ]
  ],


  ST_TRADE_COIN => [
    "name" => "tradeCoin",
    'description' => '',
    'type' => 'game',
    "action" => "stTradeCoin",
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
// TODO      'uline' => ST_NEXT_PLAYER,
    ]
  ],


  // Player end of turn
  ST_END_OF_TURN => [
    "name" => "endOfTurn",
    "description" => "",
    "type" => "game",
    "action" => "stEndOfTurn",
    "transitions" => [
      'nextTurn' => ST_START_OF_TURN,
      'nextAge' => ST_END_OF_AGE,
    ]
  ],


  // End of age
  ST_END_OF_AGE => [
    "name" => "endOfAge",
    "description" => "",
    "type" => "game",
    "action" => "stEndOfAge",
    "transitions" => [ ''
//      'distrinction' => ST_START_OF_AGE,
    ]
  ],



  // Final state.
  // Please do not modify (and do not overload action/args methods).
  ST_GAME_END => [
    "name" => "gameEnd",
    "description" => clienttranslate("End of game"),
    "type" => "manager",
    "action" => "stGameEnd",
    "args" => "argGameEnd"
  ]
];
