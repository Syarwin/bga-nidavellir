{OVERALL_GAME_HEADER}

<div id="nidavellir-board">
  <div id="exterior-wrapper">
    <div id="exterior">
      <div id="treasure-container">
        <div id="treasure"></div>
      </div>


      <div id="taverns-container">
        <div id="taverns">
          <div id="tavern-roof"></div>
          <div id="hor-beam-1" class="hor-beam"></div>

          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-swing">
              <div class="tavern-chain"></div>
              <div class="tavern-sign" id="tavern-sign-0">
                <div class="tavern-coin-holder" id="tavern-coin-holder-0"></div>
              </div>
            </div>
          </div>
          <div id="ver-beam-1" class="ver-beam"></div>
          <div class="tavern-cards-holder" id="tavern_0"></div>
          <div id="ver-beam-2" class="ver-beam"></div>

          <div id="hor-beam-2" class="hor-beam"></div>

          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-swing">
              <div class="tavern-chain"></div>
              <div class="tavern-sign" id="tavern-sign-1">
                <div class="tavern-coin-holder" id="tavern-coin-holder-1"></div>
              </div>
            </div>
          </div>
          <div id="ver-beam-3" class="ver-beam"></div>
          <div class="tavern-cards-holder" id="tavern_1"></div>
          <div id="ver-beam-4" class="ver-beam"></div>

          <div id="hor-beam-3" class="hor-beam"></div>

          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-swing">
              <div class="tavern-chain"></div>
              <div class="tavern-sign" id="tavern-sign-2">
                <div class="tavern-coin-holder" id="tavern-coin-holder-2"></div>
              </div>
            </div>
          </div>
          <div id="ver-beam-5" class="ver-beam"></div>
          <div class="tavern-cards-holder" id="tavern_2"></div>
          <div id="ver-beam-6" class="ver-beam"></div>
        </div>
      </div>
    </div>
  </div>

  <div id="foundations"></div>

  <div id="player-boards"></div>
</div>

<script type="text/javascript">
var jstpl_playerPanel = `
<div class="panel-container" id="panel-container-\${id}" data-no="\${no}">
  <div class="coins-zone" id="coins-zone-\${id}"></div>
  <div class="bids-zone" id="bids-zone-\${id}">
    <div class="bids-zone-tavern tavern-0" id="bids-zone-0-\${id}"></div>
    <div class="bids-zone-tavern tavern-1" id="bids-zone-1-\${id}"></div>
    <div class="bids-zone-tavern tavern-2" id="bids-zone-2-\${id}"></div>
  </div>
  <div class="command-zone" id="command-zone-\${id}">
    <div id="command-zone-overview-\${id}-1" class="command-zone-class" data-class="1"></div>
    <div id="command-zone-overview-\${id}-2" class="command-zone-class" data-class="2"></div>
    <div id="command-zone-overview-\${id}-3" class="command-zone-class" data-class="3"></div>
    <div id="command-zone-overview-\${id}-4" class="command-zone-class" data-class="4"></div>
    <div id="command-zone-overview-\${id}-5" class="command-zone-class" data-class="5"></div>
  </div>
</div>`;

var jstpl_gemContainer = `
<div id="gem-container-\${id}" class="gem-container"></div>
`;

var jstpl_gem = `
<div id="gem-\${value}" data-value="\${value}" class="gem"></div>
`;



var jstpl_coin = `
<div class="coin" id="coin-\${id}" data-type="\${type}" data-value="\${value}">
  <div class="coin-holder">
    <div class="coin-inner">
      <div class="coin-value">\${value}</div>
    </div>
  </div>
</div>
`;


var jstpl_card = `
<div class="card" id="card-\${id}" data-class="\${class}" data-parity="\${parity}">
  <div class="card-grade" id="card-grade-\${id}">
    <div class="card-class-icon"></div>
  </div>
</div>
`;

var jstpl_rank = `
  <div class="card-rank">\${rank}</div>
`;

var jstpl_playerBoard = `
<div id="player-board-\${no}" class="nidavellir-player-board">
  <div class="cards-class" id="command-zone_\${id}_1"></div>
  <div class="cards-class" id="command-zone_\${id}_2"></div>
  <div class="cards-class" id="command-zone_\${id}_3"></div>
  <div class="cards-class" id="command-zone_\${id}_4"></div>
  <div class="cards-class" id="command-zone_\${id}_5"></div>
  <div class="cards-class" id="command-zone_\${id}_6"></div>
</div>
`

</script>

{OVERALL_GAME_FOOTER}
