{OVERALL_GAME_HEADER}

<div id="nidavellir-board">
  <div id="exterior-wrapper">
    <div id="exterior">
      <div id="tabs-container">
        <div class="nid-tab" id="tab-heroes"></div>
        <div class="nid-tab" id="tab-distinctions"></div>
        <div class="nid-tab" id="tab-score">{SCORE}</div>
        <div class="nid-tab" id="tab-discard">{DISCARD}</div>
      </div>

      <div id="treasure-container">
        <div id="treasure"></div>
        <div class="treasure-decoration" id="treasure-decoration-1"></div>
        <div class="treasure-decoration" id="treasure-decoration-2"></div>
        <div class="treasure-decoration" id="treasure-decoration-3"></div>

        <div id="camp-container">
          <div id="camp"></div>
        </div>
      </div>


      <div id="taverns-container">
        <div id="taverns-pre-bids-zone"></div>

        <div id="taverns">
          <div id="turn-counter-holder">
            {TURN}
            <span id="turn-counter"></span>
          </div>
          <div id="age-counter"></div>

          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-0">
              <div class="bids-drop-zone" id="bids-drop-zone-0"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-0"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_0"></div>
          </div>


          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-1">
              <div class="bids-drop-zone" id="bids-drop-zone-1"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-1"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_1"></div>
          </div>


          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-2">
              <div class="bids-drop-zone" id="bids-drop-zone-2"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-2"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="player-boards"></div>
</div>

<script type="text/javascript">
var jstpl_playerPanel = `
<div class="panel-container" id="panel-container-\${id}" data-no="\${no}">
  <div class="coins-zone" id="coins-zone-\${id}"></div>
  <div class="bids-zone" id="bids-zone-\${id}">
    <div class="bids-zone-tavern-holder tavern-0">
      <div class="bids-zone-tavern" id="bids-zone-0-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-1">
      <div class="bids-zone-tavern" id="bids-zone-1-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-2">
      <div class="bids-zone-tavern" id="bids-zone-2-\${id}"></div>
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
<div class="card" id="card-\${id}" data-id="\${id}" data-class="\${class}" data-parity="\${parity}" data-ranks="\${ranks}" data-offer="\${offer}" data-flag="\${flag}">
  <div class="card-grade" id="card-grade-\${id}">
    \${gradeHtml}
    <div class="card-class-icon"></div>
  </div>
   \${olrunToken}
</div>
`;

var jstpl_cardTooltip = `
<div class="card-tooltip">
  <div class="card-title-holder">
    <span class="card-title" data-class="\${symbol}">\${title}</span>
    <span class="card-subtitle" data-class="\${class}">\${subtitle}</span>
  </div>
  <div class="card" data-id="\${id}" data-class="\${class}" data-parity="\${parity}" data-ranks="\${ranks}" data-offer="\${offer}" data-flag="\${flag}">
    <div class="card-grade" id="card-grade-\${id}">
      \${gradeHtml}
      <div class="card-class-icon"></div>
    </div>
    \${olrunToken}
  </div>
  <div class="card-description">
    \${desc}
  </div>
</div>
`;

var jstpl_rank = `
  <div class="card-rank">\${rank}</div>
`;

