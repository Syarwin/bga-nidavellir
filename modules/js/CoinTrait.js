define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.coinTrait", null, {
    constructor(){
      this._notifications.push(
        ['tradeCoin', 1000]
      );

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
          dojo.place('<div class="treasure-slot" id="treasure-slot-' + slot +'"></div>', oRow);
        })
      })

      Object.values(this.gamedatas.royalTreasure).forEach(coin => this.addCoin(coin));
    },


    getCoinContainer(coin){
      let container = null;

      if(coin.pId != 0){
        // Belongs to some player
        container = 'coins-zone-' + coin.pId;

        if(coin.location == "bid"){
          let tavern = coin.location_arg;
          container = "tavern-coin-holder-" + tavern;
          // Make it work with bids
          this._tavernBids[tavern] = coin;
        }
        else if(coin.location == "tavern"){
          container = "bids-zone-" + coin.location_arg + "-" + coin.pId;
        }
      } else if(coin.location == "treasure"){
        // Belongs to royal treasure
        container = 'treasure-slot-' + coin.value;
      }

      return container;
    },

    addCoin(coin, container = null){
      if(container == null){
        container = this.getCoinContainer(coin);
      }

      if($('coin-' + coin.id)){
        this.slide('coin-' + coin.id, container, {})
        .then( () => this.attachToNewParent('coin-' + coin.id, container) );
      } else {
        this.place('jstpl_coin', coin, container);
        dojo.connect($('coin-' + coin.id), "click", (evt) => this.onClickCoin(coin, evt) );
      }
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
      this.slide(oldCoin, target, {
        duration:600,
      });
    },


    slideCoinToTavernSign(coin, tavern){
      let coinDiv = "coin-" + coin.id,
        tavernTarget = "tavern-coin-holder-" + tavern;

      coin.location = tavern;
      this.slide(coinDiv, tavernTarget, {
        pos:{x: 10, y:10},
        duration:600,
      }).then(() => {
        this.clearBidSelection();
      })
    },


    notif_tradeCoin(n){
      debug("Notif: trading coins", n);

      // Slide new coin
      this.slide('coin-' + n.args.new.id, 'coins-zone-' + n.args.player_id, {
        duration:1000,
      });

      // Slide old coin depending on his type
      if(n.args.max.type == 1){ // TREASURE
        this.slide('coin-' + n.args.max.id, 'treasure-slot-' + n.args.max.value);
      } else {
        // Slide to tower and destroy
        this.slide('coin-' + n.args.max.id, 'treasure', {
          destroy: true,
        });
      }
    },
  });
});
