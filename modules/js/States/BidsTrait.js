define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.bidsTrait", null, {
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

      this._selectedCoin = null;
      this._tavernBids = [];
    },

    onEnteringStatePlayerBids(args){
      if(!args._private)
        return;

      if(!this.isCurrentPlayerActive()){
        this.waitOtherBids();
        return;
      }

      this._bidableCoins = args._private;
//      this._tavernBids = [null, null, null];
      this.clearBidSelection();
      [0,1,2].forEach(tavern => this.connect("tavern-coin-holder-" + tavern, "click", () => this.onClickTavernSign(tavern) ));
      this.toggleConfirmBidsBtn();
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

      // Already a coin here ? Remove it
      if(this._tavernBids[tavern] != null){
        this.slideCoinToPlayerZone(this._tavernBids[tavern]);
      }

      this._tavernBids[tavern] = this._selectedCoin;
      if(coin.location != "player")
        this._tavernBids[coin.location] = null;
      this.slideCoinToTavernSign(coin, tavern);
      this.toggleConfirmBidsBtn();
    },

    toggleConfirmBidsBtn(){
      // All bids are set ?
      let allSet = this._tavernBids.reduce( (carry, bid) => carry && bid != null, true);
      if(allSet){
        this.addPrimaryActionButton("btnConfirmBids", _("Confirm"), () => this.onClickConfirmBids());
      }
    },


    onClickConfirmBids(){
      this.takeAction("playerBids", { bids: this._tavernBids.map(coin => coin.id).join(";") });
    },



    waitOtherBids(){
      this.clearBidSelection(false);
      this.addSecondaryActionButton("btnChangeBids", _("Change the bids"), () => this.takeAction("changeBids", {}) );
    },
  });
});
