<?php
namespace NID;
use Nidavellir;

/*
 * Coins: all utility functions concerning coins
 */

class Coins extends Helpers\Pieces
{
	protected static $table = "coin";
	protected static $prefix = "coin_";
  protected static $customFields = ['value', 'type'];
  protected static function cast($coin){
    $data = explode('_', $coin['location']);
    return [
      'id'       => (int) $coin['id'],
      'location' => $data[0],
      'pId'      => count($data) > 1? $data[1] : null,
      'value'    => (int) $coin['value'],
      'type'     => (int) $coin['type'], // PLAYER, TREASURE, DISTINCTION
    ];
  }

  /*
   * Royal treasure content
   */
	private static $coins = [
    5 => 2, 6 => 2, 7 => 3, 8 => 2, 9 => 3, 10 => 2, 11 => 3,
    12 => 2, 13 => 2, 14 => 2, 15 => 1, 16 => 2, 17 => 1, 18 => 1,
    19 => 1, 20 => 1, 21 => 1, 22 => 1, 23 => 1, 24 => 1, 25 => 1
	];

  /*
   * Player hand
   */
  private static $playerCoins = [0, 2, 3, 4, 5];


	public static function setupNewGame($players)	{
    // Create default coins
		$deck = self::$coins;
    if(count($players) <= 3){
      $deck[7] = 1;
      $deck[9] = 1;
      $deck[11] = 1;
    }

    $coins = [];
		foreach($deck as $value => $copies){
      array_push($coins, [
        'nbr' => $copies,
				'value' => $value,
				'type' => COIN_TREASURE,
			]);
		}
		self::create($coins, 'treasure');

    // Create player's coin
    foreach($players as $pId => $player){
      $coins = [];
  		foreach(self::$playerCoins as $value){
        array_push($coins, [
  				'value' => $value,
  				'type' => COIN_PLAYER,
  			]);
  		}

      self::create($coins, ['player', $pId]);
    }
	}


  public static function getOfPlayer($pId)
  {
    return self::getInLocation(['%', $pId]);
  }


  public static function bid($coinId, $pId, $tavern)
  {
    self::move($coinId, ['bid'.$tavern, $pId]);
  }
}