var jstpl_playerBoard = `
<div id="player-board-\${no}" data-color="\${color}" class="nidavellir-player-board">
  <div class="player-board-name" style="color:#\${color}">\${name}</div>

  <div class="enlist-mercenaries" id="enlist-\${id}"></div>

  <div class="command-zone-container">
    <div class="hero-line" id="hero-line-\${id}" data-n="\${line}"></div>
    <div class="cards-class-header card-class_0">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_0"></span>
      </div>

      <div class="card-class-score class-artifact">
        <span id="command-zone-score_\${id}_6"></span>
        <i class="fa fa-star"></i>
      </div>

      <div class="card-class-score class-mythology">
        <span id="command-zone-score_\${id}_7"></span>
        <i class="fa fa-star"></i>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_0"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>


    <div class="cards-class-header card-class_1">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_1"></span>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_1"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>

    <div class="cards-class-header card-class_2">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_2"></span>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_2"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>

    <div class="cards-class-header card-class_3">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_3"></span>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_3"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>

    <div class="cards-class-header card-class_5">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_5"></span>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_5"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>

    <div class="cards-class-header card-class_4">
      <div class="card-class-ranks">
        <div class="rank"></div>
        <span id="command-zone-ranks_\${id}_4"></span>
      </div>

      <div class="card-class-score">
        <span id="command-zone-score_\${id}_4"></span>
        <i class="fa fa-star"></i>
      </div>
    </div>

    <div class="cards-class card-class_0" id="command-zone_\${id}_0"></div>
    <div class="cards-class card-class_1" id="command-zone_\${id}_1">
      <ul class="column-score-helper" id="blacksmith-score-helper-\${id}">
        <li></li>
        <li>3</li>       <li>7</li>        <li>12</li>        <li>18</li>        <li>25</li>
        <li>33</li>        <li>42</li>        <li>52</li>        <li>63</li>        <li>75</li>
        <li>88</li>        <li>102</li>        <li>117</li>        <li>133</li>        <li>150</li>
        <li>168</li>       <li>187</li>        <li>207</li>    <li>228</li>    <li>250</li>    <li>273</li>
        <li>297</li>    <li>322</li>    <li>348</li>    <li>375</li>
      </ul>
    </div>
    <div class="cards-class card-class_2" id="command-zone_\${id}_2">
      <ul class="column-score-helper" id="hunter-score-helper-\${id}">
        <li></li>
        <li>1</li>       <li>4</li>        <li>9</li>        <li>16</li>        <li>25</li>
        <li>36</li>        <li>49</li>        <li>64</li>        <li>81</li>        <li>100</li>
        <li>121</li>        <li>144</li>        <li>169</li>        <li>196</li>        <li>225</li>
        <li>256</li>      <li>289</li>      <li>324</li>      <li>361</li>      <li>400</li>      <li>441</li>
    </ul>
    </div>
    <div class="cards-class card-class_3" id="command-zone_\${id}_3"></div>
    <div class="cards-class card-class_5" id="command-zone_\${id}_5"></div>
    <div class="cards-class card-class_4" id="command-zone_\${id}_4"></div>
  </div>
</div>
`;

var jstpl_ZolkurZone = `
<div class="zolkur-zone" id="command-zone_\${id}_80"></div>
`;



var jstpl_configPlayerBoard = `
<div class='player-board' id="player_board_config">
  <div id="player_config" class="player_board_content">

    <div id="player_config_row">
      <div id="show-help">
      <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
        <g>
          <path d="m 233.2661,19.969492 -65.3,132.399998 -146.099998,21.3 c -26.2000003,3.8 -36.7,36.1 -17.7000003,54.6 l 105.6999983,103 -24.999998,145.5 c -4.5,26.3 23.199998,46 46.399998,33.7 l 130.7,-68.7 130.7,68.7 c 23.2,12.2 50.9,-7.4 46.4,-33.7 l -25,-145.5 105.7,-103 c 19,-18.5 8.5,-50.8 -17.7,-54.6 l -146.1,-21.3 -65.3,-132.399998 c -11.7,-23.6000005 -45.6,-23.9000005 -57.4,0 z"
             style="fill:currentColor;fill-opacity:0.4;stroke-width:0.50514001" />
          <path style="fill:currentColor"
             d="m 266.54954,154.03389 c -41.43656,0 -68.27515,16.07436 -89.34619,44.74159 -3.82236,5.20034 -2.64393,12.33041 2.68806,16.15841 l 22.3943,16.0773 c 5.38496,3.86585 13.04682,2.96194 17.26269,-2.03884 13.00373,-15.42456 22.64971,-24.30544 42.96178,-24.30544 15.97056,0 35.72456,9.73171 35.72456,24.39489 0,11.08489 -9.66467,16.77774 -25.43382,25.14841 -18.3892,9.7617 -42.72402,21.91024 -42.72402,52.30077 v 4.81105 c 0,6.51516 5.57808,11.7966 12.45917,11.7966 h 37.62199 c 6.88108,0 12.45915,-5.28144 12.45915,-11.7966 v -2.83758 c 0,-21.06678 65.03059,-21.94415 65.03059,-78.95226 5.2e-4,-42.93179 -47.03384,-75.4983 -91.09826,-75.4983 z m -5.20222,183.56459 c -19.82875,0 -35.96076,15.27415 -35.96076,34.04846 0,18.77381 16.13201,34.04797 35.96076,34.04797 19.82875,0 35.96077,-15.27416 35.96077,-34.04846 0,-18.7743 -16.13202,-34.04797 -35.96077,-34.04797 z" />
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
      <div id="layout-mode">
        <div id="layout-normal">
          <div id="layout-normal-1"></div>
          <div id="layout-normal-2"></div>
        </div>

        <div id="layout-compact">
          <div id="layout-compact-1"></div>
          <div id="layout-compact-2"></div>
        </div>
      </div>

      <div id="autopick-selector">
        \${autopick}
        <select id="autopick">
          <option value='0'>\${disabled}</option>
          <option value='1'>\${enabled}</option>
        </select>
      </div>
    </div>
  </div>
</div>
`;



