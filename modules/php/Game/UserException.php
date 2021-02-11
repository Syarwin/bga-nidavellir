<?php
namespace NID\Game;
use Nidavellir;

class UserException extends \BgaUserException {
  public function __construct($str)
  {
    parent::__construct(Nidavellir::translate($str));
  }
}
?>
