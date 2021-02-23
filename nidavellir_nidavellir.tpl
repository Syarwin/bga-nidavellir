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
              <div class="tavern-sign" id="tavern-sign-0"></div>
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
              <div class="tavern-sign" id="tavern-sign-1"></div>
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
              <div class="tavern-sign" id="tavern-sign-2"></div>
            </div>
          </div>
          <div id="ver-beam-5" class="ver-beam"></div>
          <div class="tavern-cards-holder" id="tavern_2"></div>
          <div id="ver-beam-6" class="ver-beam"></div>
        </div>
      </div>


      <div id="hall-container">
        <div id="hall"></div>
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
    <div class="bids-zone-tavern-holder tavern-0">
      <div class="bids-zone-tavern" id="bids-zone-0-\${id}"></div>
      <div class="bids-drop-zone" id="bids-drop-zone-0-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-1">
      <div class="bids-zone-tavern" id="bids-zone-1-\${id}"></div>
      <div class="bids-drop-zone" id="bids-drop-zone-1-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-2">
      <div class="bids-zone-tavern" id="bids-zone-2-\${id}"></div>
      <div class="bids-drop-zone" id="bids-drop-zone-2-\${id}"></div>
    </div>
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
<div class="card" id="card-\${id}" data-id="\${id}" data-class="\${class}" data-parity="\${parity}" data-ranks="\${ranks}">
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



