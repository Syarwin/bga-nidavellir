define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.cardTrait", null, {
    constructor(){
      /*
      this._notifications.push(
        ['newHand', 100],
        ['giveCard', 1000],
        ['receiveCard', 1000]
      );
      */
      this._callbackOnCard = null;
      this._selectableCards = [];
    },

    setupTaverns(){
      this.gamedatas.cards.taverns.forEach(card => this.addCard(card, card.location));
    },

    setupHall(){
      debug(this.gamedatas.cards.hall);
      this.gamedatas.cards.hall.forEach(card => this.addCard(card, card.location));
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
      cards.forEach(cardId => dojo.addClass('card-' + cardId, "selectable") );
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

  });
});
