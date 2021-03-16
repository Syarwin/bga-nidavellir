<?php
namespace NID\Cards\Artifacts;

/*
 * ArtifactCard: all utility functions concerning an artifact
 */

class ArtifactCard extends \NID\Cards\AbstractCard
{
  protected $age;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = ARTIFACT;
  }

  public function getAge(){ return $this->age; }
}
