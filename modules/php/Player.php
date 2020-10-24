<?php
namespace NID;
use Nidavellir;

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

  public function __construct($row) {
    if($row != null) {
      $this->id = $row['player_id'];
      $this->no = (int)$row['player_no'];
      $this->name = $row['player_name'];
      $this->color = $row['player_color'];
      $this->eliminated = $row['player_eliminated'] == 1;
      $this->zombie = $row['player_zombie'] == 1;
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

/*
  public function getCardsInHand($formatted = false){ return BangCardManager::getHand($this->id, $formatted); }
  public function getCardsInPlay(){ return BangCardManager::getCardsInPlay($this->id); }
  public function countCardsInHand() { return BangCardManager::countCards("hand", $this->id);}
*/

  public function getUiData($currentPlayerId = null) {
    $current = $this->id == $currentPlayerId;
    return [
      'id'        => $this->id,
      'eliminated'=> $this->eliminated,
      'no'        => $this->no,
      'name'      => $this->getName(),
      'color'     => $this->color,
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



/*************************
********** Utils *********
*************************/

}