var jstpl_overview = `
<table id='players-overview'>
  <thead>
    <tr id="overview-headers">
      <th></th>
    </tr>
  </thead>
  <tbody id="player-overview-body">
    <tr id="overview-row-1">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-2">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-3">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-4">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-5">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-0">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-6">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-7">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-row-8">
      <td class="row-header"><div class="row-rank"></div></td>
    </tr>
    <tr id="overview-total">
      <td class="row-header"></td>
    </tr>
  </tbody>
</table>
`;


var jstpl_heroesModal = `
<div id="hall-container">
  <div id="hall"></div>
</div>
`;

var jstpl_distinctionsModal = `
<div id="evaluation-container">
  <div id="evaluation"></div>
</div>
`;

var jstpl_helpModal = `
<div id="help-container">
  <div id="helper-classes">
    <div class="helper-classes-row" data-class="5">
      <div class="helpers-classes-cards" id="helpers-cards-5"></div>
      <div class="helper-classes-desc">
        <h2>\${warriors}</h2>
        <p>\${warriorsText} \${warriorsText2}</p>
      </div>
      <div class="helper-classes-symbol"></div>
    </div>

    <div class="helper-classes-row" data-class="2">
      <div class="helpers-classes-cards" id="helpers-cards-2"></div>
      <div class="helper-classes-desc">
        <h2>\${hunters}</h2>
        <p>
          \${hunterText}
          <ul>
            <li>1</li>       <li>4</li>        <li>9</li>        <li>16</li>        <li>25</li>
            <li>36</li>        <li>49</li>        <li>64</li>        <li>81</li>        <li>100</li>
            <li>121</li>        <li>144</li>        <li>169</li>        <li>196</li>        <li>225</li>
            <li>256</li>      <li>289</li>      <li>324</li>      <li>361</li>      <li>400</li>      <li>441</li>
          </ul>
        </p>
      </div>
      <div class="helper-classes-symbol"></div>
    </div>

    <div class="helper-classes-row" data-class="4">
      <div class="helpers-classes-cards" id="helpers-cards-4"></div>
      <div class="helper-classes-desc">
        <h2>\${miners}</h2>
        <p>\${minersText}</p>
      </div>
      <div class="helper-classes-symbol"></div>
    </div>

    <div class="helper-classes-row" data-class="1">
      <div class="helpers-classes-cards" id="helpers-cards-1"></div>
      <div class="helper-classes-desc">
        <h2>\${blacksmith}</h2>
        <p>
          \${blacksmithText}
          <ul>
            <li>3</li>       <li>7</li>        <li>12</li>        <li>18</li>        <li>25</li>
            <li>33</li>        <li>42</li>        <li>52</li>        <li>63</li>        <li>75</li>
            <li>88</li>        <li>102</li>        <li>117</li>        <li>133</li>        <li>150</li>
            <li>168</li>       <li>187</li>       <li>207</li>    <li>228</li>    <li>250</li>    <li>273</li>
            <li>297</li>    <li>322</li>    <li>348</li>    <li>375</li>
          </ul>
        </p>
      </div>
      <div class="helper-classes-symbol"></div>
    </div>

    <div class="helper-classes-row" data-class="3">
      <div class="helpers-classes-cards" id="helpers-cards-3"></div>
      <div class="helper-classes-desc">
        <h2>\${explorers}</h2>
        <p>\${explorerText}</p>
      </div>
      <div class="helper-classes-symbol"></div>
    </div>
  </div>
</div>
`;
</script>

{OVERALL_GAME_FOOTER}
