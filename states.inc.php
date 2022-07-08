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
    'name' => 'gameSetup',
    'description' => '',
    'type' => 'manager',
    'action' => 'stGameSetup',
    'transitions' => ['' => ST_START_OF_AGE],
  ],

  // Start of an age or distinction
  ST_START_OF_AGE => [
    'name' => 'startOfAge',
    'description' => '',
    'type' => 'game',
    'action' => 'stStartOfAge',
    'transitions' => [
      'turn' => ST_START_OF_TURN,
    ],
  ],

  // Start of turn : draw the cards
  ST_START_OF_TURN => [
    'name' => 'startOfTurn',
    'description' => '',
    'type' => 'game',
    'action' => 'stStartOfTurn',
    'updateGameProgression' => true,
    'transitions' => [
      'start' => ST_BIDS,
      'freya' => ST_FREYA,
      'loki' => ST_LOKI,
    ],
  ],

  ST_LOKI => [
    'name' => 'loki',
    'description' => clienttranslate('${actplayer} may use Loki power to reserve a card'),
    'descriptionmyturn' => clienttranslate('${you} may use Loki power to reserve a card'),
    'type' => 'activeplayer',
    'args' => 'argLoki',
    'possibleactions' => ['actUseLokiPower', 'actSkipLokiPower'],
    'transitions' => [
      '' => ST_PRE_FREYA,
    ],
  ],

  ST_PRE_FREYA => [
    'name' => 'preFreya',
    'description' => '',
    'type' => 'game',
    'action' => 'stPreFreya',
    'transitions' => [
      'start' => ST_BIDS,
      'freya' => ST_FREYA,
    ],
  ],

  ST_FREYA => [
    'name' => 'freya',
    'description' => clienttranslate('${actplayer} may use Freya power to exchange two cards'),
    'descriptionmyturn' => clienttranslate('${you} may use Freya power to exchange two cards'),
    'type' => 'activeplayer',
    'args' => 'argFreya',
    'possibleactions' => ['actUseFreyaPower', 'actSkipFreyaPower'],
    'transitions' => [
      'start' => ST_BIDS,
    ],
  ],

  // Virtual states that allow to resolve stack of states
  ST_RESOLVE_STACK => [
    'name' => 'resolveStack',
    'description' => '',
    'type' => 'game',
    'action' => 'stResolveStack',
    'transitions' => [],
  ],

  ST_BIDS => [
    'name' => 'playerBids',
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
    ],
  ],

  ST_NEXT_RESOLUTION => [
    'name' => 'nextResolution',
    'description' => '',
    'type' => 'game',
    'action' => 'stNextResolution',
    'transitions' => [
      'reveal' => ST_REVEAL_BIDS,
      'finished' => ST_END_OF_TURN,
      'odin' => ST_ODIN,
    ],
  ],

  ST_REVEAL_BIDS => [
    'name' => 'resolveBids',
    'description' => '',
    'type' => 'game',
    'action' => 'stRevealBids',
    'transitions' => [
      'revealed' => ST_RESOLVE_BIDS,
      'uline' => ST_ULINE_BID,
    ],
  ],

  ST_ULINE_BID => [
    'name' => 'ulineBid',
    'description' => clienttranslate('${actplayer} must choose its bid (Uline\'s power)'),
    'descriptionmyturn' => clienttranslate('${you} must choose a coin to bid (Uline\'s power)'),
    'type' => 'activeplayer',
    'args' => 'argUlineBid',
    'possibleactions' => ['bid'],
    'transitions' => [
      'revealed' => ST_RESOLVE_BIDS,
    ],
  ],

  ST_RESOLVE_BIDS => [
    'name' => 'resolveBids',
    'description' => '',
    'type' => 'game',
    'action' => 'stResolveBids',
    'transitions' => [
      'resolved' => ST_NEXT_PLAYER,
    ],
  ],

  ST_NEXT_PLAYER => [
    'name' => 'nextPlayer',
    'description' => '',
    'type' => 'game',
    'action' => 'stNextPlayer',
    'updateGameProgression' => true,
    'transitions' => [
      'recruit' => ST_RECRUIT_DWARF,
      'done' => ST_NEXT_RESOLUTION,
      'discardTavern' => ST_DISCARD_TAVERN_CARD,
    ],
  ],

  ST_DISCARD_TAVERN_CARD => [
    'name' => 'discardTavernCard',
    'description' => clienttranslate('${actplayer} must discard a card from the ${tavern_name}'),
    'descriptionmyturn' => clienttranslate('${you} must discard a card from the ${tavern_name}'),
    'type' => 'activeplayer',
    'args' => 'argDiscardTavernCard',
    'possibleactions' => ['discard'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
    ],
  ],

  ST_RECRUIT_DWARF => [
    'name' => 'recruitDwarf',
    'description' => clienttranslate('${actplayer} must recruit a dwarf at the ${tavern_name}${camp_title}'),
    'descriptionmyturn' => clienttranslate('${you} must recruit a dwarf at the ${tavern_name}${camp_title}'),
    'type' => 'activeplayer',
    'args' => 'argRecruitDwarf',
    'action' => 'stRecruitDwarf',
    'possibleactions' => ['recruit', 'actUseFriggPower'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'vidofnir' => ST_VIDOFNIR,
      'hofud' => ST_PRE_HOFUD,
      'brisingamens' => ST_PRE_BRISINGAMENS,
      'placeGullinbursti' => ST_GULLINBURSTI,
      'frigg' => ST_FRIGG,
      'skymir' => ST_SKYMIR,
      'olrun' => ST_OLRUN,
      'hrungnir' => ST_HRUNGNIR,

      'recruitDone' => ST_TRADE_COIN,
    ],
  ],

  ST_TRANSFORM_COIN => [
    'name' => 'transformCoin',
    'description' => clienttranslate('${actplayer} must choose a coin to transform (+${value})'),
    'descriptionmyturn' => clienttranslate('${you} must choose a coin to transform (+${value})'),
    'type' => 'activeplayer',
    'args' => 'argTransformCoin',
    'possibleactions' => ['transform'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,

      'transformDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_VIDOFNIR => [
    'name' => 'vidofnirTransforms',
    'description' => clienttranslate(
      '${actplayer} must choose which transformations to make (Vidofnir and Vedrfölnir\'s effect)'
    ),
    'descriptionmyturn' => clienttranslate(
      '${you} must choose which transformations to make (Vidofnir and Vedrfölnir\'s effect)'
    ),
    'type' => 'activeplayer',
    'args' => 'argVidofnirTransform',
    'action' => 'stVidofnirTransform',
    'possibleactions' => ['vidofnirTransform'],
    'transitions' => [
      'vidofnir' => ST_VIDOFNIR,
      'done' => ST_RESOLVE_STACK,
    ],
  ],

  ST_RECRUIT_HERO => [
    'name' => 'recruitHero',
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
      'khradTransform' => ST_KHRAD_TRANSFORM,
      'skymir' => ST_SKYMIR,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_ANDUMIA => [
    'name' => 'pickDiscardAndumia',
    'description' => clienttranslate('${actplayer} must pick one card in the discard'),
    'descriptionmyturn' => clienttranslate('${you} must pick one card in the discard'),
    'type' => 'activeplayer',
    'args' => 'argPickDiscardAndumia',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'olwyn' => ST_OLWYN,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_OLWYN => [
    'name' => 'placeOlwynDouble',
    'description' => clienttranslate('${actplayer} must choose where to place Olwyn double'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Olwyn double'),
    'type' => 'activeplayer',
    'action' => 'stPlaceOlwynDouble',
    'args' => 'argPlaceOlwynDouble',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'olwyn' => ST_OLWYN,

      'recruitDone' => ST_OLWYN,
      'finished' => ST_RESOLVE_STACK,
    ],
  ],

  ST_GULLINBURSTI => [
    'name' => 'placeGullinbursti',
    'description' => clienttranslate('${actplayer} must choose where to place Gullinbursti'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Gullinbursti'),
    'type' => 'activeplayer',
    'args' => 'argPlaceGullinbursti',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'olwyn' => ST_OLWYN,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_KHRAD_TRANSFORM => [
    'name' => 'khradTransform',
    'description' => clienttranslate('${actplayer} must choose a coin to upgrade (Khrad\'s effect)'),
    'descriptionmyturn' => clienttranslate('${you} must choose a coin to upgrade (Khrad\'s effect)'),
    'type' => 'activeplayer',
    'action' => 'stKhradTransform',
    'args' => 'argKhradTransform',
    'possibleactions' => ['transform'],
    'transitions' => [
      'transformDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_RECRUIT_CAMP => [
    'name' => 'recruitCamp',
    'description' => clienttranslate(
      '${actplayer} must recruit a mercenary or an artifact at the camp (Holda\'s effect)'
    ),
    'descriptionmyturn' => clienttranslate(
      '${you} must recruit a mercenary or an artifact at the camp (Holda\'s effect)'
    ),
    'type' => 'activeplayer',
    'args' => 'argRecruitCamp',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      'transform' => ST_TRANSFORM_COIN,
      'vidofnir' => ST_VIDOFNIR,
      'hofud' => ST_PRE_HOFUD,
      'brisingamens' => ST_PRE_BRISINGAMENS,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_PRE_HOFUD => [
    'name' => 'preHofud',
    'description' => '',
    'type' => 'game',
    'action' => 'stPreHofud',
    'transitions' => ['' => ST_HOFUD],
  ],

  ST_HOFUD => [
    'name' => 'discardHofud',
    'description' => clienttranslate('Others must choose a card in the warrior column and discard it'),
    'descriptionmyturn' => clienttranslate('${you} must choose a card of the warrior column and discard it'),
    'args' => 'argDiscardHofud',
    'type' => 'multipleactiveplayer',
    'possibleactions' => ['discard', 'actUseThorPowerHofud'],
    'transitions' => [
      'done' => ST_RESOLVE_STACK,
    ],
  ],

  ST_PRE_BRISINGAMENS => [
    'name' => 'preBrisingamens',
    'description' => '',
    'type' => 'game',
    'action' => 'stPreBrisingamens',
    'transitions' => [
      'recruit' => ST_BRISINGAMENS,
      'done' => ST_RESOLVE_STACK,
    ],
  ],

  ST_BRISINGAMENS => [
    'name' => 'brisingamens',
    'description' => clienttranslate('${actplayer} must pick one card in the discard'),
    'descriptionmyturn' => clienttranslate('${you} must pick one card in the discard'),
    'args' => 'argPickDiscardAndumia', // Same arg as Andumia
    'type' => 'activeplayer',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_PRE_BRISINGAMENS,
    ],
  ],

  ST_DISCARD_CARD => [
    'name' => 'discardCard',
    'description' => clienttranslate('${actplayer} must discard ${n} cards'),
    'descriptionmyturn' => clienttranslate('${you} must discard ${n} cards'),
    'descriptionsingle' => clienttranslate('${actplayer} must discard a card'),
    'descriptionmyturnsingle' => clienttranslate('${you} must discard a card'),
    'type' => 'activeplayer',
    'args' => 'argDiscardCard',
    'possibleactions' => ['discard', 'actUseThorPower'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'recruitDone' => ST_RESOLVE_STACK,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'discard' => ST_DISCARD_CARD,
    ],
  ],

  ST_TRADE_COIN => [
    'name' => 'tradeCoin',
    'description' => '',
    'type' => 'game',
    'action' => 'stTradeCoin',
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'uline' => ST_ULINE_TRADE_COIN,
    ],
  ],

  ST_ULINE_TRADE_COIN => [
    'name' => 'ulineTradeCoin',
    'description' => clienttranslate('${actplayer} must choose two coins to trade (Uline\'s power)'),
    'descriptionmyturn' => clienttranslate('${you} must choose two coins to trade (Uline\'s power)'),
    'type' => 'activeplayer',
    'args' => 'argUlineTradeCoin',
    'possibleactions' => ['trade'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
    ],
  ],

  ST_CHOOSE_THRUD_COLUMN => [
    'name' => 'chooseThrudColumn',
    'description' => clienttranslate('${actplayer} must choose where to place Thrud'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Thrud'),
    'type' => 'activeplayer',
    'possibleactions' => ['pickColumn', 'recruit'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'hero' => ST_RECRUIT_HERO,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_ODIN => [
    'name' => 'odin',
    'description' => clienttranslate('${actplayer} may use Odin power to exchange a neutral Hero'),
    'descriptionmyturn' => clienttranslate('${you} may use Odin power to exchange a neutral Hero'),
    'type' => 'activeplayer',
    'args' => 'argOdin',
    'possibleactions' => ['actUseOdinPower', 'actSkipOdinPower'],
    'transitions' => [
      'done' => ST_END_OF_TURN,
      'recruitDone' => ST_END_OF_TURN,

      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      'discard' => ST_DISCARD_CARD,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'recruitCamp' => ST_RECRUIT_CAMP,
      'andumia' => ST_ANDUMIA,
      'olwyn' => ST_OLWYN,
      'khradTransform' => ST_KHRAD_TRANSFORM,
    ],
  ],

  ST_FRIGG => [
    'name' => 'friggPick',
    'description' => clienttranslate('${actplayer} must keep one card (Frigg\'s power)'),
    'descriptionmyturn' => clienttranslate('${you} must keep one card (Frigg\'s power)'),
    'type' => 'activeplayer',
    'args' => 'argFrigg',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'vidofnir' => ST_VIDOFNIR,
      'hofud' => ST_PRE_HOFUD,
      'brisingamens' => ST_PRE_BRISINGAMENS,
      'placeGullinbursti' => ST_GULLINBURSTI,

      'recruitDone' => ST_TRADE_COIN,
    ],
  ],

  ST_SKYMIR => [
    'name' => 'skymir',
    'description' => clienttranslate('${actplayer} must keep one card (Skymir\'s power, first card)'),
    'descriptionmyturn' => clienttranslate('${you} must keep one card (Skymir\'s power, first card)'),
    'descriptionsecond' => clienttranslate('${actplayer} must keep one card (Skymir\'s power, second card)'),
    'descriptionmyturnsecond' => clienttranslate('${you} must keep one card (Skymir\'s power, second card)'),
    'type' => 'activeplayer',
    'args' => 'argSkymir',
    'possibleactions' => ['recruit'],
    'transitions' => [
      'hero' => ST_RECRUIT_HERO,
      'transform' => ST_TRANSFORM_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,
      'vidofnir' => ST_VIDOFNIR,
      'hofud' => ST_PRE_HOFUD,
      'brisingamens' => ST_PRE_BRISINGAMENS,
      'placeGullinbursti' => ST_GULLINBURSTI,
      'skymir' => ST_SKYMIR,
      'olrun' => ST_OLRUN,

      'recruitDone' => ST_TRADE_COIN,
    ],
  ],

  ST_OLRUN => [
    'name' => 'chooseOlrunClass',
    'description' => clienttranslate('${actplayer} must choose the class for Ölrun (click corresponding column)'),
    'descriptionmyturn' => clienttranslate('${you} must choose the class for Ölrun (click corresponding column)'),
    'type' => 'activeplayer',
    'possibleactions' => ['actChooseOlrunClass'],
    'transitions' => [
      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  ST_HRUNGNIR => [
    'name' => 'hrungnir',
    'description' => clienttranslate('${actplayer} must choose the order of coins in hand for Hrungnir power'),
    'descriptionmyturn' => clienttranslate('${you} must choose the order of coins in hand for Hrungnir power'),
    'type' => 'activeplayer',
    'args' => 'argHrungnir',
    'possibleactions' => ['actHrungnir'],
    'transitions' => [
      'recruitDone' => ST_TRADE_COIN,
    ],
  ],


  // Player end of turn
  ST_END_OF_TURN => [
    'name' => 'endOfTurn',
    'description' => '',
    'type' => 'game',
    'action' => 'stEndOfTurn',
    'transitions' => [
      'nextTurn' => ST_START_OF_TURN,
      'nextAge' => ST_START_MERCENARY_ENLISTMENT,
    ],
  ],

  // Mercenary enlistment
  ST_START_MERCENARY_ENLISTMENT => [
    'name' => 'startMercenaryEnlistment',
    'description' => '',
    'type' => 'game',
    'action' => 'stStartMercenaryEnlistment',
    'transitions' => [
      'resolved' => ST_NEXT_PLAYER_ENLIST,
      'skip' => ST_PRE_END_OF_AGE,
    ],
  ],

  ST_NEXT_PLAYER_ENLIST => [
    'name' => 'nextPlayerMercenaryEnlistment',
    'description' => '',
    'type' => 'game',
    'action' => 'stNextPlayerEnlist',
    'transitions' => [
      'chooseOrder' => ST_ENLIST_CHOOSE_ORDER,
      'end' => ST_PRE_END_OF_AGE,
      'enlist' => ST_ENLIST_MERCENARY,
    ],
  ],

  ST_ENLIST_CHOOSE_ORDER => [
    'name' => 'chooseEnlistOrder',
    'description' => clienttranslate('${actplayer} must choose to either enlist mercenaries first or last'),
    'descriptionmyturn' => clienttranslate('${you} must choose to either enlist mercenaries first or last'),
    'type' => 'activeplayer',
    'possibleactions' => ['chooseOrder'],
    'transitions' => [
      'first' => ST_ENLIST_MERCENARY,
      'last' => ST_NEXT_PLAYER_ENLIST,
    ],
  ],

  ST_ENLIST_MERCENARY => [
    'name' => 'enlistMercenary',
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
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_ENLIST_MERCENARY,
    ],
  ],

  // Pre-End of age
  ST_PRE_END_OF_AGE => [
    'name' => 'preEndOfAge',
    'description' => '',
    'type' => 'game',
    'action' => 'stPreEndOfAge',
    'transitions' => [
      'end' => ST_END_OF_AGE,
      'placeYlud' => ST_CHOOSE_YLUD_COLUMN,
    ],
  ],

  ST_CHOOSE_YLUD_COLUMN => [
    'name' => 'chooseYludColumn',
    'description' => clienttranslate('${actplayer} must choose where to place Ylud'),
    'descriptionmyturn' => clienttranslate('${you} must choose where to place Ylud'),
    'type' => 'activeplayer',
    'possibleactions' => ['pickColumn'],
    'transitions' => [
      'next' => ST_NEXT_PLAYER,
      'hero' => ST_RECRUIT_HERO,
      'trade' => ST_TRADE_COIN,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_RESOLVE_STACK,
    ],
  ],

  // End of age
  ST_END_OF_AGE => [
    'name' => 'endOfAge',
    'description' => '',
    'type' => 'game',
    'action' => 'stEndOfAge',
    'transitions' => [
      'distinctions' => ST_NEXT_DISTINCTION,
      'scores' => ST_PRE_END_OF_GAME,
    ],
  ],

  // Distinctions
  ST_NEXT_DISTINCTION => [
    'name' => 'nextDistinction',
    'description' => '',
    'type' => 'game',
    'action' => 'stNextDistinction',
    'transitions' => [
      'next' => ST_NEXT_DISTINCTION,
      'done' => ST_START_OF_AGE,
      'transform' => ST_TRANSFORM_COIN,
      'hero' => ST_RECRUIT_HERO,
      'explorer' => ST_DISTINCTION_EXPLORER,
      'placeThrud' => ST_CHOOSE_THRUD_COLUMN,

      'recruitDone' => ST_NEXT_DISTINCTION,
    ],
  ],

  ST_DISTINCTION_EXPLORER => [
    'name' => 'distinctionExplorer',
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
    ],
  ],

  ST_PRE_END_OF_GAME => [
    'name' => 'preEndOfGame',
    'description' => '',
    'type' => 'game',
    'updateGameProgression' => true,
    'action' => 'stPreEndOfGame',
    'transitions' => [
      'brisingamens' => ST_BRISINGAMENS_DISCARD,
      'end' => ST_GAME_END,
    ],
  ],

  ST_BRISINGAMENS_DISCARD => [
    'name' => 'brisingamensDiscard',
    'description' => clienttranslate('${actplayer} must discard a card (Brisingamens\'s effect)'),
    'descriptionmyturn' => clienttranslate('${you} must choose a card (Brisingamens\'s effect)'),
    'type' => 'activeplayer',
    'args' => 'argBrisingamensDiscard',
    'possibleactions' => ['discard', 'actUseThorPower'],
    'transitions' => ['recruitDone' => ST_GAME_END],
  ],

  // Final state.
  // Please do not modify (and do not overload action/args methods).
  ST_GAME_END => [
    'name' => 'gameEnd',
    'description' => clienttranslate('End of game'),
    'type' => 'manager',
    'action' => 'stGameEnd',
    'args' => 'argGameEnd',
  ],
];
