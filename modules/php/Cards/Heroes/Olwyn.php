<?php
namespace NID\Cards\Heroes;

class Olwyn extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->expansion = true;
    $this->id = OLWYN;
    $this->name = 'Olwyn';
    $this->subname = clienttranslate("The Illusionist");
    $this->tooltip = [
      clienttranslate("Add 9 points to your Final Bravery Value."),
      clienttranslate("When you recruit him, also take his two doubles whose Bravery value is 0 and then place each of these cards in two different columns of your choice."),
      clienttranslate("Their placement may result in the recruitment of a Hero card"),
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      9
    ];
  }

  public function stateAfterRecruit($player){
    return 'olwyn';
  }
}
