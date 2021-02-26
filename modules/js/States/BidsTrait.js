define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.bidsTrait", null, {
    constructor(){
      this._notifications.push(
        ['newTurn', 700],
        ['playerBid', 500],
        ['revealBids', 1000],
        ['recruitStart', 500],
        ['tradeGems', 1500],
        ['clearTurn', 1000],
      );

      this._selectedCoin = null;
      this._tavernBids = [null, null, null];
    },


    notif_newTurn(n){
      debug("Starting a new turn", n);
      n.args.cards.forEach(card => this.addCard(card, card.location, true) );
      this.gamedatas.turn = n.args.turn;
      this.udpateInfoCounters();
    },


    onEnteringStatePlayerBids(args){
      if(!args._private)
        return;

      this.clearPossible();
      if(this.isCurrentPlayerActive()){
        this._bidableCoins = args._private;
        this.clearBidSelection();
        [0,1,2].forEach(tavern => this.connect("bids-drop-zone-" + tavern, "click", () => this.onClickTavernSign(tavern) ));
        this.toggleConfirmBidsBtn();
      }
      else {
        this.clearBidSelection(false);
        this.addSecondaryActionButton("btnChangeBids", _("Change the bids"), () => this.takeAction("changeBids", {}) );
      }
    },

    onLeavingStatePlayerBids(){
      this._tavernBids = [null, null, null];
    },


    onUpdateActivityPlayerBids(arg, status){
      this.onEnteringStatePlayerBids(arg);
    },


    clearBidSelection(makeSelectable = true){
      dojo.query('.coin').removeClass('selected');
      dojo.query(".bids-drop-zone").removeClass("selectable");
      dojo.destroy("btnCancelBid");
      this._selectedCoin = null;
      if(makeSelectable){
        this.makeCoinsSelectable(this._bidableCoins, this.onClickCoinBid.bind(this));
      }
      this.changePageTitle();
    },


    onClickCoinBid(coin){
      let tmp = this._selectedCoin;
      this.clearBidSelection();
      if(tmp != null && coin.id == tmp.id){
        return;
      }

      this._selectedCoin = coin;
      //this.makeCoinsSelectable([coin.id], this.onClickCoinBid.bind(this));
      dojo.addClass("coin-" + coin.id, 'selected');
      dojo.query(".bids-drop-zone").addClass("selectable");
      this.addSecondaryActionButton("btnCancelBid", _("Cancel"), () => this.clearBidSelection());
      this.changePageTitle('placecoin');
    },



    onClickTavernSign(tavern){
      let coin = this._selectedCoin;
      if(coin == null)
        return;

      this.takeAction("playerBid", {
        coinId: coin.id,
        tavern
      });
    },

    notif_playerBid(n){
      debug("Notif : new bid", n);
      let tavern = n.args.tavern,
          coin = n.args.coin;

      // Already a coin here ? Remove it
      if(this._tavernBids[tavern] != null){
        this.slideCoinToPlayerZone(this._tavernBids[tavern]);
      }

      if(coin.location != "hand")
        this._tavernBids[coin.location_arg] = null;
      this._tavernBids[tavern] = coin;
      this.slideCoinToTavernSign(coin, tavern);
      this.toggleConfirmBidsBtn();
    },

    toggleConfirmBidsBtn(){
      // All bids are set ?
      let allSet = this._tavernBids[0] && this._tavernBids[1] && this._tavernBids[2];
      if(allSet){
        this.addPrimaryActionButton("btnConfirmBids", _("Confirm"), () => this.onClickConfirmBids());
      } else {
        dojo.destroy("btnConfirmBids");
      }
    },


    onClickConfirmBids(){
      this.takeAction("confirmBids", {});
    },



    notif_revealBids(n){
      debug("Notif: reveal bids", n);
      Object.values(n.args.coins).forEach(coin => this.addCoin(coin) );
    },

    notif_recruitStart(n) {
      debug("Notif: recruitStart", n);
    },


    notif_tradeGems(n){
      debug("Notif: trading gems", n);
      n.args.trades.forEach(trade => {
        // [p1_id, p1_gem, p2_id, p2_gem]
        this.slide('gem-' + trade[1], 'gem-container-' + trade[2]);
        this.slide('gem-' + trade[3], 'gem-container-' + trade[0]);
      })
    },


    notif_clearTurn(n){
      debug("Clearing turn", n);
      dojo.query('.tavern-cards-holder .card').forEach(this.slideToRightAndDestroy);
      this.forEachPlayer(player => {
        dojo.query('#overall_player_board_' + player.id + ' .coin').forEach(coin => {
          this.slide(coin, "coins-zone-" + player.id, {
            duration:600,
          });
        });
      });
      this._tavernBids = [null, null, null];
    },
  });
});
