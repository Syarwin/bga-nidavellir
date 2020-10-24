<?php
namespace NID;
use Nidavellir;

/*
 * Cards: all utility functions concerning cards
 */

class Cards extends Helpers\Pieces
{
	protected static $table = "card";
	protected static $prefix = "card_";

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


	private static function createDeck($deck, $age){
		$cards = [];
		foreach($deck as $class => $copies){
			$info = [
				'class' => $class,
				'grade' => $class == ROYAL_OFFER? [] : [null],
			];

			if(is_array($copies)){
				foreach($copies as $bp){
					$info['grade'] = [$bp];
					array_push($cards, $info);
				}
			} else {
				$info['nbr'] = $copies;
				array_push($cards, $info);
			}
		}

		self::create($cards, ['age',$age], null, "dwarf{$age}_{INDEX}");
    self::shuffle(['age',$age]);
	}

	public static function setupNewGame($players)	{
		$deck = count($players) == 5? self::$deck5Players : self::$deck;
		self::createDeck($deck, 1);
		$deck[ROYAL_OFFER]++; // One more royal offer at age 2
    self::createDeck($deck, 2);
	}
}
