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
    "transitions" => [
      'start' => ST_BIDS,
      'recruitDone' => ST_TRADE_COIN, // Fake transition, used as source
      'transformDone' => ST_TRADE_COIN, // Fake transition, used as source
    ]
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
    'description' => clienttranslate('${actplayer} must choose its bid (Uline\'s power)'),
    'descriptionmyturn' => clienttranslate('${you} must choose a coin to bid (Uline\'s power)'),
    'type' => 'activeplayer',
    'args' => 'argUlineBid',
    'possibleactions' => ['bid'],
    'transitions' => [
      'revealed' => ST_RESOLVE_BIDS,
    ]
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
    'action' => 'stRecruitDwarf',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
    ]
  ],

  ST_TRANSFORM_COIN => [
    "name" => "transformCoin",
    'description' => clienttranslate('${actplayer} must choose a coin to transform (+${value})'),
    'descriptionmyturn' => clienttranslate('${you} must choose a coin to transform (+${value})'),
    'type' => 'activeplayer',
    'args' => 'argTransformCoin',
    'possibleactions' => ['transform'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      //'distinction' => ST_NEXT_DISTINCTION,
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
      'discard' => ST_DISCARD_CARD,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
    ]
  ],


  ST_DISCARD_CARD => [
    "name" => "discardCard",
    'description' => clienttranslate('${actplayer} must discard ${n} cards'),
    'descriptionmyturn' => clienttranslate('${you} must discard ${n} cards'),
    'descriptionsingle' => clienttranslate('${actplayer} must discard a card'),
    'descriptionmyturnsingle' => clienttranslate('${you} must discard a card'),
    'type' => 'activeplayer',
    'args' => 'argDiscardCard',
    'possibleactions' => ['discard'],
    'transitions' => [
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
      'uline' => ST_ULINE_TRADE_COIN,
    ]
  ],

  ST_ULINE_TRADE_COIN => [
    "name" => "ulineTradeCoin",
    'description' => clienttranslate('${actplayer} must choose two coins to trade (Uline\'s power)'),
    'descriptionmyturn' => clienttranslate('${you} must choose two coins to trade (Uline\'s power)'),
    'type' => 'activeplayer',
    'args' => 'argUlineTradeCoin',
    'possibleactions' => ['trade'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
    ]
  ],


  ST_CHOOSE_THRUD_COLUMN => [
    "name" => "chooseThrudColumn",
    'description' => clienttranslate('${actplayer} must choose where to place Thrud'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Thrud'),
    'type' => 'activeplayer',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
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
      'nextAge' => ST_PRE_END_OF_AGE,
    ]
  ],


  // Pre-End of age
  ST_PRE_END_OF_AGE => [
    "name" => "preEndOfAge",
    "description" => "",
    "type" => "game",
    "action" => "stPreEndOfAge",
    "transitions" => [
      'end' => ST_END_OF_AGE,
      'placeYlud' => ST_CHOOSE_YLUD_COLUMN,
      'recruitDone' => ST_END_OF_AGE, // Fake transition, used as source
      'transformDone' => ST_END_OF_AGE, // Fake transition, used as source
    ]
  ],


  ST_CHOOSE_YLUD_COLUMN => [
    "name" => "chooseYludColumn",
    'description' => clienttranslate('${actplayer} must choose where to place Ylud'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Ylud'),
    'type' => 'activeplayer',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
    ]
  ],



  // End of age
  ST_END_OF_AGE => [
    "name" => "endOfAge",
    "description" => "",
    "type" => "game",
    "action" => "stEndOfAge",
    "transitions" => [
      'distinctions' => ST_NEXT_DISTINCTION,
      'scores' => ST_PRE_END_OF_GAME,
    ]
  ],


  // Distinctions
  ST_NEXT_DISTINCTION => [
    "name" => "nextDistinction",
    "description" => "",
    "type" => "game",
    "action" => "stNextDistinction",
    "transitions" => [
      'next' => ST_NEXT_DISTINCTION,
      'done' => ST_START_OF_AGE,
      'transform' => ST_TRANSFORM_COIN,
      'hero' => ST_RECRUIT_HERO,
      'recruitDone' => ST_NEXT_DISTINCTION, // Fake transition, used as source
      'transformDone' => ST_NEXT_DISTINCTION, // Fake transition, used as source
      'explorer' => ST_DISTINCTION_EXPLORER,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
    ]
  ],


  ST_DISTINCTION_EXPLORER => [
    "name" => "distinctionExplorer",
    'description' => clienttranslate('${actplayer} must choose a card'),
    'descriptionmyturn' => clienttranslate('${you} must choose a card'),
    'type' => 'activeplayer',
    'args' => 'argDistinctionExplorer',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'next' => ST_NEXT_DISTINCTION,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'recruitDone' => ST_NEXT_DISTINCTION, // Fake transition, used as source
      'transformDone' => ST_NEXT_DISTINCTION, // Fake transition, used as source
    ]
  ],


  ST_PRE_END_OF_GAME => [
    "name" => "preEndOfGame",
    "description" => "",
    "type" => "game",
    "action" => "stPreEndOfGame",
    "transitions" => ['' => ST_GAME_END ]
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
