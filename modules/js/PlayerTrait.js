define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.playerTrait", null, {
    constructor(){
      /*
      this._notifications.push(
        ['newHand', 100],
        ['giveCard', 1000],
        ['receiveCard', 1000]
      );
      this._callbackOnCard = null;
      this._selectableCards = [];
      */
    },

    setupPlayers(){
      Object.values(this.gamedatas.players).forEach(player => {
        this.place('jstpl_playerPanel', player, "overall_player_board_" + player.id);

        // TODO : remove
        for(var i = 1; i <= 5; i++){
          var n = Math.ceil(Math.random() * 3);
          for(var j = 0; j < n; j++){
            dojo.place('<div></div>', "command-zone-" + player.id + "-" + i);
          }
        }

        // Gem
        this.place("jstpl_gemContainer", player, "player_board_" + player.id);
        this.place('jstpl_gem', { value : player.gem }, 'gem-container-' + player.id);

        if(this.player_id == player.id){
          Object.values(player.coins).forEach(coin => this.addCoin(coin))
        }
      });
    },

  });
});
