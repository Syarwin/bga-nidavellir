define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.coinTrait", null, {
    constructor(){
      /*
      this._notifications.push(
        ['newHand', 100],
        ['giveCard', 1000],
        ['receiveCard', 1000]
      );
      */
      this._callbackOnCoin = null;
      this._selectableCoins = [];
    },


    setupRoyalTreasure(){
      let rows = [
        [22, 23, 24, 25],
        [19, 20, 21],
        [15, 16, 17, 18],
        [12, 13, 14],
        [8, 9, 10, 11],
        [5, 6, 7]
      ];

      rows.forEach((row, i) => {
        var oRow = dojo.place('<div class="treasure-row">', 'treasure');
        row.forEach(slot => {
          dojo.place('<div class="treasure-slot" id="treasure-slot-' + slot +'">' + slot + '</div>', oRow);
        })
      })
    },

    addCoin(coin, container = null){
      if(container == null){
        if(coin.pId != null){
          container = 'coins-zone-' + coin.pId;

          if(coin.location.substr(0,3) == "bid"){
            let tavern = parseInt(coin.location.substr(3,1));
            container = "tavern-coin-holder-" + tavern;

            // Make it work with bids
            coin.location = tavern;
            this._tavernBids[tavern] = coin;
          }
        }
      }

      this.place('jstpl_coin', coin, container);
      dojo.connect($('coin-' + coin.id), "click", (evt) => this.onClickCoin(coin, evt) );
    },

    makeCoinsSelectable(coins, callback){
      this._selectableCoins = coins;
      this._callbackOnCoin = callback;

      dojo.query(".coin").removeClass("selectable");
      coins.forEach(coinId => {
        dojo.addClass("coin-" + coinId, "selectable");
      });
    },

    onClickCoin(coin, evt){
      if(this._selectableCoins.includes(coin.id)){
        evt.stopPropagation();
        this._callbackOnCoin(coin);
      }
    },



    slideCoinToPlayerZone(coin){
      let oldCoin = "coin-" + coin.id;
      let target = "coins-zone-" + this.player_id;

      coin.location = "player";
      this.attachToNewParent(oldCoin, "ebd-body");
      this.slide(oldCoin, target, 600).then( () => {
        this.attachToNewParent(oldCoin, target);
        dojo.style(oldCoin, { top:null, left:null, right: null });
        dojo.connect($('coin-' + coin.id), "click", (evt) => this.onClickCoin(coin, evt) );
      });
    },


    slideCoinToTavernSign(coin, tavern){
      let coinDiv = "coin-" + coin.id,
        tavernTarget = "tavern-coin-holder-" + tavern;

      coin.location = tavern;
      this.attachToNewParent(coinDiv, "ebd-body");
      this.slidePos(coinDiv, tavernTarget , 10, 10, 600).then( () => {
        this.attachToNewParent(coinDiv, tavernTarget );
        dojo.style(coinDiv, { top:null, left:null, right: null });
        dojo.connect($('coin-' + coin.id), "click", (evt) => this.onClickCoin(coin, evt) );
        this.clearBidSelection();
      });
    },
  });
});
