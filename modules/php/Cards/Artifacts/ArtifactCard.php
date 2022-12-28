<?php
namespace NID\Cards\Artifacts;
use Nidavellir;

/*
 * ArtifactCard: all utility functions concerning an artifact
 */

class ArtifactCard extends \NID\Cards\AbstractCard
{
  protected $age;

  public function __construct($row)
  {
    parent::__construct($row);
    $this->class = ARTIFACT;
  }

  public function getAge()
  {
    return $this->age;
  }

  public function getRecruitementZone()
  {
    return NEUTRAL;
  }

  public function getNotifSymbol()
  {
    return ARTIFACT;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
  }

  public function updateBraveryValues(&$values, $player)
  {
  }

  public function getNotifString()
  {
    return [
      'log' => clienttranslate('an artifact (${artifact_name})'),
      'args' => [
        'i18n' => ['artifact_name'],
        'artifact_name' => $this->name,
      ],
    ];
  }
}
