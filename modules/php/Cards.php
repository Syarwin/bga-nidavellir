<?php
namespace NID;
use Nidavellir;
use NID\Game\Players;
use NID\Game\Globals;
use NID\Game\Notifications;

/*
 * Cards: all utility functions concerning cards
 */

class Cards extends Helpers\Pieces
{
  protected static $table = 'card';
  protected static $prefix = 'card_';
  protected static $customFields = ['class', 'grade'];
  protected static function cast($card)
  {
    if (in_array($card['class'], [BLACKSMITH, HUNTER, WARRIOR, MINER, EXPLORER])) {
      return new \NID\Cards\DwarfCard($card);
    } elseif ($card['class'] == ROYAL_OFFER) {
      return new \NID\Cards\RoyalOfferingCard($card);
    } elseif ($card['class'] == HERO) {
      return self::getHero($card['id'], $card);
    } elseif ($card['class'] == DISTINCTION) {
      return self::getDistinction($card['id'], $card);
    }

    // Thingvellir
    elseif ($card['class'] == ARTIFACT) {
      return self::getArtifact($card['id'], $card);
    } elseif ($card['class'] == MERCENARY) {
      return self::getMercenary($card['id'], $card);
    }

    // Idavoll
    elseif ($card['class'] == ANIMAL) {
      return self::getAnimal($card['id'], $card);
    } elseif ($card['class'] == ASE) {
      return self::getAse($card['id'], $card);
    }
  }

  public static function getUiData()
  {
    return [
      'taverns' => self::getInLocation(['tavern', '%'])->ui(),
      'hall' => self::getInLocation('hall')->ui(),
      'evaluation' => self::getInLocation('evaluation')->ui(),
      'camp' => self::getInLocation('camp')->ui(),
      'discard' => self::getInLocation('discard')->ui(),
    ];
  }

  public static function refresh(&$card)
  {
    $card = self::get($card->getId());
  }

  /*
   * Create both age1 and age2 decks, heroes and distinctions
   */
  public static function setupNewGame($players, $options)
  {
    $deck = count($players) == 5 ? self::$deck5Players : self::$deck;
    self::createDeck($deck, 1);

    $deck[ROYAL_OFFER] = count($players) == 5 ? [5, 5, 5] : [5, 5]; // One more royal offer at age 2
    self::createDeck($deck, 2);

    self::createHeroes($options);

    self::createDistinctions();

    if ($options[OPTION_THINGVELLIR] == THINGVELLIR) {
      self::createCamp();
    }

    if ($options[OPTION_IDAVOLL] == IDAVOLL) {
      self::createMythology();
    }
  }

  /*********************
   ***** DECK SETUP *****
   *********************/
  private static $deck = [
    BLACKSMITH => 8,
    HUNTER => 6,
    WARRIOR => [3, 4, 5, 6, 6, 7, 8, 9],
    MINER => [0, 0, 1, 1, 2, 2],
    EXPLORER => [5, 6, 7, 8, 9, 10, 11],
    ROYAL_OFFER => [3],
  ];
  private static $deck5Players = [
    BLACKSMITH => 10,
    HUNTER => 8,
    WARRIOR => [3, 4, 5, 6, 6, 7, 8, 9, 10],
    MINER => [0, 0, 0, 1, 1, 1, 2, 2],
    EXPLORER => [5, 6, 7, 8, 9, 10, 11, 12],
    ROYAL_OFFER => [3, 3],
  ];

  /*
   * Create one deck for age $age
   */
  private static function createDeck($deck, $age)
  {
    $cards = [];
    foreach ($deck as $class => $copies) {
      $info = [
        'class' => $class,
        'grade' => json_encode([null]),
      ];

      if (is_array($copies)) {
        foreach ($copies as $bp) {
          $info['grade'] = json_encode([$bp]);
          array_push($cards, $info);
        }
      } else {
        $info['nbr'] = $copies;
        array_push($cards, $info);
      }
    }

    self::create($cards, ['age', $age]);
    self::shuffle(['age', $age]);
  }

