var jsPsychHtmlButtonResponse=function(t){"use strict";const e={name:"html-button-response",parameters:{stimulus:{type:t.ParameterType.HTML_STRING,pretty_name:"Stimulus",default:void 0},choices:{type:t.ParameterType.STRING,pretty_name:"Choices",default:void 0,array:!0},button_html:{type:t.ParameterType.HTML_STRING,pretty_name:"Button HTML",default:'<button class="jspsych-btn">%choice%</button>',array:!0},prompt:{type:t.ParameterType.HTML_STRING,pretty_name:"Prompt",default:null},stimulus_duration:{type:t.ParameterType.INT,pretty_name:"Stimulus duration",default:null},trial_duration:{type:t.ParameterType.INT,pretty_name:"Trial duration",default:null},margin_vertical:{type:t.ParameterType.STRING,pretty_name:"Margin vertical",default:"0px"},margin_horizontal:{type:t.ParameterType.STRING,pretty_name:"Margin horizontal",default:"8px"},response_ends_trial:{type:t.ParameterType.BOOL,pretty_name:"Response ends trial",default:!0}}};class s{constructor(t){this.jsPsych=t}trial(t,e){var s='<div id="jspsych-html-button-response-stimulus">'+e.stimulus+"</div>",r=[];if(Array.isArray(e.button_html))e.button_html.length==e.choices.length?r=e.button_html:console.error("Error in html-button-response plugin. The length of the button_html array does not equal the length of the choices array");else for(var n=0;n<e.choices.length;n++)r.push(e.button_html);s+='<div id="jspsych-html-button-response-btngroup">';for(n=0;n<e.choices.length;n++){var a=r[n].replace(/%choice%/g,e.choices[n]);s+='<div class="jspsych-html-button-response-button" style="display: inline-block; margin:'+e.margin_vertical+" "+e.margin_horizontal+'" id="jspsych-html-button-response-button-'+n+'" data-choice="'+n+'">'+a+"</div>"}s+="</div>",null!==e.prompt&&(s+=e.prompt),t.innerHTML=s;var i=performance.now();for(n=0;n<e.choices.length;n++)t.querySelector("#jspsych-html-button-response-button-"+n).addEventListener("click",(t=>{u(t.currentTarget.getAttribute("data-choice"))}));var l={rt:null,button:null};const o=()=>{this.jsPsych.pluginAPI.clearAllTimeouts();var s={rt:l.rt,stimulus:e.stimulus,response:l.button};t.innerHTML="",this.jsPsych.finishTrial(s)};function u(s){var r=performance.now(),n=Math.round(r-i);l.button=parseInt(s),l.rt=n,t.querySelector("#jspsych-html-button-response-stimulus").className+=" responded";for(var a=document.querySelectorAll(".jspsych-html-button-response-button button"),u=0;u<a.length;u++)a[u].setAttribute("disabled","disabled");e.response_ends_trial&&o()}null!==e.stimulus_duration&&this.jsPsych.pluginAPI.setTimeout((()=>{t.querySelector("#jspsych-html-button-response-stimulus").style.visibility="hidden"}),e.stimulus_duration),null!==e.trial_duration&&this.jsPsych.pluginAPI.setTimeout(o,e.trial_duration)}simulate(t,e,s,r){"data-only"==e&&(r(),this.simulate_data_only(t,s)),"visual"==e&&this.simulate_visual(t,s,r)}create_simulation_data(t,e){const s={stimulus:t.stimulus,rt:this.jsPsych.randomization.sampleExGaussian(500,50,1/150,!0),response:this.jsPsych.randomization.randomInt(0,t.choices.length-1)},r=this.jsPsych.pluginAPI.mergeSimulationData(s,e);return this.jsPsych.pluginAPI.ensureSimulationDataConsistency(t,r),r}simulate_data_only(t,e){const s=this.create_simulation_data(t,e);this.jsPsych.finishTrial(s)}simulate_visual(t,e,s){const r=this.create_simulation_data(t,e),n=this.jsPsych.getDisplayElement();this.trial(n,t),s(),null!==r.rt&&this.jsPsych.pluginAPI.clickTarget(n.querySelector(`div[data-choice="${r.response}"] button`),r.rt)}}return s.info=e,s}(jsPsychModule);
//# sourceMappingURL=index.browser.min.js.map