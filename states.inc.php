<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * states.inc.php
 *
 * Nidavellir game states description
 *
 */

 $machinestates = [

     // The initial state. Please do not modify.
     ST_GAME_SETUP => [
         "name" => "gameSetup",
         "description" => "",
         "type" => "manager",
         "action" => "stGameSetup",
         "transitions" => [
           "" => ST_START_OF_TURN
         ]
     ],

     // Player start of turn => depending on variant, go to assam or to roll dice
     ST_START_OF_TURN => [
       "name" => "startOfTurn",
       "description" => "",
       "type" => "game",
       "action" => "stStartOfTurn",
       "transitions" => [
       ]
     ],

     // Check for end or go to next player
     ST_NEXT_PLAYER => [
       "name" => "nextPlayer",
       "description" => '',
       "type" => "game",
       "action" => "stNextPlayer",
       "updateGameProgression" => true,
       "transitions" => [
         "endGame" => ST_GAME_END,
         "startTurn" => ST_START_OF_TURN
       ]
     ],


     // Final state.
     // Please do not modify (and do not overload action/args methods).
     ST_GAME_END => [
         "name" => "gameEnd",
         "description" => clienttranslate("End of game"),
         "type" => "manager",
         "action" => "stGameEnd",
         "args" => "argGameEnd"
     ]
 ];
