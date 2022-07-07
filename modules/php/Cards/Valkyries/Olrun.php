<?php
namespace NID\Cards\Valkyries;
use NID\Cards;
use NID\Game\Globals;

class Olrun extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = OLRUN;
    $this->name = 'Ölrun';
    $this->tooltip[] = clienttranslate('As soon as you place Ölrun in your Command Zone, place 1 Class token on her.');
    $this->tooltip[] = clienttranslate(
      'Each time you recruit a card with at least a rank of the chosen class in your Army, move down one notch the Strength token on this Valkyrie.'
    );
    $this->forces = [0, 3, 6, 10, 16];
  }

  public function getUiData()
  {
    $data = parent::getUiData();
    $data['olrun'] = Globals::getOlrunClass();
    return $data;
  }

  public function stateAfterRecruit($player)
  {
    return 'olrun';
  }
}
