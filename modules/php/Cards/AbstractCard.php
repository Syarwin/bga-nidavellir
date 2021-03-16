<?php
namespace NID\Cards;

/*
 * AbstractCard: all utility functions concerning a card : dwarf/improvements or hero
 */

class AbstractCard
{
  protected $id = -1;
  protected $location = '';
  protected $state = 0;
  protected $class = 0;
  protected $grade = [];
  protected $pId = null;
  protected $zone = null;
  protected $name = '';
  protected $tooltip = [];


  public function __construct($row) {
    if($row != null) {
      $data = explode('_', $row['location']);
      $this->id = (int) $row['id'];
      $this->location = $row['location'];
      $this->state = $row['state'];
      $this->class = $row['class'];
      $this->grade = json_decode($row['grade']);

      if($data[0] == "command-zone"){
        $this->pId = $data[1];
        $this->zone = $data[2];
      }
    }
  }

  /*
   * Getters
   */
  public function getId(){ return $this->id; }
  public function getPId(){ return $this->pId; }
  public function getClass(){ return $this->class; }
  public function getRanks(){ return is_array($this->grade)? count($this->grade) : 0; }
  public function getBV(){
    //var_dump($this->grade);
    return is_array($this->grade)?
      array_reduce($this->grade, function($carry, $rank){ return $carry + $rank; }, 0)
      : 0;
  }
  public function getZone(){ return $this->zone; }

  public function applyEffect($player){}

  public function getUiData() {
    return [
      'id'       => $this->id,
      'location' => $this->location,
      'state'    => $this->state,
      'class'    => $this->class,
      'grade'    => $this->grade,
      'name'     => $this->getName(),
      'tooltip'  => $this->getTooltip(),
      'symbol'   => $this->getNotifSymbol(),
    ];
  }

  public function getName(){
    return $this->name;
  }

  public function getTooltip(){
    return $this->tooltip;
  }

  public function getRecruitementZone(){
    return $this->class;
  }

  public function updateRanks(&$ranks){
    $ranks[$this->class] += $this->getRanks();
  }

  public function updateBraveryValues(&$values, $player){
    $values[$this->class] += $this->getBV();
  }

  public function updateScores(&$scores, $player){}


  public function stateAfterRecruit(){
    return null;
  }

  public function getNotifString(){
    $recruitNames = [
      BLACKSMITH => clienttranslate('a blacksmith'),
      HUNTER => clienttranslate('a hunter'),
      EXPLORER => clienttranslate('an explorer'),
      MINER => clienttranslate('a miner'),
      WARRIOR => clienttranslate('a warrior'),
      ROYAL_OFFER => clienttranslate('a royal offering'),
      HERO => clienttranslate('a hero'),
      MERCENARY => clienttranslate('a mercenary'),
    ];

    return $recruitNames[$this->getClass()];
  }

  public function getNotifSymbol(){
    return $this->getRecruitementZone();
  }
}
