
/**
 * @fileoverview Custom Blocks for aiforu's Edubot application.
 * @author liujialiwhu@google.com (Jiali Liu)
 */

Blockly.Blocks['start_motor'] = {
  init: function() {
    this.jsonInit({
        "message0": "run %1  motor at speed %2",
        "args0": [
            {
            "type": "field_dropdown",
            "name": "choose_motor",
            "options": [
                [
                "left",
                "left_motor"
                ],
                [
                "right",
                "right_motor"
                ]
            ]
            },
            {
            "type": "field_number",
            "name": "speed",
            "value": 0,
            "min": 0,
            "max": 120
            }
        ],
        "previousStatement": null,
        "nextStatement": null,
        "colour": 0,
        "tooltip": "",
        "helpUrl": ""
    });
  }
};

Blockly.Blocks['stop_motor'] = {
    init: function() {
      this.jsonInit({
        "message0": "stop motor %1",
        "args0": [
          {
            "type": "field_dropdown",
            "name": "choose_motor",
            "options": [
              [
                "left",
                "left_motor"
              ],
              [
                "right",
                "right_motor"
              ]
            ]
          }
        ],
        "previousStatement": null,
        "nextStatement": null,
        "colour": 0,
        "tooltip": "",
        "helpUrl": ""
      });
    }
};

Blockly.Blocks['stop_all_motors'] = {
    init: function() {
      this.jsonInit({
        "message0": "stop all motors",
        "previousStatement": null,
        "nextStatement": null,
        "colour": 0,
        "tooltip": "",
        "helpUrl": ""
      });
    }
};

Blockly.Blocks['grayscale'] = {
    init: function() {
        this.jsonInit({
            "message0": " Grayscale %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                  [
                    "4",
                    "port4"
                  ],
                  [
                    "5",
                    "port5"
                  ]
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};

//Temperature Sensor 
Blockly.Blocks['temperature'] = {
    init: function() {
        this.jsonInit({
            "message0": " Temperature sensor %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};

//Humidity Sensor
Blockly.Blocks['humidity'] = {
    init: function() {
        this.jsonInit({
            "message0": " Humidity sensor %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};

//Decibel Senspr
Blockly.Blocks['decibel'] = {
    init: function() {
        this.jsonInit({
            "message0": " Decibel sensor %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};

//Infrared Sensor
Blockly.Blocks['infrared'] = {
    init: function() {
        this.jsonInit({
            "message0": " Infrared sensor %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                  [
                    "4",
                    "port4"
                  ],
                  [
                    "5",
                    "port5"
                  ]
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};

//Ultrasonic
Blockly.Blocks['ultrasonic'] = {
    init: function() {
        this.jsonInit({
            "message0": " Ultrasonic sensor %1",
            "args0": [
              {
                "type": "field_dropdown",
                "name": "choose_port",
                "options": [
                  [
                    "1",
                    "port1"
                  ],
                  [
                    "2",
                    "port2"
                  ],
                  [
                    "3",
                    "port3"
                  ],
                  [
                    "4",
                    "port4"
                  ],
                  [
                    "5",
                    "port5"
                  ]
                ]
              }
            ],
            "output": "Number",
            "colour": 230,
            "tooltip": "",
            "helpUrl": ""
        });
    }
};



/**
 * 
 *
 */
Blockly.JavaScript['start_motor'] = function(block) {
    var dropdown_choose_motor = block.getFieldValue('choose_motor');
    var number_speed = block.getFieldValue('speed');
    // TODO: Assemble JavaScript into code variable.
    var code = 'start '+ dropdown_choose_motor +' motor at speed of '+ number_speed +';\n';
    return code;
  };
  
  Blockly.JavaScript['stop_motor'] = function(block) {
    var dropdown_choose_motor = block.getFieldValue('choose_motor');
    // TODO: Assemble JavaScript into code variable.
    var code = '...;\n';
    return code;
  };
  
  Blockly.JavaScript['stop_all_motors'] = function(block) {
    // TODO: Assemble JavaScript into code variable.
    var code = '...;\n';
    return code;
  };
  
  Blockly.JavaScript['grayscale'] = function(block) {
    let dropdown_choose_port = block.getFieldValue('choose_port');
    // TODO: Assemble JavaScript into code variable.
    let value;
    switch (dropdown_choose_port) {
        case 'port1': 
            value = 300;
            break;
        case 'port2':
            value = 3000;
            break;
        case 'port3':
            value = 433;
            break;
        case 'port4':
            value = 433;
            break;
        case 'port5':
            value = 433;
            break;
        default:
            value = 0;
    }
    return [value.toString(), Blockly.JavaScript.ORDER_NONE];
    //return value.toString();
  };


  Blockly.JavaScript['end_program'] = function(block) {
    // TODO: Assemble JavaScript into code variable.
    var code = '...;\n';
    return code;
  };
  
  Blockly.JavaScript['timer'] = function(block) {
    var number_time = block.getFieldValue('time');
    var dropdown_time_unit = block.getFieldValue('time_unit');
    // TODO: Assemble JavaScript into code variable.
    var code = '...;\n';
    return code;
  };
  
  Blockly.JavaScript['program'] = function(block) {
    var statements_name = Blockly.JavaScript.statementToCode(block, 'NAME');
    // TODO: Assemble JavaScript into code variable.
    var code = '...;\n';
    return code;
  };