  // TEST ONLY
  public static function addClass($pId, $class)
  {
    $card = self::getSelectQuery()
      ->where('class', $class)
      ->where('card_location', 'age_1')
      ->limit(1)
      ->getSingle();
    self::recruit($card, $pId);
  }

  /****************
   ***** HEROS *****
   ****************/
  public static $heroes = [
    KRAAL => 'Kraal',
    TARAH => 'Tarah',
    ARAL => 'Aral',
    DAGDA => 'Dagda',
    AEGUR => 'Aegur',
    BONFUR => 'Bonfur',
    ZORAL => 'Zoral',
    LOKDUR => 'Lokdur',
    HOURYA => 'Hourya',
    IDUNN => 'Idunn',

    DWERG1 => 'Dwerg1',
    DWERG2 => 'Dwerg2',
    DWERG3 => 'Dwerg3',
    DWERG4 => 'Dwerg4',
    DWERG5 => 'Dwerg5',

    SKAA => 'Skaa',
    ASTRID => 'Astrid',
    GRID => 'Grid',
    ULINE => 'Uline',
    YLUD => 'Ylud',
    THRUD => 'Thrud',

    ANDUMIA => 'Andumia',
    HOLDA => 'Holda',
    KHRAD => 'Khrad',
    JARIKA => 'Jarika',
    OLWYN => 'Olwyn',
    OLWYN_DOUBLE1 => 'OlwynDouble1',
    OLWYN_DOUBLE2 => 'OlwynDouble2',
    ZOLKUR => 'Zolkur',
  ];

  public static function getHero($id, $row = null)
  {
    $className = '\NID\Cards\Heroes\\' . self::$heroes[$id];
    return new $className($row);
  }

  public static function createHeroes($options)
  {
    $values = [];
    foreach (self::$heroes as $hId => $class) {
      if (in_array($hId, [OLWYN_DOUBLE1, OLWYN_DOUBLE2])) {
        continue;
      }

      $hero = self::getHero($hId);
      $values[] = [$hId, $hero->isSupported($options) ? 'hall' : 'box', HERO, null];
    }

    // Adding Olwyn's doubles in a hidden location
    $values[] = [OLWYN_DOUBLE1, 'pending', HERO, null];
    $values[] = [OLWYN_DOUBLE2, 'pending', HERO, null];

    self::DB()
      ->multipleInsert(['card_id', 'card_location', 'class', 'grade'])
      ->values($values);
  }

  public function getOwner($cardId)
  {
    try {
      return self::get($cardId)->getPID();
    } catch (\feException $e) {
      return null;
    }
  }

  public function getUlineOwner()
  {
    return self::getOwner(ULINE);
  }
  public function getYludOwner()
  {
    return self::getOwner(YLUD);
  }
  public function getThrudOwner()
  {
    return self::getOwner(THRUD);
  }
  public function getJarikaOwner()
  {
    return self::getOwner(JARIKA);
  }
  public function getFafnirOwner()
  {
    return self::getOwner(FAFNIR);
  }
  public function getMegingjordOwner()
  {
    return self::getOwner(MEGINGJORD);
  }
  public function getZolkurOwner()
  {
    return self::getOwner(ZOLKUR);
  }
  public function getHofudOwner()
  {
    return self::getOwner(HOFUD);
  }
  public function getBrisingamensOwner()
  {
    return self::getOwner(BRISINGAMENS);
  }
  public function getDurathorOwner()
  {
    return self::getOwner(DURATHOR);
  }
  public function getRatatoskOwner()
  {
    return self::getOwner(RATATOSK);
  }
  public function getGarmkOwner()
  {
    return self::getOwner(GARM);
  }

  /*********************
   **** DISTINCTIONS ****
   *********************/
  public static $distinctions = [
    DISTINCTION_WARRIOR => 'DistinctionWarrior',
    DISTINCTION_HUNTER => 'DistinctionHunter',
    DISTINCTION_MINER => 'DistinctionMiner',
    DISTINCTION_BLACKSMITH => 'DistinctionBlacksmith',
    DISTINCTION_EXPLORER => 'DistinctionExplorer',
  ];

