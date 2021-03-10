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
        verticalAlign:'flex-start',

//        scale:0.9,
//        breakpoint:750,
      });

      this.gamedatas.cards.hall.forEach(card => this.addCard(card, card.location));
      dojo.connect($('tab-heroes'), 'click', () => this.openHeroesModal() );

      this._selectedHero = null;
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

        scale:0.85,
        breakpoint:420,
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
      card.title = card.name? card.name : '';
      card.subtitle = card.subname? _(card.subname) : '';
      card.desc = card.tooltip? card.tooltip.map(o => _(o)).join('<br />') : '';
      card.gradeHtml = card.grade.map(r => this.getRankHtml(r)).join('');

      this.place('jstpl_card', card, container);
      dojo.connect($("card-" + card.id), "click", () => this.onClickCard(card) );
      this.addTooltipHtml('card-' + card.id, this.format_block('jstpl_cardTooltip', card));

      if(animation)
        this.slideFromLeft('card-' + card.id);
    },

    getRankHtml(rank){
      return this.format_block('jstpl_rank', { rank : rank ?? '' });
    },


    onClickCard(card){
      if(card.location == 'hall'){
        if(this._selectedHero != null){
          dojo.empty("hero-viewer");
          dojo.removeClass('card-' + this._selectedHero.id, "selected");
        }

        this._selectedHero = card;
        this.place('jstpl_cardTooltip', card, "hero-viewer");
        dojo.addClass('card-' + card.id, "selected");
        // TODO add button
      }
      else if(this._selectableCards.includes(card.id)){
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

      if(!$('card-' + n.args.card.id)){ // Special case of explorer distinction
        this.addCard(n.args.card, "tab-distinctions");
      }
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
