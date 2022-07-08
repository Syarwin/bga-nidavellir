define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.coinTrait", null, {
    constructor(){
      this._notifications.push(
        ['tradeCoin', 1500],
        ['discardCoin', 1000],
        ['ulineRecruited', 1000],
      );

      this._callbackOnCoin = null;
      this._selectableCoins = [];
    },


    setupRoyalTreasure(){
      let slots = [
        22, 23, 24, 25,
        18, 19, 20, 21,
        14, 15, 16, 17,
        11, 12, 13,
        8, 9, 10,
        5, 6, 7
      ];

      slots.forEach(slot => {
        dojo.place('<div class="treasure-slot" id="treasure-slot-' + slot +'"></div>', 'treasure');
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
        this.slide('coin-' + coin.id, container, { duration: 1000 })
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
      let target = "taverns-pre-bids-zone"; //"coins-zone-" + this.player_id;

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
        duration:600,
      }).then(() => {
        this.clearBidSelection();
      })
    },


    notif_tradeCoin(n){
      debug("Notif: trading coins", n);

      if(!$('coin-' + n.args.new.id)){
        // Distinction
        this.addCoin(n.args.new, "tab-distinctions");
      }

      // Slide new coin
      let location = n.args.new.location == 'hand'? 'coins-zone-' + n.args.player_id
        : (n.args.player_id == this.player_id?
            this.getCoinContainer(n.args.new)
            : ('bids-zone-'+ n.args.new.location_arg + '-' + n.args.player_id)
        );


      this.slide('coin-' + n.args.new.id, location, {
        duration:1500,
      });

      // Slide old coin depending on his type
      delete $('coin-' + n.args.max.id).dataset.selected;
      if(n.args.max.type == 1){ // TREASURE
        this.slide('coin-' + n.args.max.id, 'treasure-slot-' + n.args.max.value, { duration:1500 });
      } else {
        // Slide to tower and destroy
        this.slide('coin-' + n.args.max.id, 'treasure', {
          destroy: true,
          duration:1500,
        });
      }
    },


    notif_discardCoin(n){
      debug("Notif: discard coins", n);

      this.slide('coin-' + n.args.max.id, 'treasure', {
        destroy: true,
        duration:1000,
      });
    },

    onEnteringStateTransformCoin(args){
      if(this.isCurrentPlayerActive()){
        this.makeCoinsSelectable(args.coins, this.onClickCoinTransform.bind(this));
      }
    },

    onEnteringStateKhradTransform(args){
      if(this.isCurrentPlayerActive()){
        this.makeCoinsSelectable(args.coins, this.onClickCoinTransform.bind(this));
      }
    },

    onClickCoinTransform(coin){
      this.takeAction('transformCoin', { coinId : coin.id });
    },


    /*###################
    ####### ULINE #######
    ###################*/

    notif_ulineRecruited(n){
      debug("Notif: you've just recruited Uline", n);
      n.args.coins.forEach(coin => {
        this.slide("coin-" + coin, 'coins-zone-' + this.player_id);
      })
    },


    onEnteringStateUlineTradeCoin(args){
      if(this.isCurrentPlayerActive()){
        this._selectedCoinsForUlineTrade = [];
        this.makeCoinsSelectable(args.coins, this.onClickCoinUlineTrade.bind(this));
      }
    },


    onClickCoinUlineTrade(coin){
      if(this._selectedCoinsForUlineTrade.includes(coin.id)){
        // Already selected => unselect it
        dojo.removeClass("coin-" + coin.id, "selected");
        this._selectedCoinsForUlineTrade.splice(this._selectedCoinsForUlineTrade.indexOf(coin.id), 1)
      } else if(this._selectedCoinsForUlineTrade.length < 2){
        // Select it
        dojo.addClass("coin-" + coin.id, "selected");
        this._selectedCoinsForUlineTrade.push(coin.id);
      }

      dojo.destroy("btnConfirmUlineTrade");
      if(this._selectedCoinsForUlineTrade.length == 2){
        this.addPrimaryActionButton("btnConfirmUlineTrade", _("Confirm trade"), () => this.onClickConfirmUlineTrade())
      }
    },

    onClickConfirmUlineTrade(){
      this.takeAction('ulineTrade', {
        coinIds: this._selectedCoinsForUlineTrade.join(';'),
      });
    },


    /*######################
    ####### VIDOFNIR #######
    #######################*/
    onEnteringStateVidofnirTransforms(args){
      debug("estt");
      if(this.isCurrentPlayerActive()){
        this._selectedCoinForVidofnirTransform = null;
        this._selectedTransformation = null;
        this.makeCoinsSelectable(args.coins, this.onClickCoinVidofnirTransform.bind(this));
        args.transformations.forEach(t => this.addPrimaryActionButton('btnVidofnir'+ t, "+" + t, () => this.onClickTransformationVidofnir(t) ) )
      }
    },

    onClickCoinVidofnirTransform(coin){
      if(this._selectedTransformation != null){
        this._selectedCoinForVidofnirTransform = coin.id;
        this.actVidofnirUpgrade();
        return;
      }

      if(this._selectedCoinForVidofnirTransform == coin.id){
        // Already selected => unselect it
        dojo.removeClass("coin-" + coin.id, "selected");
        this._selectedCoinForVidofnirTransform = null;
      } else {
        if(this._selectedCoinForVidofnirTransform != null)
          dojo.removeClass('coin-' + this._selectedCoinForVidofnirTransform, "selected");

        // Select it
        dojo.addClass("coin-" + coin.id, "selected");
        this._selectedCoinForVidofnirTransform = coin.id;
      }
    },


    onClickTransformationVidofnir(transform){
      if(this._selectedCoinForVidofnirTransform != null){
        this._selectedTransformation = transform;
        this.actVidofnirUpgrade();
        return;
      }

      if(this._selectedTransformation == transform){
        // Already selected => unselect it
        dojo.removeClass("btnVidofnir" + transform, "selected");
        this._selectedTransformation = null;
      } else {
        if(this._selectedTransformation != null)
          dojo.removeClass('btnVidofnir' + this._selectedTransformation, "selected");

        // Select it
        dojo.addClass("btnVidofnir" + transform, "selected");
        this._selectedTransformation = transform;
      }
    },

    actVidofnirUpgrade(){
      this.takeAction("vidofnirUpgrade", {
        coinId: this._selectedCoinForVidofnirTransform,
        transform: this._selectedTransformation,
      });
    },
  });
});