  public static function createDistinctions()
  {
    $values = [
      [DISTINCTION_WARRIOR, 'evaluation', DISTINCTION, null],
      [DISTINCTION_HUNTER, 'evaluation', DISTINCTION, null],
      [DISTINCTION_MINER, 'evaluation', DISTINCTION, null],
      [DISTINCTION_BLACKSMITH, 'evaluation', DISTINCTION, null],
      [DISTINCTION_BLACKSMITH_SPECIAL, 'pending', BLACKSMITH, json_encode([null, null])],
      [DISTINCTION_EXPLORER, 'evaluation', DISTINCTION, null],
    ];
    self::DB()
      ->multipleInsert(['card_id', 'card_location', 'class', 'grade'])
      ->values($values);
  }

  public static function getDistinction($id, $row = null)
  {
    $className = '\NID\Cards\Distinctions\\' . self::$distinctions[$id];
    return new $className($row);
  }

  public static function getDistinctionCard($i)
  {
    if ($i > 5 || $i == 0) {
      return null;
    }
    $ids = [
      null,
      DISTINCTION_WARRIOR,
      DISTINCTION_HUNTER,
      DISTINCTION_MINER,
      DISTINCTION_BLACKSMITH,
      DISTINCTION_EXPLORER,
    ];
    return self::get($ids[$i]);
  }

  /*************
   **************
   **** CAMP ****
   **************
   *************/
  public static $artifacts = [
    FAFNIR => 'Fafnir',
    DRAUPNIR => 'Draupnir',
    VEGVISIR => 'Vegvisir',
    SVALINN => 'Svalinn',
    MEGINGJORD => 'Megingjord',
    VIDOFNIR => 'Vidofnir',
    BRISINGAMENS => 'Brisingamens',
    MJOLLNIR => 'Mjollnir',
    HOFUD => 'Hofud',
    HRAFNSMERKI => 'Hrafnsmerki',
    JARNGLOFI => 'Jarnglofi',
    GJALLARHORN => 'Gjallarhorn',
  ];

  public static $mercenaries = [
    MERCENARY_0 => 'Mercenary0',
    MERCENARY_1 => 'Mercenary1',
    MERCENARY_2 => 'Mercenary2',
    MERCENARY_3 => 'Mercenary3',
    MERCENARY_4 => 'Mercenary4',
    MERCENARY_5 => 'Mercenary5',
    MERCENARY_6 => 'Mercenary6',
    MERCENARY_7 => 'Mercenary7',
    MERCENARY_8 => 'Mercenary8',
    MERCENARY_9 => 'Mercenary9',
    MERCENARY_10 => 'Mercenary10',
    MERCENARY_11 => 'Mercenary11',
  ];

  public static function createCamp()
  {
    $values = [];
    foreach (self::$artifacts as $aId => $class) {
      $artifact = self::getArtifact($aId);
      $values[] = [$aId, 'campdeck_' . $artifact->getAge(), ARTIFACT, null];
    }
    foreach (self::$mercenaries as $mId => $class) {
      $mercenary = self::getMercenary($mId);
      $values[] = [$mId, 'campdeck_' . $mercenary->getAge(), MERCENARY, null];
    }
    self::DB()
      ->multipleInsert(['card_id', 'card_location', 'class', 'grade'])
      ->values($values);
    self::shuffle('campdeck_1');
    self::shuffle('campdeck_2');
  }

  public static function getArtifact($id, $row = null)
  {
    $className = '\NID\Cards\Artifacts\\' . self::$artifacts[$id];
    return new $className($row);
  }

  public static function getMercenary($id, $row = null)
  {
    $className = '\NID\Cards\Mercenaries\\' . self::$mercenaries[$id];
    return new $className($row);
  }

  /*******************
   *******************
   **** MYTHOLOGY ****
   *******************
   ******************/
  public static $animals = [
    DURATHOR => 'Durathor',
    GARM => 'Garm',
    HREASVELG => 'Hreasvelg',
    NIDHOGG => 'Nidhogg',
    RATATOSK => 'Ratatosk',
  ];

  public static $ases = [
    FRIGG => 'Frigg',
    THOR => 'Thor',
    FREYA => 'Freya',
    LOKI => 'Loki',
    ODIN => 'Odin',
  ];

