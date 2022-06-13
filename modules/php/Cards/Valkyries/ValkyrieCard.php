<?php
namespace NID\Cards\Valkyries;
use Nidavellir;
use NID\Cards;
use NID\Game\Notifications;
use NID\Game\Players;

/*
 * ValkyrieCard: all utility functions concerning a Giant
 */

class ValkyrieCard extends \NID\Cards\AbstractCard
{
  protected $forces = [];

  public function __construct($row)
  {
    parent::__construct($row);
    $this->grade = [null];
    $this->class = VALKYRIE;
    $this->tooltip = [
      clienttranslate('As soon as you recruit a Valkyrie, put her in your Command Zone and place 1 Strength token on the highest notch of the card.'),
    ];
  }

  public function getUiData()
  {
    $data = parent::getUiData();
    $data['forces'] = $this->forces;
    return $data;
  }

  public function updateScores(&$scores, $player)
  {
    $scores[MYTHOLOGY_SCORE] += $this->forces[$this->flag];
  }

  public function increaseForce()
  {
    if ($this->flag + 1 < count($this->forces)) {
      $this->flag++;
      Cards::DB()->update(['flag' => $this->flag], $this->id);
      Notifications::increaseForce(Players::get($this->pId), $this, $this->flag);
    }
  }

  public function getRecruitementZone()
  {
    return NEUTRAL;
  }

  public function getNotifSymbol()
  {
    return VALKYRIE;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
  }

  public function updateBraveryValues(&$values, $player)
  {
  }

  public function getNotifString()
  {
    return sprintf(Nidavellir::translate(clienttranslate('a valkyrie (%s)')), $this->name);
  }
}
