define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.cardTrait", null, {
    constructor(){
      this._notifications.push(
        ['discardCards', 1000]
      );
      this._callbackOnCard = null;
      this._selectableCards = [];
      this._distinctionExplorerCards = null;
    },

    setupCards(){
      this.gamedatas.cards.taverns.forEach(card => this.addCard(card, card.location));

      this.setupHall();
      this.setupDistinctions();
      dojo.place("<div id='card-overlay'></div>", "ebd-body");
    },



    setupHall(){
      this._heroesDialog = new customgame.modal("showHeroes", {
        class:"nidavellir_popin",
        closeIcon:'fa-times',
        openAnimation:true,
        openAnimationTarget:"tab-heroes",
        contents:jstpl_heroesModal,
        closeAction:'hide',
        statusElt:"tab-heroes",
      });

      this.gamedatas.cards.hall.forEach(card => this.addCard(card, card.location));
      dojo.connect($('tab-heroes'), 'click', () => this.openHeroesModal() );
    },

    openHeroesModal(){
      this._heroesDialog.show();
    },


    setupDistinctions(){
      this._distinctionsDialog = new customgame.modal("showDistinctions", {
        class:"nidavellir_popin",
        closeIcon:'fa-times',
        openAnimation:true,
        openAnimationTarget:"tab-distinctions",
        contents:jstpl_distinctionsModal,
        closeAction:'hide',
        statusElt:"tab-distinctions",
      });

      this.gamedatas.cards.evaluation.forEach(card => this.addCard(card, card.location));
      dojo.connect($('tab-distinctions'), 'click', () => this.openDistinctionModal() );
    },

    openDistinctionModal(){
      this._distinctionsDialog.show();
    },



    addCard(card, container, animation = false){
      card.parity = card.id % 2;
      card.ranks = card.grade.length;
      this.place('jstpl_card', card, container);
      card.grade.forEach(rank => this.addRank(rank, card) );
      dojo.connect($("card-" + card.id), "click", () => this.onClickCard(card) );

      if(animation)
        this.slideFromLeft('card-' + card.id);
    },

    addRank(rank, card){
      this.place('jstpl_rank', { rank : rank ?? '' }, 'card-grade-' + card.id);
    },


    onClickCard(card){
      if(this._selectableCards.includes(card.id)){
        this._callbackOnCard(card);
      }
    },

    makeCardSelectable(cards, callback){
      this._selectableCards = cards;
      this._callbackOnCard = callback;

      dojo.query(".card").removeClass("selectable");
      cards.forEach(cardId => {
        dojo.removeClass('card-' + cardId, "unselectable");
        dojo.addClass('card-' + cardId, "selectable");
      });
    },



    slideFromLeft(elem){
      elem = typeof elem == 'string'? $(elem) : elem;
      let x = (elem.offsetWidth + elem.offsetLeft + 30);
      dojo.addClass(elem, 'notransition')
      dojo.style(elem, "opacity", "0");
      dojo.style(elem, "left", -x + "px");
      elem.offsetHeight;
      dojo.removeClass(elem, 'notransition');

      dojo.style(elem, "opacity", "1");
      dojo.style(elem, "left", "0px")
    },

    slideToRightAndDestroy(elem){
      elem = typeof elem == 'string'? $(elem) : elem;
      let stack = elem.parentNode;
      let x = (stack.offsetWidth - elem.offsetLeft + 100);
      dojo.style(elem, "left", x + "px");
      setTimeout(() => dojo.destroy(elem), 1000);
    },


    notif_discardCards(n){
      debug("Notif: discarding cards", n);
      this.slide('card-' + n.args.card.id, 'page-title', {
        duration:1000,
        destroy:true,
      });

      if(n.args.card2){
        this.slide('card-' + n.args.card2.id, 'page-title', {
          duration:1000,
          destroy:true,
        });
      }
    },



    onEnteringStateDistinctionExplorer(args){
      if(!this.isCurrentPlayerActive())
        return;
        
      this._distinctionExplorerCards = args.cardsObj;
      this.addPrimaryActionButton("btnShowDistinctionExplorerCards", _("Show cards"), () => this.openDistinctionExplorerModal() )
      this.openDistinctionExplorerModal();
    },

    openDistinctionExplorerModal(){
      this._distinctionExplorerModal = new customgame.modal("distinctionExplorer", {
        class:"nidavellir_popin",
        closeIcon:'fa-times',
      });

      this._distinctionExplorerCards.forEach(card => this.addCard(card, 'popin_distinctionExplorer_contents'));
      this.makeCardSelectable(this._distinctionExplorerCards.map(card => card.id), this.onClickCardRecruit.bind(this));
      this._distinctionExplorerModal.show();
    },
  });
});
