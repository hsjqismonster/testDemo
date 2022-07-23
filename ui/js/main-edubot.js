/**
 * @fileoverview JavaScript for aiforu's Edubot website (using Google Blockly)
 * @author liujialiwhu@google.com (Jiali Liu)
 */
//'use strict';

/**
 * Create a namespace for the application.
 */
let Edubot = {};

/**
 * Blockly's main workspace.
 * @type {Blockly.WorkspaceSvg}
 */
Edubot.workspace = null;

/**
 * Load blocks saved on App Engine Storage or in session/local storage.
 * @param {string} defaultXml Text representation of default blocks.
 */

// Edubot.loadBlocks = function(defaultXml) {
//   console.log("loadBlocks");
//   try {
//     var loadOnce = window.sessionStorage.loadOnceBlocks;
//     console.log(loadOnce);

//   } catch(e) {
//     // Firefox sometimes throws a SecurityError when accessing sessionStorage.
//     // Restarting Firefox fixes this, so it looks like a bug.
//     var loadOnce = null;
//   }
//   if ('BlocklyStorage' in window && window.location.hash.length > 1) {
//     // An href with #key trigers an AJAX call to retrieve saved blocks.
//     BlocklyStorage.retrieveXml(window.location.hash.substring(1));
//     console.log(" BlocklyStorage &&");
//   } else if (loadOnce) {
//     // Language switching stores the blocks during the reload.
//     delete window.sessionStorage.loadOnceBlocks;
//     var xml = Blockly.Xml.textToDom(loadOnce);
//     console.log(xml);
//     Blockly.Xml.domToWorkspace(xml, Code.workspace);
//   } else if (defaultXml) {
//     // Load the editor with default starting blocks.
//     var xml = Blockly.Xml.textToDom(defaultXml);
//     Blockly.Xml.domToWorkspace(xml, Code.workspace);
//     console.log(" defaultXml");

//   } else if ('BlocklyStorage' in window) {
//     // Restore saved blocks in a separate thread so that subsequent
//     // initialization is not affected from a failed load.
//     window.setTimeout(BlocklyStorage.restoreBlocks, 0);
//     console.log(" BlocklyStorage");
//   }
// };

function saveWorkspace() {
  console.log("test saving...");
  var xmlDom = Blockly.Xml.workspaceToDom(Blockly.mainWorkspace);
  var xmlText = Blockly.Xml.domToPrettyText(xmlDom);
  
  localStorage.setItem("blockly.xml", xmlText);
}

function loadWorkspace() {
  console.log("test loading...");
  var xmlText = localStorage.getItem("blockly.xml");
  console.log(xmlText);
  if (xmlText) {
      Blockly.mainWorkspace.clear();
      xmlDom = Blockly.Xml.textToDom(xmlText);
      Blockly.Xml.domToWorkspace(Blockly.mainWorkspace, xmlDom);
  }
}

/**
 * Attempt to generate the code and display it in the UI, pretty printed.
 * @param generator {!Blockly.Generator} The generator to use.
 */
Edubot.codeGeneration = function() {

  let generator = Blockly.JavaScript;
  if (Edubot.checkAllGeneratorFunctionsDefined(generator)) {
    code = generator.workspaceToCode(Edubot.workspace);
  }
  console.log("generate code:");
  console.log(code);
  return code;
};

/**
 * Check whether all blocks in use have generator functions.
 * @param generator {!Blockly.Generator} The generator to use.
 */
Edubot.checkAllGeneratorFunctionsDefined = function(generator) {
  let blocks = Edubot.workspace.getAllBlocks(false);
  let missingBlockGenerators = [];
  for (var i = 0; i < blocks.length; i++) {
    let blockType = blocks[i].type;
    if (!generator[blockType]) {
      if (missingBlockGenerators.indexOf(blockType) == -1) {
        missingBlockGenerators.push(blockType);
      }
    }
  }

  let valid = missingBlockGenerators.length == 0;
  if (!valid) {
    let msg = 'The generator code for the following blocks not specified for ' +
        generator.name_ + ':\n - ' + missingBlockGenerators.join('\n - ');
    Blockly.alert(msg);  // Assuming synchronous. No callback.
  }
  return valid;
};

/**
 * Bind a function to a button's click event.
 * On touch enabled browsers, ontouchend is treated as equivalent to onclick.
 * @param {!Element|string} el Button element or ID thereof.
 * @param {!Function} func Event handler to bind.
 */
Edubot.bindClick = function(el, func) {
    if (typeof el == 'string') {
      el = document.getElementById(el);
    }
    el.addEventListener('click', func, true);
    el.addEventListener('touchend', func, true);
};

function updateBlockMenu() {
  console.log("updateBlockMenu");
  let grayscale_block = document.querySelector('block[type="grayscale"]');
  let infrared_block = document.querySelector('block[type="infrared"]');
  // let ultrasonic_block = document.querySelector('block[type="ultrasonic"]');

  if(Edubot.model == "car"){
    grayscale_block.setAttribute("disabled", "false");
    infrared_block.setAttribute("disabled", "true");

  }else if(Edubot.model == "helicopter"){
    grayscale_block.setAttribute("disabled", "true");
    infrared_block.setAttribute("disabled", "false");
  }

  // let current = document.querySelector('.blocklyFlyout[diaplay="block"]');
  // console.log(current);
  // if(current){
  //   current.setAttribute("diaplay", "none");
  // }
  Edubot.workspace.updateToolbox(document.getElementById('toolbox'));
 
};

