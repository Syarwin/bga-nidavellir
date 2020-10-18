<?php
namespace NID;
use Nidavellir;

class NotificationManager extends \APP_DbObject
{
  protected static function notifyAll($name, $msg, $data){
    Nidavellir::get()->notifyAllPlayers($name, $msg, $data);
  }

  protected static function notify($pId, $name, $msg, $data){
    Nidavellir::get()->notifyPlayer($pId, $name, $msg, $data);
  }
}

?>
