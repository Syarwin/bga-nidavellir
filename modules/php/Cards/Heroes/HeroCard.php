<?php
namespace NID\Cards\Heroes;
use Nidavellir;

/*
 * HeroCard: basic holder for hero card
 */

abstract class HeroCard extends \NID\Cards\AbstractCard
{
  protected $subname = '';
  protected $thingvellir = false;
  protected $advanced = false;
  protected $heroClass = null;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = HERO;
  }

  public function isSupported($options){
    return (!$this->advanced && !$this->thingvellir)
      || ($this->advanced && $options[OPTION_SETUP] == NORMAL)
      || ($this->thingvellir && $options[OPTION_THINGVELLIR] == THINGVELLIR);
  }

  public function isDiscardable() { return false; }

  public function getUiData() {
    $data = parent::getUiData();
    $data['subname'] = $this->subname;
    $data['heroClass'] = $this->heroClass;
    return $data;
  }

  public function getRecruitementZone(){
    return $this->heroClass;
  }

  public function hasRankOfClass($class){
    return $this->heroClass == $class;
  }

  public function updateRanks(&$ranks,$uselessExceptThrud){
    $ranks[$this->heroClass] += $this->getRanks();
  }

  public function updateBraveryValues(&$values, $player){
    $values[$this->heroClass] += $this->getBV();
  }

  public function getNotifString(){
    return [
      'log' => clienttranslate('a hero (${hero_name})'),
      'args' => [
        'hero_name' => $this->name,
      ]
    ];
  }

}
