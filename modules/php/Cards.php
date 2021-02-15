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
    return [
      'id'       => (int) $card['id'],
      'location' => $card['location'],
      'state'    => (int) $card['state'],
      'class'    => (int) $card['class'],
      'grade'    => json_decode($card['grade']),
    ];
  }

  public static function getUiData()
  {
    return self::getInLocation(['tavern', '%'])->toArray();
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
	public static function setupNewGame($players)
  {
		$deck = count($players) == 5? self::$deck5Players : self::$deck;
		self::createDeck($deck, 1);
		$deck[ROYAL_OFFER]++; // One more royal offer at age 2
    self::createDeck($deck, 2);
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
      'tavern_1' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 0]),
      'tavern_2' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 1]),
      'tavern_3' => self::pickForLocation($nCardsPerTavern, ['age', $age], ['tavern', 2]),
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
    // TODO handle heroes
    self::move($card['id'], ["commander-zone", $pId, $card['class']]);
  }

}
