<?php
namespace NID\Cards;

/*
 * AbstractCard: all utility functions concerning a card : dwarf/improvements or hero
 */

abstract class AbstractCard
{
  protected $id = -1;
  protected $location = '';
  protected $state = 0;
  protected $class = 0;
  protected $grade = [];

  public function __construct($row) {
    if($row != null) {
      $this->id = (int) $row['id'];
      $this->location = $row['location'];
      $this->state = $row['state'];
      $this->class = $row['class'];
      $this->grade = json_decode($row['grade']);
    }
  }

  /*
   * Getters
   */
  public function getId(){ return $this->id; }
  public function getClass(){ return $this->class; }
  public function getRanks(){ return is_array($this->grade)? count($this->grade) : 0; }
  public function getBP(){
    return is_array($this->grade)?
      array_reduce($this->grade, function($carry, $rank){ return $carry + $rank; }, 0)
      : 0;
  }

  public function getUiData() {
    return [
      'id'       => $this->id,
      'location' => $this->location,
      'state'    => $this->state,
      'class'    => $this->class,
      'grade'    => $this->grade,
    ];
  }

  public function getRecruitementZone(){
    return $this->class;
  }

  public function updateRanks(&$ranks){
    $ranks[$this->class] += $this->getRanks();
  }
}
