<?php
namespace NID\States;

use Nidavellir;
use NID\NotificationManager;
use NID\PlayerManager;
use NID\Game\Globals;
use NID\Log;

trait RecruitTrait
{
    public function argRecruitDwarf()
    {
        return [
            'tavern' => Globals::getTavern()
        ];
    }

}