function chooseModel() {
  //choose different robot models
  Edubot.model = undefined;
  let carModel = document.querySelector('#car-model');
  let helicopModel = document.querySelector('#helicopter-model');
  let confirmBtn = document.querySelector('#confirm-model');

  if(carModel && helicopModel){
    let select = (e) => {
      if(e.currentTarget.id == "car-model"){
        carModel.style.backgroundColor = "red";
        helicopModel.style.backgroundColor = "white";
        Edubot.model = "car";
      }else if(e.currentTarget.id == "helicopter-model"){
        helicopModel.style.backgroundColor = "red";
        carModel.style.backgroundColor = "white";
        Edubot.model = "helicopter";
      }
    }

    Edubot.bindClick(carModel, select); 
    Edubot.bindClick(helicopModel, select); 
  } 

  if(confirmBtn){
    Edubot.bindClick(confirmBtn, ()=>{
      if(Edubot.model){
        updateBlockMenu();

        $("#modelPane").modal('hide');
      }else{
        alert("please select a model before comfirm");
      }
    }); 
  }
};

Edubot.initHandlers = function() {
  console.log("initHandlers");

  chooseModel();

  let saveBlocks = document.querySelector('#saveBlocks');
  if(saveBlocks){
    Edubot.bindClick(saveBlocks, saveWorkspace);
  }
  
  let languageList = document.querySelectorAll('.dropdown-item');
  if(languageList){
      languageList.forEach( lang => {
          if(lang) Edubot.bindClick(lang, Edubot.changeLanguage);
      });
  }

  //let trash = document.querySelector('.blocklyTrash');
  //if(trash) Edubot.bindClick(trash,Edubot.discard());

  let uploadBtn = document.querySelector('#uploadCode');
  if(uploadBtn) Edubot.bindClick(uploadBtn, () => {
    let content = document.querySelector('#excutionPane .modal-body');
    //console.log("diaplay code: ", Edubot.codeGeneration(Blockly.JavaScript));
    if(content) content.innerText = Edubot.codeGeneration(Blockly.JavaScript);
  });   

};

/**
 * change system language
 */
Edubot.changeLanguage = function() {
    let newLang = event.currentTarget.getAttribute("value");
    let url = "blockly/msg/js/"+newLang+".js";
    let replace_div = '<script id="system_language" src="blockly/msg/js/'+newLang+'.js"/>';
    //$('#system_language').replaceWith(replace_div);

    //console.log(document.URL);
    $('#blocklyDiv').load(url +  ' #blocklyDiv');
}

/**
 * Discard all blocks from the workspace.
 */
Edubot.discard = function() {
  var count = Edubot.workspace.getAllBlocks(false).length;
  if (count < 2 ||
      window.confirm(Blockly.Msg['DELETE_ALL_BLOCKS'].replace('%1', count))) {
      Edubot.workspace.clear();
    if (window.location.hash) {
      window.location.hash = '';
    }
  }
};

/**
 * Compute the absolute coordinates and dimensions of an HTML element.
 * @param {!Element} element Element to match.
 * @return {!Object} Contains height, width, x, and y properties.
 * @private
 */
Edubot.getBBox_ = function(element) {
    var height = element.offsetHeight;
    var width = element.offsetWidth;
    var x = 0;
    var y = 0;
    do {
      x += element.offsetLeft;
      y += element.offsetTop;
      element = element.offsetParent;
    } while (element);
    return {
      height: height,
      width: width,
      x: x,
      y: y
    };
};

/**
 * Initialize Blockly. Called on page load.
 */
Edubot.init = function() {
    //Edubot.initLanguage();

    let blocklyArea = document.getElementById('blocklyArea');
    let blocklyDiv = document.getElementById('blocklyDiv');

    Edubot.workspace = Blockly.inject(blocklyDiv,
    {
        collapse: true,
        toolbox: document.getElementById('toolbox'),
        zoom:
        {
            controls: true,
            wheel: true,
            startScale: 1.0,
            maxScale: 2,
            minScale: 0.5,
            scaleSpeed: 1.1
        }
    });

    let onresize = function(e) {
        // Compute the absolute coordinates and dimensions of blocklyArea.
        let container = blocklyArea;
        let bBox = Edubot.getBBox_(container);

        // Position blocklyDiv over blocklyArea.
        blocklyDiv.style.left = bBox.x + 'px';
        blocklyDiv.style.top = bBox.y + 'px';
        blocklyDiv.style.width = bBox.width + 'px';
        blocklyDiv.style.height = bBox.height + 'px';
        Blockly.svgResize(Edubot.workspace);
    };

    window.addEventListener('resize', onresize, false);

    setTimeout(loadWorkspace, 0);
    //test later
    // Edubot.loadBlocks('');

    // if ('BlocklyStorage' in window) {
    //   // Hook a save function onto unload.
    //   BlocklyStorage.backupOnUnload(Code.workspace);
    // }


    onresize();
    Blockly.svgResize(Edubot.workspace);

    Edubot.initHandlers();
  
    // Lazy-load the syntax-highlighting.
    //window.setTimeout(Edubot.importPrettify, 1);
  };

  window.addEventListener('load', Edubot.init);
  //window.addEventListener('onbeforeunload', saveWorkspace)