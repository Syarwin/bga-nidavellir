define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.bidsTrait", null, {
    constructor(){
      this._notifications.push(
        ['playerBid', 500],
        ['revealBids', 800],
        ['recruitStart', 500]
      );

      this._selectedCoin = null;
      this._tavernBids = [null, null, null];
    },


    onEnteringStatePlayerBids(args){
      if(!args._private)
        return;

      this.clearPossible();
      if(this.isCurrentPlayerActive()){
        this._bidableCoins = args._private;
        this.clearBidSelection();
        [0,1,2].forEach(tavern => this.connect("tavern-coin-holder-" + tavern, "click", () => this.onClickTavernSign(tavern) ));
        this.toggleConfirmBidsBtn();
      }
      else {
        this.clearBidSelection(false);
        this.addSecondaryActionButton("btnChangeBids", _("Change the bids"), () => this.takeAction("changeBids", {}) );
      }
    },

    onUpdateActivityPlayerBids(arg, status){
      this.onEnteringStatePlayerBids(arg);
    },


    clearBidSelection(makeSelectable = true){
      dojo.query('.coin').removeClass('selected');
      dojo.query(".tavern-coin-holder").removeClass("selectable");
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
      this.makeCoinsSelectable([coin.id], this.onClickCoinBid.bind(this));
      dojo.addClass("coin-" + coin.id, 'selected');
      dojo.query(".tavern-coin-holder").addClass("selectable");
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

    notif_recruitStart(args) {
      debug("notif: recruitStart", args);
    },
  });
});
