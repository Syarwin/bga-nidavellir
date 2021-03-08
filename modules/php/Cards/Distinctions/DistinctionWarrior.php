<?php
namespace NID\Cards\Distinctions;
use NID\Game\Globals;


class DistinctionWarrior extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = WARRIOR;
    $this->name = clienttranslate("The King's Hand");
    $this->tooltip = [
      clienttranslate("Add +5 immediately to one of your coins."),
    ];
  }


  public function stateAfterRecruit(){
    Globals::setTransformValue(5);
    return 'transform';
  }
}
