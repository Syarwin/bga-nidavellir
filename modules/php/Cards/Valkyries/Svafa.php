<?php
namespace NID\Cards\Valkyries;
use NID\Cards;

class Svafa extends ValkyrieCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = SVAFA;
    $this->name = 'Svafa';
    $this->tooltip[] = clienttranslate(
      'Each time you realise a betterment (cf Glossary) while trading or transforming a coin, move down one notch the Strength token on this Valkyrie per point of betterment'
    );
    $this->forces = [0, 4, 8, 16];
  }
}
