var jsPsychCallFunction=function(t){"use strict";const n={name:"call-function",parameters:{func:{type:t.ParameterType.FUNCTION,pretty_name:"Function",default:void 0},async:{type:t.ParameterType.BOOL,pretty_name:"Asynchronous",default:!1}}};class s{constructor(t){this.jsPsych=t}trial(t,n){let s;const e=()=>{const t={value:s};this.jsPsych.finishTrial(t)};if(n.async){const t=t=>{s=t,e()};n.func(t)}else s=n.func(),e()}}return s.info=n,s}(jsPsychModule);
//# sourceMappingURL=index.browser.min.js.map
