<?php
namespace NID;
use Nidavellir;
use NID\Game\Players;
use NID\Game\Globals;

/*
 * Cards: all utility functions concerning cards
 */

class Cards extends Helpers\Pieces
{
	protected static $table = "card";
	protected static $prefix = "card_";
  protected static $customFields = ['class', 'grade'];
  protected static function cast($card){
    if(in_array($card['class'], [BLACKSMITH, HUNTER, WARRIOR, MINER, EXPLORER, ROYAL_OFFER]) )
      return new \NID\Cards\DwarfCard($card);
    else if($card['class'] == HERO)
      return self::getHero($card['id'], $card);
  }

  public static function getUiData()
  {
    return [
      'taverns' => self::getInLocation(['tavern', '%'])->ui(),
      'hall' => self::getInLocation('hall')->ui(),
    ];
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
    ROYAL_OFFER => 1,
	];
	private static $deck5Players = [
		BLACKSMITH  => 10,
		HUNTER 		  => 8,
		WARRIOR     => [3,4,5,6,6,7,8,9,10],
		MINER			  => [0,0,0,1,1,1,2,2],
		EXPLORER    => [5,6,7,8,9,10,11,12],
		ROYAL_OFFER => 2,
	];


  /*
   * Create one deck for age $age
   */
	private static function createDeck($deck, $age){
		$cards = [];
		foreach($deck as $class => $copies){
			$info = [
				'class' => $class,
				'grade' => json_encode($class == ROYAL_OFFER? [] : [null]),
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

  /*
   * Create both age1 and age2 decks
   */
	public static function setupNewGame($players, $options)
  {
		$deck = count($players) == 5? self::$deck5Players : self::$deck;
		self::createDeck($deck, 1);
		$deck[ROYAL_OFFER]++; // One more royal offer at age 2
    self::createDeck($deck, 2);

    self::createHeroes($options);
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

    return [
      'tavern_1' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 0])->ui(),
      'tavern_2' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 1])->ui(),
      'tavern_3' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 2])->ui(),
    ];
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
  public static function recruit($card, $pId)
  {
    self::move($card->getId(), ["command-zone", $pId, $card->getRecruitementZone()] );
  }


  public static function getOfPlayer($pId)
  {
    return self::getInLocation(['command-zone', $pId, '%']);
  }


  public static function getRecruitableHeroes($player)
  {
    $heroes = self::getInLocation('hall');
    return $heroes->filter(function($hero) use ($player){ return $hero->canBeRecruited($player); });
  }
}
