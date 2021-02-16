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
      let players = Object.values(this.gamedatas.players);
      var nPlayers = players.length;
      var currentPlayerNo = players.reduce((carry, player) => (player.id == this.player_id) ? player.no : carry, 0);

      players.forEach(player => {
        player.no = (player.no + nPlayers - currentPlayerNo) % nPlayers;

        this.place('jstpl_playerPanel', player, "overall_player_board_" + player.id);

        // Gem
        this.place("jstpl_gemContainer", player, "player_board_" + player.id);
        this.place('jstpl_gem', { value : player.gem }, 'gem-container-' + player.id);

        // Coins/bids
        Object.values(player.coins).forEach(coin => this.addCoin(coin));

        // Board
        this.place('jstpl_playerBoard', player, "player-boards");
        Object.values(player.cards).forEach(card => this.addCard(card, card.location) );
      });
    },

  });
});