  public static function createMythology()
  {
    $values = [];
    foreach (self::$animals as $aId => $class) {
      $values[] = [$aId, 'mythology', ANIMAL, null];
    }
    foreach (self::$ases as $aId => $class) {
      $values[] = [$aId, 'mythology', ASE, null];
    }

    self::DB()
      ->multipleInsert(['card_id', 'card_location', 'class', 'grade'])
      ->values($values);
    self::shuffle('mythology');
  }

  public static function getAnimal($id, $row = null)
  {
    $className = '\NID\Cards\Animals\\' . self::$animals[$id];
    return new $className($row);
  }

  public static function getAse($id, $row = null)
  {
    $className = '\NID\Cards\Ases\\' . self::$ases[$id];
    return new $className($row);
  }

  /****************
   ***** PLAY  *****
   ****************/

  /*
   * Prepare turn : draw the corresponding amount of card from appropriate deck
   */
  public static function startNewTurn()
  {
    $age = Globals::getAge();
    $nPlayers = Players::count();
    $nCardsPerTavern = $nPlayers == 2 ? 3 : $nPlayers;
    $dancingDragonLocation = Globals::getTurn() <= 3 && Globals::isIdavoll() ? 'mythology' : ['age', $age];

    return array_merge(
      self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 0])->ui(),
      self::pickForLocation($nCardsPerTavern, $dancingDragonLocation, ['tavern', 1])->ui(),
      self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 2])->ui(),
      self::pickForLocation(5 - self::countInLocation('camp'), ['campdeck', $age], 'camp')->ui()
    );
  }

  /*
   * Return available cards in tavern
   */
  public static function getInTavern($tavern)
  {
    return self::getInLocation(['tavern', $tavern]);
  }

  /*
   * Return available cards in camp
   */
  public static function getInCamp()
  {
    $camp = self::getInLocation('camp');
    $fcamp = $camp->filter(function ($card) {
      return $card->canBeRecruited(null);
    });
    return array_map(function ($card) {
      return $card->getId();
    }, $fcamp);
  }

  /*
   * Put a card in corresponding player stack of color
   */
  public static function recruit($card, $pId, $forceZone = null)
  {
    if ($card->getClass() == ROYAL_OFFER) {
      self::discard([$card->getId()], true);
    }
    // Put back in the box
    else {
      $location = ['command-zone', $pId, $forceZone ?? $card->getRecruitementZone()];

      $top = self::getTopOf($location);
      if ($top != null && $top->getId() == THRUD && $location[2] != NEUTRAL) {
        self::changeColumn($top, Players::get($pId), NEUTRAL, true);
      }

      self::insertOnTop($card->getId(), $location);
    }
  }

  public static function changeColumn($card, $player, $column, $silent = false)
  {
    self::recruit($card, $player->getId(), $column);
    self::refresh($card); // Update location
    Notifications::changeColumn($player, $card, $silent);
  }

  public static function getOfPlayer($pId, $zone = '%')
  {
    return self::getInLocation(['command-zone', $pId, $zone], null, ['card_state', 'ASC']);
  }

  public static function getRecruitableHeroes($player)
  {
    $heroes = self::getInLocation('hall');
    return $heroes->filter(function ($hero) use ($player) {
      return $hero->canBeRecruited($player);
    });
  }

  public static function clearTavern($tavern)
  {
    self::moveAllInLocation(['tavern', $tavern], 'discard');
  }

  public static function clearTaverns()
  {
    self::clearTavern(0);
    self::clearTavern(1);
    self::clearTavern(2);
  }

  public static function clearCamp()
  {
    self::moveAllInLocation('camp', 'discard_camp');
  }

  public static function getTopOfStacks($pId, $stacks)
  {
    $cards = [];
    foreach ($stacks as $stack) {
      $card = self::getTopOf(['command-zone', $pId, $stack]);
      if ($card != null) {
        $cards[] = $card;
      }
    }
    return $cards;
  }

  public static function discard($cardIds, $putInTheBox = false)
  {
    // $putInTheBox is useful for cards that shouldn't be selectable by Anduma
    self::move($cardIds, $putInTheBox ? 'box' : 'discard');
  }
}
