<?php
namespace NID\States;
use NID\Game\Globals;

trait AgeTrait
{
  public function stStartOfAge()
  {
    Globals::startNewAge();
    $this->gamestate->nextState('turn');
  }
}
?>
