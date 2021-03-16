<?php

$gameinfos = [
  'game_name' => "Nidavellir",
  'designer' => 'Serge Laget',
  'artist' => 'Jean-Marie Minguez',
  'year' => 2020,
  'publisher' => 'GRRRE Games',
  'publisher_website' => 'https://www.grrre-games.com/',
  'publisher_bgg_id' => 40652,
  'bgg_id' => 293014,

  'players' => [2,3,4,5],
  'suggest_player_number' => 3,
  'not_recommend_player_number' => null,

  'estimated_duration' => 30,
  'fast_additional_time' => 30,
  'medium_additional_time' => 40,
  'slow_additional_time' => 50,

  'tie_breaker_description' => "",
  'losers_not_ranked' => false,
  'solo_mode_ranked' => false,

  'is_beta' => 1,
  'is_coop' => 0,


  'complexity' => 2,
  'luck' => 3,
  'strategy' => 3,
  'diplomacy' => 3,

  'player_colors' => ["ff0000", "008000", "0000ff", "ffa500", "773300"],
  'favorite_colors_support' => true,
  'disable_player_order_swap_on_rematch' => false,

  'game_interface_width' => [
    'min' => 820,
    'max' => null
  ],

  'presentation' => [
    totranslate("Nidavellir, the Dwarf Kingdom, is threatened by the dragon Fafnir. As a venerable Elvaland, you have been appointed by the King. Search through every tavern in the kingdom, hire the most skillful dwarves, recruit the most prestigious heroes, and build the best battalion you can to defeat your mortal enemy!"),
    totranslate("Each turn in Nidavellir, bid a coin on each tavern. In descending order, choose a character and add this character to your army. Each dwarf class has its own scoring way: blacksmith, hunter, warrior, explorer, and miner. A meticulous recruitment will allow you to attract a powerful hero to your army."),
    totranslate("You will also be able to increase the value of your gold coins thanks to the smart 'coin-building' system, and get the best of the other Elvalands.")
  ],
  'tags' => [3,11,100,106],


//////// BGA SANDBOX ONLY PARAMETERS (DO NOT MODIFY)
'is_sandbox' => false,
'turnControl' => 'simple'
////////
];
