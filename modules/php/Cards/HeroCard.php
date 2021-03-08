<?php
namespace NID\Cards;
use Nidavellir;

/*
 * HeroCard: basic holder for hero card
 */

abstract class HeroCard extends AbstractCard
{
  protected $subname = '';
  protected $expansion = false;
  protected $advanced = false;
  protected $heroClass = null;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = HERO;
  }

  public function isSupported($options){
    return !$this->advanced || $options[OPTION_SETUP] == NORMAL;
    // TODO : handle expansion
  }

  public function getUiData() {
    $data = parent::getUiData();
    $data['subname'] = $this->subname;
    $data['heroClass'] = $this->heroClass;
    return $data;
  }

  public function getRecruitementZone(){
    return $this->heroClass;
  }

  public function updateRanks(&$ranks){
    $ranks[$this->heroClass] += $this->getRanks();
  }

  public function canBeRecruited($player){
    return true;
  }

  public function updateBraveryValues(&$values, $player){
    $values[$this->heroClass] += $this->getBV();
  }

  public function getNotifString(){
    return sprintf( Nidavellir::translate(clienttranslate("a hero (%s)")), $this->name);
  }

}
