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
    "updateGameProgression" => true,
    "transitions" => [
      'start' => ST_BIDS,
    ]
  ],


  // Virtual states that allow to resolve stack of states
  ST_RESOLVE_STACK => [
    "name" => "resolveStack",
    "description" => '',
    "type" => "game",
    "action" => "stResolveStack",
    "transitions" => []
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
    "transitions" => [
      "resolved" => ST_NEXT_PLAYER,
    ]
  ],


  ST_NEXT_PLAYER => [
		'name' => 'nextPlayer',
		'description' => '',
		'type' => 'game',
		'action' => 'stNextPlayer',
    "updateGameProgression" => true,
		'transitions' => [
      'recruit' => ST_RECRUIT_DWARF,
      'done' => ST_NEXT_RESOLUTION,
      'discardTavern' => ST_DISCARD_TAVERN_CARD,
    ],
	],

  ST_DISCARD_TAVERN_CARD => [
    "name" => "discardTavernCard",
    'description' => clienttranslate('${actplayer} must discard a card from the ${tavern_name}'),
    'descriptionmyturn' => clienttranslate('${you} must discard a card from the ${tavern_name}'),
    'type' => 'activeplayer',
    'args' => 'argDiscardTavernCard',
    'possibleactions' => ['discard'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
    ]
  ],



  ST_RECRUIT_DWARF => [
    "name" => "recruitDwarf",
    'description' => clienttranslate('${actplayer} must recruit a dwarf at the ${tavern_name}${camp_title}'),
    'descriptionmyturn' => clienttranslate('${you} must recruit a dwarf at the ${tavern_name}${camp_title}'),
    'type' => 'activeplayer',
    'args' => 'argRecruitDwarf',
    'action' => 'stRecruitDwarf',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'vidofnir' => ST_VIDOFNIR,

//      'trade' => ST_TRADE_COIN,
      'recruitDone' => ST_TRADE_COIN,
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

      'transformDone' => ST_RESOLVE_STACK,
    ]
  ],

  ST_VIDOFNIR => [
    "name" => "vidofnirTransforms",
    'description' => clienttranslate('${actplayer} must choose which transformations to make (Vidofnir and Vedrfölnir\'s effect)'),
    'descriptionmyturn' => clienttranslate('${you} must choose which transformations to make (Vidofnir and Vedrfölnir\'s effect)'),
    'type' => 'activeplayer',
    'args' => 'argVidofnirTransform',
    "action" => 'stVidofnirTransform',
    'possibleactions' => ['vidofnirTransform'],
    'transitions' => [
      'vidofnir' => ST_VIDOFNIR,
      'done' => ST_RESOLVE_STACK,
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
      'recruitCamp' => ST_RECRUIT_CAMP,
      'andumia' => ST_ANDUMIA,
      'olwyn' => ST_OLWYN,

      'recruitDone' => ST_RESOLVE_STACK,
    ]
  ],


  ST_ANDUMIA => [
    "name" => "pickDiscardAndumia",
    'description' => clienttranslate('${actplayer} must pick one card in the discard'),
    'descriptionmyturn' => clienttranslate('${you} must pick one card in the discard'),
    'type' => 'activeplayer',
    'args' => 'argPickDiscardAndumia',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_RESOLVE_STACK,
    ]
  ],

  ST_OLWYN => [
    "name" => "placeOlwynDouble",
    'description' => clienttranslate('${actplayer} must choose where to place Olwyn double'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Olwyn double'),
    'type' => 'activeplayer',
    'action' => 'stPlaceOlwynDouble',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_OLWYN,
      'finished' => ST_RESOLVE_STACK,
    ]
  ],


  ST_RECRUIT_CAMP => [
    "name" => "recruitCamp",
    'description' => clienttranslate('${actplayer} must recruit a mercenary or an artifact at the camp (Holda\'s effect)'),
    'descriptionmyturn' => clienttranslate('${you} must recruit a mercenary or an artifact at the camp (Holda\'s effect)'),
    'type' => 'activeplayer',
    'args' => 'argRecruitCamp',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      'transform' => ST_TRANSFORM_COIN,
      'vidofnir' => ST_VIDOFNIR,

      'recruitDone' => ST_RESOLVE_STACK,
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
      'discardDone' => ST_RESOLVE_STACK,
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

      'recruitDone' => ST_RESOLVE_STACK,
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
      'nextAge' => ST_START_MERCENARY_ENLISTMENT,
    ]
  ],

  // Mercenary enlistment
  ST_START_MERCENARY_ENLISTMENT => [
    "name" => "startMercenaryEnlistment",
    "description" => "",
    "type" => "game",
    "action" => "stStartMercenaryEnlistment",
    "transitions" => [
      'resolved' => ST_NEXT_PLAYER_ENLIST,
    ]
  ],

  ST_NEXT_PLAYER_ENLIST => [
    "name" => "nextPlayerMercenaryEnlistment",
    "description" => "",
    "type" => "game",
    "action" => "stNextPlayerEnlist",
    "transitions" => [
      'chooseOrder' => ST_ENLIST_CHOOSE_ORDER,
      'end' => ST_PRE_END_OF_AGE,
      'enlist' => ST_ENLIST_MERCENARY,
    ]
  ],

  ST_ENLIST_CHOOSE_ORDER => [
    "name" => "chooseEnlistOrder",
    'description' => clienttranslate('${actplayer} must choose to either enlist mercenaries first or last'),
    'descriptionmyturn' => clienttranslate('${you} must choose to either enlist mercenaries first or last'),
    'type' => 'activeplayer',
    'possibleactions' => ['chooseOrder'],
    'transitions' => [
      'first' => ST_ENLIST_MERCENARY,
      'last' => ST_NEXT_PLAYER_ENLIST,
    ]
  ],

  ST_ENLIST_MERCENARY => [
    "name" => "enlistMercenary",
    'description' => clienttranslate('${actplayer} must choose a mercenary and a column to place it'),
    'descriptionmyturn' => clienttranslate('${you} must choose a mercenary and a column to place it'),
    'args' => 'argEnlistMercenary',
    'action' => 'stEnlistMercenary',
    'type' => 'activeplayer',
    'possibleactions' => ['enlist'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER_ENLIST,
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,

      'recruitDone' => ST_ENLIST_MERCENARY,
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

      'recruitDone' => ST_RESOLVE_STACK,
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
      'explorer' => ST_DISTINCTION_EXPLORER,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_NEXT_DISTINCTION,
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

      'recruitDone' => ST_RESOLVE_STACK,
    ]
  ],


  ST_PRE_END_OF_GAME => [
    "name" => "preEndOfGame",
    "description" => "",
    "type" => "game",
    "updateGameProgression" => true,
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
