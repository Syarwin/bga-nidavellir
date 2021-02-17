<?php
namespace NID;
use NID\Coins;
use NID\Game\Globals;

/*
 * Player: all utility functions concerning a player
 */

class Player
{
  protected $id;
  protected $no; // natural order
  protected $name; // player name
  protected $color;
  protected $eliminated = false;
  protected $zombie = false;
  protected $gem = 0;

  public function __construct($row) {
    if($row != null) {
      $this->id = $row['player_id'];
      $this->no = (int)$row['player_no'];
      $this->name = $row['player_name'];
      $this->color = $row['player_color'];
      $this->eliminated = $row['player_eliminated'] == 1;
      $this->zombie = $row['player_zombie'] == 1;
      $this->gem = $row['player_gem'];
    }
  }

  /*
   * Getters
   */
  public function getId(){ return $this->id; }
  public function getNo(){ return $this->no; }
  public function getName(){ return $this->name; }
  public function getColor(){ return $this->color; }
  public function isEliminated(){ return $this->eliminated; }
  public function isZombie(){ return $this->zombie; }

  public function getGem(){ return $this->gem; }

  public function getUiData($currentPlayerId = null) {
    $current = $this->id == $currentPlayerId;
    return [
      'id'        => $this->id,
      'eliminated'=> $this->eliminated,
      'no'        => $this->no,
      'name'      => $this->getName(),
      'color'     => $this->color,
      'coins'     => $this->getVisibleCoins($current),
      'cards'     => $this->getCards(),
      'gem'       => $this->gem,
    ];
  }


  /**
   * saves eliminated status and hp to the database
   */
  public function save() {
    $eliminated = ($this->eliminated) ? 1 : 0;
    $sql = "UPDATE player SET player_eliminated=$eliminated, player_score= " . $this->hp . " WHERE player_id=" . $this->id;
    self::DbQuery($sql);
  }


  public function getCoins()
  {
    return Coins::getOfPlayer($this->id);
  }

  public function getVisibleCoins($current = false)
  {
    $coins = $this->getCoins();
    return $current? $coins : $coins->filter(function($coin){ return $coin['location'] == "tavern"; });
  }

  public function getCoinIds()
  {
    return Coins::getOfPlayer($this->id)->getIds();
  }

  public function getBids()
  {
    $coins = Coins::getOfPlayer($this->id, 'bid%');
    return $coins;
  }

  public function getBid($currentTavern, $returnCoin = false)
  {
    //$coins = Coins::getOfPlayer($this->id, 'bid_' . $current_tavern);
    $coin = Coins::getInLocationQ(['tavern', $currentTavern])->where('pId', $this->id)->getSingle();
    return $returnCoin? $coin : $coin['value'];
  }


  public function shouldTrade()
  {
    $currentTavern = Globals::getTavern();
    $coin = $this->getBid($currentTavern, true);
    return $coin['value'] == 0 || $coin['type'] == COIN_DISTINCTION;
  }


  public function getCards()
  {
    return Cards::getOfPlayer($this->id);
  }

  public function getHeroes()
  {
    return $this->getCards()->filter(function($card){ return $card['class'] == HERO; });
  }

  public function countHeroes()
  {
    return count($this->getHeroes());
  }

/*************************
********** Utils *********
*************************/

  public function bid($coinId, $tavern)
  {
    Coins::bid($coinId, $this->id, $tavern);
  }

  public function clearBids()
  {
    Coins::clearBids($this->id);
  }
  

  public function recruit($card)
  {
    Cards::recruit($card, $this->id);
  }
}
