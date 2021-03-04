<?php
namespace NID\Cards;
use NID\Cards;
use NID\Game\Notifications;

class DistinctionExplorer extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = EXPLORER;
  }

  public function applyTieEffect(){
    $card = Cards::getTopOf(['age', 2], 1);
    Cards::discard([$card->getId()]);
    Notifications::discardCardExplorerDistinction($card);
  }


  public function stateAfterRecruit(){
    return 'explorer';
  }
}
