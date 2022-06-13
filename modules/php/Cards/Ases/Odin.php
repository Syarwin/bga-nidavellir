<?php
namespace NID\Cards\Ases;

class Odin extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = ODIN;
    $this->name = 'Odin';
    $this->tooltip[] = clienttranslate(
      'At the end of your turn, you may put one of your Neutral Heroes back in the reserve and recruit another Neutral Hero instead.'
    );
    $this->tooltip[] = clienttranslate('Then, possibly apply the effect of the newly recruited Hero.');
    $this->grade = [0];
  }
}