var jstpl_configPlayerBoard = `
<div class='player-board' id="player_board_config">
  <div id="player_config" class="player_board_content">
    <div id="player_info_row">
      <div id="age-counter-holder">
        \${age}
        <span id="age-counter"></span>
      </div>

      <div id="turn-counter-holder">
        \${turn}
        <span id="turn-counter"></span>
      </div>
    </div>

    <div id="player_config_row">
      <div id="show-scoresheet">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
          <g class="fa-group">
            <path class="fa-secondary" fill="currentColor" d="M544 464v32a16 16 0 0 1-16 16H112a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h416a16 16 0 0 1 16 16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M640 176a48 48 0 0 1-48 48 49 49 0 0 1-7.7-.8L512 416H128L55.7 223.2a49 49 0 0 1-7.7.8 48.36 48.36 0 1 1 43.7-28.2l72.3 43.4a32 32 0 0 0 44.2-11.6L289.7 85a48 48 0 1 1 60.6 0l81.5 142.6a32 32 0 0 0 44.2 11.6l72.4-43.4A47 47 0 0 1 544 176a48 48 0 0 1 96 0z"></path>
          </g>
        </svg>
      </div>

      <div id="show-settings">
        <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
          <g>
            <path class="fa-secondary" fill="currentColor" d="M638.41 387a12.34 12.34 0 0 0-12.2-10.3h-16.5a86.33 86.33 0 0 0-15.9-27.4L602 335a12.42 12.42 0 0 0-2.8-15.7 110.5 110.5 0 0 0-32.1-18.6 12.36 12.36 0 0 0-15.1 5.4l-8.2 14.3a88.86 88.86 0 0 0-31.7 0l-8.2-14.3a12.36 12.36 0 0 0-15.1-5.4 111.83 111.83 0 0 0-32.1 18.6 12.3 12.3 0 0 0-2.8 15.7l8.2 14.3a86.33 86.33 0 0 0-15.9 27.4h-16.5a12.43 12.43 0 0 0-12.2 10.4 112.66 112.66 0 0 0 0 37.1 12.34 12.34 0 0 0 12.2 10.3h16.5a86.33 86.33 0 0 0 15.9 27.4l-8.2 14.3a12.42 12.42 0 0 0 2.8 15.7 110.5 110.5 0 0 0 32.1 18.6 12.36 12.36 0 0 0 15.1-5.4l8.2-14.3a88.86 88.86 0 0 0 31.7 0l8.2 14.3a12.36 12.36 0 0 0 15.1 5.4 111.83 111.83 0 0 0 32.1-18.6 12.3 12.3 0 0 0 2.8-15.7l-8.2-14.3a86.33 86.33 0 0 0 15.9-27.4h16.5a12.43 12.43 0 0 0 12.2-10.4 112.66 112.66 0 0 0 .01-37.1zm-136.8 44.9c-29.6-38.5 14.3-82.4 52.8-52.8 29.59 38.49-14.3 82.39-52.8 52.79zm136.8-343.8a12.34 12.34 0 0 0-12.2-10.3h-16.5a86.33 86.33 0 0 0-15.9-27.4l8.2-14.3a12.42 12.42 0 0 0-2.8-15.7 110.5 110.5 0 0 0-32.1-18.6A12.36 12.36 0 0 0 552 7.19l-8.2 14.3a88.86 88.86 0 0 0-31.7 0l-8.2-14.3a12.36 12.36 0 0 0-15.1-5.4 111.83 111.83 0 0 0-32.1 18.6 12.3 12.3 0 0 0-2.8 15.7l8.2 14.3a86.33 86.33 0 0 0-15.9 27.4h-16.5a12.43 12.43 0 0 0-12.2 10.4 112.66 112.66 0 0 0 0 37.1 12.34 12.34 0 0 0 12.2 10.3h16.5a86.33 86.33 0 0 0 15.9 27.4l-8.2 14.3a12.42 12.42 0 0 0 2.8 15.7 110.5 110.5 0 0 0 32.1 18.6 12.36 12.36 0 0 0 15.1-5.4l8.2-14.3a88.86 88.86 0 0 0 31.7 0l8.2 14.3a12.36 12.36 0 0 0 15.1 5.4 111.83 111.83 0 0 0 32.1-18.6 12.3 12.3 0 0 0 2.8-15.7l-8.2-14.3a86.33 86.33 0 0 0 15.9-27.4h16.5a12.43 12.43 0 0 0 12.2-10.4 112.66 112.66 0 0 0 .01-37.1zm-136.8 45c-29.6-38.5 14.3-82.5 52.8-52.8 29.59 38.49-14.3 82.39-52.8 52.79z" opacity="0.4"></path>
            <path class="fa-primary" fill="currentColor" d="M420 303.79L386.31 287a173.78 173.78 0 0 0 0-63.5l33.7-16.8c10.1-5.9 14-18.2 10-29.1-8.9-24.2-25.9-46.4-42.1-65.8a23.93 23.93 0 0 0-30.3-5.3l-29.1 16.8a173.66 173.66 0 0 0-54.9-31.7V58a24 24 0 0 0-20-23.6 228.06 228.06 0 0 0-76 .1A23.82 23.82 0 0 0 158 58v33.7a171.78 171.78 0 0 0-54.9 31.7L74 106.59a23.91 23.91 0 0 0-30.3 5.3c-16.2 19.4-33.3 41.6-42.2 65.8a23.84 23.84 0 0 0 10.5 29l33.3 16.9a173.24 173.24 0 0 0 0 63.4L12 303.79a24.13 24.13 0 0 0-10.5 29.1c8.9 24.1 26 46.3 42.2 65.7a23.93 23.93 0 0 0 30.3 5.3l29.1-16.7a173.66 173.66 0 0 0 54.9 31.7v33.6a24 24 0 0 0 20 23.6 224.88 224.88 0 0 0 75.9 0 23.93 23.93 0 0 0 19.7-23.6v-33.6a171.78 171.78 0 0 0 54.9-31.7l29.1 16.8a23.91 23.91 0 0 0 30.3-5.3c16.2-19.4 33.7-41.6 42.6-65.8a24 24 0 0 0-10.5-29.1zm-151.3 4.3c-77 59.2-164.9-28.7-105.7-105.7 77-59.2 164.91 28.7 105.71 105.7z"></path>
          </g>
        </svg>
      </div>
    </div>
    <div class='layoutControlsHidden' id="layout-controls-container">
      Settings :)
    </div>
  </div>
</div>
`;

</script>

{OVERALL_GAME_FOOTER}
