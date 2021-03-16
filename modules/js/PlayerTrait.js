define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.playerTrait", null, {
    constructor(){
      this._notifications.push(
        ['updateScores', 100],
      );
      this._scoresCounters = {};
      this._ranksCounters = {};
      this._overviewCounters = {};
    },

    setupPlayers(){
      let players = Object.values(this.gamedatas.players);
      var nPlayers = players.length;
      var currentPlayerNo = players.reduce((carry, player) => (player.id == this.player_id) ? player.no : carry, 0);

      dojo.attr('overall-content', 'data-nplayers', nPlayers);
      this.forEachPlayer(player => {
        player.no = (player.no + nPlayers - currentPlayerNo) % nPlayers;

        this.place('jstpl_playerPanel', player, "overall_player_board_" + player.id);
        dojo.attr('overall_player_board_' + player.id, "data-color", player.color);

        // Gem
        this.place("jstpl_gemContainer", player, "player_board_" + player.id);
        this.place('jstpl_gem', { value : player.gem }, 'gem-container-' + player.id);

        // Coins/bids
        Object.values(player.coins).forEach(coin => this.addCoin(coin));

        // Board
        this.place('jstpl_playerBoard', player, "player-boards");
        Object.values(player.cards).forEach(card => this.addCard(card, card.location) );
      });

      this.setupPlayersScores();
      this.updatePlayersScores();
      this.updatePlayersOrder();
    },

    setupPlayersScores(){
      this.forEachPlayer(player => {
        this._scoresCounters[player.id] = {};
        this._ranksCounters[player.id] = {};
        this._overviewCounters[player.id] = {};

        for(var i = 0; i < 8; i++){
          if(i < 6) {
            this._scoresCounters[player.id][i] = new ebg.counter();
            this._scoresCounters[player.id][i].create('command-zone-score_' + player.id + '_' + i);

            this._ranksCounters[player.id][i] = new ebg.counter();
            this._ranksCounters[player.id][i].create('command-zone-ranks_' + player.id + '_' + i);
          }

          this._overviewCounters[player.id][i] = new ebg.counter();
          this._overviewCounters[player.id][i].create('overview-' + player.id + '-' + i);
        }

        this._overviewCounters[player.id]['total'] = new ebg.counter();
        this._overviewCounters[player.id]['total'].create('overview-' + player.id + '-total');
      });
    },

    updatePlayersScores(){
      this.forEachPlayer(player => {
        for(var i = 0; i < 8; i++){
          if(i < 6){
            this._scoresCounters[player.id][i].toValue(player.scores[i]);
            this._ranksCounters[player.id][i].toValue(player.ranks[i]);
          }

          this._overviewCounters[player.id][i].toValue(player.scores[i]);
        }
        this._overviewCounters[player.id]["total"].toValue(player.scores['total']);

        // Blacksmith and hunter counter
        dojo.attr("blacksmith-score-helper-" + player.id, "data-line", player.ranks[1]);
        dojo.attr("hunter-score-helper-" + player.id, "data-line", player.ranks[2]);
      });
    },

    notif_updateScores(n){
      debug("Notif: updating scores", n);
      this.forEachPlayer(player => {
        this.gamedatas.players[player.id].scores = n.args.scores[player.id];
        this.gamedatas.players[player.id].ranks = n.args.ranks[player.id];
        this.scoreCtrl[player.id].toValue(n.args.scores[player.id].total);
      });
      this.updatePlayersScores();
    },


    updatePlayersOrder(){
      if(this.gamedatas.tavern == -1)
        return;

      // Clear (useful if zombie)
      this.forEachPlayer(player => dojo.attr("bids-zone-" + player.id, "data-order", -1));

      if(this.gamedatas.order != null){
        // Update
        this.gamedatas.order.forEach((pId, i) => {
          dojo.attr("bids-zone-" + pId, "data-order", i);
        })
      }
    },
  });
});
