<?php
namespace NID\Cards;

/*
 * HeroCard: basic holder for hero card
 */

abstract class DistinctionCard extends AbstractCard
{
  protected $distinctionClass;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = DISTINCTION;
    $this->grade = [
      [null],
    ];
  }


  public function getUiData() {
    $data = parent::getUiData();
    $data['distinctionClass'] = $this->distinctionClass;
    return $data;
  }

  public function updateRanks(&$ranks){}

  public function getRecruitementZone(){
    return NEUTRAL;
  }

  public function getDistinctionClass(){
    return $this->distinctionClass;
  }

  public function applyEffect(){}

  public function getNotifString(){
    $basicNames = [
      BLACKSMITH => clienttranslate('blacksmith'),
      HUNTER => clienttranslate('hunter'),
      EXPLORER => clienttranslate('explorer'),
      MINER => clienttranslate('miner'),
      WARRIOR => clienttranslate('warrior'),
    ];

    return $basicNames[$this->getDistinctionClass()];
  }

  public function getNotifSymbol(){
    return $this->getDistinctionClass();
  }
}
