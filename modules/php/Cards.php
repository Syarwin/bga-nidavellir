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
	protected static $table = "card";
	protected static $prefix = "card_";
  protected static $customFields = ['class', 'grade'];
  protected static function cast($card){
    if(in_array($card['class'], [BLACKSMITH, HUNTER, WARRIOR, MINER, EXPLORER]) )
      return new \NID\Cards\DwarfCard($card);
    else if($card['class'] == ROYAL_OFFER)
      return new \NID\Cards\RoyalOfferingCard($card);
    else if($card['class'] == HERO)
      return self::getHero($card['id'], $card);
    else if($card['class'] == DISTINCTION)
      return self::getDistinction($card['id'], $card);
  }

  public static function getUiData()
  {
    return [
      'taverns' => self::getInLocation(['tavern', '%'])->ui(),
      'hall' => self::getInLocation('hall')->ui(),
      'evaluation' => self::getInLocation('evaluation')->ui(),
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
		$deck = count($players) == 5? self::$deck5Players : self::$deck;
		self::createDeck($deck, 1);

		$deck[ROYAL_OFFER] = count($players) == 5? [5,5,5] : [5,5]; // One more royal offer at age 2
    self::createDeck($deck, 2);

    self::createHeroes($options);

    self::createDistinctions();
	}


  /*********************
  ***** DECK SETUP *****
  *********************/
	private static $deck = [
    BLACKSMITH  => 8,
    HUNTER 		  => 6,
    WARRIOR     => [3,4,5,6,6,7,8,9],
    MINER			  => [0,0,1,1,2,2],
    EXPLORER    => [5,6,7,8,9,10,11],
    ROYAL_OFFER => [3],
	];
	private static $deck5Players = [
		BLACKSMITH  => 10,
		HUNTER 		  => 8,
		WARRIOR     => [3,4,5,6,6,7,8,9,10],
		MINER			  => [0,0,0,1,1,1,2,2],
		EXPLORER    => [5,6,7,8,9,10,11,12],
		ROYAL_OFFER => [3,3],
	];


  /*
   * Create one deck for age $age
   */
	private static function createDeck($deck, $age){
		$cards = [];
		foreach($deck as $class => $copies){
			$info = [
				'class' => $class,
				'grade' => json_encode([null]),
			];

			if(is_array($copies)){
				foreach($copies as $bp){
					$info['grade'] = json_encode([$bp]);
					array_push($cards, $info);
				}
			} else {
				$info['nbr'] = $copies;
				array_push($cards, $info);
			}
		}

		self::create($cards, ['age',$age]);
    self::shuffle(['age',$age]);
	}



  // TEST ONLY
  public static function addClass($pId, $class)
  {
    $card = self::getSelectQuery()->where('class', $class)->where('card_location', 'age_1')->limit(1)->getSingle();
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
  ];

  public static function getHero($id, $row = null)
  {
    $className = '\NID\Cards\\' . self::$heroes[$id];
    return new $className($row);
  }


  public static function createHeroes($options)
  {
    $values = [];
    foreach(self::$heroes as $hId => $class){
      $hero = self::getHero($hId);
      if($hero->isSupported($options)){
        $values[] = [
          $hId,
          'hall',
          HERO,
          null
        ];
      }
    }

    self::DB()->multipleInsert(['card_id', 'card_location', 'class', 'grade'])->values($values);
  }



  public function getOwner($cardId)
  {
    return self::get($cardId)->getPId();
  }

  public function getUlineOwner()
  {
    return self::getOwner(ULINE);
  }

  public function getYludOwner()
  {
    return self::getOwner(YLUD);
  }



  /*********************
  **** DISTINCTIONS ****
  *********************/
  public static $distinctions = [
    DISTINCTION_WARRIOR => 'DistinctionWarrior',
    DISTINCTION_HUNTER  => 'DistinctionHunter',
    DISTINCTION_MINER   => 'DistinctionMiner',
    DISTINCTION_BLACKSMITH => 'DistinctionBlacksmith',
    DISTINCTION_EXPLORER => 'DistinctionExplorer',
  ];

  public static function createDistinctions()
  {
    $values = [
      [DISTINCTION_WARRIOR, 'evaluation', DISTINCTION, null ],
      [DISTINCTION_HUNTER, 'evaluation', DISTINCTION, null ],
      [DISTINCTION_MINER, 'evaluation', DISTINCTION, null ],
      [DISTINCTION_BLACKSMITH, 'evaluation', DISTINCTION, null ],
      [DISTINCTION_BLACKSMITH_SPECIAL, 'evaluation', BLACKSMITH, json_encode([null, null])],
      [DISTINCTION_EXPLORER, 'evaluation', DISTINCTION, null ],
    ];
    self::DB()->multipleInsert(['card_id', 'card_location', 'class', 'grade'])->values($values);
  }


  public static function getDistinction($id, $row = null)
  {
    $className = '\NID\Cards\\' . self::$distinctions[$id];
    return new $className($row);
  }

  public static function getDistinctionCard($i)
  {
    if($i > 5 || $i == 0)
      return null;
    $ids = [null, DISTINCTION_WARRIOR, DISTINCTION_HUNTER, DISTINCTION_MINER, DISTINCTION_BLACKSMITH, DISTINCTION_EXPLORER];
    return self::get($ids[$i]);
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

    return array_merge(
      self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 0])->ui(),
      self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 1])->ui(),
      self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 2])->ui()
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
   * Put a card in corresponding player stack of color
   */
  public static function recruit($card, $pId, $forceZone = null)
  {
    if($card->getClass() == ROYAL_OFFER)
      self::move($card->getId(), "discard");
    else {
      $location = ["command-zone", $pId, $forceZone ?? $card->getRecruitementZone()];

      $top = self::getTopOf($location);
      if($top != null && $top->getId() == THRUD && $location[2] != NEUTRAL){
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


  public static function getOfPlayer($pId)
  {
    return self::getInLocation(['command-zone', $pId, '%'], null, ['card_state', 'ASC']);
  }


  public static function getRecruitableHeroes($player)
  {
    $heroes = self::getInLocation('hall');
    return $heroes->filter(function($hero) use ($player){ return $hero->canBeRecruited($player); });
  }


  public static function clearTaverns()
  {
    self::moveAllInLocation(['tavern', '0'], 'discard');
    self::moveAllInLocation(['tavern', '1'], 'discard');
    self::moveAllInLocation(['tavern', '2'], 'discard');
  }


  public static function getTopOfStacks($pId, $stacks)
  {
    $cards = [];
    foreach($stacks as $stack){
      $card = self::getTopOf(['command-zone', $pId, $stack]);
      if($card != null)
        $cards[] = $card;
    }
    return $cards;
  }

  public static function discard($cardIds)
  {
    self::move($cardIds, "discard");
  }
}
