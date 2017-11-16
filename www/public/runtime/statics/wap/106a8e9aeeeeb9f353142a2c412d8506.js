/*!
 * jQuery JavaScript Library v1.6.2
 * http://jquery.com/
 *
 * Copyright 2011, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 * Copyright 2011, The Dojo Foundation
 * Released under the MIT, BSD, and GPL Licenses.
 *
 * Date: Thu Jun 30 14:16:56 2011 -0400
 */
(function(a,b){function cv(a){return f.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}function cs(a){if(!cg[a]){var b=c.body,d=f("<"+a+">").appendTo(b),e=d.css("display");d.remove();if(e==="none"||e===""){ch||(ch=c.createElement("iframe"),ch.frameBorder=ch.width=ch.height=0),b.appendChild(ch);if(!ci||!ch.createElement)ci=(ch.contentWindow||ch.contentDocument).document,ci.write((c.compatMode==="CSS1Compat"?"<!doctype html>":"")+"<html><body>"),ci.close();d=ci.createElement(a),ci.body.appendChild(d),e=f.css(d,"display"),b.removeChild(ch)}cg[a]=e}return cg[a]}function cr(a,b){var c={};f.each(cm.concat.apply([],cm.slice(0,b)),function(){c[this]=a});return c}function cq(){cn=b}function cp(){setTimeout(cq,0);return cn=f.now()}function cf(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function ce(){try{return new a.XMLHttpRequest}catch(b){}}function b$(a,c){a.dataFilter&&(c=a.dataFilter(c,a.dataType));var d=a.dataTypes,e={},g,h,i=d.length,j,k=d[0],l,m,n,o,p;for(g=1;g<i;g++){if(g===1)for(h in a.converters)typeof h=="string"&&(e[h.toLowerCase()]=a.converters[h]);l=k,k=d[g];if(k==="*")k=l;else if(l!=="*"&&l!==k){m=l+" "+k,n=e[m]||e["* "+k];if(!n){p=b;for(o in e){j=o.split(" ");if(j[0]===l||j[0]==="*"){p=e[j[1]+" "+k];if(p){o=e[o],o===!0?n=p:p===!0&&(n=o);break}}}}!n&&!p&&f.error("No conversion from "+m.replace(" "," to ")),n!==!0&&(c=n?n(c):p(o(c)))}}return c}function bZ(a,c,d){var e=a.contents,f=a.dataTypes,g=a.responseFields,h,i,j,k;for(i in g)i in d&&(c[g[i]]=d[i]);while(f[0]==="*")f.shift(),h===b&&(h=a.mimeType||c.getResponseHeader("content-type"));if(h)for(i in e)if(e[i]&&e[i].test(h)){f.unshift(i);break}if(f[0]in d)j=f[0];else{for(i in d){if(!f[0]||a.converters[i+" "+f[0]]){j=i;break}k||(k=i)}j=j||k}if(j){j!==f[0]&&f.unshift(j);return d[j]}}function bY(a,b,c,d){if(f.isArray(b))f.each(b,function(b,e){c||bC.test(a)?d(a,e):bY(a+"["+(typeof e=="object"||f.isArray(e)?b:"")+"]",e,c,d)});else if(!c&&b!=null&&typeof b=="object")for(var e in b)bY(a+"["+e+"]",b[e],c,d);else d(a,b)}function bX(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h=a[f],i=0,j=h?h.length:0,k=a===bR,l;for(;i<j&&(k||!l);i++)l=h[i](c,d,e),typeof l=="string"&&(!k||g[l]?l=b:(c.dataTypes.unshift(l),l=bX(a,c,d,e,l,g)));(k||!l)&&!g["*"]&&(l=bX(a,c,d,e,"*",g));return l}function bW(a){return function(b,c){typeof b!="string"&&(c=b,b="*");if(f.isFunction(c)){var d=b.toLowerCase().split(bN),e=0,g=d.length,h,i,j;for(;e<g;e++)h=d[e],j=/^\+/.test(h),j&&(h=h.substr(1)||"*"),i=a[h]=a[h]||[],i[j?"unshift":"push"](c)}}}function bA(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=b==="width"?bv:bw;if(d>0){c!=="border"&&f.each(e,function(){c||(d-=parseFloat(f.css(a,"padding"+this))||0),c==="margin"?d+=parseFloat(f.css(a,c+this))||0:d-=parseFloat(f.css(a,"border"+this+"Width"))||0});return d+"px"}d=bx(a,b,b);if(d<0||d==null)d=a.style[b]||0;d=parseFloat(d)||0,c&&f.each(e,function(){d+=parseFloat(f.css(a,"padding"+this))||0,c!=="padding"&&(d+=parseFloat(f.css(a,"border"+this+"Width"))||0),c==="margin"&&(d+=parseFloat(f.css(a,c+this))||0)});return d+"px"}function bm(a,b){b.src?f.ajax({url:b.src,async:!1,dataType:"script"}):f.globalEval((b.text||b.textContent||b.innerHTML||"").replace(be,"/*$0*/")),b.parentNode&&b.parentNode.removeChild(b)}function bl(a){f.nodeName(a,"input")?bk(a):"getElementsByTagName"in a&&f.grep(a.getElementsByTagName("input"),bk)}function bk(a){if(a.type==="checkbox"||a.type==="radio")a.defaultChecked=a.checked}function bj(a){return"getElementsByTagName"in a?a.getElementsByTagName("*"):"querySelectorAll"in a?a.querySelectorAll("*"):[]}function bi(a,b){var c;if(b.nodeType===1){b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase();if(c==="object")b.outerHTML=a.outerHTML;else if(c!=="input"||a.type!=="checkbox"&&a.type!=="radio"){if(c==="option")b.selected=a.defaultSelected;else if(c==="input"||c==="textarea")b.defaultValue=a.defaultValue}else a.checked&&(b.defaultChecked=b.checked=a.checked),b.value!==a.value&&(b.value=a.value);b.removeAttribute(f.expando)}}function bh(a,b){if(b.nodeType===1&&!!f.hasData(a)){var c=f.expando,d=f.data(a),e=f.data(b,d);if(d=d[c]){var g=d.events;e=e[c]=f.extend({},d);if(g){delete e.handle,e.events={};for(var h in g)for(var i=0,j=g[h].length;i<j;i++)f.event.add(b,h+(g[h][i].namespace?".":"")+g[h][i].namespace,g[h][i],g[h][i].data)}}}}function bg(a,b){return f.nodeName(a,"table")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function W(a,b,c){b=b||0;if(f.isFunction(b))return f.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return f.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=f.grep(a,function(a){return a.nodeType===1});if(R.test(b))return f.filter(b,d,!c);b=f.filter(b,d)}return f.grep(a,function(a,d){return f.inArray(a,b)>=0===c})}function V(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function N(a,b){return(a&&a!=="*"?a+".":"")+b.replace(z,"`").replace(A,"&")}function M(a){var b,c,d,e,g,h,i,j,k,l,m,n,o,p=[],q=[],r=f._data(this,"events");if(!(a.liveFired===this||!r||!r.live||a.target.disabled||a.button&&a.type==="click")){a.namespace&&(n=new RegExp("(^|\\.)"+a.namespace.split(".").join("\\.(?:.*\\.)?")+"(\\.|$)")),a.liveFired=this;var s=r.live.slice(0);for(i=0;i<s.length;i++)g=s[i],g.origType.replace(x,"")===a.type?q.push(g.selector):s.splice(i--,1);e=f(a.target).closest(q,a.currentTarget);for(j=0,k=e.length;j<k;j++){m=e[j];for(i=0;i<s.length;i++){g=s[i];if(m.selector===g.selector&&(!n||n.test(g.namespace))&&!m.elem.disabled){h=m.elem,d=null;if(g.preType==="mouseenter"||g.preType==="mouseleave")a.type=g.preType,d=f(a.relatedTarget).closest(g.selector)[0],d&&f.contains(h,d)&&(d=h);(!d||d!==h)&&p.push({elem:h,handleObj:g,level:m.level})}}}for(j=0,k=p.length;j<k;j++){e=p[j];if(c&&e.level>c)break;a.currentTarget=e.elem,a.data=e.handleObj.data,a.handleObj=e.handleObj,o=e.handleObj.origHandler.apply(e.elem,arguments);if(o===!1||a.isPropagationStopped()){c=e.level,o===!1&&(b=!1);if(a.isImmediatePropagationStopped())break}}return b}}function K(a,c,d){var e=f.extend({},d[0]);e.type=a,e.originalEvent={},e.liveFired=b,f.event.handle.call(c,e),e.isDefaultPrevented()&&d[0].preventDefault()}function E(){return!0}function D(){return!1}function m(a,c,d){var e=c+"defer",g=c+"queue",h=c+"mark",i=f.data(a,e,b,!0);i&&(d==="queue"||!f.data(a,g,b,!0))&&(d==="mark"||!f.data(a,h,b,!0))&&setTimeout(function(){!f.data(a,g,b,!0)&&!f.data(a,h,b,!0)&&(f.removeData(a,e,!0),i.resolve())},0)}function l(a){for(var b in a)if(b!=="toJSON")return!1;return!0}function k(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(j,"$1-$2").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:f.isNaN(d)?i.test(d)?f.parseJSON(d):d:parseFloat(d)}catch(g){}f.data(a,c,d)}else d=b}return d}var c=a.document,d=a.navigator,e=a.location,f=function(){function J(){if(!e.isReady){try{c.documentElement.doScroll("left")}catch(a){setTimeout(J,1);return}e.ready()}}var e=function(a,b){return new e.fn.init(a,b,h)},f=a.jQuery,g=a.$,h,i=/^(?:[^<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,j=/\S/,k=/^\s+/,l=/\s+$/,m=/\d/,n=/^<(\w+)\s*\/?>(?:<\/\1>)?$/,o=/^[\],:{}\s]*$/,p=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,q=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,r=/(?:^|:|,)(?:\s*\[)+/g,s=/(webkit)[ \/]([\w.]+)/,t=/(opera)(?:.*version)?[ \/]([\w.]+)/,u=/(msie) ([\w.]+)/,v=/(mozilla)(?:.*? rv:([\w.]+))?/,w=/-([a-z])/ig,x=function(a,b){return b.toUpperCase()},y=d.userAgent,z,A,B,C=Object.prototype.toString,D=Object.prototype.hasOwnProperty,E=Array.prototype.push,F=Array.prototype.slice,G=String.prototype.trim,H=Array.prototype.indexOf,I={};e.fn=e.prototype={constructor:e,init:function(a,d,f){var g,h,j,k;if(!a)return this;if(a.nodeType){this.context=this[0]=a,this.length=1;return this}if(a==="body"&&!d&&c.body){this.context=c,this[0]=c.body,this.selector=a,this.length=1;return this}if(typeof a=="string"){a.charAt(0)!=="<"||a.charAt(a.length-1)!==">"||a.length<3?g=i.exec(a):g=[null,a,null];if(g&&(g[1]||!d)){if(g[1]){d=d instanceof e?d[0]:d,k=d?d.ownerDocument||d:c,j=n.exec(a),j?e.isPlainObject(d)?(a=[c.createElement(j[1])],e.fn.attr.call(a,d,!0)):a=[k.createElement(j[1])]:(j=e.buildFragment([g[1]],[k]),a=(j.cacheable?e.clone(j.fragment):j.fragment).childNodes);return e.merge(this,a)}h=c.getElementById(g[2]);if(h&&h.parentNode){if(h.id!==g[2])return f.find(a);this.length=1,this[0]=h}this.context=c,this.selector=a;return this}return!d||d.jquery?(d||f).find(a):this.constructor(d).find(a)}if(e.isFunction(a))return f.ready(a);a.selector!==b&&(this.selector=a.selector,this.context=a.context);return e.makeArray(a,this)},selector:"",jquery:"1.6.2",length:0,size:function(){return this.length},toArray:function(){return F.call(this,0)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=this.constructor();e.isArray(a)?E.apply(d,a):e.merge(d,a),d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")");return d},each:function(a,b){return e.each(this,a,b)},ready:function(a){e.bindReady(),A.done(a);return this},eq:function(a){return a===-1?this.slice(a):this.slice(a,+a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(F.apply(this,arguments),"slice",F.call(arguments).join(","))},map:function(a){return this.pushStack(e.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:E,sort:[].sort,splice:[].splice},e.fn.init.prototype=e.fn,e.extend=e.fn.extend=function(){var a,c,d,f,g,h,i=arguments[0]||{},j=1,k=arguments.length,l=!1;typeof i=="boolean"&&(l=i,i=arguments[1]||{},j=2),typeof i!="object"&&!e.isFunction(i)&&(i={}),k===j&&(i=this,--j);for(;j<k;j++)if((a=arguments[j])!=null)for(c in a){d=i[c],f=a[c];if(i===f)continue;l&&f&&(e.isPlainObject(f)||(g=e.isArray(f)))?(g?(g=!1,h=d&&e.isArray(d)?d:[]):h=d&&e.isPlainObject(d)?d:{},i[c]=e.extend(l,h,f)):f!==b&&(i[c]=f)}return i},e.extend({noConflict:function(b){a.$===e&&(a.$=g),b&&a.jQuery===e&&(a.jQuery=f);return e},isReady:!1,readyWait:1,holdReady:function(a){a?e.readyWait++:e.ready(!0)},ready:function(a){if(a===!0&&!--e.readyWait||a!==!0&&!e.isReady){if(!c.body)return setTimeout(e.ready,1);e.isReady=!0;if(a!==!0&&--e.readyWait>0)return;A.resolveWith(c,[e]),e.fn.trigger&&e(c).trigger("ready").unbind("ready")}},bindReady:function(){if(!A){A=e._Deferred();if(c.readyState==="complete")return setTimeout(e.ready,1);if(c.addEventListener)c.addEventListener("DOMContentLoaded",B,!1),a.addEventListener("load",e.ready,!1);else if(c.attachEvent){c.attachEvent("onreadystatechange",B),a.attachEvent("onload",e.ready);var b=!1;try{b=a.frameElement==null}catch(d){}c.documentElement.doScroll&&b&&J()}}},isFunction:function(a){return e.type(a)==="function"},isArray:Array.isArray||function(a){return e.type(a)==="array"},isWindow:function(a){return a&&typeof a=="object"&&"setInterval"in a},isNaN:function(a){return a==null||!m.test(a)||isNaN(a)},type:function(a){return a==null?String(a):I[C.call(a)]||"object"},isPlainObject:function(a){if(!a||e.type(a)!=="object"||a.nodeType||e.isWindow(a))return!1;if(a.constructor&&!D.call(a,"constructor")&&!D.call(a.constructor.prototype,"isPrototypeOf"))return!1;var c;for(c in a);return c===b||D.call(a,c)},isEmptyObject:function(a){for(var b in a)return!1;return!0},error:function(a){throw a},parseJSON:function(b){if(typeof b!="string"||!b)return null;b=e.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(o.test(b.replace(p,"@").replace(q,"]").replace(r,"")))return(new Function("return "+b))();e.error("Invalid JSON: "+b)},parseXML:function(b,c,d){a.DOMParser?(d=new DOMParser,c=d.parseFromString(b,"text/xml")):(c=new ActiveXObject("Microsoft.XMLDOM"),c.async="false",c.loadXML(b)),d=c.documentElement,(!d||!d.nodeName||d.nodeName==="parsererror")&&e.error("Invalid XML: "+b);return c},noop:function(){},globalEval:function(b){b&&j.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(w,x)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toUpperCase()===b.toUpperCase()},each:function(a,c,d){var f,g=0,h=a.length,i=h===b||e.isFunction(a);if(d){if(i){for(f in a)if(c.apply(a[f],d)===!1)break}else for(;g<h;)if(c.apply(a[g++],d)===!1)break}else if(i){for(f in a)if(c.call(a[f],f,a[f])===!1)break}else for(;g<h;)if(c.call(a[g],g,a[g++])===!1)break;return a},trim:G?function(a){return a==null?"":G.call(a)}:function(a){return a==null?"":(a+"").replace(k,"").replace(l,"")},makeArray:function(a,b){var c=b||[];if(a!=null){var d=e.type(a);a.length==null||d==="string"||d==="function"||d==="regexp"||e.isWindow(a)?E.call(c,a):e.merge(c,a)}return c},inArray:function(a,b){if(H)return H.call(b,a);for(var c=0,d=b.length;c<d;c++)if(b[c]===a)return c;return-1},merge:function(a,c){var d=a.length,e=0;if(typeof c.length=="number")for(var f=c.length;e<f;e++)a[d++]=c[e];else while(c[e]!==b)a[d++]=c[e++];a.length=d;return a},grep:function(a,b,c){var d=[],e;c=!!c;for(var f=0,g=a.length;f<g;f++)e=!!b(a[f],f),c!==e&&d.push(a[f]);return d},map:function(a,c,d){var f,g,h=[],i=0,j=a.length,k=a instanceof e||j!==b&&typeof j=="number"&&(j>0&&a[0]&&a[j-1]||j===0||e.isArray(a));if(k)for(;i<j;i++)f=c(a[i],i,d),f!=null&&(h[h.length]=f);else for(g in a)f=c(a[g],g,d),f!=null&&(h[h.length]=f);return h.concat.apply([],h)},guid:1,proxy:function(a,c){if(typeof c=="string"){var d=a[c];c=a,a=d}if(!e.isFunction(a))return b;var f=F.call(arguments,2),g=function(){return a.apply(c,f.concat(F.call(arguments)))};g.guid=a.guid=a.guid||g.guid||e.guid++;return g},access:function(a,c,d,f,g,h){var i=a.length;if(typeof c=="object"){for(var j in c)e.access(a,j,c[j],f,g,d);return a}if(d!==b){f=!h&&f&&e.isFunction(d);for(var k=0;k<i;k++)g(a[k],c,f?d.call(a[k],k,g(a[k],c)):d,h);return a}return i?g(a[0],c):b},now:function(){return(new Date).getTime()},uaMatch:function(a){a=a.toLowerCase();var b=s.exec(a)||t.exec(a)||u.exec(a)||a.indexOf("compatible")<0&&v.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},sub:function(){function a(b,c){return new a.fn.init(b,c)}e.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function(d,f){f&&f instanceof e&&!(f instanceof a)&&(f=a(f));return e.fn.init.call(this,d,f,b)},a.fn.init.prototype=a.fn;var b=a(c);return a},browser:{}}),e.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){I["[object "+b+"]"]=b.toLowerCase()}),z=e.uaMatch(y),z.browser&&(e.browser[z.browser]=!0,e.browser.version=z.version),e.browser.webkit&&(e.browser.safari=!0),j.test(" ")&&(k=/^[\s\xA0]+/,l=/[\s\xA0]+$/),h=e(c),c.addEventListener?B=function(){c.removeEventListener("DOMContentLoaded",B,!1),e.ready()}:c.attachEvent&&(B=function(){c.readyState==="complete"&&(c.detachEvent("onreadystatechange",B),e.ready())});return e}(),g="done fail isResolved isRejected promise then always pipe".split(" "),h=[].slice;f.extend({_Deferred:function(){var a=[],b,c,d,e={done:function(){if(!d){var c=arguments,g,h,i,j,k;b&&(k=b,b=0);for(g=0,h=c.length;g<h;g++)i=c[g],j=f.type(i),j==="array"?e.done.apply(e,i):j==="function"&&a.push(i);k&&e.resolveWith(k[0],k[1])}return this},resolveWith:function(e,f){if(!d&&!b&&!c){f=f||[],c=1;try{while(a[0])a.shift().apply(e,f)}finally{b=[e,f],c=0}}return this},resolve:function(){e.resolveWith(this,arguments);return this},isResolved:function(){return!!c||!!b},cancel:function(){d=1,a=[];return this}};return e},Deferred:function(a){var b=f._Deferred(),c=f._Deferred(),d;f.extend(b,{then:function(a,c){b.done(a).fail(c);return this},always:function(){return b.done.apply(b,arguments).fail.apply(this,arguments)},fail:c.done,rejectWith:c.resolveWith,reject:c.resolve,isRejected:c.isResolved,pipe:function(a,c){return f.Deferred(function(d){f.each({done:[a,"resolve"],fail:[c,"reject"]},function(a,c){var e=c[0],g=c[1],h;f.isFunction(e)?b[a](function(){h=e.apply(this,arguments),h&&f.isFunction(h.promise)?h.promise().then(d.resolve,d.reject):d[g](h)}):b[a](d[g])})}).promise()},promise:function(a){if(a==null){if(d)return d;d=a={}}var c=g.length;while(c--)a[g[c]]=b[g[c]];return a}}),b.done(c.cancel).fail(b.cancel),delete b.cancel,a&&a.call(b,b);return b},when:function(a){function i(a){return function(c){b[a]=arguments.length>1?h.call(arguments,0):c,--e||g.resolveWith(g,h.call(b,0))}}var b=arguments,c=0,d=b.length,e=d,g=d<=1&&a&&f.isFunction(a.promise)?a:f.Deferred();if(d>1){for(;c<d;c++)b[c]&&f.isFunction(b[c].promise)?b[c].promise().then(i(c),g.reject):--e;e||g.resolveWith(g,b)}else g!==a&&g.resolveWith(g,d?[a]:[]);return g.promise()}}),f.support=function(){var a=c.createElement("div"),b=c.documentElement,d,e,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u;a.setAttribute("className","t"),a.innerHTML="   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>",d=a.getElementsByTagName("*"),e=a.getElementsByTagName("a")[0];if(!d||!d.length||!e)return{};g=c.createElement("select"),h=g.appendChild(c.createElement("option")),i=a.getElementsByTagName("input")[0],k={leadingWhitespace:a.firstChild.nodeType===3,tbody:!a.getElementsByTagName("tbody").length,htmlSerialize:!!a.getElementsByTagName("link").length,style:/top/.test(e.getAttribute("style")),hrefNormalized:e.getAttribute("href")==="/a",opacity:/^0.55$/.test(e.style.opacity),cssFloat:!!e.style.cssFloat,checkOn:i.value==="on",optSelected:h.selected,getSetAttribute:a.className!=="t",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0},i.checked=!0,k.noCloneChecked=i.cloneNode(!0).checked,g.disabled=!0,k.optDisabled=!h.disabled;try{delete a.test}catch(v){k.deleteExpando=!1}!a.addEventListener&&a.attachEvent&&a.fireEvent&&(a.attachEvent("onclick",function(){k.noCloneEvent=!1}),a.cloneNode(!0).fireEvent("onclick")),i=c.createElement("input"),i.value="t",i.setAttribute("type","radio"),k.radioValue=i.value==="t",i.setAttribute("checked","checked"),a.appendChild(i),l=c.createDocumentFragment(),l.appendChild(a.firstChild),k.checkClone=l.cloneNode(!0).cloneNode(!0).lastChild.checked,a.innerHTML="",a.style.width=a.style.paddingLeft="1px",m=c.getElementsByTagName("body")[0],o=c.createElement(m?"div":"body"),p={visibility:"hidden",width:0,height:0,border:0,margin:0},m&&f.extend(p,{position:"absolute",left:-1e3,top:-1e3});for(t in p)o.style[t]=p[t];o.appendChild(a),n=m||b,n.insertBefore(o,n.firstChild),k.appendChecked=i.checked,k.boxModel=a.offsetWidth===2,"zoom"in a.style&&(a.style.display="inline",a.style.zoom=1,k.inlineBlockNeedsLayout=a.offsetWidth===2,a.style.display="",a.innerHTML="<div style='width:4px;'></div>",k.shrinkWrapBlocks=a.offsetWidth!==2),a.innerHTML="<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>",q=a.getElementsByTagName("td"),u=q[0].offsetHeight===0,q[0].style.display="",q[1].style.display="none",k.reliableHiddenOffsets=u&&q[0].offsetHeight===0,a.innerHTML="",c.defaultView&&c.defaultView.getComputedStyle&&(j=c.createElement("div"),j.style.width="0",j.style.marginRight="0",a.appendChild(j),k.reliableMarginRight=(parseInt((c.defaultView.getComputedStyle(j,null)||{marginRight:0}).marginRight,10)||0)===0),o.innerHTML="",n.removeChild(o);if(a.attachEvent)for(t in{submit:1,change:1,focusin:1})s="on"+t,u=s in a,u||(a.setAttribute(s,"return;"),u=typeof a[s]=="function"),k[t+"Bubbles"]=u;o=l=g=h=m=j=a=i=null;return k}(),f.boxModel=f.support.boxModel;var i=/^(?:\{.*\}|\[.*\])$/,j=/([a-z])([A-Z])/g;f.extend({cache:{},uuid:0,expando:"jQuery"+(f.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){a=a.nodeType?f.cache[a[f.expando]]:a[f.expando];return!!a&&!l(a)},data:function(a,c,d,e){if(!!f.acceptData(a)){var g=f.expando,h=typeof c=="string",i,j=a.nodeType,k=j?f.cache:a,l=j?a[f.expando]:a[f.expando]&&f.expando;if((!l||e&&l&&!k[l][g])&&h&&d===b)return;l||(j?a[f.expando]=l=++f.uuid:l=f.expando),k[l]||(k[l]={},j||(k[l].toJSON=f.noop));if(typeof c=="object"||typeof c=="function")e?k[l][g]=f.extend(k[l][g],c):k[l]=f.extend(k[l],c);i=k[l],e&&(i[g]||(i[g]={}),i=i[g]),d!==b&&(i[f.camelCase(c)]=d);if(c==="events"&&!i[c])return i[g]&&i[g].events;return h?i[f.camelCase(c)]||i[c]:i}},removeData:function(b,c,d){if(!!f.acceptData(b)){var e=f.expando,g=b.nodeType,h=g?f.cache:b,i=g?b[f.expando]:f.expando;if(!h[i])return;if(c){var j=d?h[i][e]:h[i];if(j){delete j[c];if(!l(j))return}}if(d){delete h[i][e];if(!l(h[i]))return}var k=h[i][e];f.support.deleteExpando||h!=a?delete h[i]:h[i]=null,k?(h[i]={},g||(h[i].toJSON=f.noop),h[i][e]=k):g&&(f.support.deleteExpando?delete b[f.expando]:b.removeAttribute?b.removeAttribute(f.expando):b[f.expando]=null)}},_data:function(a,b,c){return f.data(a,b,c,!0)},acceptData:function(a){if(a.nodeName){var b=f.noData[a.nodeName.toLowerCase()];if(b)return b!==!0&&a.getAttribute("classid")===b}return!0}}),f.fn.extend({data:function(a,c){var d=null;if(typeof a=="undefined"){if(this.length){d=f.data(this[0]);if(this[0].nodeType===1){var e=this[0].attributes,g;for(var h=0,i=e.length;h<i;h++)g=e[h].name,g.indexOf("data-")===0&&(g=f.camelCase(g.substring(5)),k(this[0],g,d[g]))}}return d}if(typeof a=="object")return this.each(function(){f.data(this,a)});var j=a.split(".");j[1]=j[1]?"."+j[1]:"";if(c===b){d=this.triggerHandler("getData"+j[1]+"!",[j[0]]),d===b&&this.length&&(d=f.data(this[0],a),d=k(this[0],a,d));return d===b&&j[1]?this.data(j[0]):d}return this.each(function(){var b=f(this),d=[j[0],c];b.triggerHandler("setData"+j[1]+"!",d),f.data(this,a,c),b.triggerHandler("changeData"+j[1]+"!",d)})},removeData:function(a){return this.each(function(){f.removeData(this,a)})}}),f.extend({_mark:function(a,c){a&&(c=(c||"fx")+"mark",f.data(a,c,(f.data(a,c,b,!0)||0)+1,!0))},_unmark:function(a,c,d){a!==!0&&(d=c,c=a,a=!1);if(c){d=d||"fx";var e=d+"mark",g=a?0:(f.data(c,e,b,!0)||1)-1;g?f.data(c,e,g,!0):(f.removeData(c,e,!0),m(c,d,"mark"))}},queue:function(a,c,d){if(a){c=(c||"fx")+"queue";var e=f.data(a,c,b,!0);d&&(!e||f.isArray(d)?e=f.data(a,c,f.makeArray(d),!0):e.push(d));return e||[]}},dequeue:function(a,b){b=b||"fx";var c=f.queue(a,b),d=c.shift(),e;d==="inprogress"&&(d=c.shift()),d&&(b==="fx"&&c.unshift("inprogress"),d.call(a,function(){f.dequeue(a,b)})),c.length||(f.removeData(a,b+"queue",!0),m(a,b,"queue"))}}),f.fn.extend({queue:function(a,c){typeof a!="string"&&(c=a,a="fx");if(c===b)return f.queue(this[0],a);return this.each(function(){var b=f.queue(this,a,c);a==="fx"&&b[0]!=="inprogress"&&f.dequeue(this,a)})},dequeue:function(a){return this.each(function(){f.dequeue(this,a)})},delay:function(a,b){a=f.fx?f.fx.speeds[a]||a:a,b=b||"fx";return this.queue(b,function(){var c=this;setTimeout(function(){f.dequeue(c,b)},a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){function m(){--h||d.resolveWith(e,[e])}typeof a!="string"&&(c=a,a=b),a=a||"fx";var d=f.Deferred(),e=this,g=e.length,h=1,i=a+"defer",j=a+"queue",k=a+"mark",l;while(g--)if(l=f.data(e[g],i,b,!0)||(f.data(e[g],j,b,!0)||f.data(e[g],k,b,!0))&&f.data(e[g],i,f._Deferred(),!0))h++,l.done(m);m();return d.promise()}});var n=/[\n\t\r]/g,o=/\s+/,p=/\r/g,q=/^(?:button|input)$/i,r=/^(?:button|input|object|select|textarea)$/i,s=/^a(?:rea)?$/i,t=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,u=/\:|^on/,v,w;f.fn.extend({attr:function(a,b){return f.access(this,a,b,!0,f.attr)},removeAttr:function(a){return this.each(function(){f.removeAttr(this,a)})},prop:function(a,b){return f.access(this,a,b,!0,f.prop)},removeProp:function(a){a=f.propFix[a]||a;return this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,g,h,i;if(f.isFunction(a))return this.each(function(b){f(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(o);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{g=" "+e.className+" ";for(h=0,i=b.length;h<i;h++)~g.indexOf(" "+b[h]+" ")||(g+=b[h]+" ");e.className=f.trim(g)}}}return this},removeClass:function(a){var c,d,e,g,h,i,j;if(f.isFunction(a))return this.each(function(b){f(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(o);for(d=0,e=this.length;d<e;d++){g=this[d];if(g.nodeType===1&&g.className)if(a){h=(" "+g.className+" ").replace(n," ");for(i=0,j=c.length;i<j;i++)h=h.replace(" "+c[i]+" "," ");g.className=f.trim(h)}else g.className=""}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";if(f.isFunction(a))return this.each(function(c){f(this).toggleClass(a.call(this,c,this.className,b),b)});return this.each(function(){if(c==="string"){var e,g=0,h=f(this),i=b,j=a.split(o);while(e=j[g++])i=d?i:!h.hasClass(e),h[i?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&f._data(this,"__className__",this.className),this.className=this.className||a===!1?"":f._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ";for(var c=0,d=this.length;c<d;c++)if((" "+this[c].className+" ").replace(n," ").indexOf(b)>-1)return!0;return!1},val:function(a){var c,d,e=this[0];if(!arguments.length){if(e){c=f.valHooks[e.nodeName.toLowerCase()]||f.valHooks[e.type];if(c&&"get"in c&&(d=c.get(e,"value"))!==b)return d;d=e.value;return typeof d=="string"?d.replace(p,""):d==null?"":d}return b}var g=f.isFunction(a);return this.each(function(d){var e=f(this),h;if(this.nodeType===1){g?h=a.call(this,d,e.val()):h=a,h==null?h="":typeof h=="number"?h+="":f.isArray(h)&&(h=f.map(h,function(a){return a==null?"":a+""})),c=f.valHooks[this.nodeName.toLowerCase()]||f.valHooks[this.type];if(!c||!("set"in c)||c.set(this,h,"value")===b)this.value=h}})}}),f.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c=a.selectedIndex,d=[],e=a.options,g=a.type==="select-one";if(c<0)return null;for(var h=g?c:0,i=g?c+1:e.length;h<i;h++){var j=e[h];if(j.selected&&(f.support.optDisabled?!j.disabled:j.getAttribute("disabled")===null)&&(!j.parentNode.disabled||!f.nodeName(j.parentNode,"optgroup"))){b=f(j).val();if(g)return b;d.push(b)}}if(g&&!d.length&&e.length)return f(e[c]).val();return d},set:function(a,b){var c=f.makeArray(b);f(a).find("option").each(function(){this.selected=f.inArray(f(this).val(),c)>=0}),c.length||(a.selectedIndex=-1);return c}}},attrFn:{val:!0,css:!0,html:!0,text:!0,data:!0,width:!0,height:!0,offset:!0},attrFix:{tabindex:"tabIndex"},attr:function(a,c,d,e){var g=a.nodeType;if(!a||g===3||g===8||g===2)return b;if(e&&c in f.attrFn)return f(a)[c](d);if(!("getAttribute"in a))return f.prop(a,c,d);var h,i,j=g!==1||!f.isXMLDoc(a);j&&(c=f.attrFix[c]||c,i=f.attrHooks[c],i||(t.test(c)?i=w:v&&c!=="className"&&(f.nodeName(a,"form")||u.test(c))&&(i=v)));if(d!==b){if(d===null){f.removeAttr(a,c);return b}if(i&&"set"in i&&j&&(h=i.set(a,d,c))!==b)return h;a.setAttribute(c,""+d);return d}if(i&&"get"in i&&j&&(h=i.get(a,c))!==null)return h;h=a.getAttribute(c);return h===null?b:h},removeAttr:function(a,b){var c;a.nodeType===1&&(b=f.attrFix[b]||b,f.support.getSetAttribute?a.removeAttribute(b):(f.attr(a,b,""),a.removeAttributeNode(a.getAttributeNode(b))),t.test(b)&&(c=f.propFix[b]||b)in a&&(a[c]=!1))},attrHooks:{type:{set:function(a,b){if(q.test(a.nodeName)&&a.parentNode)f.error("type property can't be changed");else if(!f.support.radioValue&&b==="radio"&&f.nodeName(a,"input")){var c=a.value;a.setAttribute("type",b),c&&(a.value=c);return b}}},tabIndex:{get:function(a){var c=a.getAttributeNode("tabIndex");return c&&c.specified?parseInt(c.value,10):r.test(a.nodeName)||s.test(a.nodeName)&&a.href?0:b}},value:{get:function(a,b){if(v&&f.nodeName(a,"button"))return v.get(a,b);return b in a?a.value:null},set:function(a,b,c){if(v&&f.nodeName(a,"button"))return v.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e=a.nodeType;if(!a||e===3||e===8||e===2)return b;var g,h,i=e!==1||!f.isXMLDoc(a);i&&(c=f.propFix[c]||c,h=f.propHooks[c]);return d!==b?h&&"set"in h&&(g=h.set(a,d,c))!==b?g:a[c]=d:h&&"get"in h&&(g=h.get(a,c))!==b?g:a[c]},propHooks:{}}),w={get:function(a,c){return f.prop(a,c)?c.toLowerCase():b},set:function(a,b,c){var d;b===!1?f.removeAttr(a,c):(d=f.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase()));return c}},f.support.getSetAttribute||(f.attrFix=f.propFix,v=f.attrHooks.name=f.attrHooks.title=f.valHooks.button={get:function(a,c){var d;d=a.getAttributeNode(c);return d&&d.nodeValue!==""?d.nodeValue:b},set:function(a,b,c){var d=a.getAttributeNode(c);if(d){d.nodeValue=b;return b}}},f.each(["width","height"],function(a,b){f.attrHooks[b]=f.extend(f.attrHooks[b],{set:function(a,c){if(c===""){a.setAttribute(b,"auto");return c}}})})),f.support.hrefNormalized||f.each(["href","src","width","height"],function(a,c){f.attrHooks[c]=f.extend(f.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),f.support.style||(f.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=""+b}}),f.support.optSelected||(f.propHooks.selected=f.extend(f.propHooks.selected,{get:function(a){var b=a.parentNode;b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex)}})),f.support.checkOn||f.each(["radio","checkbox"],function(){f.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),f.each(["radio","checkbox"],function(){f.valHooks[this]=f.extend(f.valHooks[this],{set:function(a,b){if(f.isArray(b))return a.checked=f.inArray(f(a).val(),b)>=0}})});var x=/\.(.*)$/,y=/^(?:textarea|input|select)$/i,z=/\./g,A=/ /g,B=/[^\w\s.|`]/g,C=function(a){return a.replace(B,"\\$&")};f.event={add:function(a,c,d,e){if(a.nodeType!==3&&a.nodeType!==8){if(d===!1)d=D;else if(!d)return;var g,h;d.handler&&(g=d,d=g.handler),d.guid||(d.guid=f.guid++);var i=f._data(a);if(!i)return;var j=i.events,k=i.handle;j||(i.events=j={}),k||(i.handle=k=function(a){return typeof f!="undefined"&&(!a||f.event.triggered!==a.type)?f.event.handle.apply(k.elem,arguments):b}),k.elem=a,c=c.split(" ");var l,m=0,n;while(l=c[m++]){h=g?f.extend({},g):{handler:d,data:e},l.indexOf(".")>-1?(n=l.split("."),l=n.shift(),h.namespace=n.slice(0).sort().join(".")):(n=[],h.namespace=""),h.type=l,h.guid||(h.guid=d.guid);var o=j[l],p=f.event.special[l]||{};if(!o){o=j[l]=[];if(!p.setup||p.setup.call(a,e,n,k)===!1)a.addEventListener?a.addEventListener(l,k,!1):a.attachEvent&&a.attachEvent("on"+l,k)}p.add&&(p.add.call(a,h),h.handler.guid||(h.handler.guid=d.guid)),o.push(h),f.event.global[l]=!0}a=null}},global:{},remove:function(a,c,d,e){if(a.nodeType!==3&&a.nodeType!==8){d===!1&&(d=D);var g,h,i,j,k=0,l,m,n,o,p,q,r,s=f.hasData(a)&&f._data(a),t=s&&s.events;if(!s||!t)return;c&&c.type&&(d=c.handler,c=c.type);if(!c||typeof c=="string"&&c.charAt(0)==="."){c=c||"";for(h in t)f.event.remove(a,h+c);return}c=c.split(" ");while(h=c[k++]){r=h,q=null,l=h.indexOf(".")<0,m=[],l||(m=h.split("."),h=m.shift(),n=new RegExp("(^|\\.)"+f.map(m.slice(0).sort(),C).join("\\.(?:.*\\.)?")+"(\\.|$)")),p=t[h];if(!p)continue;if(!d){for(j=0;j<p.length;j++){q=p[j];if(l||n.test(q.namespace))f.event.remove(a,r,q.handler,j),p.splice(j--,1)}continue}o=f.event.special[h]||{};for(j=e||0;j<p.length;j++){q=p[j];if(d.guid===q.guid){if(l||n.test(q.namespace))e==null&&p.splice(j--,1),o.remove&&o.remove.call(a,q);if(e!=null)break}}if(p.length===0||e!=null&&p.length===1)(!o.teardown||o.teardown.call(a,m)===!1)&&f.removeEvent(a,h,s.handle),g=null,delete t[h]}if(f.isEmptyObject(t)){var u=s.handle;u&&(u.elem=null),delete s.events,delete s.handle,f.isEmptyObject(s)&&f.removeData(a,b,!0)}}},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,e,g){var h=c.type||c,i=[],j;h.indexOf("!")>=0&&(h=h.slice(0,-1),j=!0),h.indexOf(".")>=0&&(i=h.split("."),h=i.
shift(),i.sort());if(!!e&&!f.event.customEvent[h]||!!f.event.global[h]){c=typeof c=="object"?c[f.expando]?c:new f.Event(h,c):new f.Event(h),c.type=h,c.exclusive=j,c.namespace=i.join("."),c.namespace_re=new RegExp("(^|\\.)"+i.join("\\.(?:.*\\.)?")+"(\\.|$)");if(g||!e)c.preventDefault(),c.stopPropagation();if(!e){f.each(f.cache,function(){var a=f.expando,b=this[a];b&&b.events&&b.events[h]&&f.event.trigger(c,d,b.handle.elem)});return}if(e.nodeType===3||e.nodeType===8)return;c.result=b,c.target=e,d=d!=null?f.makeArray(d):[],d.unshift(c);var k=e,l=h.indexOf(":")<0?"on"+h:"";do{var m=f._data(k,"handle");c.currentTarget=k,m&&m.apply(k,d),l&&f.acceptData(k)&&k[l]&&k[l].apply(k,d)===!1&&(c.result=!1,c.preventDefault()),k=k.parentNode||k.ownerDocument||k===c.target.ownerDocument&&a}while(k&&!c.isPropagationStopped());if(!c.isDefaultPrevented()){var n,o=f.event.special[h]||{};if((!o._default||o._default.call(e.ownerDocument,c)===!1)&&(h!=="click"||!f.nodeName(e,"a"))&&f.acceptData(e)){try{l&&e[h]&&(n=e[l],n&&(e[l]=null),f.event.triggered=h,e[h]())}catch(p){}n&&(e[l]=n),f.event.triggered=b}}return c.result}},handle:function(c){c=f.event.fix(c||a.event);var d=((f._data(this,"events")||{})[c.type]||[]).slice(0),e=!c.exclusive&&!c.namespace,g=Array.prototype.slice.call(arguments,0);g[0]=c,c.currentTarget=this;for(var h=0,i=d.length;h<i;h++){var j=d[h];if(e||c.namespace_re.test(j.namespace)){c.handler=j.handler,c.data=j.data,c.handleObj=j;var k=j.handler.apply(this,g);k!==b&&(c.result=k,k===!1&&(c.preventDefault(),c.stopPropagation()));if(c.isImmediatePropagationStopped())break}}return c.result},props:"altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),fix:function(a){if(a[f.expando])return a;var d=a;a=f.Event(d);for(var e=this.props.length,g;e;)g=this.props[--e],a[g]=d[g];a.target||(a.target=a.srcElement||c),a.target.nodeType===3&&(a.target=a.target.parentNode),!a.relatedTarget&&a.fromElement&&(a.relatedTarget=a.fromElement===a.target?a.toElement:a.fromElement);if(a.pageX==null&&a.clientX!=null){var h=a.target.ownerDocument||c,i=h.documentElement,j=h.body;a.pageX=a.clientX+(i&&i.scrollLeft||j&&j.scrollLeft||0)-(i&&i.clientLeft||j&&j.clientLeft||0),a.pageY=a.clientY+(i&&i.scrollTop||j&&j.scrollTop||0)-(i&&i.clientTop||j&&j.clientTop||0)}a.which==null&&(a.charCode!=null||a.keyCode!=null)&&(a.which=a.charCode!=null?a.charCode:a.keyCode),!a.metaKey&&a.ctrlKey&&(a.metaKey=a.ctrlKey),!a.which&&a.button!==b&&(a.which=a.button&1?1:a.button&2?3:a.button&4?2:0);return a},guid:1e8,proxy:f.proxy,special:{ready:{setup:f.bindReady,teardown:f.noop},live:{add:function(a){f.event.add(this,N(a.origType,a.selector),f.extend({},a,{handler:M,guid:a.handler.guid}))},remove:function(a){f.event.remove(this,N(a.origType,a.selector),a)}},beforeunload:{setup:function(a,b,c){f.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}}},f.removeEvent=c.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){a.detachEvent&&a.detachEvent("on"+b,c)},f.Event=function(a,b){if(!this.preventDefault)return new f.Event(a,b);a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.getPreventDefault&&a.getPreventDefault()?E:D):this.type=a,b&&f.extend(this,b),this.timeStamp=f.now(),this[f.expando]=!0},f.Event.prototype={preventDefault:function(){this.isDefaultPrevented=E;var a=this.originalEvent;!a||(a.preventDefault?a.preventDefault():a.returnValue=!1)},stopPropagation:function(){this.isPropagationStopped=E;var a=this.originalEvent;!a||(a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0)},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=E,this.stopPropagation()},isDefaultPrevented:D,isPropagationStopped:D,isImmediatePropagationStopped:D};var F=function(a){var b=a.relatedTarget,c=!1,d=a.type;a.type=a.data,b!==this&&(b&&(c=f.contains(this,b)),c||(f.event.handle.apply(this,arguments),a.type=d))},G=function(a){a.type=a.data,f.event.handle.apply(this,arguments)};f.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){f.event.special[a]={setup:function(c){f.event.add(this,b,c&&c.selector?G:F,a)},teardown:function(a){f.event.remove(this,b,a&&a.selector?G:F)}}}),f.support.submitBubbles||(f.event.special.submit={setup:function(a,b){if(!f.nodeName(this,"form"))f.event.add(this,"click.specialSubmit",function(a){var b=a.target,c=b.type;(c==="submit"||c==="image")&&f(b).closest("form").length&&K("submit",this,arguments)}),f.event.add(this,"keypress.specialSubmit",function(a){var b=a.target,c=b.type;(c==="text"||c==="password")&&f(b).closest("form").length&&a.keyCode===13&&K("submit",this,arguments)});else return!1},teardown:function(a){f.event.remove(this,".specialSubmit")}});if(!f.support.changeBubbles){var H,I=function(a){var b=a.type,c=a.value;b==="radio"||b==="checkbox"?c=a.checked:b==="select-multiple"?c=a.selectedIndex>-1?f.map(a.options,function(a){return a.selected}).join("-"):"":f.nodeName(a,"select")&&(c=a.selectedIndex);return c},J=function(c){var d=c.target,e,g;if(!!y.test(d.nodeName)&&!d.readOnly){e=f._data(d,"_change_data"),g=I(d),(c.type!=="focusout"||d.type!=="radio")&&f._data(d,"_change_data",g);if(e===b||g===e)return;if(e!=null||g)c.type="change",c.liveFired=b,f.event.trigger(c,arguments[1],d)}};f.event.special.change={filters:{focusout:J,beforedeactivate:J,click:function(a){var b=a.target,c=f.nodeName(b,"input")?b.type:"";(c==="radio"||c==="checkbox"||f.nodeName(b,"select"))&&J.call(this,a)},keydown:function(a){var b=a.target,c=f.nodeName(b,"input")?b.type:"";(a.keyCode===13&&!f.nodeName(b,"textarea")||a.keyCode===32&&(c==="checkbox"||c==="radio")||c==="select-multiple")&&J.call(this,a)},beforeactivate:function(a){var b=a.target;f._data(b,"_change_data",I(b))}},setup:function(a,b){if(this.type==="file")return!1;for(var c in H)f.event.add(this,c+".specialChange",H[c]);return y.test(this.nodeName)},teardown:function(a){f.event.remove(this,".specialChange");return y.test(this.nodeName)}},H=f.event.special.change.filters,H.focus=H.beforeactivate}f.support.focusinBubbles||f.each({focus:"focusin",blur:"focusout"},function(a,b){function e(a){var c=f.event.fix(a);c.type=b,c.originalEvent={},f.event.trigger(c,null,c.target),c.isDefaultPrevented()&&a.preventDefault()}var d=0;f.event.special[b]={setup:function(){d++===0&&c.addEventListener(a,e,!0)},teardown:function(){--d===0&&c.removeEventListener(a,e,!0)}}}),f.each(["bind","one"],function(a,c){f.fn[c]=function(a,d,e){var g;if(typeof a=="object"){for(var h in a)this[c](h,d,a[h],e);return this}if(arguments.length===2||d===!1)e=d,d=b;c==="one"?(g=function(a){f(this).unbind(a,g);return e.apply(this,arguments)},g.guid=e.guid||f.guid++):g=e;if(a==="unload"&&c!=="one")this.one(a,d,e);else for(var i=0,j=this.length;i<j;i++)f.event.add(this[i],a,g,d);return this}}),f.fn.extend({unbind:function(a,b){if(typeof a=="object"&&!a.preventDefault)for(var c in a)this.unbind(c,a[c]);else for(var d=0,e=this.length;d<e;d++)f.event.remove(this[d],a,b);return this},delegate:function(a,b,c,d){return this.live(b,c,d,a)},undelegate:function(a,b,c){return arguments.length===0?this.unbind("live"):this.die(b,null,c,a)},trigger:function(a,b){return this.each(function(){f.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return f.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||f.guid++,d=0,e=function(c){var e=(f.data(this,"lastToggle"+a.guid)||0)%d;f.data(this,"lastToggle"+a.guid,e+1),c.preventDefault();return b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}});var L={focus:"focusin",blur:"focusout",mouseenter:"mouseover",mouseleave:"mouseout"};f.each(["live","die"],function(a,c){f.fn[c]=function(a,d,e,g){var h,i=0,j,k,l,m=g||this.selector,n=g?this:f(this.context);if(typeof a=="object"&&!a.preventDefault){for(var o in a)n[c](o,d,a[o],m);return this}if(c==="die"&&!a&&g&&g.charAt(0)==="."){n.unbind(g);return this}if(d===!1||f.isFunction(d))e=d||D,d=b;a=(a||"").split(" ");while((h=a[i++])!=null){j=x.exec(h),k="",j&&(k=j[0],h=h.replace(x,""));if(h==="hover"){a.push("mouseenter"+k,"mouseleave"+k);continue}l=h,L[h]?(a.push(L[h]+k),h=h+k):h=(L[h]||h)+k;if(c==="live")for(var p=0,q=n.length;p<q;p++)f.event.add(n[p],"live."+N(h,m),{data:d,selector:m,handler:e,origType:h,origHandler:e,preType:l});else n.unbind("live."+N(h,m),e)}return this}}),f.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error".split(" "),function(a,b){f.fn[b]=function(a,c){c==null&&(c=a,a=null);return arguments.length>0?this.bind(b,a,c):this.trigger(b)},f.attrFn&&(f.attrFn[b]=!0)}),function(){function u(a,b,c,d,e,f){for(var g=0,h=d.length;g<h;g++){var i=d[g];if(i){var j=!1;i=i[a];while(i){if(i.sizcache===c){j=d[i.sizset];break}if(i.nodeType===1){f||(i.sizcache=c,i.sizset=g);if(typeof b!="string"){if(i===b){j=!0;break}}else if(k.filter(b,[i]).length>0){j=i;break}}i=i[a]}d[g]=j}}}function t(a,b,c,d,e,f){for(var g=0,h=d.length;g<h;g++){var i=d[g];if(i){var j=!1;i=i[a];while(i){if(i.sizcache===c){j=d[i.sizset];break}i.nodeType===1&&!f&&(i.sizcache=c,i.sizset=g);if(i.nodeName.toLowerCase()===b){j=i;break}i=i[a]}d[g]=j}}}var a=/((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,d=0,e=Object.prototype.toString,g=!1,h=!0,i=/\\/g,j=/\W/;[0,0].sort(function(){h=!1;return 0});var k=function(b,d,f,g){f=f||[],d=d||c;var h=d;if(d.nodeType!==1&&d.nodeType!==9)return[];if(!b||typeof b!="string")return f;var i,j,n,o,q,r,s,t,u=!0,w=k.isXML(d),x=[],y=b;do{a.exec(""),i=a.exec(y);if(i){y=i[3],x.push(i[1]);if(i[2]){o=i[3];break}}}while(i);if(x.length>1&&m.exec(b))if(x.length===2&&l.relative[x[0]])j=v(x[0]+x[1],d);else{j=l.relative[x[0]]?[d]:k(x.shift(),d);while(x.length)b=x.shift(),l.relative[b]&&(b+=x.shift()),j=v(b,j)}else{!g&&x.length>1&&d.nodeType===9&&!w&&l.match.ID.test(x[0])&&!l.match.ID.test(x[x.length-1])&&(q=k.find(x.shift(),d,w),d=q.expr?k.filter(q.expr,q.set)[0]:q.set[0]);if(d){q=g?{expr:x.pop(),set:p(g)}:k.find(x.pop(),x.length===1&&(x[0]==="~"||x[0]==="+")&&d.parentNode?d.parentNode:d,w),j=q.expr?k.filter(q.expr,q.set):q.set,x.length>0?n=p(j):u=!1;while(x.length)r=x.pop(),s=r,l.relative[r]?s=x.pop():r="",s==null&&(s=d),l.relative[r](n,s,w)}else n=x=[]}n||(n=j),n||k.error(r||b);if(e.call(n)==="[object Array]")if(!u)f.push.apply(f,n);else if(d&&d.nodeType===1)for(t=0;n[t]!=null;t++)n[t]&&(n[t]===!0||n[t].nodeType===1&&k.contains(d,n[t]))&&f.push(j[t]);else for(t=0;n[t]!=null;t++)n[t]&&n[t].nodeType===1&&f.push(j[t]);else p(n,f);o&&(k(o,h,f,g),k.uniqueSort(f));return f};k.uniqueSort=function(a){if(r){g=h,a.sort(r);if(g)for(var b=1;b<a.length;b++)a[b]===a[b-1]&&a.splice(b--,1)}return a},k.matches=function(a,b){return k(a,null,null,b)},k.matchesSelector=function(a,b){return k(b,null,null,[a]).length>0},k.find=function(a,b,c){var d;if(!a)return[];for(var e=0,f=l.order.length;e<f;e++){var g,h=l.order[e];if(g=l.leftMatch[h].exec(a)){var j=g[1];g.splice(1,1);if(j.substr(j.length-1)!=="\\"){g[1]=(g[1]||"").replace(i,""),d=l.find[h](g,b,c);if(d!=null){a=a.replace(l.match[h],"");break}}}}d||(d=typeof b.getElementsByTagName!="undefined"?b.getElementsByTagName("*"):[]);return{set:d,expr:a}},k.filter=function(a,c,d,e){var f,g,h=a,i=[],j=c,m=c&&c[0]&&k.isXML(c[0]);while(a&&c.length){for(var n in l.filter)if((f=l.leftMatch[n].exec(a))!=null&&f[2]){var o,p,q=l.filter[n],r=f[1];g=!1,f.splice(1,1);if(r.substr(r.length-1)==="\\")continue;j===i&&(i=[]);if(l.preFilter[n]){f=l.preFilter[n](f,j,d,i,e,m);if(!f)g=o=!0;else if(f===!0)continue}if(f)for(var s=0;(p=j[s])!=null;s++)if(p){o=q(p,f,s,j);var t=e^!!o;d&&o!=null?t?g=!0:j[s]=!1:t&&(i.push(p),g=!0)}if(o!==b){d||(j=i),a=a.replace(l.match[n],"");if(!g)return[];break}}if(a===h)if(g==null)k.error(a);else break;h=a}return j},k.error=function(a){throw"Syntax error, unrecognized expression: "+a};var l=k.selectors={order:["ID","NAME","TAG"],match:{ID:/#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,CLASS:/\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,NAME:/\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,ATTR:/\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,TAG:/^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,CHILD:/:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,POS:/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,PSEUDO:/:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/},leftMatch:{},attrMap:{"class":"className","for":"htmlFor"},attrHandle:{href:function(a){return a.getAttribute("href")},type:function(a){return a.getAttribute("type")}},relative:{"+":function(a,b){var c=typeof b=="string",d=c&&!j.test(b),e=c&&!d;d&&(b=b.toLowerCase());for(var f=0,g=a.length,h;f<g;f++)if(h=a[f]){while((h=h.previousSibling)&&h.nodeType!==1);a[f]=e||h&&h.nodeName.toLowerCase()===b?h||!1:h===b}e&&k.filter(b,a,!0)},">":function(a,b){var c,d=typeof b=="string",e=0,f=a.length;if(d&&!j.test(b)){b=b.toLowerCase();for(;e<f;e++){c=a[e];if(c){var g=c.parentNode;a[e]=g.nodeName.toLowerCase()===b?g:!1}}}else{for(;e<f;e++)c=a[e],c&&(a[e]=d?c.parentNode:c.parentNode===b);d&&k.filter(b,a,!0)}},"":function(a,b,c){var e,f=d++,g=u;typeof b=="string"&&!j.test(b)&&(b=b.toLowerCase(),e=b,g=t),g("parentNode",b,f,a,e,c)},"~":function(a,b,c){var e,f=d++,g=u;typeof b=="string"&&!j.test(b)&&(b=b.toLowerCase(),e=b,g=t),g("previousSibling",b,f,a,e,c)}},find:{ID:function(a,b,c){if(typeof b.getElementById!="undefined"&&!c){var d=b.getElementById(a[1]);return d&&d.parentNode?[d]:[]}},NAME:function(a,b){if(typeof b.getElementsByName!="undefined"){var c=[],d=b.getElementsByName(a[1]);for(var e=0,f=d.length;e<f;e++)d[e].getAttribute("name")===a[1]&&c.push(d[e]);return c.length===0?null:c}},TAG:function(a,b){if(typeof b.getElementsByTagName!="undefined")return b.getElementsByTagName(a[1])}},preFilter:{CLASS:function(a,b,c,d,e,f){a=" "+a[1].replace(i,"")+" ";if(f)return a;for(var g=0,h;(h=b[g])!=null;g++)h&&(e^(h.className&&(" "+h.className+" ").replace(/[\t\n\r]/g," ").indexOf(a)>=0)?c||d.push(h):c&&(b[g]=!1));return!1},ID:function(a){return a[1].replace(i,"")},TAG:function(a,b){return a[1].replace(i,"").toLowerCase()},CHILD:function(a){if(a[1]==="nth"){a[2]||k.error(a[0]),a[2]=a[2].replace(/^\+|\s*/g,"");var b=/(-?)(\d*)(?:n([+\-]?\d*))?/.exec(a[2]==="even"&&"2n"||a[2]==="odd"&&"2n+1"||!/\D/.test(a[2])&&"0n+"+a[2]||a[2]);a[2]=b[1]+(b[2]||1)-0,a[3]=b[3]-0}else a[2]&&k.error(a[0]);a[0]=d++;return a},ATTR:function(a,b,c,d,e,f){var g=a[1]=a[1].replace(i,"");!f&&l.attrMap[g]&&(a[1]=l.attrMap[g]),a[4]=(a[4]||a[5]||"").replace(i,""),a[2]==="~="&&(a[4]=" "+a[4]+" ");return a},PSEUDO:function(b,c,d,e,f){if(b[1]==="not")if((a.exec(b[3])||"").length>1||/^\w/.test(b[3]))b[3]=k(b[3],null,null,c);else{var g=k.filter(b[3],c,d,!0^f);d||e.push.apply(e,g);return!1}else if(l.match.POS.test(b[0])||l.match.CHILD.test(b[0]))return!0;return b},POS:function(a){a.unshift(!0);return a}},filters:{enabled:function(a){return a.disabled===!1&&a.type!=="hidden"},disabled:function(a){return a.disabled===!0},checked:function(a){return a.checked===!0},selected:function(a){a.parentNode&&a.parentNode.selectedIndex;return a.selected===!0},parent:function(a){return!!a.firstChild},empty:function(a){return!a.firstChild},has:function(a,b,c){return!!k(c[3],a).length},header:function(a){return/h\d/i.test(a.nodeName)},text:function(a){var b=a.getAttribute("type"),c=a.type;return a.nodeName.toLowerCase()==="input"&&"text"===c&&(b===c||b===null)},radio:function(a){return a.nodeName.toLowerCase()==="input"&&"radio"===a.type},checkbox:function(a){return a.nodeName.toLowerCase()==="input"&&"checkbox"===a.type},file:function(a){return a.nodeName.toLowerCase()==="input"&&"file"===a.type},password:function(a){return a.nodeName.toLowerCase()==="input"&&"password"===a.type},submit:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"submit"===a.type},image:function(a){return a.nodeName.toLowerCase()==="input"&&"image"===a.type},reset:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"reset"===a.type},button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&"button"===a.type||b==="button"},input:function(a){return/input|select|textarea|button/i.test(a.nodeName)},focus:function(a){return a===a.ownerDocument.activeElement}},setFilters:{first:function(a,b){return b===0},last:function(a,b,c,d){return b===d.length-1},even:function(a,b){return b%2===0},odd:function(a,b){return b%2===1},lt:function(a,b,c){return b<c[3]-0},gt:function(a,b,c){return b>c[3]-0},nth:function(a,b,c){return c[3]-0===b},eq:function(a,b,c){return c[3]-0===b}},filter:{PSEUDO:function(a,b,c,d){var e=b[1],f=l.filters[e];if(f)return f(a,c,b,d);if(e==="contains")return(a.textContent||a.innerText||k.getText([a])||"").indexOf(b[3])>=0;if(e==="not"){var g=b[3];for(var h=0,i=g.length;h<i;h++)if(g[h]===a)return!1;return!0}k.error(e)},CHILD:function(a,b){var c=b[1],d=a;switch(c){case"only":case"first":while(d=d.previousSibling)if(d.nodeType===1)return!1;if(c==="first")return!0;d=a;case"last":while(d=d.nextSibling)if(d.nodeType===1)return!1;return!0;case"nth":var e=b[2],f=b[3];if(e===1&&f===0)return!0;var g=b[0],h=a.parentNode;if(h&&(h.sizcache!==g||!a.nodeIndex)){var i=0;for(d=h.firstChild;d;d=d.nextSibling)d.nodeType===1&&(d.nodeIndex=++i);h.sizcache=g}var j=a.nodeIndex-f;return e===0?j===0:j%e===0&&j/e>=0}},ID:function(a,b){return a.nodeType===1&&a.getAttribute("id")===b},TAG:function(a,b){return b==="*"&&a.nodeType===1||a.nodeName.toLowerCase()===b},CLASS:function(a,b){return(" "+(a.className||a.getAttribute("class"))+" ").indexOf(b)>-1},ATTR:function(a,b){var c=b[1],d=l.attrHandle[c]?l.attrHandle[c](a):a[c]!=null?a[c]:a.getAttribute(c),e=d+"",f=b[2],g=b[4];return d==null?f==="!=":f==="="?e===g:f==="*="?e.indexOf(g)>=0:f==="~="?(" "+e+" ").indexOf(g)>=0:g?f==="!="?e!==g:f==="^="?e.indexOf(g)===0:f==="$="?e.substr(e.length-g.length)===g:f==="|="?e===g||e.substr(0,g.length+1)===g+"-":!1:e&&d!==!1},POS:function(a,b,c,d){var e=b[2],f=l.setFilters[e];if(f)return f(a,c,b,d)}}},m=l.match.POS,n=function(a,b){return"\\"+(b-0+1)};for(var o in l.match)l.match[o]=new RegExp(l.match[o].source+/(?![^\[]*\])(?![^\(]*\))/.source),l.leftMatch[o]=new RegExp(/(^(?:.|\r|\n)*?)/.source+l.match[o].source.replace(/\\(\d+)/g,n));var p=function(a,b){a=Array.prototype.slice.call(a,0);if(b){b.push.apply(b,a);return b}return a};try{Array.prototype.slice.call(c.documentElement.childNodes,0)[0].nodeType}catch(q){p=function(a,b){var c=0,d=b||[];if(e.call(a)==="[object Array]")Array.prototype.push.apply(d,a);else if(typeof a.length=="number")for(var f=a.length;c<f;c++)d.push(a[c]);else for(;a[c];c++)d.push(a[c]);return d}}var r,s;c.documentElement.compareDocumentPosition?r=function(a,b){if(a===b){g=!0;return 0}if(!a.compareDocumentPosition||!b.compareDocumentPosition)return a.compareDocumentPosition?-1:1;return a.compareDocumentPosition(b)&4?-1:1}:(r=function(a,b){if(a===b){g=!0;return 0}if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],h=a.parentNode,i=b.parentNode,j=h;if(h===i)return s(a,b);if(!h)return-1;if(!i)return 1;while(j)e.unshift(j),j=j.parentNode;j=i;while(j)f.unshift(j),j=j.parentNode;c=e.length,d=f.length;for(var k=0;k<c&&k<d;k++)if(e[k]!==f[k])return s(e[k],f[k]);return k===c?s(a,f[k],-1):s(e[k],b,1)},s=function(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}),k.getText=function(a){var b="",c;for(var d=0;a[d];d++)c=a[d],c.nodeType===3||c.nodeType===4?b+=c.nodeValue:c.nodeType!==8&&(b+=k.getText(c.childNodes));return b},function(){var a=c.createElement("div"),d="script"+(new Date).getTime(),e=c.documentElement;a.innerHTML="<a name='"+d+"'/>",e.insertBefore(a,e.firstChild),c.getElementById(d)&&(l.find.ID=function(a,c,d){if(typeof c.getElementById!="undefined"&&!d){var e=c.getElementById(a[1]);return e?e.id===a[1]||typeof e.getAttributeNode!="undefined"&&e.getAttributeNode("id").nodeValue===a[1]?[e]:b:[]}},l.filter.ID=function(a,b){var c=typeof a.getAttributeNode!="undefined"&&a.getAttributeNode("id");return a.nodeType===1&&c&&c.nodeValue===b}),e.removeChild(a),e=a=null}(),function(){var a=c.createElement("div");a.appendChild(c.createComment("")),a.getElementsByTagName("*").length>0&&(l.find.TAG=function(a,b){var c=b.getElementsByTagName(a[1]);if(a[1]==="*"){var d=[];for(var e=0;c[e];e++)c[e].nodeType===1&&d.push(c[e]);c=d}return c}),a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!="undefined"&&a.firstChild.getAttribute("href")!=="#"&&(l.attrHandle.href=function(a){return a.getAttribute("href",2)}),a=null}(),c.querySelectorAll&&function(){var a=k,b=c.createElement("div"),d="__sizzle__";b.innerHTML="<p class='TEST'></p>";if(!b.querySelectorAll||b.querySelectorAll(".TEST").length!==0){k=function(b,e,f,g){e=e||c;if(!g&&!k.isXML(e)){var h=/^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(b);if(h&&(e.nodeType===1||e.nodeType===9)){if(h[1])return p(e.getElementsByTagName(b),f);if(h[2]&&l.find.CLASS&&e.getElementsByClassName)return p(e.getElementsByClassName(h[2]),f)}if(e.nodeType===9){if(b==="body"&&e.body)return p([e.body],f);if(h&&h[3]){var i=e.getElementById(h[3]);if(!i||!i.parentNode)return p([],f);if(i.id===h[3])return p([i],f)}try{return p(e.querySelectorAll(b),f)}catch(j){}}else if(e.nodeType===1&&e.nodeName.toLowerCase()!=="object"){var m=e,n=e.getAttribute("id"),o=n||d,q=e.parentNode,r=/^\s*[+~]/.test(b);n?o=o.replace(/'/g,"\\$&"):e.setAttribute("id",o),r&&q&&(e=e.parentNode);try{if(!r||q)return p(e.querySelectorAll("[id='"+o+"'] "+b),f)}catch(s){}finally{n||m.removeAttribute("id")}}}return a(b,e,f,g)};for(var e in a)k[e]=a[e];b=null}}(),function(){var a=c.documentElement,b=a.matchesSelector||a.mozMatchesSelector||a.webkitMatchesSelector||a.msMatchesSelector;if(b){var d=!b.call(c.createElement("div"),"div"),e=!1;try{b.call(c.documentElement,"[test!='']:sizzle")}catch(f){e=!0}k.matchesSelector=function(a,c){c=c.replace(/\=\s*([^'"\]]*)\s*\]/g,"='$1']");if(!k.isXML(a))try{if(e||!l.match.PSEUDO.test(c)&&!/!=/.test(c)){var f=b.call(a,c);if(f||!d||a.document&&a.document.nodeType!==11)return f}}catch(g){}return k(c,null,null,[a]).length>0}}}(),function(){var a=c.createElement("div");a.innerHTML="<div class='test e'></div><div class='test'></div>";if(!!a.getElementsByClassName&&a.getElementsByClassName("e").length!==0){a.lastChild.className="e";if(a.getElementsByClassName("e").length===1)return;l.order.splice(1,0,"CLASS"),l.find.CLASS=function(a,b,c){if(typeof b.getElementsByClassName!="undefined"&&!c)return b.getElementsByClassName(a[1])},a=null}}(),c.documentElement.contains?k.contains=function(a,b){return a!==b&&(a.contains?a.contains(b):!0)}:c.documentElement.compareDocumentPosition?k.contains=function(a,b){return!!(a.compareDocumentPosition(b)&16)}:k.contains=function(){return!1},k.isXML=function(a){var b=(a?a.ownerDocument||a:0).documentElement;return b?b.nodeName!=="HTML":!1};var v=function(a,b){var c,d=[],e="",f=b.nodeType?[b]:b;while(c=l.match.PSEUDO.exec(a))e+=c[0],a=a.replace(l.match.PSEUDO,"");a=l.relative[a]?a+"*":a;for(var g=0,h=f.length;g<h;g++)k(a,f[g],d);return k.filter(e,d)};f.find=k,f.expr=k.selectors,f.expr[":"]=f.expr.filters,f.unique=k.uniqueSort,f.text=k.getText,f.isXMLDoc=k.isXML,f.contains=k.contains}();var O=/Until$/,P=/^(?:parents|prevUntil|prevAll)/,Q=/,/,R=/^.[^:#\[\.,]*$/,S=Array.prototype.slice,T=f.expr.match.POS,U={children:!0,contents:!0,next:!0,prev:!0};f.fn.extend({find:function(a){var b=this,c,d;if(typeof a!="string")return f(a).filter(function(){for(c=0,d=b.length;c<d;c++)if(f.contains(b[c],this))return!0});var e=this.pushStack("","find",a),g,h,i;for(c=0,d=this.length;c<d;c++){g=e.length,f.find(a,this[c],e);if(c>0)for(h=g;h<e.length;h++)for(i=0;i<g;i++)if(e[i]===e[h]){e.splice(h--,1);break}}return e},has:function(a){var b=f(a);return this.filter(function(){for(var a=0,c=b.length;a<c;a++)if(f.contains(this,b[a]))return!0})},not:function(a){return this.pushStack(W(this,a,!1),"not",a)},filter:function(a){return this.pushStack(W(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?f.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c=[],d,e,g=this[0];if(f.isArray(a)){var h,i,j={},k=1;if(g&&a.length){for(d=0,e=a.length;d<e;d++)i=a[d],j[i]||(j[i]=T.test(i)?f(i,b||this.context):i);while(g&&g.ownerDocument&&g!==b){for(i in j)h=j[i],(h.jquery?h.index(g)>-1:f(g).is(h))&&c.push({selector:i,elem:g,level:k});g=g.parentNode,k++}}return c}var l=T.test(a)||typeof a!="string"?f(a,b||this.context):0;for(d=0,e=this.length;d<e;d++){g=this[d];while(g){if(l?l.index(g)>-1:f.find.matchesSelector(g,a)){c.push(g);break}g=g.parentNode;if(!g||!g.ownerDocument||g===b||g.nodeType===11)break}}c=c.length>1?f.unique(c):c;return this.pushStack(c,"closest",a)},index:function(a){if(!a||typeof a=="string")return f.inArray(this[0],a?f(a):this.parent().children());return f.inArray(a.jquery?a[0]:a,this)},add:function(a,b){var c=typeof a=="string"?f(a,b):f.makeArray(a&&a.nodeType?[a]:a),d=f.merge(this.get(),c);return this.pushStack(V(c[0])||V(d[0])?d:f.unique(d))},andSelf:function(){return this.add(this.prevObject)}}),f.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return f.dir(a,"parentNode")},parentsUntil:function(a,b,c){return f.dir(a,"parentNode",c)},next:function(a){return f.nth(a,2,"nextSibling")},prev:function(a){return f.nth(a,2,"previousSibling")},nextAll:function(a){return f.dir(a,"nextSibling")},prevAll:function(a){return f.dir(a,"previousSibling")},nextUntil:function(a,b,c){return f.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return f.dir(a,"previousSibling",c)},siblings:function(a){return f.sibling(a.parentNode.firstChild,a)},children:function(a){return f.sibling(a.firstChild)},contents:function(a){return f.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:f.makeArray(a.childNodes)}},function(a,b){f.fn[a]=function(c,d){var e=f.map(this,b,c),g=S.call(arguments);O.test(a)||(d=c),d&&typeof d=="string"&&(e=f.filter(d,e)),e=this.length>1&&!U[a]?f.unique(e):e,(this.length>1||Q.test(d))&&P.test(a)&&(e=e.reverse());return this.pushStack(e,a,g.join(","))}}),f.extend({filter:function(a,b,c){c&&(a=":not("+a+")");return b.length===1?f.find.matchesSelector(b[0],a)?[b[0]]:[]:f.find.matches(a,b)},dir:function(a,c,d){var e=[],g=a[c];while(g&&g.nodeType!==9&&(d===b||g.nodeType!==1||!f(g).is(d)))g.nodeType===1&&e.push(g),g=g[c];return e},nth:function(a,b,c,d){b=b||1;var e=0;for(;a;a=a[c])if(a.nodeType===1&&++e===b)break;return a},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var X=/ jQuery\d+="(?:\d+|null)"/g,Y=/^\s+/,Z=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,$=/<([\w:]+)/,_=/<tbody/i,ba=/<|&#?\w+;/,bb=/<(?:script|object|embed|option|style)/i,bc=/checked\s*(?:[^=]|=\s*.checked.)/i,bd=/\/(java|ecma)script/i,be=/^\s*<!(?:\[CDATA\[|\-\-)/,bf={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]};bf.optgroup=bf.option,bf.tbody=bf.tfoot=bf.colgroup=bf.caption=bf.thead,bf.th=bf.td,f.support.htmlSerialize||(bf._default=[1,"div<div>","</div>"]),f.fn.extend({text:function(a){if(f.isFunction(a))return this.each(function(b){var c=f(this);c.text(a.call(this,b,c.text()))});if(typeof a!="object"&&a!==b)return this.empty().append((this[0]&&this[0].ownerDocument||c).createTextNode(a));return f.text(this)},wrapAll:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapAll(a.call(this,b))});if(this[0]){var b=f(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapInner(a.call(this,b))});return this.each(function(){var b=f(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){return this.each(function(){f(this).wrapAll(a)})},unwrap:function(){return this.parent().each(function(){f.nodeName(this,"body")||f(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.insertBefore(a,this.firstChild)})},before:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=f(arguments[0]);a.push.apply(a,this.toArray());return this.pushStack(a,"before",arguments)}},after:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=this.pushStack(this,"after",arguments);a.push.apply(a,f(arguments[0]).toArray());return a}},remove:function(a,b){for(var c=0,d;(d=this[c])!=null;c++)if(!a||f.filter(a,[d]).length)!b&&d.nodeType===1&&(f.cleanData(d.getElementsByTagName("*")),f.cleanData([d])),d.parentNode&&d.parentNode.removeChild(d);return this},empty:function(){for(var a=0,b;(b=this[a])!=null;a++){b.nodeType===1&&f.cleanData(b.getElementsByTagName("*"));while(b.firstChild)b.removeChild(b.firstChild)}return this},clone:function(a,b){a=a==null?!1:a,b=b==null?a:b;return this.map(function(){return f.clone(this,a,b)})},html:function(a){if(a===b)return this[0]&&this[0].nodeType===1?this[0].innerHTML.replace(X,""):null;if(typeof a=="string"&&!bb.test(a)&&(f.support.leadingWhitespace||!Y.test(a))&&!bf[($.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(Z,"<$1></$2>");try{for(var c=0,d=this.length;c<d;c++)this[c].nodeType===1&&(f.cleanData(this[c].getElementsByTagName("*")),this[c].innerHTML=a)}catch(e){this.empty().append(a)}}else f.isFunction(a)?this.each(function(b){var c=f(this);c.html(a.call(this,b,c.html()))}):this.empty().append(a);return this},replaceWith:function(a){if(this[0]&&this[0].parentNode){if(f.isFunction(a))return this.each(function(b){var c=f(this),d=c.html();c.replaceWith(a.call(this,b,d))});typeof a!="string"&&(a=f(a).detach());return this.each(function(){var b=this.nextSibling,c=this.parentNode;f(this).remove(),b?f(b).before(a):f(c).append(a)})}return this.length?this.pushStack(f(f.isFunction(a)?a():a),"replaceWith",a):this},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){var e,g,h,i,j=a[0],k=[];if(!f.support.checkClone&&arguments.length===3&&typeof j=="string"&&bc.test(j))return this.each(function(){f(this).domManip(a,c,d,!0)});if(f.isFunction(j))return this.each(function(e){var g=f(this);a[0]=j.call(this,e,c?g.html():b),g.domManip(a,c,d)});if(this[0]){i=j&&j.parentNode,f.support.parentNode&&i&&i.nodeType===11&&i.childNodes.length===this.length?e={fragment:i}:e=f.buildFragment(a,this,k),h=e.fragment,h.childNodes.length===1?g=h=h.firstChild:g=h.firstChild;if(g){c=c&&f.nodeName(g,"tr");for(var l=0,m=this.length,n=m-1;l<m;l++)d.call(c?bg(this[l],g):this[l],e.cacheable||m>1&&l<n?f.clone(h,!0,!0):h)}k.length&&f.each(k,bm)}return this}}),f.buildFragment=function(a,b,d){var e,g,h,i;b&&b[0]&&(i=b[0].ownerDocument||b[0]),i.createDocumentFragment||(i=c),a.length===1&&typeof a[0]=="string"&&a[0].length<512&&i===c&&a[0].charAt(0)==="<"&&!bb.test(a[0])&&(f.support.checkClone||!bc.test(a[0]))&&(g=!0,h=f.fragments[a[0]],h&&h!==1&&(e=h)),e||(e=i.createDocumentFragment(),f.clean(a,i,e,d)),g&&(f.fragments[a[0]]=h?e:1);return{fragment:e,cacheable:g}},f.fragments={},f.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){f.fn[a]=function(c){var d=[],e=f(c),g=this.length===1&&this[0].parentNode;if(g&&g.nodeType===11&&g.childNodes.length===1&&e.length===1){e[b](this[0]);return this}for(var h=0,i=e.length;h<i;h++){var j=(h>0?this.clone(!0):this).get();f(e[h])[b](j),d=d.concat(j
)}return this.pushStack(d,a,e.selector)}}),f.extend({clone:function(a,b,c){var d=a.cloneNode(!0),e,g,h;if((!f.support.noCloneEvent||!f.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!f.isXMLDoc(a)){bi(a,d),e=bj(a),g=bj(d);for(h=0;e[h];++h)bi(e[h],g[h])}if(b){bh(a,d);if(c){e=bj(a),g=bj(d);for(h=0;e[h];++h)bh(e[h],g[h])}}e=g=null;return d},clean:function(a,b,d,e){var g;b=b||c,typeof b.createElement=="undefined"&&(b=b.ownerDocument||b[0]&&b[0].ownerDocument||c);var h=[],i;for(var j=0,k;(k=a[j])!=null;j++){typeof k=="number"&&(k+="");if(!k)continue;if(typeof k=="string")if(!ba.test(k))k=b.createTextNode(k);else{k=k.replace(Z,"<$1></$2>");var l=($.exec(k)||["",""])[1].toLowerCase(),m=bf[l]||bf._default,n=m[0],o=b.createElement("div");o.innerHTML=m[1]+k+m[2];while(n--)o=o.lastChild;if(!f.support.tbody){var p=_.test(k),q=l==="table"&&!p?o.firstChild&&o.firstChild.childNodes:m[1]==="<table>"&&!p?o.childNodes:[];for(i=q.length-1;i>=0;--i)f.nodeName(q[i],"tbody")&&!q[i].childNodes.length&&q[i].parentNode.removeChild(q[i])}!f.support.leadingWhitespace&&Y.test(k)&&o.insertBefore(b.createTextNode(Y.exec(k)[0]),o.firstChild),k=o.childNodes}var r;if(!f.support.appendChecked)if(k[0]&&typeof (r=k.length)=="number")for(i=0;i<r;i++)bl(k[i]);else bl(k);k.nodeType?h.push(k):h=f.merge(h,k)}if(d){g=function(a){return!a.type||bd.test(a.type)};for(j=0;h[j];j++)if(e&&f.nodeName(h[j],"script")&&(!h[j].type||h[j].type.toLowerCase()==="text/javascript"))e.push(h[j].parentNode?h[j].parentNode.removeChild(h[j]):h[j]);else{if(h[j].nodeType===1){var s=f.grep(h[j].getElementsByTagName("script"),g);h.splice.apply(h,[j+1,0].concat(s))}d.appendChild(h[j])}}return h},cleanData:function(a){var b,c,d=f.cache,e=f.expando,g=f.event.special,h=f.support.deleteExpando;for(var i=0,j;(j=a[i])!=null;i++){if(j.nodeName&&f.noData[j.nodeName.toLowerCase()])continue;c=j[f.expando];if(c){b=d[c]&&d[c][e];if(b&&b.events){for(var k in b.events)g[k]?f.event.remove(j,k):f.removeEvent(j,k,b.handle);b.handle&&(b.handle.elem=null)}h?delete j[f.expando]:j.removeAttribute&&j.removeAttribute(f.expando),delete d[c]}}}});var bn=/alpha\([^)]*\)/i,bo=/opacity=([^)]*)/,bp=/([A-Z]|^ms)/g,bq=/^-?\d+(?:px)?$/i,br=/^-?\d/,bs=/^[+\-]=/,bt=/[^+\-\.\de]+/g,bu={position:"absolute",visibility:"hidden",display:"block"},bv=["Left","Right"],bw=["Top","Bottom"],bx,by,bz;f.fn.css=function(a,c){if(arguments.length===2&&c===b)return this;return f.access(this,a,c,!0,function(a,c,d){return d!==b?f.style(a,c,d):f.css(a,c)})},f.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=bx(a,"opacity","opacity");return c===""?"1":c}return a.style.opacity}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":f.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!!a&&a.nodeType!==3&&a.nodeType!==8&&!!a.style){var g,h,i=f.camelCase(c),j=a.style,k=f.cssHooks[i];c=f.cssProps[i]||i;if(d===b){if(k&&"get"in k&&(g=k.get(a,!1,e))!==b)return g;return j[c]}h=typeof d;if(h==="number"&&isNaN(d)||d==null)return;h==="string"&&bs.test(d)&&(d=+d.replace(bt,"")+parseFloat(f.css(a,c)),h="number"),h==="number"&&!f.cssNumber[i]&&(d+="px");if(!k||!("set"in k)||(d=k.set(a,d))!==b)try{j[c]=d}catch(l){}}},css:function(a,c,d){var e,g;c=f.camelCase(c),g=f.cssHooks[c],c=f.cssProps[c]||c,c==="cssFloat"&&(c="float");if(g&&"get"in g&&(e=g.get(a,!0,d))!==b)return e;if(bx)return bx(a,c)},swap:function(a,b,c){var d={};for(var e in b)d[e]=a.style[e],a.style[e]=b[e];c.call(a);for(e in b)a.style[e]=d[e]}}),f.curCSS=f.css,f.each(["height","width"],function(a,b){f.cssHooks[b]={get:function(a,c,d){var e;if(c){if(a.offsetWidth!==0)return bA(a,b,d);f.swap(a,bu,function(){e=bA(a,b,d)});return e}},set:function(a,b){if(!bq.test(b))return b;b=parseFloat(b);if(b>=0)return b+"px"}}}),f.support.opacity||(f.cssHooks.opacity={get:function(a,b){return bo.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?parseFloat(RegExp.$1)/100+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle;c.zoom=1;var e=f.isNaN(b)?"":"alpha(opacity="+b*100+")",g=d&&d.filter||c.filter||"";c.filter=bn.test(g)?g.replace(bn,e):g+" "+e}}),f(function(){f.support.reliableMarginRight||(f.cssHooks.marginRight={get:function(a,b){var c;f.swap(a,{display:"inline-block"},function(){b?c=bx(a,"margin-right","marginRight"):c=a.style.marginRight});return c}})}),c.defaultView&&c.defaultView.getComputedStyle&&(by=function(a,c){var d,e,g;c=c.replace(bp,"-$1").toLowerCase();if(!(e=a.ownerDocument.defaultView))return b;if(g=e.getComputedStyle(a,null))d=g.getPropertyValue(c),d===""&&!f.contains(a.ownerDocument.documentElement,a)&&(d=f.style(a,c));return d}),c.documentElement.currentStyle&&(bz=function(a,b){var c,d=a.currentStyle&&a.currentStyle[b],e=a.runtimeStyle&&a.runtimeStyle[b],f=a.style;!bq.test(d)&&br.test(d)&&(c=f.left,e&&(a.runtimeStyle.left=a.currentStyle.left),f.left=b==="fontSize"?"1em":d||0,d=f.pixelLeft+"px",f.left=c,e&&(a.runtimeStyle.left=e));return d===""?"auto":d}),bx=by||bz,f.expr&&f.expr.filters&&(f.expr.filters.hidden=function(a){var b=a.offsetWidth,c=a.offsetHeight;return b===0&&c===0||!f.support.reliableHiddenOffsets&&(a.style.display||f.css(a,"display"))==="none"},f.expr.filters.visible=function(a){return!f.expr.filters.hidden(a)});var bB=/%20/g,bC=/\[\]$/,bD=/\r?\n/g,bE=/#.*$/,bF=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,bG=/^(?:color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,bH=/^(?:about|app|app\-storage|.+\-extension|file|widget):$/,bI=/^(?:GET|HEAD)$/,bJ=/^\/\//,bK=/\?/,bL=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,bM=/^(?:select|textarea)/i,bN=/\s+/,bO=/([?&])_=[^&]*/,bP=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,bQ=f.fn.load,bR={},bS={},bT,bU;try{bT=e.href}catch(bV){bT=c.createElement("a"),bT.href="",bT=bT.href}bU=bP.exec(bT.toLowerCase())||[],f.fn.extend({load:function(a,c,d){if(typeof a!="string"&&bQ)return bQ.apply(this,arguments);if(!this.length)return this;var e=a.indexOf(" ");if(e>=0){var g=a.slice(e,a.length);a=a.slice(0,e)}var h="GET";c&&(f.isFunction(c)?(d=c,c=b):typeof c=="object"&&(c=f.param(c,f.ajaxSettings.traditional),h="POST"));var i=this;f.ajax({url:a,type:h,dataType:"html",data:c,complete:function(a,b,c){c=a.responseText,a.isResolved()&&(a.done(function(a){c=a}),i.html(g?f("<div>").append(c.replace(bL,"")).find(g):c)),d&&i.each(d,[c,b,a])}});return this},serialize:function(){return f.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?f.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||bM.test(this.nodeName)||bG.test(this.type))}).map(function(a,b){var c=f(this).val();return c==null?null:f.isArray(c)?f.map(c,function(a,c){return{name:b.name,value:a.replace(bD,"\r\n")}}):{name:b.name,value:c.replace(bD,"\r\n")}}).get()}}),f.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){f.fn[b]=function(a){return this.bind(b,a)}}),f.each(["get","post"],function(a,c){f[c]=function(a,d,e,g){f.isFunction(d)&&(g=g||e,e=d,d=b);return f.ajax({type:c,url:a,data:d,success:e,dataType:g})}}),f.extend({getScript:function(a,c){return f.get(a,b,c,"script")},getJSON:function(a,b,c){return f.get(a,b,c,"json")},ajaxSetup:function(a,b){b?f.extend(!0,a,f.ajaxSettings,b):(b=a,a=f.extend(!0,f.ajaxSettings,b));for(var c in{context:1,url:1})c in b?a[c]=b[c]:c in f.ajaxSettings&&(a[c]=f.ajaxSettings[c]);return a},ajaxSettings:{url:bT,isLocal:bH.test(bU[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":"*/*"},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":f.parseJSON,"text xml":f.parseXML}},ajaxPrefilter:bW(bR),ajaxTransport:bW(bS),ajax:function(a,c){function w(a,c,l,m){if(s!==2){s=2,q&&clearTimeout(q),p=b,n=m||"",v.readyState=a?4:0;var o,r,u,w=l?bZ(d,v,l):b,x,y;if(a>=200&&a<300||a===304){if(d.ifModified){if(x=v.getResponseHeader("Last-Modified"))f.lastModified[k]=x;if(y=v.getResponseHeader("Etag"))f.etag[k]=y}if(a===304)c="notmodified",o=!0;else try{r=b$(d,w),c="success",o=!0}catch(z){c="parsererror",u=z}}else{u=c;if(!c||a)c="error",a<0&&(a=0)}v.status=a,v.statusText=c,o?h.resolveWith(e,[r,c,v]):h.rejectWith(e,[v,c,u]),v.statusCode(j),j=b,t&&g.trigger("ajax"+(o?"Success":"Error"),[v,d,o?r:u]),i.resolveWith(e,[v,c]),t&&(g.trigger("ajaxComplete",[v,d]),--f.active||f.event.trigger("ajaxStop"))}}typeof a=="object"&&(c=a,a=b),c=c||{};var d=f.ajaxSetup({},c),e=d.context||d,g=e!==d&&(e.nodeType||e instanceof f)?f(e):f.event,h=f.Deferred(),i=f._Deferred(),j=d.statusCode||{},k,l={},m={},n,o,p,q,r,s=0,t,u,v={readyState:0,setRequestHeader:function(a,b){if(!s){var c=a.toLowerCase();a=m[c]=m[c]||a,l[a]=b}return this},getAllResponseHeaders:function(){return s===2?n:null},getResponseHeader:function(a){var c;if(s===2){if(!o){o={};while(c=bF.exec(n))o[c[1].toLowerCase()]=c[2]}c=o[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){s||(d.mimeType=a);return this},abort:function(a){a=a||"abort",p&&p.abort(a),w(0,a);return this}};h.promise(v),v.success=v.done,v.error=v.fail,v.complete=i.done,v.statusCode=function(a){if(a){var b;if(s<2)for(b in a)j[b]=[j[b],a[b]];else b=a[v.status],v.then(b,b)}return this},d.url=((a||d.url)+"").replace(bE,"").replace(bJ,bU[1]+"//"),d.dataTypes=f.trim(d.dataType||"*").toLowerCase().split(bN),d.crossDomain==null&&(r=bP.exec(d.url.toLowerCase()),d.crossDomain=!(!r||r[1]==bU[1]&&r[2]==bU[2]&&(r[3]||(r[1]==="http:"?80:443))==(bU[3]||(bU[1]==="http:"?80:443)))),d.data&&d.processData&&typeof d.data!="string"&&(d.data=f.param(d.data,d.traditional)),bX(bR,d,c,v);if(s===2)return!1;t=d.global,d.type=d.type.toUpperCase(),d.hasContent=!bI.test(d.type),t&&f.active++===0&&f.event.trigger("ajaxStart");if(!d.hasContent){d.data&&(d.url+=(bK.test(d.url)?"&":"?")+d.data),k=d.url;if(d.cache===!1){var x=f.now(),y=d.url.replace(bO,"$1_="+x);d.url=y+(y===d.url?(bK.test(d.url)?"&":"?")+"_="+x:"")}}(d.data&&d.hasContent&&d.contentType!==!1||c.contentType)&&v.setRequestHeader("Content-Type",d.contentType),d.ifModified&&(k=k||d.url,f.lastModified[k]&&v.setRequestHeader("If-Modified-Since",f.lastModified[k]),f.etag[k]&&v.setRequestHeader("If-None-Match",f.etag[k])),v.setRequestHeader("Accept",d.dataTypes[0]&&d.accepts[d.dataTypes[0]]?d.accepts[d.dataTypes[0]]+(d.dataTypes[0]!=="*"?", */*; q=0.01":""):d.accepts["*"]);for(u in d.headers)v.setRequestHeader(u,d.headers[u]);if(d.beforeSend&&(d.beforeSend.call(e,v,d)===!1||s===2)){v.abort();return!1}for(u in{success:1,error:1,complete:1})v[u](d[u]);p=bX(bS,d,c,v);if(!p)w(-1,"No Transport");else{v.readyState=1,t&&g.trigger("ajaxSend",[v,d]),d.async&&d.timeout>0&&(q=setTimeout(function(){v.abort("timeout")},d.timeout));try{s=1,p.send(l,w)}catch(z){status<2?w(-1,z):f.error(z)}}return v},param:function(a,c){var d=[],e=function(a,b){b=f.isFunction(b)?b():b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=f.ajaxSettings.traditional);if(f.isArray(a)||a.jquery&&!f.isPlainObject(a))f.each(a,function(){e(this.name,this.value)});else for(var g in a)bY(g,a[g],c,e);return d.join("&").replace(bB,"+")}}),f.extend({active:0,lastModified:{},etag:{}});var b_=f.now(),ca=/(\=)\?(&|$)|\?\?/i;f.ajaxSetup({jsonp:"callback",jsonpCallback:function(){return f.expando+"_"+b_++}}),f.ajaxPrefilter("json jsonp",function(b,c,d){var e=b.contentType==="application/x-www-form-urlencoded"&&typeof b.data=="string";if(b.dataTypes[0]==="jsonp"||b.jsonp!==!1&&(ca.test(b.url)||e&&ca.test(b.data))){var g,h=b.jsonpCallback=f.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,i=a[h],j=b.url,k=b.data,l="$1"+h+"$2";b.jsonp!==!1&&(j=j.replace(ca,l),b.url===j&&(e&&(k=k.replace(ca,l)),b.data===k&&(j+=(/\?/.test(j)?"&":"?")+b.jsonp+"="+h))),b.url=j,b.data=k,a[h]=function(a){g=[a]},d.always(function(){a[h]=i,g&&f.isFunction(i)&&a[h](g[0])}),b.converters["script json"]=function(){g||f.error(h+" was not called");return g[0]},b.dataTypes[0]="json";return"script"}}),f.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){f.globalEval(a);return a}}}),f.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),f.ajaxTransport("script",function(a){if(a.crossDomain){var d,e=c.head||c.getElementsByTagName("head")[0]||c.documentElement;return{send:function(f,g){d=c.createElement("script"),d.async="async",a.scriptCharset&&(d.charset=a.scriptCharset),d.src=a.url,d.onload=d.onreadystatechange=function(a,c){if(c||!d.readyState||/loaded|complete/.test(d.readyState))d.onload=d.onreadystatechange=null,e&&d.parentNode&&e.removeChild(d),d=b,c||g(200,"success")},e.insertBefore(d,e.firstChild)},abort:function(){d&&d.onload(0,1)}}}});var cb=a.ActiveXObject?function(){for(var a in cd)cd[a](0,1)}:!1,cc=0,cd;f.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&ce()||cf()}:ce,function(a){f.extend(f.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(f.ajaxSettings.xhr()),f.support.ajax&&f.ajaxTransport(function(c){if(!c.crossDomain||f.support.cors){var d;return{send:function(e,g){var h=c.xhr(),i,j;c.username?h.open(c.type,c.url,c.async,c.username,c.password):h.open(c.type,c.url,c.async);if(c.xhrFields)for(j in c.xhrFields)h[j]=c.xhrFields[j];c.mimeType&&h.overrideMimeType&&h.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(j in e)h.setRequestHeader(j,e[j])}catch(k){}h.send(c.hasContent&&c.data||null),d=function(a,e){var j,k,l,m,n;try{if(d&&(e||h.readyState===4)){d=b,i&&(h.onreadystatechange=f.noop,cb&&delete cd[i]);if(e)h.readyState!==4&&h.abort();else{j=h.status,l=h.getAllResponseHeaders(),m={},n=h.responseXML,n&&n.documentElement&&(m.xml=n),m.text=h.responseText;try{k=h.statusText}catch(o){k=""}!j&&c.isLocal&&!c.crossDomain?j=m.text?200:404:j===1223&&(j=204)}}}catch(p){e||g(-1,p)}m&&g(j,k,m,l)},!c.async||h.readyState===4?d():(i=++cc,cb&&(cd||(cd={},f(a).unload(cb)),cd[i]=d),h.onreadystatechange=d)},abort:function(){d&&d(0,1)}}}});var cg={},ch,ci,cj=/^(?:toggle|show|hide)$/,ck=/^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,cl,cm=[["height","marginTop","marginBottom","paddingTop","paddingBottom"],["width","marginLeft","marginRight","paddingLeft","paddingRight"],["opacity"]],cn,co=a.webkitRequestAnimationFrame||a.mozRequestAnimationFrame||a.oRequestAnimationFrame;f.fn.extend({show:function(a,b,c){var d,e;if(a||a===0)return this.animate(cr("show",3),a,b,c);for(var g=0,h=this.length;g<h;g++)d=this[g],d.style&&(e=d.style.display,!f._data(d,"olddisplay")&&e==="none"&&(e=d.style.display=""),e===""&&f.css(d,"display")==="none"&&f._data(d,"olddisplay",cs(d.nodeName)));for(g=0;g<h;g++){d=this[g];if(d.style){e=d.style.display;if(e===""||e==="none")d.style.display=f._data(d,"olddisplay")||""}}return this},hide:function(a,b,c){if(a||a===0)return this.animate(cr("hide",3),a,b,c);for(var d=0,e=this.length;d<e;d++)if(this[d].style){var g=f.css(this[d],"display");g!=="none"&&!f._data(this[d],"olddisplay")&&f._data(this[d],"olddisplay",g)}for(d=0;d<e;d++)this[d].style&&(this[d].style.display="none");return this},_toggle:f.fn.toggle,toggle:function(a,b,c){var d=typeof a=="boolean";f.isFunction(a)&&f.isFunction(b)?this._toggle.apply(this,arguments):a==null||d?this.each(function(){var b=d?a:f(this).is(":hidden");f(this)[b?"show":"hide"]()}):this.animate(cr("toggle",3),a,b,c);return this},fadeTo:function(a,b,c,d){return this.filter(":hidden").css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=f.speed(b,c,d);if(f.isEmptyObject(a))return this.each(e.complete,[!1]);a=f.extend({},a);return this[e.queue===!1?"each":"queue"](function(){e.queue===!1&&f._mark(this);var b=f.extend({},e),c=this.nodeType===1,d=c&&f(this).is(":hidden"),g,h,i,j,k,l,m,n,o;b.animatedProperties={};for(i in a){g=f.camelCase(i),i!==g&&(a[g]=a[i],delete a[i]),h=a[g],f.isArray(h)?(b.animatedProperties[g]=h[1],h=a[g]=h[0]):b.animatedProperties[g]=b.specialEasing&&b.specialEasing[g]||b.easing||"swing";if(h==="hide"&&d||h==="show"&&!d)return b.complete.call(this);c&&(g==="height"||g==="width")&&(b.overflow=[this.style.overflow,this.style.overflowX,this.style.overflowY],f.css(this,"display")==="inline"&&f.css(this,"float")==="none"&&(f.support.inlineBlockNeedsLayout?(j=cs(this.nodeName),j==="inline"?this.style.display="inline-block":(this.style.display="inline",this.style.zoom=1)):this.style.display="inline-block"))}b.overflow!=null&&(this.style.overflow="hidden");for(i in a)k=new f.fx(this,b,i),h=a[i],cj.test(h)?k[h==="toggle"?d?"show":"hide":h]():(l=ck.exec(h),m=k.cur(),l?(n=parseFloat(l[2]),o=l[3]||(f.cssNumber[i]?"":"px"),o!=="px"&&(f.style(this,i,(n||1)+o),m=(n||1)/k.cur()*m,f.style(this,i,m+o)),l[1]&&(n=(l[1]==="-="?-1:1)*n+m),k.custom(m,n,o)):k.custom(m,h,""));return!0})},stop:function(a,b){a&&this.queue([]),this.each(function(){var a=f.timers,c=a.length;b||f._unmark(!0,this);while(c--)a[c].elem===this&&(b&&a[c](!0),a.splice(c,1))}),b||this.dequeue();return this}}),f.each({slideDown:cr("show",1),slideUp:cr("hide",1),slideToggle:cr("toggle",1),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){f.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),f.extend({speed:function(a,b,c){var d=a&&typeof a=="object"?f.extend({},a):{complete:c||!c&&b||f.isFunction(a)&&a,duration:a,easing:c&&b||b&&!f.isFunction(b)&&b};d.duration=f.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in f.fx.speeds?f.fx.speeds[d.duration]:f.fx.speeds._default,d.old=d.complete,d.complete=function(a){f.isFunction(d.old)&&d.old.call(this),d.queue!==!1?f.dequeue(this):a!==!1&&f._unmark(this)};return d},easing:{linear:function(a,b,c,d){return c+d*a},swing:function(a,b,c,d){return(-Math.cos(a*Math.PI)/2+.5)*d+c}},timers:[],fx:function(a,b,c){this.options=b,this.elem=a,this.prop=c,b.orig=b.orig||{}}}),f.fx.prototype={update:function(){this.options.step&&this.options.step.call(this.elem,this.now,this),(f.fx.step[this.prop]||f.fx.step._default)(this)},cur:function(){if(this.elem[this.prop]!=null&&(!this.elem.style||this.elem.style[this.prop]==null))return this.elem[this.prop];var a,b=f.css(this.elem,this.prop);return isNaN(a=parseFloat(b))?!b||b==="auto"?0:b:a},custom:function(a,b,c){function h(a){return d.step(a)}var d=this,e=f.fx,g;this.startTime=cn||cp(),this.start=a,this.end=b,this.unit=c||this.unit||(f.cssNumber[this.prop]?"":"px"),this.now=this.start,this.pos=this.state=0,h.elem=this.elem,h()&&f.timers.push(h)&&!cl&&(co?(cl=!0,g=function(){cl&&(co(g),e.tick())},co(g)):cl=setInterval(e.tick,e.interval))},show:function(){this.options.orig[this.prop]=f.style(this.elem,this.prop),this.options.show=!0,this.custom(this.prop==="width"||this.prop==="height"?1:0,this.cur()),f(this.elem).show()},hide:function(){this.options.orig[this.prop]=f.style(this.elem,this.prop),this.options.hide=!0,this.custom(this.cur(),0)},step:function(a){var b=cn||cp(),c=!0,d=this.elem,e=this.options,g,h;if(a||b>=e.duration+this.startTime){this.now=this.end,this.pos=this.state=1,this.update(),e.animatedProperties[this.prop]=!0;for(g in e.animatedProperties)e.animatedProperties[g]!==!0&&(c=!1);if(c){e.overflow!=null&&!f.support.shrinkWrapBlocks&&f.each(["","X","Y"],function(a,b){d.style["overflow"+b]=e.overflow[a]}),e.hide&&f(d).hide();if(e.hide||e.show)for(var i in e.animatedProperties)f.style(d,i,e.orig[i]);e.complete.call(d)}return!1}e.duration==Infinity?this.now=b:(h=b-this.startTime,this.state=h/e.duration,this.pos=f.easing[e.animatedProperties[this.prop]](this.state,h,0,1,e.duration),this.now=this.start+(this.end-this.start)*this.pos),this.update();return!0}},f.extend(f.fx,{tick:function(){for(var a=f.timers,b=0;b<a.length;++b)a[b]()||a.splice(b--,1);a.length||f.fx.stop()},interval:13,stop:function(){clearInterval(cl),cl=null},speeds:{slow:600,fast:200,_default:400},step:{opacity:function(a){f.style(a.elem,"opacity",a.now)},_default:function(a){a.elem.style&&a.elem.style[a.prop]!=null?a.elem.style[a.prop]=(a.prop==="width"||a.prop==="height"?Math.max(0,a.now):a.now)+a.unit:a.elem[a.prop]=a.now}}}),f.expr&&f.expr.filters&&(f.expr.filters.animated=function(a){return f.grep(f.timers,function(b){return a===b.elem}).length});var ct=/^t(?:able|d|h)$/i,cu=/^(?:body|html)$/i;"getBoundingClientRect"in c.documentElement?f.fn.offset=function(a){var b=this[0],c;if(a)return this.each(function(b){f.offset.setOffset(this,a,b)});if(!b||!b.ownerDocument)return null;if(b===b.ownerDocument.body)return f.offset.bodyOffset(b);try{c=b.getBoundingClientRect()}catch(d){}var e=b.ownerDocument,g=e.documentElement;if(!c||!f.contains(g,b))return c?{top:c.top,left:c.left}:{top:0,left:0};var h=e.body,i=cv(e),j=g.clientTop||h.clientTop||0,k=g.clientLeft||h.clientLeft||0,l=i.pageYOffset||f.support.boxModel&&g.scrollTop||h.scrollTop,m=i.pageXOffset||f.support.boxModel&&g.scrollLeft||h.scrollLeft,n=c.top+l-j,o=c.left+m-k;return{top:n,left:o}}:f.fn.offset=function(a){var b=this[0];if(a)return this.each(function(b){f.offset.setOffset(this,a,b)});if(!b||!b.ownerDocument)return null;if(b===b.ownerDocument.body)return f.offset.bodyOffset(b);f.offset.initialize();var c,d=b.offsetParent,e=b,g=b.ownerDocument,h=g.documentElement,i=g.body,j=g.defaultView,k=j?j.getComputedStyle(b,null):b.currentStyle,l=b.offsetTop,m=b.offsetLeft;while((b=b.parentNode)&&b!==i&&b!==h){if(f.offset.supportsFixedPosition&&k.position==="fixed")break;c=j?j.getComputedStyle(b,null):b.currentStyle,l-=b.scrollTop,m-=b.scrollLeft,b===d&&(l+=b.offsetTop,m+=b.offsetLeft,f.offset.doesNotAddBorder&&(!f.offset.doesAddBorderForTableAndCells||!ct.test(b.nodeName))&&(l+=parseFloat(c.borderTopWidth)||0,m+=parseFloat(c.borderLeftWidth)||0),e=d,d=b.offsetParent),f.offset.subtractsBorderForOverflowNotVisible&&c.overflow!=="visible"&&(l+=parseFloat(c.borderTopWidth)||0,m+=parseFloat(c.borderLeftWidth)||0),k=c}if(k.position==="relative"||k.position==="static")l+=i.offsetTop,m+=i.offsetLeft;f.offset.supportsFixedPosition&&k.position==="fixed"&&(l+=Math.max(h.scrollTop,i.scrollTop),m+=Math.max(h.scrollLeft,i.scrollLeft));return{top:l,left:m}},f.offset={initialize:function(){var a=c.body,b=c.createElement("div"),d,e,g,h,i=parseFloat(f.css(a,"marginTop"))||0,j="<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";f.extend(b.style,{position:"absolute",top:0,left:0,margin:0,border:0,width:"1px",height:"1px",visibility:"hidden"}),b.innerHTML=j,a.insertBefore(b,a.firstChild),d=b.firstChild,e=d.firstChild,h=d.nextSibling.firstChild.firstChild,this.doesNotAddBorder=e.offsetTop!==5,this.doesAddBorderForTableAndCells=h.offsetTop===5,e.style.position="fixed",e.style.top="20px",this.supportsFixedPosition=e.offsetTop===20||e.offsetTop===15,e.style.position=e.style.top="",d.style.overflow="hidden",d.style.position="relative",this.subtractsBorderForOverflowNotVisible=e.offsetTop===-5,this.doesNotIncludeMarginInBodyOffset=a.offsetTop!==i,a.removeChild(b),f.offset.initialize=f.noop},bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;f.offset.initialize(),f.offset.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(f.css(a,"marginTop"))||0,c+=parseFloat(f.css(a,"marginLeft"))||0);return{top:b,left:c}},setOffset:function(a,b,c){var d=f.css(a,"position");d==="static"&&(a.style.position="relative");var e=f(a),g=e.offset(),h=f.css(a,"top"),i=f.css(a,"left"),j=(d==="absolute"||d==="fixed")&&f.inArray("auto",[h,i])>-1,k={},l={},m,n;j?(l=e.position(),m=l.top,n=l.left):(m=parseFloat(h)||0,n=parseFloat(i)||0),f.isFunction(b)&&(b=b.call(a,c,g)),b.top!=null&&(k.top=b.top-g.top+m),b.left!=null&&(k.left=b.left-g.left+n),"using"in b?b.using.call(a,k):e.css(k)}},f.fn.extend({position:function(){if(!this[0])return null;var a=this[0],b=this.offsetParent(),c=this.offset(),d=cu.test(b[0].nodeName)?{top:0,left:0}:b.offset();c.top-=parseFloat(f.css(a,"marginTop"))||0,c.left-=parseFloat(f.css(a,"marginLeft"))||0,d.top+=parseFloat(f.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(f.css(b[0],"borderLeftWidth"))||0;return{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||c.body;while(a&&!cu.test(a.nodeName)&&f.css(a,"position")==="static")a=a.offsetParent;return a})}}),f.each(["Left","Top"],function(a,c){var d="scroll"+c;f.fn[d]=function(c){var e,g;if(c===b){e=this[0];if(!e)return null;g=cv(e);return g?"pageXOffset"in g?g[a?"pageYOffset":"pageXOffset"]:f.support.boxModel&&g.document.documentElement[d]||g.document.body[d]:e[d]}return this.each(function(){g=cv(this),g?g.scrollTo(a?f(g).scrollLeft():c,a?c:f(g).scrollTop()):this[d]=c})}}),f.each(["Height","Width"],function(a,c){var d=c.toLowerCase();f.fn["inner"+c]=function(){var a=this[0];return a&&a.style?parseFloat(f.css(a,d,"padding")):null},f.fn["outer"+c]=function(a){var b=this[0];return b&&b.style?parseFloat(f.css(b,d,a?"margin":"border")):null},f.fn[d]=function(a){var e=this[0];if(!e)return a==null?null:this;if(f.isFunction(a))return this.each(function(b){var c=f(this);c[d](a.call(this,b,c[d]()))});if(f.isWindow(e)){var g=e.document.documentElement["client"+c];return e.document.compatMode==="CSS1Compat"&&g||e.document.body["client"+c]||g}if(e.nodeType===9)return Math.max(e.documentElement["client"+c],e.body["scroll"+c],e.documentElement["scroll"+c],e.body["offset"+c],e.documentElement["offset"+c]);if(a===b){var h=f.css(e,d),i=parseFloat(h);return f.isNaN(i)?h:i}return this.css(d,typeof a=="string"?a:a+"px")}}),a.jQuery=a.$=f})(window);
/* Copyright (c) 2006 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $LastChangedDate: 2007-07-22 01:45:56 +0200 (Son, 22 Jul 2007) $
 * $Rev: 2447 $
 *
 * Version 2.1.1
 */
(function($){$.fn.bgIframe=$.fn.bgiframe=function(s){if($.browser.msie&&/6.0/.test(navigator.userAgent)){s=$.extend({top:'auto',left:'auto',width:'auto',height:'auto',opacity:true,src:'javascript:false;'},s||{});var prop=function(n){return n&&n.constructor==Number?n+'px':n;},html='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="'+s.src+'"'+'style="display:block;position:absolute;z-index:-1;'+(s.opacity!==false?'filter:Alpha(Opacity=\'0\');':'')+'top:'+(s.top=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')':prop(s.top))+';'+'left:'+(s.left=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')':prop(s.left))+';'+'width:'+(s.width=='auto'?'expression(this.parentNode.offsetWidth+\'px\')':prop(s.width))+';'+'height:'+(s.height=='auto'?'expression(this.parentNode.offsetHeight+\'px\')':prop(s.height))+';'+'"/>';return this.each(function(){if($('> iframe.bgiframe',this).length==0)this.insertBefore(document.createElement('html'),this.firstChild);});}return this;};})(jQuery);
﻿/**
 * jQuery.timers - Timer abstractions for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/02/08
 *
 * @author Blair Mitchelmore
 * @version 1.1.2
 *
 **/

jQuery.fn.extend({
	everyTime: function(interval, label, fn, times, belay) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, times, belay);
		});
	},
	oneTime: function(interval, label, fn) {
		return this.each(function() {
			jQuery.timer.add(this, interval, label, fn, 1);
		});
	},
	stopTime: function(label, fn) {
		return this.each(function() {
			jQuery.timer.remove(this, label, fn);
		});
	}
});

jQuery.event.special

jQuery.extend({
	timer: {
		global: [],
		guid: 1,
		dataKey: "jQuery.timer",
		regex: /^([0-9]+(?:\.[0-9]*)?)\s*(.*s)?$/,
		powers: {
			// Yeah this is major overkill...
			'ms': 1,
			'cs': 10,
			'ds': 100,
			's': 1000,
			'das': 10000,
			'hs': 100000,
			'ks': 1000000
		},
		timeParse: function(value) {
			if (value == undefined || value == null)
				return null;
			var result = this.regex.exec(jQuery.trim(value.toString()));
			if (result[2]) {
				var num = parseFloat(result[1]);
				var mult = this.powers[result[2]] || 1;
				return num * mult;
			} else {
				return value;
			}
		},
		add: function(element, interval, label, fn, times, belay) {
			var counter = 0;
			
			if (jQuery.isFunction(label)) {
				if (!times) 
					times = fn;
				fn = label;
				label = interval;
			}
			
			interval = jQuery.timer.timeParse(interval);

			if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
				return;

			if (times && times.constructor != Number) {
				belay = !!times;
				times = 0;
			}
			
			times = times || 0;
			belay = belay || false;
			
			var timers = jQuery.data(element, this.dataKey) || jQuery.data(element, this.dataKey, {});
			
			if (!timers[label])
				timers[label] = {};
			
			fn.timerID = fn.timerID || this.guid++;
			
			var handler = function() {
				if (belay && this.inProgress) 
					return;
				this.inProgress = true;
				if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
					jQuery.timer.remove(element, label, fn);
				this.inProgress = false;
			};
			
			handler.timerID = fn.timerID;
			
			if (!timers[label][fn.timerID])
				timers[label][fn.timerID] = window.setInterval(handler,interval);
			
			this.global.push( element );
			
		},
		remove: function(element, label, fn) {
			var timers = jQuery.data(element, this.dataKey), ret;
			
			if ( timers ) {
				
				if (!label) {
					for ( label in timers )
						this.remove(element, label, fn);
				} else if ( timers[label] ) {
					if ( fn ) {
						if ( fn.timerID ) {
							window.clearInterval(timers[label][fn.timerID]);
							delete timers[label][fn.timerID];
						}
					} else {
						for ( var fn in timers[label] ) {
							window.clearInterval(timers[label][fn]);
							delete timers[label][fn];
						}
					}
					
					for ( ret in timers[label] ) break;
					if ( !ret ) {
						ret = null;
						delete timers[label];
					}
				}
				
				for ( ret in timers ) break;
				if ( !ret ) 
					jQuery.removeData(element, this.dataKey);
			}
		}
	}
});

jQuery(window).bind("unload", function() {
	jQuery.each(jQuery.timer.global, function(index, item) {
		jQuery.timer.remove(item);
	});
});
/**
 * weebox.js
 *
 * weebox js
 *
 * @category   javascript
 * @package    jquery
 * @author     Jack <xiejinci@gmail.com>
 * @copyright  Copyright (c) 2006-2008 9wee Com. (http://www.9wee.com)
 * @license    http://www.9wee.com/license/
 * @version    
 */ 
(function($) {
	/*if(typeof($.fn.bgIframe) == 'undefined') {
		$.ajax({
			type: "GET",
		  	url: '/js/jquery/bgiframe.js',//路径不好处理
		  	success: function(js){eval(js);},
		  	async: false				  	
		});
	}*/
	var weebox = function(content, options) {
		var self = this;
		this._dragging = false;
		this._content = content;
		this._options = options;
		this.dh = null;
		this.mh = null;
		this.dt = null;
		this.dc = null;
		this.bo = null;
		this.bc = null;
		this.selector = null;	
		this.ajaxurl = null;
		this.options = null;
		this.defaults = {
			boxid: null,
			boxclass: null,
			type: 'dialog',
			title: '',
			width: 0,
			height: 0,
			timeout: 0, 
			draggable: true,
			modal: true,
			focus: null,
			position: 'center',
			overlay: 50,
			showTitle: true,
			showButton: true,
			showCancel: true, 
			showOk: true,
			okBtnName: '确定',
			cancelBtnName: '取消',
			contentType: 'text',
			contentChange: false,
			clickClose: false,
			zIndex: 999,
			animate: false,
			trigger: null,
			onclose: null,
			onopen: null,
			onok: null		
		};
		this.types = new Array(
			"dialog", 
			"error", 
			"warning", 
			"success", 
			"prompt",
			"box"
		);
		this.titles = {
			"error": 	"!! Error !!",
			"warning": 	"Warning!",
			"success": 	"Success",
			"prompt": 	"Please Choose",
			"dialog": 	"Dialog",
			"box":		""
		};
		
		this.initOptions = function() {	
			if (typeof(self._options) == "undefined") {
				self._options = {};
			}
			if (typeof(self._options.type) == "undefined") {
				self._options.type = 'dialog';
			}
			if(!$.inArray(self._options.type, self.types)) {
				self._options.type = self.types[0];
			}
			if (typeof(self._options.boxclass) == "undefined") {
				self._options.boxclass = self._options.type+"box";
			}
			if (typeof(self._options.title) == "undefined") {
				self._options.title = self.titles[self._options.type];
			}
			if (content.substr(0, 1) == "#") {
				self._options.contentType = 'selector';
				self.selector = content; 
			}
			self.options = $.extend({}, self.defaults, self._options);
		};
		
		this.initBox = function() {
			var html = '';	
			if (self.options.type == 'wee') {
				html =  '<div class="weedialog">' +
				'	<div class="dialog-top">' +
				'		<div class="dialog-tl"></div>' +
				'		<div class="dialog-tc"></div>' +
				'		<div class="dialog-tr"></div>' +
				'	</div>' +
				'	<table width="100%" border="0" cellspacing="0" cellpadding="0" >' +
				'		<tr>' +
				'			<td class="dialog-cl"></td>' +
				'			<td>' +
				'				<div class="dialog-header">' +
				'					<div class="dialog-title"></div>' +
				'					<div class="dialog-close"></div>' +
				'				</div>' +
				'				<div class="dialog-content"></div>' +
				'				<div class="dialog-button">' +
				'					<button class="ui-button dialog-cancel" rel="dialog-cancel">取消</button>' +
				'					<button class="ui-button dialog-ok" rel="dialog-ok">确定</button>' +				
				'				</div>' +
				'			</td>' +
				'			<td class="dialog-cr"></td>' +
				'		</tr>' +
				'	</table>' +
				'	<div class="dialog-bot">' +
				'		<div class="dialog-bl"></div>' +
				'		<div class="dialog-bc"></div>' +
				'		<div class="dialog-br"></div>' +
				'	</div>' +
				'</div>';
				
			} else {
				html = "<div class='dialog-box'>" +
							"<div class='dialog-header'>" +
								"<div class='dialog-title'></div>" +
								"<div class='dialog-close'></div>" +
							"</div>" +
							"<div class='dialog-content'></div>" +	
							"<div style='clear:both'></div>" +				
							"<div class='dialog-button'>" +
							'					<button class="ui-button dialog-ok" rel="dialog-ok">确定</button>' +
							'					<button class="ui-button dialog-cancel" rel="dialog-cancel">关闭</button>' +
							"</div>" +
						"</div>";
			}
			self.dh = $(html).appendTo('body').css({
				position: 'absolute',	
				overflow: 'hidden',
				zIndex: self.options.zIndex
			});	
			self.dt = self.dh.find('.dialog-title');
			self.dc = self.dh.find('.dialog-content');
			self.bo = self.dh.find('.dialog-ok');
			self.bc = self.dh.find('.dialog-cancel');
			if (self.options.boxid) {
				self.dh.attr('id', self.options.boxid);
			}	
			if (self.options.boxclass) {
				self.dh.addClass(self.options.boxclass);
			}
			if (self.options.height>0) {
				self.dc.css('height', self.options.height);
			}
			if (self.options.width>0) {
				self.dh.css('width', self.options.width);
			}
			//self.dh.bgiframe();	
		}
		
		this.initMask = function() {							
			if (self.options.modal) {
				self.mh = $("<div class='dialog-mask'></div>")
				.appendTo('body').hide().css({
					opacity: self.options.overlay/100,
					filter: 'alpha(opacity='+self.options.overlay+')',
					width: self.bwidth(),
					height: self.bheight(),
					zIndex: self.options.zIndex-1
				});
			}
		}
		
		this.initContent = function(content) {
			self.dh.find(".dialog-ok").val(self.options.okBtnName);
			self.dh.find(".dialog-cancel").val(self.options.cancelBtnName);	
			self.dh.find('.dialog-title').html(self.options.title);
			if (!self.options.showTitle) {
				self.dh.find('.dialog-header').hide();
			}	
			if (!self.options.showButton) {
				self.dh.find('.dialog-button').hide();
			}
			if (!self.options.showCancel) {
				self.dh.find('.dialog-cancel').hide();
			}							
			if (!self.options.showOk) {
				self.dh.find(".dialog-ok").hide();
			}			
			if (self.options.contentType == "selector") {
				self.selector = self._content;
				self._content = $(self.selector).html();
				self.setContent(self._content);
				//if have checkbox do
				var cs = $(self.selector).find(':checkbox');
				self.dh.find('.dialog-content').find(':checkbox').each(function(i){
					this.checked = cs[i].checked;
				});				
				$(self.selector).empty();
				self.onopen();
				self.show();
				self.focus();
			} else if (self.options.contentType == "ajax") {	
				self.ajaxurl = self._content;			
				self.setContent('<div class="dialog-loading"></div>');				
				self.show();
				$.get(self.ajaxurl, function(data) {
					self._content = data;
			    	self.setContent(self._content);
			    	self.onopen();
			    	self.focus();		  	
			    	if (self.options.position == 'center') {
						self.setCenterPosition();
			    	}
				});
			} else {
				self.setContent(self._content);
				self.onopen();	
				self.show();	
				self.focus();					
			}
		}
		
		this.initEvent = function() {
			self.dh.find(".dialog-close, .dialog-cancel, .dialog-ok").unbind('click').click(function(){
				self.close();
			});			
			if (typeof(self.options.onok) == "function") {
				self.dh.find(".dialog-ok").unbind('click').click(self.options.onok);
			} 
			if (typeof(self.options.oncancel) == "function") {
				self.dh.find(".dialog-cancel").unbind('click').click(self.options.oncancel);
			}			
			if (self.options.timeout>0) {
				window.setTimeout(self.close, (self.options.timeout * 1000));
			}	
			this.draggable();			
		}
		
		this.draggable = function() {	
			if (self.options.draggable && self.options.showTitle) {
				self.dh.find('.dialog-header').mousedown(function(event){
					self._ox = self.dh.position().left;
					self._oy = self.dh.position().top;					
					self._mx = event.clientX;
					self._my = event.clientY;
					self._dragging = true;
				});
				if (self.mh) {
					var handle = self.mh;
				} else {
					var handle = $(document);
				}
				$(document).mousemove(function(event){
					if (self._dragging == true) {
						//window.status = "X:"+event.clientX+"Y:"+event.clientY;
						self.dh.css({
							left: self._ox+event.clientX-self._mx, 
							top: self._oy+event.clientY-self._my
						});
					}
				}).mouseup(function(){
					self._mx = null;
					self._my = null;
					self._dragging = false;
				});
				var e = self.dh.find('.dialog-header').get(0);
				e.unselectable = "on";
				e.onselectstart = function() { 
					return false; 
				};
				if (e.style) { 
					e.style.MozUserSelect = "none"; 
				}
			}	
		}
		
		this.onopen = function() {							
			if (typeof(self.options.onopen) == "function") {
				self.options.onopen();
			}	
		}
		
		this.show = function() {	
			if (self.options.position == 'center'||self.options.position == 'fixed') {
				self.setCenterPosition();
			}
			if (self.options.position == 'element') {
				self.setElementPosition();
			}		
			if (self.options.animate) {				
				self.dh.fadeIn("slow");
				if (self.mh) {
					self.mh.fadeIn("normal");
				}
			} else {
				self.dh.show();
				if (self.mh) {
					self.mh.show();
				}
			}	
		}
		
		this.focus = function() {
			if (self.options.focus) {
				self.dh.find(self.options.focus).focus();
			} else {
				self.dh.find('.dialog-cancel').focus();
			}
		}
		
		this.find = function(selector) {
			return self.dh.find(selector);
		}
		
		this.setTitle = function(title) {
			self.dh.find('.dialog-title').html(title);
		}
		
		this.getTitle = function() {
			return self.dh.find('.dialog-title').html();
		}
		
		this.setContent = function(content) {
			self.dh.find('.dialog-content').html(content);
		}
		
		this.getContent = function() {
			return self.dh.find('.dialog-content').html();
		}
		
		this.hideButton = function(btname) {
			self.dh.find('.dialog-'+btname).hide();			
		}
		
		this.showButton = function(btname) {
			self.dh.find('.dialog-'+btname).show();	
		}
		
		this.setButtonTitle = function(btname, title) {
			self.dh.find('.dialog-'+btname).val(title);	
		}
		
		this.close = function() {
			if (self.animate) {
				self.dh.fadeOut("slow", function () { self.dh.hide(); });
				if (self.mh) {
					self.mh.fadeOut("normal", function () { self.mh.hide(); });
				}
			} else {
				self.dh.hide();
				if (self.mh) {
					self.mh.hide();
				}
			}
			if (self.options.contentType == 'selector') {
				if (self.options.contentChange) {
					//if have checkbox do
					var cs = self.find(':checkbox');
					$(self.selector).html(self.getContent());						
					if (cs.length > 0) {
						$(self.selector).find(':checkbox').each(function(i){
							this.checked = cs[i].checked;
						});
					}
				} else {
					$(self.selector).html(self._content);
				} 
			}								
			if (typeof(self.options.onclose) == "function") {
				self.options.onclose();
			}
			self.dh.remove();
			if (self.mh) {
				self.mh.remove();
			}
		}
		
		this.bheight = function() {
			if ($.browser.msie && $.browser.version < 7) {
				var scrollHeight = Math.max(
					document.documentElement.scrollHeight,
					document.body.scrollHeight
				);
				var offsetHeight = Math.max(
					document.documentElement.offsetHeight,
					document.body.offsetHeight
				);
				
				if (scrollHeight < offsetHeight) {
					return $(window).height();
				} else {
					return scrollHeight;
				}
			} else {
				return $(document).height();
			}
		}
		
		this.bwidth = function() {
			if ($.browser.msie && $.browser.version < 7) {
				var scrollWidth = Math.max(
					document.documentElement.scrollWidth,
					document.body.scrollWidth
				);
				var offsetWidth = Math.max(
					document.documentElement.offsetWidth,
					document.body.offsetWidth
				);
				
				if (scrollWidth < offsetWidth) {
					return $(window).width();
				} else {
					return scrollWidth;
				}
			} else {
				return $(document).width();
			}
		}
		
		this.setCenterPosition = function() {
			var wnd = $(window), doc = $(document),
				pTop = doc.scrollTop(),	pLeft = doc.scrollLeft(),
				minTop = pTop;	
			pTop += (wnd.height() - self.dh.height()) / 2;
			pTop = Math.max(pTop, minTop);
			pLeft += (wnd.width() - self.dh.width()) / 2;
			self.dh.css({top: pTop, left: pLeft});
			
		}
		
//		this.setElementPosition = function() {
//			var trigger = $("#"+self.options.trigger);			
//			if (trigger.length == 0) {
//				alert('请设置位置的相对夺宝币素');
//				self.close();				
//				return false;
//			}		
//			var scrollWidth = 0;
//			if (!$.browser.msie || $.browser.version >= 7) {
//				scrollWidth = $(window).width() - document.body.scrollWidth;
//			}
//			
//			var left = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft)+trigger.position().left;
//			if (left+self.dh.width() > document.body.clientWidth) {
//				left = trigger.position().left + trigger.width() + scrollWidth - self.dh.width();
//			} 
//			var top = Math.max(document.documentElement.scrollTop, document.body.scrollTop)+trigger.position().top;
//			if (top+self.dh.height()+trigger.height() > document.documentElement.clientHeight) {
//				top = top - self.dh.height() - 5;
//			} else {
//				top = top + trigger.height() + 5;
//			}
//			self.dh.css({top: top, left: left});
//			return true;
//		}	
	
		this.setElementPosition = function() {
			var trigger = $(self.options.trigger);	
			if (trigger.length == 0) {
				alert('请设置位置的相对夺宝币素');
				self.close();				
				return false;
			}
			var left = trigger.offset().left;
			var top = trigger.offset().top + 25;
			self.dh.css({top: top, left: left});
			return true;
		}	
		
		//窗口初始化	
		this.initialize = function() {
			self.initOptions();
			self.initMask();
			self.initBox();		
			self.initContent();
			self.initEvent();
			return self;
		}
		//初始化
		this.initialize();
	}	
	
	var weeboxs = function() {		
		var self = this;
		this._onbox = false;
		this._opening = false;
		this.boxs = new Array();
		this.zIndex = 999;
		this.push = function(box) {
			this.boxs.push(box);
		}
		this.pop = function() {
			if (this.boxs.length > 0) {
				return this.boxs.pop();
			} else {
				return false;
			}
		}
		this.open = function(content, options) {
			self._opening = true;
			if (typeof(options) == "undefined") {
				options = {};
			}
			if (options.boxid) {
			//	this.close(options.boxid);
			}
			options.zIndex = this.zIndex;
			this.zIndex += 10;
			var box = new weebox(content, options);
			box.dh.click(function(){
				self._onbox = true;
			});
			this.push(box);
			return box;
		}
		this.close = function(id) {
			if (id) {
				for(var i=0; i<this.boxs.length; i++) {
					if (this.boxs[i].dh.attr('id') == id) {
						this.boxs[i].close();
						this.boxs.splice(i,1);
					}
				}
			} else {
				this.pop().close();
			}
		}
		this.length = function() {
			return this.boxs.length;
		}
		this.getTopBox = function() {
			return this.boxs[this.boxs.length-1];
		}	
		this.find = function(selector) {
			return this.getTopBox().dh.find(selector);
		}		
		this.setTitle = function(title) {
			this.getTopBox().setTitle(title);
		}		
		this.getTitle = function() {
			return this.getTopBox().getTitle();
		}		
		this.setContent = function(content) {
			this.getTopBox().setContent(content);
		}		
		this.getContent = function() {
			return this.getTopBox().getContent();
		}		
		this.hideButton = function(btname) {
			this.getTopBox().hideButton(btname);			
		}		
		this.showButton = function(btname) {
			this.getTopBox().showButton(btname);	
		}		
		this.setButtonTitle = function(btname, title) {
			this.getTopBox().setButtonTitle(btname, title);	
		}
		$(window).scroll(function() {
			if (self.length() > 0) {
				var box = self.getTopBox();
				if (box.options.position == "center") {
					self.getTopBox().setCenterPosition();
				}
			}			
		});
		$(document).click(function() {
			if (self.length()>0) {
				var box = self.getTopBox();
				if(!self._opening && !self._onbox && box.options.clickClose) {
					box.close();
				}
			}
			self._opening = false;
			self._onbox = false;
		});
	}
	$.extend({weeboxs: new weeboxs()});		
})(jQuery);
/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch and iPad, should also work with Android mobile phones (not tested yet!)
 * Common usage: wipe images (left and right to show the previous or next image)
 *
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 * @version 1.1.1 (9th December 2010) - fix bug (older IE's had problems)
 * @version 1.1 (1st September 2010) - support wipe up and wipe down
 * @version 1.0 (15th July 2010)
 */
(function($) {
  
   $.fn.touchwipe = function(settings) {
 
     var config = {
 
            min_move_x: 20,
            min_move_y: 20,
            wipeLeft: function() {
  
},
            wipeRight: function() {
  
},
            wipeUp: function() {
  
},
            wipeDown: function() {
  
},
            preventDefaultEvents: true
      
};
      
     if (settings) $.extend(config, settings);
  
     this.each(function() {
 
         var startX;
         var startY;
         var isMoving = false;
 
         function cancelTouch() {
 
             this.removeEventListener('touchmove', onTouchMove);
             startX = null;
             isMoving = false;
          
}  
          
         function onTouchMove(e) {
 
             if(config.preventDefaultEvents) {
 
                 e.preventDefault();
              
}
             if(isMoving) {
 
                 var x = e.touches[0].pageX;
                 var y = e.touches[0].pageY;
                 var dx = startX - x;
                 var dy = startY - y;
                 if(Math.abs(dx) >= config.min_move_x) {
 
                    cancelTouch();
                    if(dx > 0) {
 
                        config.wipeLeft();
                     
}
                    else {
 
                        config.wipeRight();
                     
}
                  
}
                 else if(Math.abs(dy) >= config.min_move_y) {
 
                        cancelTouch();
                        if(dy > 0) {
 
                            config.wipeDown();
                         
}
                        else {
 
                            config.wipeUp();
                         
}
                      
}
              
}
          
}
          
         function onTouchStart(e)
         {
 
             if (e.touches.length == 1) {
 
                 startX = e.touches[0].pageX;
                 startY = e.touches[0].pageY;
                 isMoving = true;
                 this.addEventListener('touchmove', onTouchMove, false);
              
}
          
}       
         if ('ontouchstart' in document.documentElement) {
 
             this.addEventListener('touchstart', onTouchStart, false);
          
}
      
});
  
     return this;
    
};
  
  
})(jQuery);
;(function () {
	'use strict';

	/**
	 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
	 *
	 * @codingstandard ftlabs-jsv2
	 * @copyright The Financial Times Limited [All Rights Reserved]
	 * @license MIT License (see LICENSE.txt)
	 */

	/*jslint browser:true, node:true*/
	/*global define, Event, Node*/


	/**
	 * Instantiate fast-clicking listeners on the specified layer.
	 *
	 * @constructor
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	function FastClick(layer, options) {
		var oldOnClick;

		options = options || {};

		/**
		 * Whether a click is currently being tracked.
		 *
		 * @type boolean
		 */
		this.trackingClick = false;


		/**
		 * Timestamp for when click tracking started.
		 *
		 * @type number
		 */
		this.trackingClickStart = 0;


		/**
		 * The element being tracked for a click.
		 *
		 * @type EventTarget
		 */
		this.targetElement = null;


		/**
		 * X-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartX = 0;


		/**
		 * Y-coordinate of touch start event.
		 *
		 * @type number
		 */
		this.touchStartY = 0;


		/**
		 * ID of the last touch, retrieved from Touch.identifier.
		 *
		 * @type number
		 */
		this.lastTouchIdentifier = 0;


		/**
		 * Touchmove boundary, beyond which a click will be cancelled.
		 *
		 * @type number
		 */
		this.touchBoundary = options.touchBoundary || 10;


		/**
		 * The FastClick layer.
		 *
		 * @type Element
		 */
		this.layer = layer;

		/**
		 * The minimum time between tap(touchstart and touchend) events
		 *
		 * @type number
		 */
		this.tapDelay = options.tapDelay || 200;

		/**
		 * The maximum time for a tap
		 *
		 * @type number
		 */
		this.tapTimeout = options.tapTimeout || 700;

		if (FastClick.notNeeded(layer)) {
			return;
		}

		// Some old versions of Android don't have Function.prototype.bind
		function bind(method, context) {
			return function() { return method.apply(context, arguments); };
		}


		var methods = ['onMouse', 'onClick', 'onTouchStart', 'onTouchMove', 'onTouchEnd', 'onTouchCancel'];
		var context = this;
		for (var i = 0, l = methods.length; i < l; i++) {
			context[methods[i]] = bind(context[methods[i]], context);
		}

		// Set up event handlers as required
		if (deviceIsAndroid) {
			layer.addEventListener('mouseover', this.onMouse, true);
			layer.addEventListener('mousedown', this.onMouse, true);
			layer.addEventListener('mouseup', this.onMouse, true);
		}

		layer.addEventListener('click', this.onClick, true);
		layer.addEventListener('touchstart', this.onTouchStart, false);
		layer.addEventListener('touchmove', this.onTouchMove, false);
		layer.addEventListener('touchend', this.onTouchEnd, false);
		layer.addEventListener('touchcancel', this.onTouchCancel, false);

		// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
		// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
		// layer when they are cancelled.
		if (!Event.prototype.stopImmediatePropagation) {
			layer.removeEventListener = function(type, callback, capture) {
				var rmv = Node.prototype.removeEventListener;
				if (type === 'click') {
					rmv.call(layer, type, callback.hijacked || callback, capture);
				} else {
					rmv.call(layer, type, callback, capture);
				}
			};

			layer.addEventListener = function(type, callback, capture) {
				var adv = Node.prototype.addEventListener;
				if (type === 'click') {
					adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
						if (!event.propagationStopped) {
							callback(event);
						}
					}), capture);
				} else {
					adv.call(layer, type, callback, capture);
				}
			};
		}

		// If a handler is already declared in the element's onclick attribute, it will be fired before
		// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
		// adding it as listener.
		if (typeof layer.onclick === 'function') {

			// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
			// - the old one won't work if passed to addEventListener directly.
			oldOnClick = layer.onclick;
			layer.addEventListener('click', function(event) {
				oldOnClick(event);
			}, false);
			layer.onclick = null;
		}
	}

	/**
	* Windows Phone 8.1 fakes user agent string to look like Android and iPhone.
	*
	* @type boolean
	*/
	var deviceIsWindowsPhone = navigator.userAgent.indexOf("Windows Phone") >= 0;

	/**
	 * Android requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0 && !deviceIsWindowsPhone;


	/**
	 * iOS requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent) && !deviceIsWindowsPhone;


	/**
	 * iOS 4 requires an exception for select elements.
	 *
	 * @type boolean
	 */
	var deviceIsIOS4 = deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


	/**
	 * iOS 6.0-7.* requires the target element to be manually derived
	 *
	 * @type boolean
	 */
	var deviceIsIOSWithBadTarget = deviceIsIOS && (/OS [6-7]_\d/).test(navigator.userAgent);

	/**
	 * BlackBerry requires exceptions.
	 *
	 * @type boolean
	 */
	var deviceIsBlackBerry10 = navigator.userAgent.indexOf('BB10') > 0;

	/**
	 * Determine whether a given element requires a native click.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element needs a native click
	 */
	FastClick.prototype.needsClick = function(target) {
		switch (target.nodeName.toLowerCase()) {

		// Don't send a synthetic click to disabled inputs (issue #62)
		case 'button':
		case 'select':
		case 'textarea':
			if (target.disabled) {
				return true;
			}

			break;
		case 'input':

			// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
			if ((deviceIsIOS && target.type === 'file') || target.disabled) {
				return true;
			}

			break;
		case 'label':
		case 'iframe': // iOS8 homescreen apps can prevent events bubbling into frames
		case 'video':
			return true;
		}

		return (/\bneedsclick\b/).test(target.className);
	};


	/**
	 * Determine whether a given element requires a call to focus to simulate click into element.
	 *
	 * @param {EventTarget|Element} target Target DOM element
	 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
	 */
	FastClick.prototype.needsFocus = function(target) {
		switch (target.nodeName.toLowerCase()) {
		case 'textarea':
			return true;
		case 'select':
			return !deviceIsAndroid;
		case 'input':
			switch (target.type) {
			case 'button':
			case 'checkbox':
			case 'file':
			case 'image':
			case 'radio':
			case 'submit':
				return false;
			}

			// No point in attempting to focus disabled inputs
			return !target.disabled && !target.readOnly;
		default:
			return (/\bneedsfocus\b/).test(target.className);
		}
	};


	/**
	 * Send a click event to the specified element.
	 *
	 * @param {EventTarget|Element} targetElement
	 * @param {Event} event
	 */
	FastClick.prototype.sendClick = function(targetElement, event) {
		var clickEvent, touch;

		// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
		if (document.activeElement && document.activeElement !== targetElement) {
			document.activeElement.blur();
		}

		touch = event.changedTouches[0];

		// Synthesise a click event, with an extra attribute so it can be tracked
		clickEvent = document.createEvent('MouseEvents');
		clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
		clickEvent.forwardedTouchEvent = true;
		targetElement.dispatchEvent(clickEvent);
	};

	FastClick.prototype.determineEventType = function(targetElement) {

		//Issue #159: Android Chrome Select Box does not open with a synthetic click event
		if (deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
			return 'mousedown';
		}

		return 'click';
	};


	/**
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.focus = function(targetElement) {
		var length;

		// Issue #160: on iOS 7, some input elements (e.g. date datetime month) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
		if (deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time' && targetElement.type !== 'month') {
			length = targetElement.value.length;
			targetElement.setSelectionRange(length, length);
		} else {
			targetElement.focus();
		}
	};


	/**
	 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
	 *
	 * @param {EventTarget|Element} targetElement
	 */
	FastClick.prototype.updateScrollParent = function(targetElement) {
		var scrollParent, parentElement;

		scrollParent = targetElement.fastClickScrollParent;

		// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
		// target element was moved to another parent.
		if (!scrollParent || !scrollParent.contains(targetElement)) {
			parentElement = targetElement;
			do {
				if (parentElement.scrollHeight > parentElement.offsetHeight) {
					scrollParent = parentElement;
					targetElement.fastClickScrollParent = parentElement;
					break;
				}

				parentElement = parentElement.parentElement;
			} while (parentElement);
		}

		// Always update the scroll top tracker if possible.
		if (scrollParent) {
			scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
		}
	};


	/**
	 * @param {EventTarget} targetElement
	 * @returns {Element|EventTarget}
	 */
	FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {

		// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
		if (eventTarget.nodeType === Node.TEXT_NODE) {
			return eventTarget.parentNode;
		}

		return eventTarget;
	};


	/**
	 * On touch start, record the position and scroll offset.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchStart = function(event) {
		var targetElement, touch, selection;

		// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
		if (event.targetTouches.length > 1) {
			return true;
		}

		targetElement = this.getTargetElementFromEventTarget(event.target);
		touch = event.targetTouches[0];

		if (deviceIsIOS) {

			// Only trusted events will deselect text on iOS (issue #49)
			selection = window.getSelection();
			if (selection.rangeCount && !selection.isCollapsed) {
				return true;
			}

			if (!deviceIsIOS4) {

				// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
				// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
				// with the same identifier as the touch event that previously triggered the click that triggered the alert.
				// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
				// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
				// Issue 120: touch.identifier is 0 when Chrome dev tools 'Emulate touch events' is set with an iOS device UA string,
				// which causes all touch events to be ignored. As this block only applies to iOS, and iOS identifiers are always long,
				// random integers, it's safe to to continue if the identifier is 0 here.
				if (touch.identifier && touch.identifier === this.lastTouchIdentifier) {
					event.preventDefault();
					return false;
				}

				this.lastTouchIdentifier = touch.identifier;

				// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
				// 1) the user does a fling scroll on the scrollable layer
				// 2) the user stops the fling scroll with another tap
				// then the event.target of the last 'touchend' event will be the element that was under the user's finger
				// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
				// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
				this.updateScrollParent(targetElement);
			}
		}

		this.trackingClick = true;
		this.trackingClickStart = event.timeStamp;
		this.targetElement = targetElement;

		this.touchStartX = touch.pageX;
		this.touchStartY = touch.pageY;

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			event.preventDefault();
		}

		return true;
	};


	/**
	 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.touchHasMoved = function(event) {
		var touch = event.changedTouches[0], boundary = this.touchBoundary;

		if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
			return true;
		}

		return false;
	};


	/**
	 * Update the last position.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchMove = function(event) {
		if (!this.trackingClick) {
			return true;
		}

		// If the touch has moved, cancel the click tracking
		if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
			this.trackingClick = false;
			this.targetElement = null;
		}

		return true;
	};


	/**
	 * Attempt to find the labelled control for the given label element.
	 *
	 * @param {EventTarget|HTMLLabelElement} labelElement
	 * @returns {Element|null}
	 */
	FastClick.prototype.findControl = function(labelElement) {

		// Fast path for newer browsers supporting the HTML5 control attribute
		if (labelElement.control !== undefined) {
			return labelElement.control;
		}

		// All browsers under test that support touch events also support the HTML5 htmlFor attribute
		if (labelElement.htmlFor) {
			return document.getElementById(labelElement.htmlFor);
		}

		// If no for attribute exists, attempt to retrieve the first labellable descendant element
		// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
		return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
	};


	/**
	 * On touch end, determine whether to send a click event at once.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onTouchEnd = function(event) {
		var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

		if (!this.trackingClick) {
			return true;
		}

		// Prevent phantom clicks on fast double-tap (issue #36)
		if ((event.timeStamp - this.lastClickTime) < this.tapDelay) {
			this.cancelNextClick = true;
			return true;
		}

		if ((event.timeStamp - this.trackingClickStart) > this.tapTimeout) {
			return true;
		}

		// Reset to prevent wrong click cancel on input (issue #156).
		this.cancelNextClick = false;

		this.lastClickTime = event.timeStamp;

		trackingClickStart = this.trackingClickStart;
		this.trackingClick = false;
		this.trackingClickStart = 0;

		// On some iOS devices, the targetElement supplied with the event is invalid if the layer
		// is performing a transition or scroll, and has to be re-detected manually. Note that
		// for this to function correctly, it must be called *after* the event target is checked!
		// See issue #57; also filed as rdar://13048589 .
		if (deviceIsIOSWithBadTarget) {
			touch = event.changedTouches[0];

			// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
			targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
			targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
		}

		targetTagName = targetElement.tagName.toLowerCase();
		if (targetTagName === 'label') {
			forElement = this.findControl(targetElement);
			if (forElement) {
				this.focus(targetElement);
				if (deviceIsAndroid) {
					return false;
				}

				targetElement = forElement;
			}
		} else if (this.needsFocus(targetElement)) {

			// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
			// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
			if ((event.timeStamp - trackingClickStart) > 100 || (deviceIsIOS && window.top !== window && targetTagName === 'input')) {
				this.targetElement = null;
				return false;
			}

			this.focus(targetElement);
			this.sendClick(targetElement, event);

			// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
			// Also this breaks opening selects when VoiceOver is active on iOS6, iOS7 (and possibly others)
			if (!deviceIsIOS || targetTagName !== 'select') {
				this.targetElement = null;
				event.preventDefault();
			}

			return false;
		}

		if (deviceIsIOS && !deviceIsIOS4) {

			// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
			// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
			scrollParent = targetElement.fastClickScrollParent;
			if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
				return true;
			}
		}

		// Prevent the actual click from going though - unless the target node is marked as requiring
		// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
		if (!this.needsClick(targetElement)) {
			event.preventDefault();
			this.sendClick(targetElement, event);
		}

		return false;
	};


	/**
	 * On touch cancel, stop tracking the click.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.onTouchCancel = function() {
		this.trackingClick = false;
		this.targetElement = null;
	};


	/**
	 * Determine mouse events which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onMouse = function(event) {

		// If a target element was never set (because a touch event was never fired) allow the event
		if (!this.targetElement) {
			return true;
		}

		if (event.forwardedTouchEvent) {
			return true;
		}

		// Programmatically generated events targeting a specific element should be permitted
		if (!event.cancelable) {
			return true;
		}

		// Derive and check the target element to see whether the mouse event needs to be permitted;
		// unless explicitly enabled, prevent non-touch click events from triggering actions,
		// to prevent ghost/doubleclicks.
		if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

			// Prevent any user-added listeners declared on FastClick element from being fired.
			if (event.stopImmediatePropagation) {
				event.stopImmediatePropagation();
			} else {

				// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
				event.propagationStopped = true;
			}

			// Cancel the event
			event.stopPropagation();
			event.preventDefault();

			return false;
		}

		// If the mouse event is permitted, return true for the action to go through.
		return true;
	};


	/**
	 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
	 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
	 * an actual click which should be permitted.
	 *
	 * @param {Event} event
	 * @returns {boolean}
	 */
	FastClick.prototype.onClick = function(event) {
		var permitted;

		// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
		if (this.trackingClick) {
			this.targetElement = null;
			this.trackingClick = false;
			return true;
		}

		// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
		if (event.target.type === 'submit' && event.detail === 0) {
			return true;
		}

		permitted = this.onMouse(event);

		// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
		if (!permitted) {
			this.targetElement = null;
		}

		// If clicks are permitted, return true for the action to go through.
		return permitted;
	};


	/**
	 * Remove all FastClick's event listeners.
	 *
	 * @returns {void}
	 */
	FastClick.prototype.destroy = function() {
		var layer = this.layer;

		if (deviceIsAndroid) {
			layer.removeEventListener('mouseover', this.onMouse, true);
			layer.removeEventListener('mousedown', this.onMouse, true);
			layer.removeEventListener('mouseup', this.onMouse, true);
		}

		layer.removeEventListener('click', this.onClick, true);
		layer.removeEventListener('touchstart', this.onTouchStart, false);
		layer.removeEventListener('touchmove', this.onTouchMove, false);
		layer.removeEventListener('touchend', this.onTouchEnd, false);
		layer.removeEventListener('touchcancel', this.onTouchCancel, false);
	};


	/**
	 * Check whether FastClick is needed.
	 *
	 * @param {Element} layer The layer to listen on
	 */
	FastClick.notNeeded = function(layer) {
		var metaViewport;
		var chromeVersion;
		var blackberryVersion;
		var firefoxVersion;

		// Devices that don't support touch don't need FastClick
		if (typeof window.ontouchstart === 'undefined') {
			return true;
		}

		// Chrome version - zero for other browsers
		chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (chromeVersion) {

			if (deviceIsAndroid) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// Chrome 32 and above with width=device-width or less don't need FastClick
					if (chromeVersion > 31 && document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}

			// Chrome desktop doesn't need FastClick (issue #15)
			} else {
				return true;
			}
		}

		if (deviceIsBlackBerry10) {
			blackberryVersion = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);

			// BlackBerry 10.3+ does not require Fastclick library.
			// https://github.com/ftlabs/fastclick/issues/251
			if (blackberryVersion[1] >= 10 && blackberryVersion[2] >= 3) {
				metaViewport = document.querySelector('meta[name=viewport]');

				if (metaViewport) {
					// user-scalable=no eliminates click delay.
					if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
						return true;
					}
					// width=device-width (or less than device-width) eliminates click delay.
					if (document.documentElement.scrollWidth <= window.outerWidth) {
						return true;
					}
				}
			}
		}

		// IE10 with -ms-touch-action: none or manipulation, which disables double-tap-to-zoom (issue #97)
		if (layer.style.msTouchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		// Firefox version - zero for other browsers
		firefoxVersion = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [,0])[1];

		if (firefoxVersion >= 27) {
			// Firefox 27+ does not have tap delay if the content is not zoomable - https://bugzilla.mozilla.org/show_bug.cgi?id=922896

			metaViewport = document.querySelector('meta[name=viewport]');
			if (metaViewport && (metaViewport.content.indexOf('user-scalable=no') !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) {
				return true;
			}
		}

		// IE11: prefixed -ms-touch-action is no longer supported and it's recomended to use non-prefixed version
		// http://msdn.microsoft.com/en-us/library/windows/apps/Hh767313.aspx
		if (layer.style.touchAction === 'none' || layer.style.touchAction === 'manipulation') {
			return true;
		}

		return false;
	};


	/**
	 * Factory method for creating a FastClick object
	 *
	 * @param {Element} layer The layer to listen on
	 * @param {Object} [options={}] The options to override the defaults
	 */
	FastClick.attach = function(layer, options) {
		return new FastClick(layer, options);
	};


	if (typeof define === 'function' && typeof define.amd === 'object' && define.amd) {

		// AMD. Register as an anonymous module.
		define(function() {
			return FastClick;
		});
	} else if (typeof module !== 'undefined' && module.exports) {
		module.exports = FastClick.attach;
		module.exports.FastClick = FastClick;
	} else {
		window.FastClick = FastClick;
	}
}());

/*!
 * TouchSlide v1.1
 * javascript触屏滑动特效插件，移动端滑动特效，触屏焦点图，触屏Tab切换，触屏多图切换等
 * 详尽信息请看官网：http://www.SuperSlide2.com/TouchSlide/
 *
 * Copyright 2013 大话主席
 *
 * 请尊重原创，保留头部版权
 * 在保留版权的前提下可应用于个人或商业用途

 * 1.1 宽度自适应（修复安卓横屏时滑动范围不变的bug）
 */

var TouchSlide=function(a){a=a||{};var b={slideCell:a.slideCell||"#touchSlide",titCell:a.titCell||".hd li",mainCell:a.mainCell||".bd",effect:a.effect||"left",autoPlay:a.autoPlay||!1,delayTime:a.delayTime||200,interTime:a.interTime||2500,defaultIndex:a.defaultIndex||0,titOnClassName:a.titOnClassName||"on",autoPage:a.autoPage||!1,prevCell:a.prevCell||".prev",nextCell:a.nextCell||".next",pageStateCell:a.pageStateCell||".pageState",pnLoop:"undefined "==a.pnLoop?!0:a.pnLoop,startFun:a.startFun||null,endFun:a.endFun||null,switchLoad:a.switchLoad||null},c=document.getElementById(b.slideCell.replace("#",""));if(!c)return!1;var d=function(a,b){a=a.split(" ");var c=[];b=b||document;var d=[b];for(var e in a)0!=a[e].length&&c.push(a[e]);for(var e in c){if(0==d.length)return!1;var f=[];for(var g in d)if("#"==c[e][0])f.push(document.getElementById(c[e].replace("#","")));else if("."==c[e][0])for(var h=d[g].getElementsByTagName("*"),i=0;i<h.length;i++){var j=h[i].className;j&&-1!=j.search(new RegExp("\\b"+c[e].replace(".","")+"\\b"))&&f.push(h[i])}else for(var h=d[g].getElementsByTagName(c[e]),i=0;i<h.length;i++)f.push(h[i]);d=f}return 0==d.length||d[0]==b?!1:d},e=function(a,b){var c=document.createElement("div");c.innerHTML=b,c=c.children[0];var d=a.cloneNode(!0);return c.appendChild(d),a.parentNode.replaceChild(c,a),m=d,c},g=function(a,b){!a||!b||a.className&&-1!=a.className.search(new RegExp("\\b"+b+"\\b"))||(a.className+=(a.className?" ":"")+b)},h=function(a,b){!a||!b||a.className&&-1==a.className.search(new RegExp("\\b"+b+"\\b"))||(a.className=a.className.replace(new RegExp("\\s*\\b"+b+"\\b","g"),""))},i=b.effect,j=d(b.prevCell,c)[0],k=d(b.nextCell,c)[0],l=d(b.pageStateCell)[0],m=d(b.mainCell,c)[0];if(!m)return!1;var N,O,n=m.children.length,o=d(b.titCell,c),p=o?o.length:n,q=b.switchLoad,r=parseInt(b.defaultIndex),s=parseInt(b.delayTime),t=parseInt(b.interTime),u="false"==b.autoPlay||0==b.autoPlay?!1:!0,v="false"==b.autoPage||0==b.autoPage?!1:!0,w="false"==b.pnLoop||0==b.pnLoop?!1:!0,x=r,y=null,z=null,A=null,B=0,C=0,D=0,E=0,G=/hp-tablet/gi.test(navigator.appVersion),H="ontouchstart"in window&&!G,I=H?"touchstart":"mousedown",J=H?"touchmove":"",K=H?"touchend":"mouseup",M=m.parentNode.clientWidth,P=n;if(0==p&&(p=n),v){p=n,o=o[0],o.innerHTML="";var Q="";if(1==b.autoPage||"true"==b.autoPage)for(var R=0;p>R;R++)Q+="<li>"+(R+1)+"</li>";else for(var R=0;p>R;R++)Q+=b.autoPage.replace("$",R+1);o.innerHTML=Q,o=o.children}"leftLoop"==i&&(P+=2,m.appendChild(m.children[0].cloneNode(!0)),m.insertBefore(m.children[n-1].cloneNode(!0),m.children[0])),N=e(m,'<div class="tempWrap" style="overflow:hidden; position:relative;"></div>'),m.style.cssText="width:"+P*M+"px;"+"position:relative;overflow:hidden;padding:0;margin:0;";for(var R=0;P>R;R++)m.children[R].style.cssText="display:table-cell;vertical-align:top;width:"+M+"px";var S=function(){"function"==typeof b.startFun&&b.startFun(r,p)},T=function(){"function"==typeof b.endFun&&b.endFun(r,p)},U=function(a){var b=("leftLoop"==i?r+1:r)+a,c=function(a){for(var b=m.children[a].getElementsByTagName("img"),c=0;c<b.length;c++)b[c].getAttribute(q)&&(b[c].setAttribute("src",b[c].getAttribute(q)),b[c].removeAttribute(q))};if(c(b),"leftLoop"==i)switch(b){case 0:c(n);break;case 1:c(n+1);break;case n:c(0);break;case n+1:c(1)}},V=function(){M=N.clientWidth,m.style.width=P*M+"px";for(var a=0;P>a;a++)m.children[a].style.width=M+"px";var b="leftLoop"==i?r+1:r;W(-b*M,0)};window.addEventListener("resize",V,!1);var W=function(a,b,c){c=c?c.style:m.style,c.webkitTransitionDuration=c.MozTransitionDuration=c.msTransitionDuration=c.OTransitionDuration=c.transitionDuration=b+"ms",c.webkitTransform="translate("+a+"px,0)"+"translateZ(0)",c.msTransform=c.MozTransform=c.OTransform="translateX("+a+"px)"},X=function(a){switch(i){case"left":r>=p?r=a?r-1:0:0>r&&(r=a?0:p-1),null!=q&&U(0),W(-r*M,s),x=r;break;case"leftLoop":null!=q&&U(0),W(-(r+1)*M,s),-1==r?(z=setTimeout(function(){W(-p*M,0)},s),r=p-1):r==p&&(z=setTimeout(function(){W(-M,0)},s),r=0),x=r}S(),A=setTimeout(function(){T()},s);for(var c=0;p>c;c++)h(o[c],b.titOnClassName),c==r&&g(o[c],b.titOnClassName);0==w&&(h(k,"nextStop"),h(j,"prevStop"),0==r?g(j,"prevStop"):r==p-1&&g(k,"nextStop")),l&&(l.innerHTML="<span>"+(r+1)+"</span>/"+p)};if(X(),u&&(y=setInterval(function(){r++,X()},t)),o)for(var R=0;p>R;R++)!function(){var a=R;o[a].addEventListener("click",function(){clearTimeout(z),clearTimeout(A),r=a,X()})}();k&&k.addEventListener("click",function(){(1==w||r!=p-1)&&(clearTimeout(z),clearTimeout(A),r++,X())}),j&&j.addEventListener("click",function(){(1==w||0!=r)&&(clearTimeout(z),clearTimeout(A),r--,X())});var Y=function(a){clearTimeout(z),clearTimeout(A),O=void 0,D=0;var b=H?a.touches[0]:a;B=b.pageX,C=b.pageY,m.addEventListener(J,Z,!1),m.addEventListener(K,$,!1)},Z=function(a){if(!H||!(a.touches.length>1||a.scale&&1!==a.scale)){var b=H?a.touches[0]:a;if(D=b.pageX-B,E=b.pageY-C,"undefined"==typeof O&&(O=!!(O||Math.abs(D)<Math.abs(E))),!O){switch(a.preventDefault(),u&&clearInterval(y),i){case"left":(0==r&&D>0||r>=p-1&&0>D)&&(D=.4*D),W(-r*M+D,0);break;case"leftLoop":W(-(r+1)*M+D,0)}null!=q&&Math.abs(D)>M/3&&U(D>-0?-1:1)}}},$=function(a){0!=D&&(a.preventDefault(),O||(Math.abs(D)>M/10&&(D>0?r--:r++),X(!0),u&&(y=setInterval(function(){r++,X()},t))),m.removeEventListener(J,Z,!1),m.removeEventListener(K,$,!1))};m.addEventListener(I,Y,!1)};
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(6($){$.1c.3w=6(7){3 O={G:0,1U:"y",U:16};7=$.1v({},O,7);3 o=$(b);3 s=$(o).4("s");$(o).1t();9(7.U){$(o).3s();7.G=$(o).3k().4("G");$(o).3k().13()}3 T=$("<1h G=\'"+7.G+"\'></1h>");$(T).4("1x",$(o).4("1x"));$(T).4("1k",$(o).4("1k"));$(T).j({"B":"2j-1j"});3 1O=$("<22></22>");$(T).1E(1O);$(1O).4("1x","3q-35-3m");3 26=$(o).8("2Q:3m");$(1O).q("<p>"+26.q()+"</p><i></i>");$(1O).4("1b",26.4("1b"));3 19=$("<17></17>");$(T).1E(19);$(o).8("2Q").2J(6(3N,29){3 1I=$("<a 3M=\'3L:3P(0);\'></a>");$(1I).j({"B":"1j"});$(1I).4("1b",$(29).4("1b"));$(1I).q($(29).q());9(26.4("1b")==$(29).4("1b"))1I.v("2g");$(19).1E(1I)});$(o).3i(T);$(19).j({"K":"1g","z-1s":3E});$(19).v("3q-35-3H");3 l=$(T).K().l+$(T).s();3 k=$(T).K().k;$(19).j("k",k);$(19).j("l",l);9(s&&$(19).s()>38(s)){$(19).j("s",38(s))}$(19).1t();9(7.U)$(o).1t();9(7.1U=="y"){$(T).t("y",6(){3 l=$(b).K().l+$(b).s();3 k=$(b).K().k;$(b).8("17").j("k",k);$(b).8("17").j("l",l);$(b).8("17").39("2C");$(b).v("2I")})}18{$(T).1A(6(){$(b).3G(X,6(){3 l=$(b).K().l+$(b).s();3 k=$(b).K().k;$(b).8("17").j("k",k);$(b).8("17").j("l",l);$(b).8("17").39("2C");$(b).v("2I")})},6(){$(b).34();$(b).8("17").3F("2C");$(b).c("2I")})}$(T).8("17 a").t("y",6(){3 1h=$(b).Q().Q();3 p=$(b);$(1h).8("22").q("<p>"+$(p).q()+"</p><i></i>");$(1h).8("22").4("1b",$(p).4("1b"));$(1h).2k().N($(p).4("1b"));$(1h).2k().1o("3I");$(1h).8("17 a").c("2g");$(b).v("2g")})},$.1c.3D=6(){3 L=$(b);9(L.j("B")=="2H")1u;$(L).1t();3 o=$("<d><d><p></p></d></d>");$(L).3i(o);$(o).4("1x",$(L).4("1x"));$(o).v($(L).4("r"));$(o).4("r",$(L).4("r"));$(o).8("p").q($(L).q());$(o).t("y",6(){9(L.4("1i")=="2Z"){3 Q=L.Q();3z{3y(Q.3A(0).3j.3B()!="3C"){Q=Q.Q()}Q.2Z()}3J(e){$(L).y()}}18{9(L.Q()[0].3j=="A"){}18 $(L).y()}});$(o).t("2R",6(){$(o).c($(o).4("r")+"Y");$(o).c($(o).4("r")+"1Q");$(o).c($(o).4("r"));$(o).v($(o).4("r")+"Y")});$(o).t("2S",6(){$(o).c($(o).4("r")+"Y");$(o).c($(o).4("r")+"1Q");$(o).c($(o).4("r"));$(o).v($(o).4("r"))});$(o).t("3K",6(){$(o).c($(o).4("r")+"Y");$(o).c($(o).4("r")+"1Q");$(o).c($(o).4("r"));$(o).v($(o).4("r")+"1Q")});$(o).t("3R",6(){$(o).c($(o).4("r")+"Y");$(o).c($(o).4("r")+"1Q");$(o).c($(o).4("r"));$(o).v($(o).4("r")+"Y")})},$.1c.3S=6(){3 m=$(b);$(m).t("2r",6(){$(m).c("1A");$(m).c("1V");$(m).v("1A")});$(m).t("3a",6(){$(m).c("1A");$(m).c("1V");$(m).v("1V")});9($(m).4("I")==""||!$(m).4("I"))1u;9(\'1Z\'3T 3U.3x(\'J\')){$(m).4("1Z",$(m).4("I"))}18{3 I=$(m).2k();9($(I).4("r")!="I"&&$(m).4("I")){I=$("<p E=\'K:1g; 3Q:#3O;\' r=\'I\'>"+$(m).4("I")+"</p>");$(I).j({"2v-2V":$(m).j("2v-2V"),"R-k":$(m).j("R-k"),"R-2X":$(m).j("R-2X"),"R-l":$(m).j("R-l"),"R-1L":$(m).j("R-1L")});$(I).j("k",0);$(I).j("l",0);3 3V=$(m).2Y("<i E=\'2v-E:1V; B:1j;\'></i>");$(m).Q().j("K","3v");$(m).3u(I)}9($.3c($(m).N())!=""){$(I).j("B","2H")}$(I).y(6(){$(m).2r()});$(m).2r(6(){$(I).j("B","2H")});$(m).3a(6(){9($.3c($(m).N())=="")$(I).3s()})}},$.1c.42=6(7){3 O={U:16};7=$.1v({},O,7);3 g=$(b);3 o=$(g).8("J[1i=\'2p\']");$(o).1t();3 h=$(o).4("h");3 f=$(g).4("r");$(g).v(f);$(g).4("1k",$(o).4("1k"));$(g).j({"B":"2j-1j"});$(g).4("h",h?1r:16);9(h){$(g).c(f);$(g).c(f+"12");$(g).v(f+"12")}18{$(g).c(f);$(g).c(f+"12");$(g).v(f)}9(7.U)1u;$(o).t("y",6(){1u 16});$(g).1A(6(){3 W=$(b).8("J[1i=\'2p\']");3 h=$(W).4("h");3 f=$(g).4("r");9(!h)$(b).v(f+"Y")},6(){$(b).c(f+"Y")});$(g).t("y",6(){3 u=$(b);3 W=$(u).8("J[1i=\'2p\']");3 h=$(W).4("h");3 f=$(g).4("r");h=h?16:1r;$(W).4("h",h);$(u).4("h",h);$(u).c(f+"Y");9(h){$(W).1o("4s");$(u).c(f);$(u).c(f+"12");$(u).v(f+"12")}18{$(W).1o("4t");$(u).c(f);$(u).c(f+"12");$(u).v(f)}})},$.1c.2U=6(7){3 O={U:16};7=$.1v({},O,7);3 g=$(b);3 o=$(g).8("J[1i=\'1W\']");$(o).1t();3 h=$(o).4("h");3 f=$(g).4("r");$(g).v(f);$(g).4("1k",$(o).4("1k"));$(g).j({"B":"2j-1j"});$(g).4("h",h?1r:16);9(h){$(g).c(f);$(g).c(f+"12");$(g).v(f+"12")}18{$(g).c(f);$(g).c(f+"12");$(g).v(f)}9(7.U)1u;$(o).t("y",6(){1u 16});$(g).1A(6(){3 W=$(b).8("J[1i=\'1W\']");3 h=$(W).4("h");3 f=$(g).4("r");9(!h)$(b).v(f+"Y")},6(){$(b).c(f+"Y")});$(g).t("y",6(){3 u=$(b);3 W=$(u).8("J[1i=\'1W\']");3 h=$(W).4("h");3 f=$(g).4("r");3 2N=h;h=1r;$(W).4("h",h);$(u).4("h",h);$(u).c(f+"Y");$("J[1k=\'"+u.4("1k")+"\'][1i=\'1W\']").Q().2J(6(i,2K){$(2K).2U({U:1r})});9(!2N){$(W).1o("h");$(u).c(f);$(u).c(f+"12");$(u).v(f+"12")}})},$.1c.4u=6(7){3 O={U:16,1N:5};7=$.1v({},O,7);3 1w=$(b);$(1w).1t();3 2B=$(1w).4("2B");3 N=$(1w).N();9(4v(N))N=0;9(N<0)N=0;9(N>7.1N)N=7.1N;9(!7.U)$(1w).2Y("<p><p></p></p>");3 F=$(1w).Q().Q();F.4("1x",$(1w).4("1x"));$(F).8("p").j("C",(4r(N)/7.1N*X)+"%");9(!7.U&&!2B){3 2P=$(F).C();3 1X=2P/7.1N;$(F).t("4q 2R",6(1U){3 2s=1U.2s;3 k=$(F).30().k;3 2O=2s-k;3 1F=1m.4x(2O/1X);3 1Y=(1F*1X)+"2T";$(F).8("J").4("1F",1F);$(F).8("p").j("C",1Y);$(F).8("J").1o("2W")});$(F).t("2S",6(){3 1M=$(F).8("p").8("J").N();3 1Y=(1M*1X)+"2T";$(F).8("p").j("C",1Y);$(F).8("J").4("1F",1M);$(F).8("J").1o("2W")});$(F).t("y",6(){3 1M=$(F).8("J").4("1F");$(F).8("p").8("J").N(1M);$(F).8("J").1o("4n")})}},$.1c.4o=6(7){3 O={2n:4p,3h:1r,20:x,23:x,21:x,1T:x};7=$.1v({},O,7);3 L=$(b);3 V=3d 4w.4z({4D:L[0],2n:7.2n,4E:4C,4y:4F,4A:7.3h,4B:{4m:4k,44:[{45:"46 1K",47:43}]}});V.4l();V.t(\'20\',6(V,1K){9(7.20!=x){9(7.20.14(x,1K)!=16){V.3r()}}18{V.3r()}});V.t(\'23\',6(V,3Y,3o){9(7.23!=x){3 2i=$.3X(3o.3W);7.23.14(x,2i);9(2i.3Z!=0){V.40()}}});V.t(\'21\',6(V,1K){9(7.21!=x)7.21.14(x,1K)});V.t(\'1T\',6(V,36){9(7.1T!=x)7.1T.14(x,36)})},$.1c.41=6(7){3 O={1Z:"",1l:"",U:16};7=$.1v({},O,7);3 33=b;33.2J(6(){3 u=$(b);3 2x=$(15).2a();3 37=$(15).s();3 2z=u.30().l;9(!u.4("3b")||7.U){$(u).4("1l",7.1Z);9(37+2x>=2z&&2x<=2z+u.s()){9(7.1l!="")u.4("1l",7.1l);18 u.4("1l",u.4("48-1l"));u.4("3b",1r)}}})},$.1c.49=6(7){3 1B=6(1f,2h){1f=4g(1f);3 25=1f.25;9(1f.25<2h){4h(3 i=0;i<2h-25;i++){1f="0"+1f}}1u 1f};3 O={31:0,32:0,3e:1,3p:"%D天 %H:%M:%S.%2M",2q:x};7=$.1v({},O,7);3 o=$(b);$(o).4i(7.3e,6(){3 2G=3d 4j().4f();2G+=7.32;1n=7.31-2G;9(1n<=0){$(o).34();9(7.2q!=x)7.2q.14();18 $(o).q("")}18{3 28=1B(1m.4e(1m.1R(1n%1S)/10),2);9(28==X)28="10";3 2L=1B(1m.1R(1n/1S%2e),2); 3 3l=1B(1m.1R((1n/1S/ 2e) % 2e),2);        	            3 3n = 1B(1m.1R((1n/1S/ 3f) % 24),2);        	            3 3t = 1B(1m.1R((1n/1S/ 3f)/24),2); 3 q=7.3p;q=q.1P("%D",3t);q=q.1P("%H",3n);q=q.1P("%M",3l);q=q.1P("%S",2L);q=q.1P("%2M",28);$(o).q(q)}})}})(4a);$.4b=6(1J,P){$("#1e").13();3 q="<d G=\'1e\'>"+"<d G=\'11\' E=\'2D-2t:2w; K:1g; z-1s:2y; 1G:#2u; C:2F;  1H-2E:1a; k:2o; l:0;\'>"+"<p E=\'R:1a;B:1j; 1H-1L:2A 2f #2c;\'>"+1J+"</p>"+"<d E=\'R:1a; B:-1q-w; B:-1p-w;B:w; C:X%;\'>"+"<d E=\'-1q-w-Z:1.0;-1p-w-Z:1.0;w-Z:1.0;\' G=\'1y\'>确定</d>"+"</d>"+"</d>"+"<d G=\'1d\' E=\'K:1g;l:0;k:0; C:X%;s:X%; 1G:#2d; z-1s:2m; 2l:0.2;\'></d>"+"</d>";$("1D").1E(q);3 n=$("#1e");3 1z=$("1D").s();$(n).8("#1d").j("s",1z);3 l=$(15).2a()+$(15).s()/2-$(n).8("#11").s()/2;3 k=$(15).C()/2-$(n).8("#11").C()/2;$(n).8("#11").j({"l":l,"k":k});$(n).8("#1y").t("y",6(){$(n).13();9(P&&1C(P)=="6")P.14(x)});$(n).8("#1d").t("y",6(){$(n).13();9(P&&1C(P)=="6")P.14(x)})};$.4c=6(1J,P){$("#1e").13();3 q="<d G=\'1e\'>"+"<d G=\'11\' E=\'2D-2t:2w; K:1g; z-1s:2y; 1G:#2u; C:2F;  1H-2E:1a; k:2o; l:0;\'>"+"<p E=\'R:1a;B:1j; 1H-1L:2A 2f #2c;\'>"+1J+"</p>"+"<d E=\'R:1a; B:-1q-w; B:-1p-w;B:w; C:X%;\'>"+"<d E=\'-1q-w-Z:1.0;-1p-w-Z:1.0;w-Z:1.0;\' G=\'1y\'>确定</d>"+"</d>"+"</d>"+"<d G=\'1d\' E=\'K:1g;l:0;k:0; C:X%;s:X%; 1G:#2d; z-1s:2m; 2l:0.2;\'></d>"+"</d>";$("1D").1E(q);3 n=$("#1e");3 1z=$("1D").s();$(n).8("#1d").j("s",1z);3 l=$(15).2a()+$(15).s()/2-$(n).8("#11").s()/2;3 k=$(15).C()/2-$(n).8("#11").C()/2;$(n).8("#11").j({"l":l,"k":k});$(n).8("#1y").t("y",6(){$(n).13();9(P&&1C(P)=="6")P.14(x)});$(n).8("#1d").t("y",6(){$(n).13();9(P&&1C(P)=="6")P.14(x)})};$.4d=6(1J,27,2b){$("#1e").13();3 q="<d G=\'1e\'>"+"<d G=\'11\' E=\'2D-2t:2w; K:1g; z-1s:2y; 1G:#2u; C:2F;  1H-2E:1a; k:2o; l:0;\'>"+"<p E=\'R:1a;B:1j; 1H-1L:2A 2f #2c;\'>"+1J+"</p>"+"<d E=\'R:1a; B:-1q-w; B:-1p-w;B:w; C:X%;\'>"+"<d E=\'-1q-w-Z:1.0;-1p-w-Z:1.0;w-Z:1.0;\' G=\'1y\'>确定</d>"+"<d E=\'-1q-w-Z:1.0;-1p-w-Z:1.0;w-Z:1.0;\' G=\'3g\'>取消</d>"+"</d>"+"</d>"+"<d G=\'1d\' E=\'K:1g;l:0;k:0; C:X%;s:X%; 1G:#2d; z-1s:2m; 2l:0.2;\'></d>"+"</d>";$("1D").1E(q);3 n=$("#1e");3 1z=$("1D").s();$(n).8("#1d").j("s",1z);3 l=$(15).2a()+$(15).s()/2-$(n).8("#11").s()/2;3 k=$(15).C()/2-$(n).8("#11").C()/2;$(n).8("#11").j({"l":l,"k":k});$(n).8("#1y").t("y",6(){$(n).13();9(27&&1C(27)=="6")27.14(x)});$(n).8("#3g").t("y",6(){$(n).13();9(2b&&1C(2b)=="6")2b.14(x)});$(n).8("#1d").t("y",6(){$(n).13()})};',62,290,'|||var|attr||function|options|find|if||this|removeClass|div||relClass|ImgCbo|checked||css|left|top|obj|dom||span|html|rel|height|bind|img|addClass|box|null|click|||display|width||style|outBar|id||holder|input|position|btn||val|op|func|parent|padding||DLselect|refresh|uploader|cbo|100|_hover|flex||pop_win|_checked|remove|call|window|false|dd|else|DDselect|10px|value|fn|bg_mask|msg_dom|number|absolute|dl|type|block|name|src|Math|timeless|trigger|webkit|moz|true|index|hide|return|extend|ipt|class|yes|fullheight|hover|fillZero|typeof|body|append|sector|background|border|SPANselect|str|files|bottom|current_sec|max|DTselect|replace|_active|floor|1000|Error|event|normal|radio|sec_width|cssWidth|placeholder|FilesAdded|UploadComplete|dt|FileUploaded||length|selectNode|funcok|mS|oo|scrollTop|funcclose|ccc|000|60|solid|current|digits|ajaxobj|inline|prev|opacity|1998|url|0px|checkbox|callback|focus|pageX|align|f8f8f8|font|center|scrolltop|1999|imgoffset|1px|disabled|fast|text|radius|250px|nowtime|none|dropdown|each|olb|nS|MS|ochecked|move_left|total_width|option|mouseover|mouseout|px|ui_radiobox|size|uichange|right|wrap|submit|offset|endtime|timespan|imgs|stopTime|select|errObject|windheight|parseInt|slideDown|blur|isload|trim|new|interval|3600|no|multi|after|tagName|next|nM|selected|nH|responseObject|format|ui|start|show|nD|before|relative|ui_select|createElement|while|try|get|toLowerCase|form|ui_button|50|fadeOut|oneTime|drop|change|catch|mousedown|javascript|href|ii|666|void|color|mouseup|ui_textbox|in|document|outer|response|parseJSON|file|error|stop|ui_lazy|ui_checkbox|ALLOW_IMAGE_EXT|mime_types|title|Image|extensions|data|count_down|jQuery|showErr|showSuccess|showConfirm|round|getTime|String|for|everyTime|Date|MAX_IMAGE_SIZE|init|max_file_size|onchange|ui_upload|UPLOAD_URL|mousemove|parseFloat|checkon|checkoff|ui_starbar|isNaN|plupload|ceil|silverlight_xap_url|Uploader|multi_selection|filters|UPLOAD_SWF|browse_button|flash_swf_url|UPLOAD_XAP'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('7 4C(){4z.4q(-1)}7 41(C){c K=\'{"3E":"\'+C[\'3E\']+\'","1X":{\';2O(c k 56 C[\'1X\']){K+=\'"\'+k+\'":"\'+C[\'1X\'][k]+\'",\'}K=K.23(0,K.t-1);K+=\'}}\';v K}7 58(){$("#2y-1o").1q(\'R\',7(){$(".2y-1o").5b(\'5p\')})}7 5s(C){$("a").f("B",C.B+"&o="+1s.2s())}7 5S(1u){b(1u==1){$.1p("支付成功",7(){E.1I()})}p b(1u==2){}p b(1u==6){$.1p("支付接口异常",7(){E.1I()})}p b(1u==4){$.1p("取消支付",7(){E.1I()})}p{E.1I()}}c 1L=1g;c 1S="";7 2h(2S){1S=2S;$(".X").3h();b($(".X").t>0){$(".X").5u("<T q=\'1c-1x\'></T>");$(".1c-1x").h("努力加载中...~")}$(y).2D(7(){c 5v=\'5w-5x-Y-3W\';c 2P=2g($(y).3N())+2g($(y).3c())+3O;b($(1r).3N()<=2P){b(1L)v;c 3i=$(".X").u("I.31").26();b(3i.t>0){c m=$(".X").u("I.31").26().f("B");$(".1c-1x").h("努力加载中...~");1L=L;$.1E({m:m,1O:"3l",H:7(h){$(".39").4A($(h).u(".39").h());$(".X").h($(h).u(".X").h());c 4E=\'4H-4G-4F\';1L=1g;$(".1c-1x").h("");b(14 1S=="7"){1S()}},24:7(){}})}p{$(".1c-1x").h("已经到底部了...~")}}})}$(1r).2U(7(){3f();3a();2V();3e();3m();33();2h();3A();b(14(2l)!=\'1z\'){1b.1X({4s:1g,2l:2l,3j:3j,2R:2R,2J:2J,4t:[\'2W\',\'30\',\'2H\',\'2I\',\'2M\',]});1b.2U(7(){1b.2W({1l:1F,1y:N,1D:N,O:O,H:7(){},1w:7(){}});1b.30({1l:1F,1y:N,1D:N,O:O,H:7(){},1w:7(){}});1b.2H({1l:1F,1y:N,1D:N,O:O,H:7(){},1w:7(){}});1b.2I({1l:1F,1y:N,1D:N,O:O,H:7(){},1w:7(){}});1b.2M({1l:1F,1y:N,1D:N,O:O,H:7(){},1w:7(){}})})}$(\'.2K-1Z\').51("R",7(){$(\'.2K-4T\').4S()});38(4o);$(".4L").R(7(){$(".4K").2X();$(".4N").2X()});$(".3q").u(".4R").1q("R",7(){$(".3q").3h();c 18=3u 3z();18.1N="53";$.1E({m:1Q,C:18,1O:"3l",H:7(){},24:7(o){}})});2e{1n.3Z()}2j(3C){}$(".3Y").R(7(){c 1U=$(3d).f("1U");$.35({\'1l\':\'虚拟商品信息\',\'42\':1U,\'3X\':{\'确定\':{\'q\':\'2i\',\'1U\':7(){}}}})})});7 43(2c,28){$.1E({m:1Q,C:{"1N":"45","2c":2c,"28":28},1O:"3D",2A:"K",H:7(C){}})}7 38(1j){1j?1j=1j:1j=50;$(y).2D(7(){c s=$(y).3c();b(s>1j){b($(".2b").46(":4h"))$(".2b").4j(2f)}p{$(".2b").2x(2f)}})};7 49(1W,Q){$(1W).R(7(){b($(Q).1f(\'1Z\')){$(Q).x(\'1Z\').13(\'2u\')}p{$(Q).x(\'2u\').13(\'1Z\')}})}7 47(1W,Q,1Y,2B){$(1W).R(7(){b($(Q).1f(1Y)){$(Q).x(1Y).13(2B)}p{$(Q).x(2B).13(1Y)}})}7 33(){$("a.35").1q("R",7(){c B=$(3d).f("B");$.4f("确认操作吗？",7(){E.B=B});v 1g})}7 3e(){$.22=7(){$("3t[4k][!4g]").3V({4M:6g})};$.22();$(y).1q("5V",7(e){$.22()});$(y).1q("2D",7(e){$.22()})}7 3m(){$("5X.J-60[j!=\'j\']").19(7(i,2E){$(2E).f("j","j");$(2E).5Y()})}7 54(G){b(G.5R){c 17="<I q=\'5L\'>"+G.3H+"</I>";b(G.27||G.2w||G.2v){17+="<I q=\'5K\'>";b(G.2v)17+=G.2v+"&2G;";b(G.2w)17+=G.2w+"&2G;";b(G.27)17+=G.27+"&2G;";17+="</I>"}$.3G(17)}}c 15=20;c 1t=0;7 2V(){$("12.J-12[j!=\'j\']").19(7(i,o){1t++;c V="2Z"+1s.2Y(1s.2s()*2L)+""+1t;c 1T={V:V};$(o).f("j","j");$(o).2N(1T)});$("12.J-1o[j!=\'j\']").19(7(i,o){1t++;c V="2Z"+1s.2Y(1s.2s()*2L)+""+1t;c 1T={V:V,6h:"6i"};$(o).f("j","j");$(o).2N(1T)});$(1r.6j).R(7(e){b($(e.1M).f("q")!=\'J-12-3k\'&&$(e.1M).2C().f("q")!=\'J-12-3k\'){$(".J-12-1o").2x("37");$(".J-12").x("3b");15=20}p{b(15!=20&&15.f("V")!=$(e.1M).2C().f("V")){$(15).u(".J-12-1o").2x("37");$(15).x("3b")}15=$(e.1M).2C()}})}7 3f(){$("29.J-29[j!=\'j\']").19(7(i,o){$(o).f("j","j");$(o).6a()})}7 3a(){$(".J-5I[j!=\'j\'],.J-5h[j!=\'j\']").19(7(i,o){$(o).f("j","j");$(o).5f()})}7 5i(){$(".5j-Q").u("29.5k[1H!=\'1H\']").19(7(i,o){$(o).f("1H","1H");c D=$(o).f("D");c 1G=$(o).26();1G.f("32",$(o).f("32"));1G.f("D",D);b(2p(D)>0)34($(1G),D)})}7 34(g,D){$(g).3n();$(g).x($(g).f("11"));$(g).x($(g).f("11")+"3o");$(g).x($(g).f("11")+"3p");$(g).f("11","36");$(g).13("36");$(g).u("I").h("重新获取("+D+")");$(g).f("D",D);$(g).55(2f,7(){c 1v=2p($(g).f("D"));1v--;$(g).u("I").h("重新获取("+1v+")");$(g).f("D",1v);b(1v==0){$(g).3n();$(g).x($(g).f("11"));$(g).x($(g).f("11")+"3o");$(g).x($(g).f("11")+"3p");$(g).f("11","3g");$(g).13("3g");$(g).u("I").h("发送验证码")}})}$.5o=7(P,t,1R){c 1m=$.1B(P).t;b(1R)1m=$.2q(P);v 1m>=t};$.5B=7(P,t,1R){c 1m=$.1B(P).t;b(1R)1m=$.2q(P);v 1m<=t};$.2q=7(1i){1i=$.1B(1i);b(1i=="")v 0;c t=0;2O(c i=0;i<1i.t;i++){b(1i.5G(i)>5F)t+=2;p t++}v t};$.5E=7(P){b($.1B(P)!=\'\'){c 1P=/^(1[5y]\\d{9})$/;v 1P.10($.1B(P))}p v L};$.5r=7(2Q){c 1P=/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-2n-2m-9]+((\\.|-)[A-2n-2m-9]+)*\\.[A-2n-2m-9]+$/;v 1P.10(2Q)};7 5t(1a){c 2T=/[a-z]+/;c 3Q=/[A-Z]+/;c 3P=/[0-9]+/;c 3K=/\\W+/;c 3S=/\\S{6,8}/;c 3r=/\\S{9,}/;c n=0;b(2T.10(1a))n++;b(3Q.10(1a))n++;b(3P.10(1a))n++;b(3K.10(1a))n++;b(3S.10(1a))n++;b(3r.10(1a))n++;b(n>=1&&n<=2)n=0;p b(n>=3&&n<=4)n=1;p b(n>=5&&n<=6)n=2;p n=-1;v n}7 5q(){$(".16").19(7(i,16){c U=$(16).f("C");c 3y=2p(U-1);c 1K=\'\';c 2k=0;c 3L=\'<i q="r-l l-M"></i>\'+\'<i q="r-l l-M"></i>\'+\'<i q="r-l l-M"></i>\'+\'<i q="r-l l-M"></i>\'+\'<i q="r-l l-M"></i>\';$(16).h(3L);b(U.25(".")>0){1K="0"+U.23(U.25("."),U.t);2k=(2g(1K)*3O).5D(1)}b(U>1)$(16).u(".r-l:5C("+3y+")").x("l-M").13("l-M-2i");p $(16).u(".r-l").x("l-M").13("l-M-2i");b(1K.t>0){$(16).u(".r-l").5z(U).h(\'<i q="r-l l-M-5A" 5n="5a:\'+2k+\'%"></i>\')}})}7 5c(2o,o){c 18=3u 3z();18.1N="59";18.2o=2o;$.1E({m:1Q,C:18,2A:"K",H:7(F){c 1d=F.1d;c h=F.h;b(1d==1){$(o).h(h)}b(1d==2){$(o).h(h)}b(1d==3){$.3G(h)}b(1d==4){$.1p(F.3H,7(){b(F.2z){y.E=F.2z}})}},24:7(57){}})}7 2r(){c m=E.B;b(m.25("?")==-1){m+="?2r=1"}p{m+="&2r=1"}E.B=m}7 5d(){1n.2a("5e")}7 5l(){1n.2a("5m")}7 5g(){1n.2a("5H")}7 69(3v){$.1E({m:1Q,C:{"1N":"6b","68":3v},1O:"3D",2A:"K",H:7(F){b(F.67==0)E.B=F.2z;p{$.1p("取消授权")}}})}7 63(m){2e{1n.64(\'{"m":"\'+m+\'"}\')}2j(3C){y.2u(m)}}7 3F(F){c 1J=1g;b(F==1){b($(\'#1A-1C-1e\').1f(\'1A-1C-1e\')){1J=L}}p{b(F.1f(\'1A-1C-1e\')){1J=L}}b(1J==L){65({66:"#1A-1C-1e",6c:".2y 3B",6d:".6k 3B",6e:"6f",62:L,61:L,5O:5P})}}7 3A(){b($(\'#1k\').1f(\'1k\')){c m=y.E.B+\'&5Q=1\';c 3s=3J();c 3R=\'<T q="2t-2F">\'+\'<T q="1e"><T>\'+\'<3t 5N="\'+3s+\'/5M/5J/5Z/5W.5T" />\'+\'<I q="r">下拉开始刷新</I>\'+\'</T></T></T>\';$(\'#1k\').5U(3R);c $1h=$(\'.2t-2F .r\');c 4m=$(\'.1k\').4e({$4i:$(\'.1k\'),$4c:$(\'.2t-2F\'),4d:20,m:m,4b:L,4a:{48:7(){$1h.r(\'松开开始刷新:\')},4l:7(){$1h.r(\'数据刷新中···\')},H:7(1V){$(\'#1k\').h($(1V).h());b(14(4n)!=\'1z\'){3F($(1V).u(\'.1A-1C-1e\'));1c=2;3U=L;3T=0;44()}b(14($(1V).u(\'.w-40-4P\').f(\'q\'))==\'4Q\'){4O()}b($(".X").1f(\'4Z\')){2h()}b(14(52)!=\'1z\'){4Y();4X();4U()}b(14(4V)!=\'1z\'){4W();4J();4I();4v()}b(14(4w)!=\'1z\'){4x()}$1h.r(\'数据刷新成功！\')},4u:7(){$1h.r(\'下拉刷新结束\')},24:7(){$1h.r(\'找不到请求地址,数据刷新失败\')}}})}}7 3J(){c 2d=y.1r.E.B;c 21=y.1r.E.4p;c 3w=2d.25(21);c 3x=2d.23(0,3w);c 3I=21.23(0,21.4r(1).4y(\'/\')+1);v(3x+3I)}7 4D(3M){2e{b(14(4B(3M))=="7"){v L}}2j(e){}v 1g}',62,393,'|||||||function||||if|var|||attr|btn|html||init||icon|url|result||else|class|text||length|find|return||removeClass|window|||href|data|lesstime|location|obj|signin_result|success|span|ui|json|true|star|shar_url|imgUrl|value|panel|click||div|avg_point|id||scroll_bottom_page|||test|rel|select|addClass|typeof|droped_select|stars|msg|query|each|pwd|wx|page|tag|box|hasClass|false|statu|str|min_height|loading_container|title|strLength|App|drop|showErr|bind|document|Math|uiselect_idx|state|lt|cancel|load|desc|undefined|index|trim|adv|link|ajax|page_title|divbtn|init_sms|reload|is_exe|start_half|infinite_loading|target|act|type|reg|AJAX_URL|isByte|init_scroll_bottom_back|op|action|response|clickon|config|switchA|close|null|pathName|refresh_image|substring|error|indexOf|next|point|dev_token|button|login_sdk|gotop|dev_type|curWwwPath|try|1000|parseFloat|init_scroll_bottom|gray|catch|half_width|appId|z0|Za|uid|parseInt|getStringLength|weixin_login|random|loading|open|money|score|fadeOut|hd|jump|dataType|switchB|parent|scroll|ipt|warp|nbsp|onMenuShareQQ|onMenuShareWeibo|signature|winner|10000000|onMenuShareQZone|ui_select|for|totalheight|val|nonceStr|callback|regex0|ready|init_ui_select|onMenuShareAppMessage|toggle|round|uiselect_|onMenuShareTimeline|current|form_prefix|init_ui_confirm|init_sms_code_btn|confirm|disabled|fast|gotoTop|scroll_bottom_list|init_ui_textbox|dropdown|scrollTop|this|init_ui_lazy|init_ui_button|light|hide|next_dom|timestamp|selected|POST|init_ui_starbar|stopTime|_hover|_active|Client|regex5|rootPath|img|new|jsonstr|pos|localhostPaht|start_cut|Object|init_pull_refresh|ul|ex|post|pay_sdk_type|init_touch_slide|showSuccess|info|projectName|getRootPath|regex3|star_html|funcName|height|100|regex2|regex1|p_fres_h|regex4|page_total|stop|ui_lazy|160725071|buttons|fictitious_info|apns|countdown|pay_sdk_json|message|js_apns|init_auto_load_data|update_dev_token|is|changeclass|pullStart|openclose|callbacks|autoHide|loadingEl|sendData|pPullRefresh|showConfirm|isload|hidden|el|fadeIn|lazy|start|pullRefresh|is_index_set|500|pathname|go|substr|debug|jsApiList|end|init_get_buy_num|is_pk_index|init_pk_index|lastIndexOf|history|append|eval|js_back|isExitsFunction|total_set_height|071|725|160|init_change_cart_num|init_info_list|pull_down|h_search|placeholder|biz_pull_down|init_count_down|nums|string|close_but|remove|layer|init_buy_form|duobao_detail_info|init_duobao_cart|init_del_cart_item|init_cartnum_btn|fy||live|cart_index|close_appdown|show_signin_message|everyTime|in|ajaxobj|hd_drop|focus|width|toggleClass|focus_user|weixin_login_app|wxlogin|ui_textbox|weibo_login_app|textarea|init_sms_btn|login|ph_verify_btn|qq_login_app|qqlogin|style|minLength|active|init_dp_star|checkEmail|mt_rand|checkPwdFormat|after|scroll_height_set|FW|XS|34578|eq|half|maxLength|gt|toFixed|checkMobilePhone|255|charCodeAt|xlwblogin|textbox|main|signin_price|signin_msg|Tpl|src|delayTime|750|ajax_refresh|status|js_pay_sdk|gif|before|touchmove|refreshing_1|input|ui_starbar|images|starbar|autoPlay|autoPage|open_url|open_type|TouchSlide|slideCell|err_code|param|js_login_sdk|ui_button|get_wx_app_userinfo|titCell|mainCell|effect|leftLoop|LOADER_IMG|event|hover|body|bd'.split('|'),0,{}))
//  window.addEventListener("DOMContentLoaded", function(){
//        btn = document.getElementById("plug-btn");
//        btn.onclick = function(){
//            var divs = document.getElementById("plug-phone").querySelectorAll("div");
//            var className = className=this.checked?"on":"";
//            for(i = 0;i<divs.length; i++){
//                divs[i].className = className;
//            }
//            document.getElementById("plug-wrap").style.display = "on" == className? "block":"none";
//        }
//    }, false);
	 $(function(){
        new Swipe(document.getElementById('banner_box'), {
            speed:500,
            auto:3000,
            callback: function(){
                var lis = $(this.element).next("ol").children();
                lis.removeClass("on").eq(this.index).addClass("on");
            }
        });
    });
/*
 * Swipe 1.0
 *
 * Brad Birdsall, Prime
 * Copyright 2011, Licensed GPL & MIT
 *
*/

window.Swipe = function(element, options) {

  // return immediately if element doesn't exist
  if (!element) return null;

  var _this = this;

  // retreive options
  this.options = options || {};
  this.index = this.options.startSlide || 0;
  this.speed = this.options.speed || 300;
  this.callback = this.options.callback || function() {};
  this.delay = this.options.auto || 0;
  this.unresize = this.options.unresize; //anjey

  // reference dom elements
  this.container = element;
  this.element = this.container.children[0]; // the slide pane

  // static css
  //this.container.style.overflow = 'hidden'; //by anjey
  this.element.style.listStyle = 'none';

  // trigger slider initialization
  this.setup();

  // begin auto slideshow
  this.begin();

  // add event listeners
  if (this.element.addEventListener) {
  	//by anjey
  	this.element.addEventListener('mousedown', this, false);
  	 
    this.element.addEventListener('touchstart', this, false);
    this.element.addEventListener('touchmove', this, false);
    this.element.addEventListener('touchend', this, false);
    this.element.addEventListener('webkitTransitionEnd', this, false);
    this.element.addEventListener('msTransitionEnd', this, false);
    this.element.addEventListener('oTransitionEnd', this, false);
    this.element.addEventListener('transitionend', this, false);
    if(!this.unresize){ // anjey
    	window.addEventListener('resize', this, false);
    }
  }

};

Swipe.prototype = {

  setup: function() {

    // get and measure amt of slides
    this.slides = this.element.children;
    this.length = this.slides.length;

    // return immediately if their are less than two slides
    if (this.length < 2) return null;

    // determine width of each slide
    this.width = this.container.getBoundingClientRect().width || this.width; //anjey
    // return immediately if measurement fails
    if (!this.width) return null;

    // hide slider element but keep positioning during setup
    this.container.style.visibility = 'hidden';

    // dynamic css
    this.element.style.width = (this.slides.length * this.width) + 'px';
    var index = this.slides.length;
    while (index--) {
      var el = this.slides[index];
      el.style.width = this.width + 'px';
      el.style.display = 'table-cell';
      el.style.verticalAlign = 'top';
    }
    // set start position and force translate to remove initial flickering
    this.slide(this.index, 0); 

    // show slider element
    this.container.style.visibility = 'visible';

  },

  slide: function(index, duration) {

    var style = this.element.style;

    // fallback to default speed
    if (duration == undefined) {
        duration = this.speed;
    }

    // set duration speed (0 represents 1-to-1 scrolling)
    style.webkitTransitionDuration = style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.transitionDuration = duration + 'ms';

    // translate to given index position
    style.MozTransform = style.webkitTransform = 'translate3d(' + -(index * this.width) + 'px,0,0)';
    style.msTransform = style.OTransform = 'translateX(' + -(index * this.width) + 'px)';

    // set new index to allow for expression arguments
    this.index = index;

  },

  getPos: function() {
    
    // return current index position
    return this.index;

  },

  prev: function(delay) {

    // cancel next scheduled automatic transition, if any
    this.delay = delay || 0;
    clearTimeout(this.interval);

    // if not at first slide
    if (this.index) this.slide(this.index-1, this.speed);

  },

  next: function(delay) {

    // cancel next scheduled automatic transition, if any
    this.delay = delay || 0;
    clearTimeout(this.interval);

    if (this.index < this.length - 1) this.slide(this.index+1, this.speed); // if not last slide
    else this.slide(0, this.speed); //if last slide return to start

  },

  begin: function() {

    var _this = this;

    this.interval = (this.delay)
      ? setTimeout(function() { 
        _this.next(_this.delay);
      }, this.delay)
      : 0;
  
  },
  
  stop: function() {
    this.delay = 0;
    clearTimeout(this.interval);
  },
  
  resume: function() {
    this.delay = this.options.auto || 0;
    this.begin();
  },

  handleEvent: function(e) {
  	var that = this;
  	if(!e.touches){
  		e.touches = new Array(e);
  		e.scale = false;
  	}
    switch (e.type) {
      // by anjey
      case 'mousedown': (function(){
      		that.element.addEventListener('mousemove', that, false);
   			that.element.addEventListener('mouseup', that, false);
   			that.element.addEventListener('mouseout', that, false);
      		that.onTouchStart(e);
      })(); break;
      case 'mousemove': this.onTouchMove(e); break;
      case 'mouseup': (function(){
	      	that.element.removeEventListener('mousemove', that, false);
	   		that.element.removeEventListener('mouseup', that, false);
	   		that.element.removeEventListener('mouseout', that, false);
	      	that.onTouchEnd(e);
      })(); break;
     case 'mouseout': (function(){
      		that.element.removeEventListener('mousemove', that, false);
   			that.element.removeEventListener('mouseup', that, false);
   			that.element.removeEventListener('mouseout', that, false);
      		that.onTouchEnd(e);
      })(); break;
    	
      case 'touchstart': this.onTouchStart(e); break;
      case 'touchmove': this.onTouchMove(e); break;
      case 'touchend': this.onTouchEnd(e); break;
      case 'webkitTransitionEnd':
      case 'msTransitionEnd':
      case 'oTransitionEnd':
      case 'transitionend': this.transitionEnd(e); break;
      case 'resize': this.setup(); break;
    }
  },

  transitionEnd: function(e) {
    e.preventDefault();
    if (this.delay) this.begin();

    this.callback(e, this.index, this.slides[this.index]);

  },

  onTouchStart: function(e) {
    
    this.start = {

      // get touch coordinates for delta calculations in onTouchMove
      pageX: e.touches[0].pageX,
      pageY: e.touches[0].pageY,

      // set initial timestamp of touch sequence
      time: Number( new Date() )

    };

    // used for testing first onTouchMove event
    this.isScrolling = undefined;
    
    // reset deltaX
    this.deltaX = 0;

    // set transition time to 0 for 1-to-1 touch movement
    this.element.style.MozTransitionDuration = this.element.style.webkitTransitionDuration = 0;

  },

  onTouchMove: function(e) {

    // ensure swiping with one touch and not pinching
    if(e.touches.length > 1 || e.scale && e.scale !== 1) return;

    this.deltaX = e.touches[0].pageX - this.start.pageX;

    // determine if scrolling test has run - one time test
    if ( typeof this.isScrolling == 'undefined') {
      this.isScrolling = !!( this.isScrolling || Math.abs(this.deltaX) < Math.abs(e.touches[0].pageY - this.start.pageY) );
    }

    // if user is not trying to scroll vertically
    if (!this.isScrolling) {

      // prevent native scrolling 
      e.preventDefault();

      // cancel slideshow
      clearTimeout(this.interval);

      // increase resistance if first or last slide
      this.deltaX = 
        this.deltaX / 
          ( (!this.index && this.deltaX > 0               // if first slide and sliding left
            || this.index == this.length - 1              // or if last slide and sliding right
            && this.deltaX < 0                            // and if sliding at all
          ) ?                      
          ( Math.abs(this.deltaX) / this.width + 1 )      // determine resistance level
          : 1 );                                          // no resistance if false
      
      // translate immediately 1-to-1
      this.element.style.MozTransform = this.element.style.webkitTransform = 'translate3d(' + (this.deltaX - this.index * this.width) + 'px,0,0)';

    }

  },

  onTouchEnd: function(e) {

    // determine if slide attempt triggers next/prev slide
    var isValidSlide = 
          Number(new Date()) - this.start.time < 250      // if slide duration is less than 250ms
          && Math.abs(this.deltaX) > 20                   // and if slide amt is greater than 20px
          || Math.abs(this.deltaX) > this.width/2,        // or if slide amt is greater than half the width

    // determine if slide attempt is past start and end
        isPastBounds = 
          !this.index && this.deltaX > 0                          // if first slide and slide amt is greater than 0
          || this.index == this.length - 1 && this.deltaX < 0;    // or if last slide and slide amt is less than 0

    // if not scrolling vertically
    if (!this.isScrolling) {

      // call slide function with slide end value based on isValidSlide and isPastBounds tests
      this.slide( this.index + ( isValidSlide && !isPastBounds ? (this.deltaX < 0 ? 1 : -1) : 0 ), this.speed );

    }

  }

};

eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(2(){J();E();z()});2 z(){3(I.12!=10){$(\'.13\').7()}3($("#B").1b(\'1e\')){A()}3($("#4").1g("18").17>16){$("#4").5("6","e");$("#4").q({"n":"x","f-y":"d"});$("#9").14("15",2(){3($("#4").5("6")=="e"){$("#4").q({"n":"19","f-y":"d"});$("#9").8("收起<i v=\'u\'>&#1a;</i>");$("#4").5("6","1f")}b{$("#4").q({"n":"x","f-y":"d"});$("#9").8("展开<i v=\'u\'>&#T;</i>");$("#4").5("6","e")}})}b{$("#9").7()}}2 A(){P({U:"#B",11:".Z D",Y:".W D",1h:"1d",1A:C,1z:C,1w:1x})}2 E(){m g=F($(".w-r-t:1C").5("1G")+"s")-1H 1E().1D();$(".w-r-t").1l(2(i,o){m c=F($(o).5("c")+"s");$(o).1t({c:c,g:g,1p:10,1q:"%H:%M:%S:%1o",1i:2(){$(o).8("开奖中");$(o).1k(1v,2(){m a=$(o).5("a");$.1F({1r:1s,1n:{"1j":"1m","a":a},1u:"1B",1y:"X",V:2(K){3(K.6==1){Q.R()}}})})}})})}2 J(){3(I.1c){$(".G").h();$(".N").7()}b{$(".G").7();$(".N").h()}L()}2 L(){3(p){3(p.O>0){$(".j-k-l").8(p.O);$(".j-k-l").h()}b{$(".j-k-l").7()}}}',62,106,'||function|if|duobao_sn_list|attr|status|hide|html|func|duobao_item_id|else|endtime|hidden|close|overflow|timespan|show||goods|in|list|var|height||cart_data_json|css|countdown|000|nums|iconfont|class||140||init_info_list|init_adv_list|banner_box|true|ul|init_count_down|parseInt|joinin||cart_conf_json|init_duobao_cart|obj|load_cart_data||gotonew|cart_item_num|TouchSlide|location|reload||xe6c3|slideCell|success|bd|json|mainCell|hd||titCell|min_buy|tenyen|bind|click|20|length|dd|auto|xe6c4|hasClass|residue_count|leftLoop|has_img|open|find|effect|callback|act|everyTime|each|duobao_status|data|MS|interval|format|url|AJAX_URL|count_down|type|5000|delayTime|750|dataType|autoPlay|autoPage|POST|first|getTime|Date|ajax|nowtime|new'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}(';!4(a){"1t 1u";3 b="";b=b?b:C.V[C.V.A-1].1s.1r(/[\\s\\S]*\\//)[0];3 c=C,d="1p",e="1q",f=4(a){t c[d](a)};3 g={9:0,u:!0,1d:!0,N:!0,13:!0};a.8={O:4(a){3 b=U.1v(U.1B(g));M(3 c 1A a)b[c]=a[c];t b},G:{},q:{}},8.E=4(a,b){3 c;a.T("1z",4(){c=!0},!1),a.T("1x",4(a){a.1o(),c||b.1C(x,a),c=!1},!1)};3 h=0,i=["1l"],j=4(a){3 b=x;b.12=8.O(a),b.10()};j.17.10=4(){3 a=x,b=a.12,d=c.1g("7");a.K=d.K=i[0]+h,d.X("r",i[0]+" "+i[0]+(b.9||0)),d.X("5",h);3 g=4(){3 a="1f"==B b.y;t b.y?\'<Y p="\'+(a?b.y[1]:"")+\'">\'+(a?b.y[0]:b.y)+\'</Y><R r="Q"></R>\':""}(),j=4(){3 a,c=(b.w||[]).A;t 0!==c&&b.w?(a=\'<D 9="1">\'+b.w[0]+"</D>",2===c&&(a=\'<D 9="0">\'+b.w[1]+"</D>"+a),\'<7 r="18">\'+a+"</7>"):""}();F(b.N||(b.z=b.1i("z")?b.z:1j,b.p=b.p||"",b.p+=" z:"+(c.J.1m+b.z)+"1k"),2===b.9&&(b.L=\'<i></i><i r="1D"></i><i></i><7>\'+(b.L||"")+"</7>"),d.19=(b.u?"<7 "+("1n"==B b.u?\'p="\'+b.u+\'"\':"")+\' r="1b"></7>\':"")+\'<7 r="1h" \'+(b.N?"":\'p="1H:1Q;"\')+\'><7 r="1P"><7 r="1O \'+(b.Z?b.Z:"")+" "+(b.9||b.u?"":"1R ")+(b.13?"1S":"")+\'" \'+(b.p?\'p="\'+b.p+\'"\':"")+">"+g+\'<7 r="1L">\'+b.L+"</7>"+j+"</7></7></7>",!b.9||2===b.9){3 l=c[e](i[0]+b.9),m=l.A;m>=1&&k.o(l[0].H("5"))}C.J.1K(d);3 n=a.1J=f("#"+a.K)[0];b.15&&b.15(n),a.5=h++,a.14(b,n)},j.17.14=4(a,b){3 c=x;F(a.16&&(8.G[c.5]=1F(4(){k.o(c.5)},1M*a.16))){3 d=b[e]("Q")[0],f=4(){a.P&&a.P(),k.o(c.5)};8.E(d,f),d.I=f}3 g=4(){3 b=x.H("9");0==b?(a.W&&a.W(),k.o(c.5)):a.11?a.11(c.5):k.o(c.5)};F(a.w)M(3 h=b[e]("18")[0].1T,i=h.A,j=0;i>j;j++)8.E(h[j],g),h[j].I=g;F(a.u&&a.1d){3 l=b[e]("1b")[0];8.E(l,4(){k.o(c.5,a.q)}),l.I=4(){k.o(c.5,a.q)}}a.q&&(8.q[c.5]=a.q)};3 k={v:"1.6",5:h,1E:4(a){3 b=1G j(a||{});t b.5},o:4(a){3 b=f("#"+i[0]+a)[0];b&&(b.19="",c.J.1N(b),1y(8.G[a]),1c 8.G[a],"4"==B 8.q[a]&&8.q[a](),1c 8.q[a])},1e:4(){M(3 a=c[e](i[0]),b=0,d=a.A;d>b;b++)k.o(0|a[0].H("5"))}};"4"==B 1a?1a(4(){t k}):a.1w=k}(1I);',62,118,'|||var|function|index||div|ready|type|||||||||||||||close|style|end|class||return|shade||btn|this|title|top|length|typeof|document|span|touch|if|timer|getAttribute|onclick|body|id|content|for|fixed|extend|cancel|layermend|button||addEventListener|JSON|scripts|no|setAttribute|h3|className|view|yes|config|anim|action|success|time|prototype|layermbtn|innerHTML|define|laymshade|delete|shadeClose|closeAll|object|createElement|layermmain|hasOwnProperty|100|px|layermbox|scrollTop|string|preventDefault|querySelectorAll|getElementsByClassName|match|src|use|strict|parse|layer|touchend|clearTimeout|touchmove|in|stringify|call|laymloadtwo|open|setTimeout|new|position|window|elem|appendChild|layermcont|1e3|removeChild|layermchild|section|static|layermborder|layermanim|children'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(1V).25(7(){1z();1M()});7 1M(){$(".23-22").1Z("L",7(){$("15").B("14",7(1L){1L.1Y()});$("#N").9($(s).6(\'2-26\'));3 k=$("#N").9();3 c=C D();c.k=k;c.1f="1S";$.1n({1g:1e,2:c,1b:"1c",1a:"1j",1k:7(P){8(P.1X!=1){$.V(P.l,7(){8(P.E){J.A=P.E}});R U}$(".b-K").1H();$(".b-o-g").W(\'b-o-g-O\');$(".f-y").q("Q-x");3 2=P.21;3 1h=$(\'.b-o-g .l .20 .24 1R\');3 1D=1h.6(\'2\')+\'&k=\'+k;1h.6(\'1Q\',2.1P);1h.1O().6(\'A\',1D);$("i[n=\'t\']").6(\'Z-j\',2.v);$("i[n=\'t\']").6(\'X-j\',2.e);$("i[n=\'t\']").6(\'16-j\',2.w);$("i[n=\'t\']").6("F-f",2.1T);3 m=r(2.v)-r(2.w);3 1p;$(".f-y").1W(7(){1p=p($(s).u());8(1p>m){$(s).W("Y")}h{$(s).q("Y")}});8(2.e>=10||m<5){$(\'.f-y\').1B(0).W("Y")}h{$(\'.f-y\').1B(0).q("Y")}8(2.e==1){$("i[n=\'t\']").9(1);$(\'.M-l G\').u(p($("i[n=\'t\']").6("F-f")))}h{$("i[n=\'t\']").9(2.e);$(\'.M-l G\').u(2.e*p($("i[n=\'t\']").6("F-f")))}}})});$(".y-2m").L(7(){$("15").1o("14");$(".b-K").I();$(".b-o-g").q(\'b-o-g-O\')});$(".b-K").L(7(){$("15").1o("14");$(".b-K").I();$(".b-o-g").q(\'b-o-g-O\')})}7 1z(){3 4=$("i[n=\'t\']");4.B("2u",7(){18($(s))});4.B("2p",7(){18($(s))});4.B(\'i 2o\',7(){18($(s))});$(\'.f-y\').B("L",7(){8($(s).2n(\'Y\')){R U}$(s).W("Q-x").2r().q("Q-x");3 d;3 T=$(s).u();3 k=$("#N").9();3 2=C D;2.v=4.6(\'Z-j\');2.e=4.6(\'X-j\');2.w=4.6(\'16-j\');3 m=r(2.v)-r(2.w);8(2t(T)||T>m){d=m}h{d=T}$(\'.M-l G\').u(d*p(4.6("F-f")));4.9(d);4.1F({1E:"0.1s",},17);4.1F({1E:"0.1q",},17)});$(\'.19-b-1r-1G\').B("L",7(){$(\'.f-y\').q("Q-x");3 T=$(s).u();3 m;3 1d=4.9();3 k=$("#N").9();3 d;3 2=C D;2.v=4.6(\'Z-j\');2.e=4.6(\'X-j\');2.w=4.6(\'16-j\');m=r(2.v)-r(2.w);d=r(1d)+r(2.e);8(d>m){d=m}$(\'.M-l G\').u(d*p(4.6("F-f")));4.9(d);4.1i("1m-1l",\'0.1s\');1w(7(){4.1i("1m-1l",\'0.1q\')},17)});$(\'.19-2s-1r-1G\').B("L",7(){$(\'.f-y\').q("Q-x");3 T=$(s).u();3 m;3 1d=4.9();3 k=$("#N").9();3 d;3 2=C D;2.v=4.6(\'Z-j\');2.e=4.6(\'X-j\');2.w=4.6(\'16-j\');m=r(2.v)-r(2.w);d=r(1d)-r(2.e);8(d>m){d=m}8(d<2.e){d=2.e}$(\'.M-l G\').u(d*p(4.6("F-f")));4.9(d);4.1i("1m-1l",\'0.1s\');1w(7(){4.1i("1m-1l",\'0.1q\')},17)});$(\'.19-2c-2b\').B("L",7(){3 k=$("#N").9();3 1J=2a+"/2e/19.2j?2i=1r&2h=1";3 1I=$("i[n=\'t\']").9();3 c=C D();c.k=k;c.11=1I;c.1f="1x";$.1n({1g:1e,2:c,1b:"1c",1a:"1j",1k:7(2){8(2.S==1){8(2.E){J.A=2.E}h{J.A=1J}}h{8(2.S==-1){$(".b-K").I();$(".b-o-g").q(\'b-o-g-O\');$.V(2.l,7(){J.A=2.E})}h{$(".b-K").I();$(".b-o-g").q(\'b-o-g-O\');$.V(2.l)}$("15").1o("14")}}})})}7 18(1K){$("i[n=\'2g\']").9(\'\');$(\'.f-y\').q("Q-x");3 1t=0;3 4=$("i[n=\'t\']");3 1N=$("i[n=\'2q\']");3 H=1K.9();3 2=C D;2.v=4.6(\'Z-j\');2.e=4.6(\'X-j\');2.w=4.6(\'16-j\');8(p(H)>p(2.e)){3 z=\'\';3 1y=2.v-2.w;8(H<2.e){}h 8(H>1y){z=1y}h{1t=H%2.e;8(1t>0){z=2f.29(H/2.e)*2.e}h{z=H}}8(z>0){4.9(z);1N.9(z);$(\'.M-l G\').u(z*p(4.6("F-f")))}}h{}$(\'.M-l G\').u(4.9()*p(4.6("F-f")))}3 1v=0;7 1x(a){3 2d=$(a);3 11=p($("i[n=\'t\']").9());3 c=C D();c.1f="1x";c.11=11;c.k=p($("#N").9());$.1n({1g:1e,2:c,1b:"1c",1a:"1j",1k:7(a){8(a.S==-1){$.V(a.l,7(){8(a.E){J.A=a.E}});R U}8(a.S==1){$(".b-K").I();$(".b-o-g").q(\'b-o-g-O\');$("15").1o("14");8(a.13>0){$(".1u-x-g").1A(a.13);$(".1u-x-g").1H();$(".12").1A(a.13);$(".12").2k(2l);8(a.13>1v){$(".12").W("1C");1w(7(){$(".12").q("1C")},17)}1v=a.13;R U}h{$(".12").I();$(".1u-x-g").I()}R U}h{$.V(a.l);R U}}})}7 28(a){3 c=C D();c.1f="1U";c.11=1;c.k=p($(a).6(\'k\'));$.1n({1g:1e,2:c,1b:"1c",1a:"1j",1k:7(a){8(a.S==-1){J.A=a.E}h 8(a.S==1){J.A=27}h{$.V(a.l)}}})}',62,155,'||data|var|num_input||attr|function|if|val|obj|add|query|number_item|min_buy|price|list|else|input|buy|data_id|info|can_buy|name|to|parseInt|removeClass|parseFloat|this|choose_item_number|text|max_buy|current_buy|in|btn|set_num|href|bind|new|Object|jump|unit|span|change_num|hide|location|bg|click|money|duobao_item_id_set|up|dataObj|all|return|status|choose_number|false|showErr|addClass|min|disable|max||buy_num|nav_cart_num|cart_item_num|touchmove|body|cur|200|get_last_num|index|dataType|type|POST|org_number|AJAX_URL|act|url|img_obj|css|json|success|size|font|ajax|unbind|btn_num|5rem|cart|9rem|mod_val|goods|cur_num|setTimeout|add_cart|cur_max_num|init_change_cart_num|html|eq|nav_cart_num_zoom|a_href|fontSize|animate|num|show|add_number|pay_url|num_obj|event|init_get_buy_num|app_num_input|parent|icon|src|img|get_duobao_item_num|unit_price|add_total_cart|document|each|user_login_status|preventDefault|live|title|duobao_number|box|right|imgbox|ready|id|totalbuy_cart_url|add_total_buy_cart_item|floor|APP_ROOT|addto_cart|comfirm|btn_item|wap|Math|org_num|show_prog|ctl|php|fadeIn|1000|close|hasClass|propertychange|focusin|app_choose_item_number|siblings|subtract|isNaN|focusout'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(12(e,t){12 4Q(e){17 t=M[e]={};14 v.1h(e.1M(y),12(e,n){t[n]=!0}),t}12 H(e,n,r){if(r===t&&e.1d===1){17 i="1r-"+n.1p(P,"-$1").1w();r=e.22(i);if(1b r=="1q"){1Q{r=r==="aE"?!0:r==="6C"?!1:r==="19"?19:+r+""===r?+r:D.1c(r)?v.9r(r):r}1U(s){}v.1r(e,n,r)}1x r=t}14 r}12 B(e){17 t;1a(t in e){if(t==="1r"&&v.6b(e[t]))4m;if(t!=="a0")14!1}14!0}12 et(){14!1}12 5f(){14!0}12 35(e){14!e||!e.1i||e.1i.1d===11}12 at(e,t){do e=e[t];1t(e&&e.1d!==1);14 e}12 ft(e,t,n){t=t||0;if(v.1v(t))14 v.4c(e,12(e,r){17 i=!!t.1f(e,r,e);14 i===n});if(t.1d)14 v.4c(e,12(e,r){14 e===t===n});if(1b t=="1q"){17 r=v.4c(e,12(e){14 e.1d===1});if(it.1c(t))14 v.1C(t,r,!n);t=v.1C(t,r)}14 v.4c(e,12(e,r){14 v.3R(e,t)>=0===n})}12 3p(e){17 t=ct.1M("|"),n=e.6t();if(n.1X)1t(t.18)n.1X(t.5r());14 n}12 dh(e,t){14 e.1O(t)[0]||e.2p(e.2h.1X(t))}12 9s(e,t){if(t.1d!==1||!v.8W(e))14;17 n,r,i,s=v.1B(e),o=v.1B(t,s),u=s.31;if(u){24 o.2H,o.31={};1a(n in u)1a(r=0,i=u[n].18;r<i;r++)v.1j.27(t,n,u[n][r])}o.1r&&(o.1r=v.1o({},o.1r))}12 9z(e,t){17 n;if(t.1d!==1)14;t.ar&&t.ar(),t.as&&t.as(e),n=t.1m.1w(),n==="25"?(t.1i&&(t.74=e.74),v.1s.6T&&e.1Z&&!v.37(t.1Z)&&(t.1Z=e.1Z)):n==="1Y"&&8U.1c(e.1e)?(t.av=t.21=e.21,t.1z!==e.1z&&(t.1z=e.1z)):n==="3e"?t.2K=e.fJ:n==="1Y"||n==="5x"?t.au=e.au:n==="26"&&t.1F!==e.1F&&(t.1F=e.1F),t.4J(v.2U)}12 63(e){14 1b e.1O!="2S"?e.1O("*"):1b e.47!="2S"?e.47("*"):[]}12 8Y(e){8U.1c(e.1e)&&(e.av=e.21)}12 8i(e,t){if(t in e)14 t;17 n=t.7x(0).8C()+t.1D(1),r=t,i=8m.18;1t(i--){t=8m[i]+n;if(t in e)14 t}14 r}12 69(e,t){14 e=t||e,v.1I(e,"1P")==="39"||!v.2P(e.2h,e)}12 89(e,t){17 n,r,i=[],s=0,o=e.18;1a(;s<o;s++){n=e[s];if(!n.1g)4m;i[s]=v.1B(n,"8T"),t?(!i[s]&&n.1g.1P==="39"&&(n.1g.1P=""),n.1g.1P===""&&69(n)&&(i[s]=v.1B(n,"8T",87(n.1m)))):(r=2m(n,"1P"),!i[s]&&r!=="39"&&v.1B(n,"8T",r))}1a(s=0;s<o;s++){n=e[s];if(!n.1g)4m;if(!t||n.1g.1P==="39"||n.1g.1P==="")n.1g.1P=t?i[s]||"":"39"}14 e}12 8k(e,t,n){17 r=d5.1T(t);14 r?3O.6E(0,r[1]-(n||0))+(r[2]||"3z"):t}12 en(e,t,n,r){17 i=n===(r?"2s":"6e")?4:t==="1W"?1:0,s=0;1a(;i<4;i+=2)n==="3o"&&(s+=v.1I(e,n+$t[i],!0)),r?(n==="6e"&&(s-=2b(2m(e,"3q"+$t[i]))||0),n!=="3o"&&(s-=2b(2m(e,"2s"+$t[i]+"77"))||0)):(s+=2b(2m(e,"3q"+$t[i]))||0,n!=="3q"&&(s+=2b(2m(e,"2s"+$t[i]+"77"))||0));14 s}12 8a(e,t,n){17 r=t==="1W"?e.4v:e.6h,i=!0,s=v.1s.5s&&v.1I(e,"5s")==="2s-3W";if(r<=0||r==19){r=2m(e,t);if(r<0||r==19)r=e.1g[t];if(5t.1c(r))14 r;i=s&&(v.1s.9p||r===e.1g[t]),r=2b(r)||0}14 r+en(e,t,n||(s?"2s":"6e"),i)+"3z"}12 87(e){if(7r[e])14 7r[e];17 t=v("<"+e+">").d7(i.2r),n=t.1I("1P");t.2G();if(n==="39"||n===""){4A=i.2r.2p(4A||v.1o(i.1X("9I"),{ag:0,1W:0,3F:0}));if(!4E||!4A.1X)4E=(4A.9K||4A.9J).32,4E.dT("<!dQ 2A><2A><2r>"),4E.dW();t=4E.2r.2p(4E.1X(e)),n=2m(t,"1P"),i.2r.2R(4A)}14 7r[e]=n,n}12 fn(e,t,n,r){17 i;if(v.2O(t))v.1h(t,12(t,i){n||c7.1c(e)?r(e,i):fn(e+"["+(1b i=="25"?t:"")+"]",i,n,r)});1x if(!n&&v.1e(t)==="25")1a(i in t)fn(e+"["+i+"]",t[i],n,r);1x r(e,t)}12 9d(e){14 12(t,n){1b t!="1q"&&(n=t,t="*");17 r,i,s,o=t.1w().1M(y),u=0,a=o.18;if(v.1v(n))1a(;u<a;u++)r=o[u],s=/^\\+/.1c(r),s&&(r=r.7J(1)||"*"),i=e[r]=e[r]||[],i[s?"3d":"1k"](n)}}12 5S(e,n,r,i,s,o){s=s||n.36[0],o=o||{},o[s]=!0;17 u,a=e[s],f=0,l=a?a.18:0,c=e===7t;1a(;f<l&&(c||!u);f++)u=a[f](n,r,i),1b u=="1q"&&(!c||o[u]?u=t:(n.36.3d(u),u=5S(e,n,r,i,u,o)));14(c||!u)&&!o["*"]&&(u=5S(e,n,r,i,"*",o)),u}12 98(e,n){17 r,i,s=v.4e.bK||{};1a(r in n)n[r]!==t&&((s[r]?e:i||(i={}))[r]=n[r]);i&&v.1o(!0,e,i)}12 bS(e,n,r){17 i,s,o,u,a=e.4T,f=e.36,l=e.bD;1a(s in l)s in r&&(n[l[s]]=r[s]);1t(f[0]==="*")f.3y(),i===t&&(i=e.6y||n.6D("6e-1e"));if(i)1a(s in a)if(a[s]&&a[s].1c(i)){f.3d(s);2f}if(f[0]in r)o=f[0];1x{1a(s in r){if(!f[0]||e.4S[s+" "+f[0]]){o=s;2f}u||(u=s)}o=o||u}if(o)14 o!==f[0]&&f.3d(o),r[o]}12 bQ(e,t){17 n,r,i,s,o=e.36.1D(),u=o[0],a={},f=0;e.aq&&(t=e.aq(t,e.5H));if(o[1])1a(n in e.4S)a[n.1w()]=e.4S[n];1a(;i=o[++f];)if(i!=="*"){if(u!=="*"&&u!==i){n=a[u+" "+i]||a["* "+i];if(!n)1a(r in a){s=r.1M(" ");if(s[1]===i){n=a[u+" "+s[0]]||a["* "+s[0]];if(n){n===!0?n=a[r]:a[r]!==!0&&(i=s[0],o.2B(f--,0,i));2f}}}if(n!==!0)if(n&&e["de"])t=n(t);1x 1Q{t=n(t)}1U(l){14{6i:"ay",2c:n?l:"cX em ek "+u+" ew "+i}}}u=i}14{6i:"66",1r:t}}12 8M(){1Q{14 1n e.ci}1U(t){}}12 cp(){1Q{14 1n e.7e("aC.dX")}1U(t){}}12 $n(){14 4p(12(){4F=t},0),4F=v.2Y()}12 ak(e,t){v.1h(t,12(t,n){17 r=(5e[t]||[]).4f(5e["*"]),i=0,s=r.18;1a(;i<s;i++)if(r[i].1f(e,t,n))14})}12 8f(e,t,n){17 r,i=0,s=0,o=6a.18,u=v.4k().44(12(){24 a.1E}),a=12(){17 t=4F||$n(),n=3O.6E(0,f.ap+f.2C-t),r=n/f.2C||0,i=1-r,s=0,o=f.59.18;1a(;s<o;s++)f.59[s].8E(i);14 u.a5(e,[f,i,n]),i<1&&o?n:(u.4s(e,[f]),!1)},f=u.2k({1E:e,3K:v.1o({},t),2y:v.1o(!0,{9l:{}},n),e6:t,i4:n,ap:4F||$n(),2C:n.2C,59:[],9g:12(t,n,r){17 i=v.cx(e,f.2y,t,n,f.2y.9l[t]||f.2y.4U);14 f.59.1k(i),i},30:12(t){17 n=0,r=t?f.59.18:0;1a(;n<r;n++)f.59[n].8E(1);14 t?u.4s(e,[f,t]):u.bP(e,[f,t]),15}}),l=f.3K;al(l,f.2y.9l);1a(;i<o;i++){r=6a[i].1f(f,e,l,f.2y);if(r)14 r}14 ak(f,l),v.1v(f.2y.3C)&&f.2y.3C.1f(e,f),v.fx.c1(v.1o(a,{cW:f,1V:f.2y.1V,1E:e})),f.54(f.2y.54).2W(f.2y.2W,f.2y.3f).4l(f.2y.4l).44(f.2y.44)}12 al(e,t){17 n,r,i,s,o;1a(n in e){r=v.45(n),i=t[r],s=e[n],v.2O(s)&&(i=s[1],s=e[n]=s[0]),n!==r&&(e[r]=s,24 e[n]),o=v.2T[r];if(o&&"8o"in o){s=o.8o(s),24 e[r];1a(n in s)n in e||(e[n]=s[n],t[n]=i)}1x t[r]=i}}12 cz(e,t,n){17 r,i,s,o,u,a,f,l,c,h=15,p=e.1g,d={},m=[],g=e.1d&&69(e);n.1V||(l=v.6G(e,"fx"),l.6c==19&&(l.6c=0,c=l.2M.5b,l.2M.5b=12(){l.6c||c()}),l.6c++,h.44(12(){h.44(12(){l.6c--,v.1V(e,"fx").18||l.2M.5b()})})),e.1d===1&&("3F"in t||"1W"in t)&&(n.3c=[p.3c,p.am,p.ao],v.1I(e,"1P")==="5u"&&v.1I(e,"7G")==="39"&&(!v.1s.9n||87(e.1m)==="5u"?p.1P="5u-4x":p.5n=1)),n.3c&&(p.3c="2x",v.1s.9h||h.2W(12(){p.3c=n.3c[0],p.am=n.3c[1],p.ao=n.3c[2]}));1a(r in t){s=t[r];if(cA.1T(s)){24 t[r],a=a||s==="46";if(s===(g?"48":"3A"))4m;m.1k(r)}}o=m.18;if(o){u=v.1B(e,"90")||v.1B(e,"90",{}),"2x"in u&&(g=u.2x),a&&(u.2x=!g),g?v(e).3A():h.2W(12(){v(e).48()}),h.2W(12(){17 t;v.4R(e,"90",!0);1a(t in d)v.1g(e,t,d[t])});1a(r=0;r<o;r++)i=m[r],f=h.9g(i,g?u[i]:0),d[i]=u[i]||v.1g(e,i),i in u||(u[i]=f.3C,g&&(f.4w=f.3C,f.3C=i==="1W"||i==="3F"?1:0))}}12 2o(e,t,n,r,i){14 1n 2o.29.3b(e,t,n,r,i)}12 65(e,t){17 n,r={3F:e},i=0;t=t?1:0;1a(;i<4;i+=2-t)n=$t[i],r["3o"+n]=r["3q"+n]=e;14 t&&(r.2n=r.1W=e),r}12 3u(e){14 v.3U(e)?e:e.1d===9?e.bf||e.bg:!1}17 n,r,i=e.32,s=e.h8,o=e.gY,u=e.3w,a=e.$,f=3X.29.1k,l=3X.29.1D,c=3X.29.2e,h=81.29.gB,p=81.29.b2,d=5W.29.37,v=12(e,t){14 1n v.fn.3b(e,t,n)},m=/[\\-+]?(?:\\d*\\.|)\\d+(?:[eE][\\-+]?\\d+|)/.ge,g=/\\S/,y=/\\s+/,b=/^[\\s\\aw\\ax]+|[\\s\\aw\\ax]+$/g,w=/^(?:[^#<]*(<[\\w\\W]+>)[^>]*$|#([\\w\\-]*)$)/,E=/^<(\\w+)\\s*\\/?>(?:<\\/\\1>|)$/,S=/^[\\],:{}\\s]*$/,x=/(?:^|:|,)(?:\\s*\\[)+/g,T=/\\\\(?:["\\\\\\/fY]|u[\\da-fA-F]{4})/g,N=/"[^"\\\\\\r\\n]*"|aE|6C|19|-?(?:\\d\\d*\\.|)\\d+(?:[eE][\\-+]?\\d+|)/g,C=/^-7M-/,k=/-([\\da-z])/gi,L=12(e,t){14(t+"").8C()},A=12(){i.41?(i.5P("aA",A,!1),v.2q()):i.3h==="3f"&&(i.79("5c",A),v.2q())},O={};v.fn=v.29={3v:v,3b:12(e,n,r){17 s,o,u,a;if(!e)14 15;if(e.1d)14 15.2Z=15[0]=e,15.18=1,15;if(1b e=="1q"){e.7x(0)==="<"&&e.7x(e.18-1)===">"&&e.18>=3?s=[19,e,19]:s=w.1T(e);if(s&&(s[1]||!n)){if(s[1])14 n=n 5o v?n[0]:n,a=n&&n.1d?n.2h||n:i,e=v.aG(s[1],a,!0),E.1c(s[1])&&v.5w(n)&&15.3Q.1f(e,n,!0),v.34(15,e);o=i.42(s[2]);if(o&&o.1i){if(o.id!==s[2])14 r.2d(e);15.18=1,15[0]=o}14 15.2Z=i,15.1R=e,15}14!n||n.5g?(n||r).2d(e):15.3v(n).2d(e)}14 v.1v(e)?r.2q(e):(e.1R!==t&&(15.1R=e.1R,15.2Z=e.2Z),v.4z(e,15))},1R:"",5g:"1.8.3",18:0,gh:12(){14 15.18},aF:12(){14 l.1f(15)},1u:12(e){14 e==19?15.aF():e<0?15[15.18+e]:15[e]},2J:12(e,t,n){17 r=v.34(15.3v(),e);14 r.6F=15,r.2Z=15.2Z,t==="2d"?r.1R=15.1R+(15.1R?" ":"")+n:t&&(r.1R=15.1R+"."+t+"("+n+")"),r},1h:12(e,t){14 v.1h(15,e,t)},2q:12(e){14 v.2q.2k().2W(e),15},eq:12(e){14 e=+e,e===-1?15.1D(e):15.1D(e,e+1)},3L:12(){14 15.eq(0)},5J:12(){14 15.eq(-1)},1D:12(){14 15.2J(l.1A(15,1l),"1D",l.1f(1l).2t(","))},2N:12(e){14 15.2J(v.2N(15,12(t,n){14 e.1f(t,n,t)}))},4w:12(){14 15.6F||15.3v(19)},1k:f,4r:[].4r,2B:[].2B},v.fn.3b.29=v.fn,v.1o=v.fn.1o=12(){17 e,n,r,i,s,o,u=1l[0]||{},a=1,f=1l.18,l=!1;1b u=="3j"&&(l=u,u=1l[1]||{},a=2),1b u!="25"&&!v.1v(u)&&(u={}),f===a&&(u=15,--a);1a(;a<f;a++)if((e=1l[a])!=19)1a(n in e){r=u[n],i=e[n];if(u===i)4m;l&&i&&(v.5w(i)||(s=v.2O(i)))?(s?(s=!1,o=r&&v.2O(r)?r:[]):o=r&&v.5w(r)?r:{},u[n]=v.1o(l,o,i)):i!==t&&(u[n]=i)}14 u},v.1o({gm:12(t){14 e.$===v&&(e.$=a),t&&e.3w===v&&(e.3w=u),v},6B:!1,7i:1,gp:12(e){e?v.7i++:v.2q(!0)},2q:12(e){if(e===!0?--v.7i:v.6B)14;if(!i.2r)14 4p(v.2q,1);v.6B=!0;if(e!==!0&&--v.7i>0)14;r.4s(i,[v]),v.fn.2l&&v(i).2l("2q").3g("2q")},1v:12(e){14 v.1e(e)==="12"},2O:3X.2O||12(e){14 v.1e(e)==="i7"},3U:12(e){14 e!=19&&e==e.9k},86:12(e){14!bW(2b(e))&&i6(e)},1e:12(e){14 e==19?5W(e):O[h.1f(e)]||"25"},5w:12(e){if(!e||v.1e(e)!=="25"||e.1d||v.3U(e))14!1;1Q{if(e.3v&&!p.1f(e,"3v")&&!p.1f(e.3v.29,"hZ"))14!1}1U(n){14!1}17 r;1a(r in e);14 r===t||p.1f(e,r)},6b:12(e){17 t;1a(t in e)14!1;14!0},2c:12(e){8X 1n 9t(e)},aG:12(e,t,n){17 r;14!e||1b e!="1q"?19:(1b t=="3j"&&(n=t,t=0),t=t||i,(r=E.1T(e))?[t.1X(r[1])]:(r=v.7Q([e],t,n?19:[]),v.34([],(r.8q?v.4D(r.7k):r.7k).3s)))},9r:12(t){if(!t||1b t!="1q")14 19;t=v.37(t);if(e.7p&&e.7p.aH)14 e.7p.aH(t);if(S.1c(t.1p(T,"@").1p(N,"]").1p(x,"")))14(1n aj("14 "+t))();v.2c("az 7p: "+t)},bJ:12(n){17 r,i;if(!n||1b n!="1q")14 19;1Q{e.aD?(i=1n aD,r=i.ih(n,"1F/3D")):(r=1n 7e("aC.ig"),r.3r="6C",r.ix(n))}1U(s){r=t}14(!r||!r.2E||r.1O("ay").18)&&v.2c("az iw: "+n),r},8F:12(){},8R:12(t){t&&g.1c(t)&&(e.hq||12(t){e.hj.1f(e,t)})(t)},45:12(e){14 e.1p(C,"7M-").1p(k,L)},1m:12(e,t){14 e.1m&&e.1m.1w()===t.1w()},1h:12(e,n,r){17 i,s=0,o=e.18,u=o===t||v.1v(e);if(r){if(u){1a(i in e)if(n.1A(e[i],r)===!1)2f}1x 1a(;s<o;)if(n.1A(e[s++],r)===!1)2f}1x if(u){1a(i in e)if(n.1f(e[i],i,e[i])===!1)2f}1x 1a(;s<o;)if(n.1f(e[s],s,e[s++])===!1)2f;14 e},37:d&&!d.1f("\\hh\\hg")?12(e){14 e==19?"":d.1f(e)}:12(e){14 e==19?"":(e+"").1p(b,"")},4z:12(e,t){17 n,r=t||[];14 e!=19&&(n=v.1e(e),e.18==19||n==="1q"||n==="12"||n==="hL"||v.3U(e)?f.1f(r,e):v.34(r,e)),r},3R:12(e,t,n){17 r;if(t){if(c)14 c.1f(t,e,n);r=t.18,n=n?n<0?3O.6E(0,r+n):n:0;1a(;n<r;n++)if(n in t&&t[n]===e)14 n}14-1},34:12(e,n){17 r=n.18,i=e.18,s=0;if(1b r=="3a")1a(;s<r;s++)e[i++]=n[s];1x 1t(n[s]!==t)e[i++]=n[s++];14 e.18=i,e},4c:12(e,t,n){17 r,i=[],s=0,o=e.18;n=!!n;1a(;s<o;s++)r=!!t(e[s],s),n!==r&&i.1k(e[s]);14 i},2N:12(e,n,r){17 i,s,o=[],u=0,a=e.18,f=e 5o v||a!==t&&1b a=="3a"&&(a>0&&e[0]&&e[a-1]||a===0||v.2O(e));if(f)1a(;u<a;u++)i=n(e[u],u,r),i!=19&&(o[o.18]=i);1x 1a(s in e)i=n(e[s],s,r),i!=19&&(o[o.18]=i);14 o.4f.1A([],o)},1J:1,hJ:12(e,n){17 r,i,s;14 1b n=="1q"&&(r=e[n],n=e,e=r),v.1v(e)?(i=l.1f(1l,2),s=12(){14 e.1A(n,i.4f(l.1f(1l)))},s.1J=e.1J=e.1J||v.1J++,s):t},3n:12(e,n,r,i,s,o,u){17 a,f=r==19,l=0,c=e.18;if(r&&1b r=="25"){1a(l in r)v.3n(e,n,l,r[l],1,o,i);s=1}1x if(i!==t){a=u===t&&v.1v(i),f&&(a?(a=n,n=12(e,t,n){14 a.1f(v(e),n)}):(n.1f(e,i),n=19));if(n)1a(;l<c;l++)n(e[l],r,a?i.1f(e[l],l,n(e[l],r)):i,u);s=1}14 s?e:f?n.1f(e):c?n(e[0],r):o},2Y:12(){14(1n 7I).bU()}}),v.2q.2k=12(t){if(!r){r=v.4k();if(i.3h==="3f")4p(v.2q,1);1x if(i.41)i.41("aA",A,!1),e.41("5T",v.2q,!1);1x{i.4N("5c",A),e.4N("7n",v.2q);17 n=!1;1Q{n=e.eb==19&&i.2E}1U(s){}n&&n.aB&&12 o(){if(!v.6B){1Q{n.aB("1H")}1U(e){14 4p(o,50)}v.2q()}}()}}14 r.2k(t)},v.1h("e4 ef 5W aj 3X 7I 1L 81".1M(" "),12(e,t){O["[25 "+t+"]"]=t.1w()}),n=v(i);17 M={};v.5m=12(e){e=1b e=="1q"?M[e]||4Q(e):v.1o({},e);17 n,r,i,s,o,u,a=[],f=!e.5O&&[],l=12(t){n=e.5i&&t,r=!0,u=s||0,s=0,o=a.18,i=!0;1a(;a&&u<o;u++)if(a[u].1A(t[0],t[1])===!1&&e.dB){n=!1;2f}i=!1,a&&(f?f.18&&l(f.3y()):n?a=[]:c.6p())},c={27:12(){if(a){17 t=a.18;(12 r(t){v.1h(t,12(t,n){17 i=v.1e(n);i==="12"?(!e.5R||!c.6m(n))&&a.1k(n):n&&n.18&&i!=="1q"&&r(n)})})(1l),i?o=a.18:n&&(s=t,l(n))}14 15},2G:12(){14 a&&v.1h(1l,12(e,t){17 n;1t((n=v.3R(t,a,n))>-1)a.2B(n,1),i&&(n<=o&&o--,n<=u&&u--)}),15},6m:12(e){14 v.3R(e,a)>-1},2M:12(){14 a=[],15},6p:12(){14 a=f=n=t,15},2w:12(){14!a},a4:12(){14 f=t,n||c.6p(),15},dA:12(){14!f},6j:12(e,t){14 t=t||[],t=[e,t.1D?t.1D():t],a&&(!r||f)&&(i?f.1k(t):l(t)),15},5b:12(){14 c.6j(15,1l),15},ds:12(){14!!r}};14 c},v.1o({4k:12(e){17 t=[["ai","2W",v.5m("5O 5i"),"dk"],["7P","4l",v.5m("5O 5i"),"dU"],["a2","54",v.5m("5i")]],n="dF",r={6i:12(){14 n},44:12(){14 i.2W(1l).4l(1l),15},a3:12(){17 e=1l;14 v.4k(12(n){v.1h(t,12(t,r){17 s=r[0],o=e[t];i[r[1]](v.1v(o)?12(){17 e=o.1A(15,1l);e&&v.1v(e.2k)?e.2k().2W(n.ai).4l(n.7P).54(n.a2):n[s+"6u"](15===i?n:15,[e])}:n[s])}),e=19}).2k()},2k:12(e){14 e!=19?v.1o(e,r):r}},i={};14 r.fD=r.a3,v.1h(t,12(e,s){17 o=s[2],u=s[3];r[s[1]]=o.27,u&&o.27(12(){n=u},t[e^1][2].6p,t[2][2].a4),i[s[0]]=o.5b,i[s[0]+"6u"]=o.6j}),r.2k(i),e&&e.1f(i,i),i},eN:12(e){17 t=0,n=l.1f(1l),r=n.18,i=r!==1||e&&v.1v(e.2k)?r:0,s=i===1?e:v.4k(),o=12(e,t,n){14 12(r){t[e]=15,n[e]=1l.18>1?l.1f(1l):r,n===u?s.a5(t,n):--i||s.4s(t,n)}},u,a,f;if(r>1){u=1n 3X(r),a=1n 3X(r),f=1n 3X(r);1a(;t<r;t++)n[t]&&v.1v(n[t].2k)?n[t].2k().2W(o(t,f,n)).4l(s.7P).54(o(t,a,u)):--i}14 i||s.4s(f,n),s.2k()}}),v.1s=12(){17 t,n,r,s,o,u,a,f,l,c,h,p=i.1X("2a");p.3m("23","t"),p.1Z="  <7m/><2i></2i><a 2X=\'/a\'>a</a><1Y 1e=\'3Y\'/>",n=p.1O("*"),r=p.1O("a")[0];if(!n||!r||!n.18)14{};s=i.1X("2v"),o=s.2p(i.1X("3e")),u=p.1O("1Y")[0],r.1g.3E="1K:4h;7G:1H;2n:.5",t={8O:p.1N.1d===3,2I:!p.1O("2I").18,8y:!!p.1O("7m").18,1g:/1K/.1c(r.22("1g")),ac:r.22("2X")==="/a",2n:/^0.5/.1c(r.1g.2n),7l:!!r.1g.7l,a9:u.1z==="2g",a8:o.2K,9X:p.23!=="t",78:!!i.1X("55").78,6T:i.1X("7j").5h(!0).74!=="<:7j></:7j>",f0:i.eI==="fg",bx:!0,bq:!0,bl:!1,7d:!0,9a:!0,9n:!1,9h:!1,84:!0,9p:!0,7Y:!1},u.21=!0,t.dc=u.5h(!0).21,s.2w=!0,t.ae=!o.2w;1Q{24 p.1c}1U(d){t.7d=!1}!p.41&&p.4N&&p.a1&&(p.4N("85",h=12(){t.9a=!1}),p.5h(!0).a1("85"),p.79("85",h)),u=i.1X("1Y"),u.1z="t",u.3m("1e","3N"),t.af=u.1z==="t",u.3m("21","21"),u.3m("2Q","t"),p.2p(u),a=i.6t(),a.2p(p.5p),t.7Z=a.5h(!0).5h(!0).5p.21,t.db=u.21,a.2R(u),a.2p(p);if(p.4N)1a(l in{4C:!0,58:!0,6W:!0})f="2g"+l,c=f in p,c||(p.3m(f,"14;"),c=1b p[f]=="12"),t[l+"ec"]=c;14 v(12(){17 n,r,s,o,u="3q:0;3o:0;2s:0;1P:4x;3c:2x;",a=i.1O("2r")[0];if(!a)14;n=i.1X("2a"),n.1g.3E="dg:2x;2s:0;1W:0;3F:0;3i:7O;1K:0;3o-1K:4h",a.3G(n,a.1N),r=i.1X("2a"),n.2p(r),r.1Z="<2i><3u><4q></4q><4q>t</4q></3u></2i>",s=r.1O("4q"),s[0].1g.3E="3q:0;3o:0;2s:0;1P:39",c=s[0].6h===0,s[0].1g.1P="",s[1].1g.1P="39",t.ce=c&&s[0].6h===0,r.1Z="",r.1g.3E="3W-9i:2s-3W;-eh-3W-9i:2s-3W;-6P-3W-9i:2s-3W;3q:4h;2s:4h;1P:4x;1W:9o;3o-1K:1%;3i:7L;1K:1%;",t.5s=r.4v===4,t.bX=a.d4!==1,e.4X&&(t.7Y=(e.4X(r,19)||{}).1K!=="1%",t.9p=(e.4X(r,19)||{1W:"9o"}).1W==="9o",o=i.1X("2a"),o.1g.3E=r.1g.3E=u,o.1g.6s=o.1g.1W="0",r.1g.1W="4h",r.2p(o),t.84=!2b((e.4X(o,19)||{}).6s)),1b r.1g.5n!="2S"&&(r.1Z="",r.1g.3E=u+"1W:4h;3q:4h;1P:5u;5n:1",t.9n=r.4v===3,r.1g.1P="4x",r.1g.3c="cb",r.1Z="<2a></2a>",r.1N.1g.1W="fd",t.9h=r.4v!==3,n.1g.5n=1),a.2R(n),n=r=s=o=19}),a.2R(p),n=r=s=o=u=a=p=19,t}();17 D=/(?:\\{[\\s\\S]*\\}|\\[[\\s\\S]*\\])$/,P=/([A-Z])/g;v.1o({3H:{},9c:[],eZ:0,2U:"3w"+(v.fn.5g+3O.aP()).1p(/\\D/g,""),9W:{8S:!0,25:"fv:f3-f4-f5-f2-f1",eY:!0},8W:12(e){14 e=e.1d?v.3H[e[v.2U]]:e[v.2U],!!e&&!B(e)},1r:12(e,n,r,i){if(!v.56(e))14;17 s,o,u=v.2U,a=1b n=="1q",f=e.1d,l=f?v.3H:e,c=f?e[u]:e[u]&&u;if((!c||!l[c]||!i&&!l[c].1r)&&a&&r===t)14;c||(f?e[u]=c=v.9c.5r()||v.1J++:c=u),l[c]||(l[c]={},f||(l[c].a0=v.8F));if(1b n=="25"||1b n=="12")i?l[c]=v.1o(l[c],n):l[c].1r=v.1o(l[c].1r,n);14 s=l[c],i||(s.1r||(s.1r={}),s=s.1r),r!==t&&(s[v.45(n)]=r),a?(o=s[n],o==19&&(o=s[v.45(n)])):o=s,o},4R:12(e,t,n){if(!v.56(e))14;17 r,i,s,o=e.1d,u=o?v.3H:e,a=o?e[v.2U]:v.2U;if(!u[a])14;if(t){r=n?u[a]:u[a].1r;if(r){v.2O(t)||(t in r?t=[t]:(t=v.45(t),t in r?t=[t]:t=t.1M(" ")));1a(i=0,s=t.18;i<s;i++)24 r[t[i]];if(!(n?B:v.6b)(r))14}}if(!n){24 u[a].1r;if(!B(u[a]))14}o?v.51([e],!0):v.1s.7d||u!=u.9k?24 u[a]:u[a]=19},1B:12(e,t,n){14 v.1r(e,t,n,!0)},56:12(e){17 t=e.1m&&v.9W[e.1m.1w()];14!t||t!==!0&&e.22("f7")===t}}),v.fn.1o({1r:12(e,n){17 r,i,s,o,u,a=15[0],f=0,l=19;if(e===t){if(15.18){l=v.1r(a);if(a.1d===1&&!v.1B(a,"9V")){s=a.a7;1a(u=s.18;f<u;f++)o=s[f].2Q,o.2e("1r-")||(o=v.45(o.9A(5)),H(a,o,l[o]));v.1B(a,"9V",!0)}}14 l}14 1b e=="25"?15.1h(12(){v.1r(15,e)}):(r=e.1M(".",2),r[1]=r[1]?"."+r[1]:"",i=r[1]+"!",v.3n(15,12(n){if(n===t)14 l=15.75("aa"+i,[r[0]]),l===t&&a&&(l=v.1r(a,e),l=H(a,e,l)),l===t&&r[1]?15.1r(r[0]):l;r[1]=n,15.1h(12(){17 t=v(15);t.75("ab"+i,r),v.1r(15,e,n),t.75("aI"+i,r)})},19,n,1l.18>1,19,!1))},4R:12(e){14 15.1h(12(){v.4R(15,e)})}}),v.1o({1V:12(e,t,n){17 r;if(e)14 t=(t||"fx")+"1V",r=v.1B(e,t),n&&(!r||v.2O(n)?r=v.1B(e,t,v.4z(n)):r.1k(n)),r||[]},4n:12(e,t){t=t||"fx";17 n=v.1V(e,t),r=n.18,i=n.3y(),s=v.6G(e,t),o=12(){v.4n(e,t)};i==="9f"&&(i=n.3y(),r--),i&&(t==="fx"&&n.3d("9f"),24 s.30,i.1f(e,o,s)),!r&&s&&s.2M.5b()},6G:12(e,t){17 n=t+"6q";14 v.1B(e,n)||v.1B(e,n,{2M:v.5m("5O 5i").27(12(){v.4R(e,t+"1V",!0),v.4R(e,n,!0)})})}}),v.fn.1o({1V:12(e,n){17 r=2;14 1b e!="1q"&&(n=e,e="fx",r--),1l.18<r?v.1V(15[0],e):n===t?15:15.1h(12(){17 t=v.1V(15,e,n);v.6G(15,e),e==="fx"&&t[0]!=="9f"&&v.4n(15,e)})},4n:12(e){14 15.1h(12(){v.4n(15,e)})},fb:12(e,t){14 e=v.fx?v.fx.68[e]||e:e,t=t||"fx",15.1V(t,12(t,n){17 r=4p(t,e);n.30=12(){bR(r)}})},f8:12(e){14 15.1V(e||"fx",[])},2k:12(e,n){17 r,i=1,s=v.4k(),o=15,u=15.18,a=12(){--i||s.4s(o,[o])};1b e!="1q"&&(n=e,e=t),e=e||"fx";1t(u--)r=v.1B(o[u],e+"6q"),r&&r.2M&&(i++,r.2M.27(a));14 a(),s.2k(n)}});17 j,F,I,q=/[\\t\\r\\n]/g,R=/\\r/g,U=/^(?:2z|1Y)$/i,z=/^(?:2z|1Y|25|2v|5x)$/i,W=/^a(?:f9|)$/i,X=/^(?:fa|eX|3r|21|eW|eK|2w|2x|eL|6f|8t|82|eM|eJ|2K)$/i,V=v.1s.9X;v.fn.1o({3Q:12(e,t){14 v.3n(15,v.3Q,e,t,1l.18>1)},64:12(e){14 15.1h(12(){v.64(15,e)})},28:12(e,t){14 v.3n(15,v.28,e,t,1l.18>1)},eF:12(e){14 e=v.52[e]||e,15.1h(12(){1Q{15[e]=t,24 15[e]}1U(n){}})},9C:12(e){17 t,n,r,i,s,o,u;if(v.1v(e))14 15.1h(12(t){v(15).9C(e.1f(15,t,15.23))});if(e&&1b e=="1q"){t=e.1M(y);1a(n=0,r=15.18;n<r;n++){i=15[n];if(i.1d===1)if(!i.23&&t.18===1)i.23=e;1x{s=" "+i.23+" ";1a(o=0,u=t.18;o<u;o++)s.2e(" "+t[o]+" ")<0&&(s+=t[o]+" ");i.23=v.37(s)}}}14 15},9B:12(e){17 n,r,i,s,o,u,a;if(v.1v(e))14 15.1h(12(t){v(15).9B(e.1f(15,t,15.23))});if(e&&1b e=="1q"||e===t){n=(e||"").1M(y);1a(u=0,a=15.18;u<a;u++){i=15[u];if(i.1d===1&&i.23){r=(" "+i.23+" ").1p(q," ");1a(s=0,o=n.18;s<o;s++)1t(r.2e(" "+n[s]+" ")>=0)r=r.1p(" "+n[s]+" "," ");i.23=e?v.37(r):""}}}14 15},9Y:12(e,t){17 n=1b e,r=1b t=="3j";14 v.1v(e)?15.1h(12(n){v(15).9Y(e.1f(15,n,15.23,t),t)}):15.1h(12(){if(n==="1q"){17 i,s=0,o=v(15),u=t,a=e.1M(y);1t(i=a[s++])u=r?u:!o.a6(i),o[u?"9C":"9B"](i)}1x if(n==="2S"||n==="3j")15.23&&v.1B(15,"9Z",15.23),15.23=15.23||e===!1?"":v.1B(15,"9Z")||""})},a6:12(e){17 t=" "+e+" ",n=0,r=15.18;1a(;n<r;n++)if(15[n].1d===1&&(" "+15[n].23+" ").1p(q," ").2e(t)>=0)14!0;14!1},5a:12(e){17 n,r,i,s=15[0];if(!1l.18){if(s)14 n=v.3J[s.1e]||v.3J[s.1m.1w()],n&&"1u"in n&&(r=n.1u(s,"1z"))!==t?r:(r=s.1z,1b r=="1q"?r.1p(R,""):r==19?"":r);14}14 i=v.1v(e),15.1h(12(r){17 s,o=v(15);if(15.1d!==1)14;i?s=e.1f(15,r,o.5a()):s=e,s==19?s="":1b s=="3a"?s+="":v.2O(s)&&(s=v.2N(s,12(e){14 e==19?"":e+""})),n=v.3J[15.1e]||v.3J[15.1m.1w()];if(!n||!("1G"in n)||n.1G(15,s,"1z")===t)15.1z=s})}}),v.1o({3J:{3e:{1u:12(e){17 t=e.a7.1z;14!t||t.6Q?e.1z:e.1F}},2v:{1u:12(e){17 t,n,r=e.4O,i=e.5y,s=e.1e==="2v-bm"||i<0,o=s?19:[],u=s?i+1:r.18,a=i<0?u:s?i:0;1a(;a<u;a++){n=r[a];if((n.2K||a===i)&&(v.1s.ae?!n.2w:n.22("2w")===19)&&(!n.1i.2w||!v.1m(n.1i,"9F"))){t=v(n).5a();if(s)14 t;o.1k(t)}}14 o},1G:12(e,t){17 n=v.4z(t);14 v(e).2d("3e").1h(12(){15.2K=v.3R(v(15).5a(),n)>=0}),n.18||(e.5y=-1),n}}},eS:{},3Q:12(e,n,r,i){17 s,o,u,a=e.1d;if(!e||a===3||a===8||a===2)14;if(i&&v.1v(v.fn[n]))14 v(e)[n](r);if(1b e.22=="2S")14 v.28(e,n,r);u=a!==1||!v.67(e),u&&(n=n.1w(),o=v.4d[n]||(X.1c(n)?F:j));if(r!==t){if(r===19){v.64(e,n);14}14 o&&"1G"in o&&u&&(s=o.1G(e,r,n))!==t?s:(e.3m(n,r+""),r)}14 o&&"1u"in o&&u&&(s=o.1u(e,n))!==19?s:(s=e.22(n),s===19?t:s)},64:12(e,t){17 n,r,i,s,o=0;if(t&&e.1d===1){r=t.1M(y);1a(;o<r.18;o++)i=r[o],i&&(n=v.52[i]||i,s=X.1c(i),s||v.3Q(e,i,""),e.4J(V?i:n),s&&n in e&&(e[n]=!1))}},4d:{1e:{1G:12(e,t){if(U.1c(e.1m)&&e.1i)v.2c("1e eP eQ\'t be ff");1x if(!v.1s.af&&t==="3N"&&v.1m(e,"1Y")){17 n=e.1z;14 e.3m("1e",t),n&&(e.1z=n),t}}},1z:{1u:12(e,t){14 j&&v.1m(e,"2z")?j.1u(e,t):t in e?e.1z:19},1G:12(e,t,n){if(j&&v.1m(e,"2z"))14 j.1G(e,t,n);e.1z=t}}},52:{ah:"8c",82:"fK","1a":"fL","73":"23",fI:"fH",fF:"fG",fM:"fN",fT:"fU",fS:"fR",fO:"fP",fQ:"ag",ad:"fC"},28:12(e,n,r){17 i,s,o,u=e.1d;if(!e||u===3||u===8||u===2)14;14 o=u!==1||!v.67(e),o&&(n=v.52[n]||n,s=v.33[n]),r!==t?s&&"1G"in s&&(i=s.1G(e,r,n))!==t?i:e[n]=r:s&&"1u"in s&&(i=s.1u(e,n))!==19?i:e[n]},33:{8c:{1u:12(e){17 n=e.3B("ah");14 n&&n.6Q?fm(n.1z,10):z.1c(e.1m)||W.1c(e.1m)&&e.2X?0:t}}}}),F={1u:12(e,n){17 r,i=v.28(e,n);14 i===!0||1b i!="3j"&&(r=e.3B(n))&&r.aK!==!1?n.1w():t},1G:12(e,t,n){17 r;14 t===!1?v.64(e,n):(r=v.52[n]||n,r in e&&(e[r]=!0),e.3m(n,n.1w())),n}},V||(I={2Q:!0,id:!0,fl:!0},j=v.3J.2z={1u:12(e,n){17 r;14 r=e.3B(n),r&&(I[n]?r.1z!=="":r.6Q)?r.1z:t},1G:12(e,t,n){17 r=e.3B(n);14 r||(r=i.fk(n),e.fh(r)),r.1z=t+""}},v.1h(["1W","3F"],12(e,t){v.4d[t]=v.1o(v.4d[t],{1G:12(e,n){if(n==="")14 e.3m(t,"7y"),n}})}),v.4d.ad={1u:j.1u,1G:12(e,t,n){t===""&&(t="6C"),j.1G(e,t,n)}}),v.1s.ac||v.1h(["2X","7D","1W","3F"],12(e,n){v.4d[n]=v.1o(v.4d[n],{1u:12(e){17 r=e.22(n,2);14 r===19?t:r}})}),v.1s.1g||(v.4d.1g={1u:12(e){14 e.1g.3E.1w()||t},1G:12(e,t){14 e.1g.3E=t+""}}),v.1s.a8||(v.33.2K=v.1o(v.33.2K,{1u:12(e){17 t=e.1i;14 t&&(t.5y,t.1i&&t.1i.5y),19}})),v.1s.78||(v.52.78="fs"),v.1s.a9||v.1h(["3N","3Y"],12(){v.3J[15]={1u:12(e){14 e.22("1z")===19?"2g":e.1z}}}),v.1h(["3N","3Y"],12(){v.3J[15]=v.1o(v.3J[15],{1G:12(e,t){if(v.2O(t))14 e.21=v.3R(v(e).5a(),t)>=0}})});17 $=/^(?:5x|1Y|2v)$/i,J=/^([^\\.]*|)(?:\\.(.+)|)$/,K=/(?:^|\\s)9b(\\.\\S+|)\\b/,Q=/^bc/,G=/^(?:fV|aR)|49/,Y=/^(?:eo|dJ)$/,Z=12(e){14 v.1j.2F.9b?e:e.1p(K,"6Z$1 70$1")};v.1j={27:12(e,n,r,i,s){17 o,u,a,f,l,c,h,p,d,m,g;if(e.1d===3||e.1d===8||!n||!r||!(o=v.1B(e)))14;r.3P&&(d=r,r=d.3P,s=d.1R),r.1J||(r.1J=v.1J++),a=o.31,a||(o.31=a={}),u=o.2H,u||(o.2H=u=12(e){14 1b v=="2S"||!!e&&v.1j.6k===e.1e?t:v.1j.7g.1A(u.1E,1l)},u.1E=e),n=v.37(Z(n)).1M(" ");1a(f=0;f<n.18;f++){l=J.1T(n[f])||[],c=l[1],h=(l[2]||"").1M(".").4r(),g=v.1j.2F[c]||{},c=(s?g.4W:g.7s)||c,g=v.1j.2F[c]||{},p=v.1o({1e:c,5k:l[1],1r:i,3P:r,1J:r.1J,1R:s,5q:s&&v.2D.7X.5q.1c(s),3k:h.2t(".")},d),m=a[c];if(!m){m=a[c]=[],m.6A=0;if(!g.57||g.57.1f(e,i,h,u)===!1)e.41?e.41(c,u,!1):e.4N&&e.4N("2g"+c,u)}g.27&&(g.27.1f(e,p),p.3P.1J||(p.3P.1J=r.1J)),s?m.2B(m.6A++,0,p):m.1k(p),v.1j.4y[c]=!0}e=19},4y:{},2G:12(e,t,n,r,i){17 s,o,u,a,f,l,c,h,p,d,m,g=v.8W(e)&&v.1B(e);if(!g||!(h=g.31))14;t=v.37(Z(t||"")).1M(" ");1a(s=0;s<t.18;s++){o=J.1T(t[s])||[],u=a=o[1],f=o[2];if(!u){1a(u in h)v.1j.2G(e,u+t[s],n,r,!0);4m}p=v.1j.2F[u]||{},u=(r?p.4W:p.7s)||u,d=h[u]||[],l=d.18,f=f?1n 1L("(^|\\\\.)"+f.1M(".").4r().2t("\\\\.(?:.*\\\\.|)")+"(\\\\.|$)"):19;1a(c=0;c<d.18;c++)m=d[c],(i||a===m.5k)&&(!n||n.1J===m.1J)&&(!f||f.1c(m.3k))&&(!r||r===m.1R||r==="**"&&m.1R)&&(d.2B(c--,1),m.1R&&d.6A--,p.2G&&p.2G.1f(e,m));d.18===0&&l!==d.18&&((!p.5j||p.5j.1f(e,f,g.2H)===!1)&&v.9D(e,u,g.2H),24 h[u])}v.6b(h)&&(24 g.2H,v.4R(e,"31",!0))},aJ:{aa:!0,ab:!0,aI:!0},2l:12(n,r,s,o){if(!s||s.1d!==3&&s.1d!==8){17 u,a,f,l,c,h,p,d,m,g,y=n.1e||n,b=[];if(Y.1c(y+v.1j.6k))14;y.2e("!")>=0&&(y=y.1D(0,-1),a=!0),y.2e(".")>=0&&(b=y.1M("."),y=b.3y(),b.4r());if((!s||v.1j.aJ[y])&&!v.1j.4y[y])14;n=1b n=="25"?n[v.2U]?n:1n v.3Z(y,n):1n v.3Z(y),n.1e=y,n.5N=!0,n.bh=a,n.3k=b.2t("."),n.8Q=n.3k?1n 1L("(^|\\\\.)"+b.2t("\\\\.(?:.*\\\\.|)")+"(\\\\.|$)"):19,h=y.2e(":")<0?"2g"+y:"";if(!s){u=v.3H;1a(f in u)u[f].31&&u[f].31[y]&&v.1j.2l(n,r,u[f].2H.1E,!0);14}n.7o=t,n.2j||(n.2j=s),r=r!=19?v.4z(r):[],r.3d(n),p=v.1j.2F[y]||{};if(p.2l&&p.2l.1A(s,r)===!1)14;m=[[s,p.7s||y]];if(!o&&!p.bu&&!v.3U(s)){g=p.4W||y,l=Y.1c(g+y)?s:s.1i;1a(c=s;l;l=l.1i)m.1k([l,g]),c=l;c===(s.2h||i)&&m.1k([c.bf||c.bg||e,g])}1a(f=0;f<m.18&&!n.7B();f++)l=m[f][0],n.1e=m[f][1],d=(v.1B(l,"31")||{})[n.1e]&&v.1B(l,"2H"),d&&d.1A(l,r),d=h&&l[h],d&&v.56(l)&&d.1A&&d.1A(l,r)===!1&&n.3S();14 n.1e=y,!o&&!n.5Y()&&(!p.3t||p.3t.1A(s.2h,r)===!1)&&(y!=="49"||!v.1m(s,"a"))&&v.56(s)&&h&&s[y]&&(y!=="4Z"&&y!=="6X"||n.2j.4v!==0)&&!v.3U(s)&&(c=s[h],c&&(s[h]=19),v.1j.6k=y,s[y](),v.1j.6k=t,c&&(s[h]=c)),n.7o}14},7g:12(n){n=v.1j.9u(n||e.1j);17 r,i,s,o,u,a,f,c,h,p,d=(v.1B(15,"31")||{})[n.1e]||[],m=d.6A,g=l.1f(1l),y=!n.bh&&!n.3k,b=v.1j.2F[n.1e]||{},w=[];g[0]=n,n.bo=15;if(b.bi&&b.bi.1f(15,n)===!1)14;if(m&&(!n.2z||n.1e!=="49"))1a(s=n.2j;s!=15;s=s.1i||15)if(s.2w!==!0||n.1e!=="49"){u={},f=[];1a(r=0;r<m;r++)c=d[r],h=c.1R,u[h]===t&&(u[h]=c.5q?v(h,15).7f(s)>=0:v.2d(h,15,19,[s]).18),u[h]&&f.1k(c);f.18&&w.1k({1E:s,3l:f})}d.18>m&&w.1k({1E:15,3l:d.1D(m)});1a(r=0;r<w.18&&!n.7B();r++){a=w[r],n.bd=a.1E;1a(i=0;i<a.3l.18&&!n.8u();i++){c=a.3l[i];if(y||!n.3k&&!c.3k||n.8Q&&n.8Q.1c(c.3k))n.1r=c.1r,n.5Q=c,o=((v.1j.2F[c.5k]||{}).2H||c.3P).1A(a.1E,g),o!==t&&(n.7o=o,o===!1&&(n.3S(),n.5Z()))}}14 b.8J&&b.8J.1f(15,n),n.7o},3K:"dD dV dz bk dm dp dl dr bd di 8z 7w dj 2j 8w dq 61".1M(" "),6K:{},aS:{3K:"dC 93 bc b8".1M(" "),1C:12(e,t){14 e.61==19&&(e.61=t.93!=19?t.93:t.b8),e}},aT:{3K:"2z dy 92 bb b9 dx du 95 ba dv dw bj".1M(" "),1C:12(e,n){17 r,s,o,u=n.2z,a=n.b9;14 e.95==19&&n.92!=19&&(r=e.2j.2h||i,s=r.2E,o=r.2r,e.95=n.92+(s&&s.4V||o&&o.4V||0)-(s&&s.7h||o&&o.7h||0),e.ba=n.bb+(s&&s.53||o&&o.53||0)-(s&&s.6r||o&&o.6r||0)),!e.7w&&a&&(e.7w=a===e.2j?n.bj:a),!e.61&&u!==t&&(e.61=u&1?1:u&2?3:u&4?2:0),e}},9u:12(e){if(e[v.2U])14 e;17 t,n,r=e,s=v.1j.6K[e.1e]||{},o=s.3K?15.3K.4f(s.3K):15.3K;e=v.3Z(r);1a(t=o.18;t;)n=o[--t],e[n]=r[n];14 e.2j||(e.2j=r.bk||i),e.2j.1d===3&&(e.2j=e.2j.1i),e.8z=!!e.8z,s.1C?s.1C(e,r):e},2F:{5T:{bu:!0},4Z:{4W:"6W"},6X:{4W:"8r"},eA:{57:12(e,t,n){v.3U(15)&&(15.8x=n)},5j:12(e,t){15.8x===t&&(15.8x=19)}}},5I:12(e,t,n,r){17 i=v.1o(1n v.3Z,n,{1e:e,9e:!0,5G:{}});r?v.1j.2l(i,19,t):v.1j.7g.1f(t,i),i.5Y()&&n.3S()}},v.1j.2H=v.1j.7g,v.9D=i.5P?12(e,t,n){e.5P&&e.5P(t,n,!1)}:12(e,t,n){17 r="2g"+t;e.79&&(1b e[r]=="2S"&&(e[r]=19),e.79(r,n))},v.3Z=12(e,t){if(!(15 5o v.3Z))14 1n v.3Z(e,t);e&&e.1e?(15.5G=e,15.1e=e.1e,15.5Y=e.ev||e.bw===!1||e.bv&&e.bv()?5f:et):15.1e=e,t&&v.1o(15,t),15.8w=e&&e.8w||v.2Y(),15[v.2U]=!0},v.3Z.29={3S:12(){15.5Y=5f;17 e=15.5G;if(!e)14;e.3S?e.3S():e.bw=!1},5Z:12(){15.7B=5f;17 e=15.5G;if(!e)14;e.5Z&&e.5Z(),e.e2=!0},e3:12(){15.8u=5f,15.5Z()},5Y:et,7B:et,8u:et},v.1h({6Z:"b7",70:"b6"},12(e,t){v.1j.2F[e]={4W:t,7s:t,2H:12(e){17 n,r=15,i=e.7w,s=e.5Q,o=s.1R;if(!i||i!==r&&!v.2P(r,i))e.1e=s.5k,n=s.3P.1A(15,1l),e.1e=t;14 n}}}),v.1s.bx||(v.1j.2F.4C={57:12(){if(v.1m(15,"55"))14!1;v.1j.27(15,"49.6x aQ.6x",12(e){17 n=e.2j,r=v.1m(n,"1Y")||v.1m(n,"2z")?n.55:t;r&&!v.1B(r,"bs")&&(v.1j.27(r,"4C.6x",12(e){e.8K=!0}),v.1B(r,"bs",!0))})},8J:12(e){e.8K&&(24 e.8K,15.1i&&!e.5N&&v.1j.5I("4C",15.1i,e,!0))},5j:12(){if(v.1m(15,"55"))14!1;v.1j.2G(15,".6x")}}),v.1s.bq||(v.1j.2F.58={57:12(){if($.1c(15.1m)){if(15.1e==="3Y"||15.1e==="3N")v.1j.27(15,"e8.5M",12(e){e.5G.e9==="21"&&(15.8D=!0)}),v.1j.27(15,"49.5M",12(e){15.8D&&!e.5N&&(15.8D=!1),v.1j.5I("58",15,e,!0)});14!1}v.1j.27(15,"gq.5M",12(e){17 t=e.2j;$.1c(t.1m)&&!v.1B(t,"9U")&&(v.1j.27(t,"58.5M",12(e){15.1i&&!e.9e&&!e.5N&&v.1j.5I("58",15.1i,e,!0)}),v.1B(t,"9U",!0))})},2H:12(e){17 t=e.2j;if(15!==t||e.9e||e.5N||t.1e!=="3N"&&t.1e!=="3Y")14 e.5Q.3P.1A(15,1l)},5j:12(){14 v.1j.2G(15,".5M"),!$.1c(15.1m)}}),v.1s.bl||v.1h({4Z:"6W",6X:"8r"},12(e,t){17 n=0,r=12(e){v.1j.5I(t,e.2j,v.1j.9u(e),!0)};v.1j.2F[t]={57:12(){n++===0&&i.41(e,r,!0)},5j:12(){--n===0&&i.5P(e,r,!0)}}}),v.fn.1o({2g:12(e,n,r,i,s){17 o,u;if(1b e=="25"){1b n!="1q"&&(r=r||n,n=t);1a(u in e)15.2g(u,n,r,e[u],s);14 15}r==19&&i==19?(i=n,r=n=t):i==19&&(1b n=="1q"?(i=r,r=t):(i=r,r=n,n=t));if(i===!1)i=et;1x if(!i)14 15;14 s===1&&(o=i,i=12(e){14 v().3g(e),o.1A(15,1l)},i.1J=o.1J||(o.1J=v.1J++)),15.1h(12(){v.1j.27(15,e,i,r,n)})},bm:12(e,t,n,r){14 15.2g(e,t,n,r,1)},3g:12(e,n,r){17 i,s;if(e&&e.3S&&e.5Q)14 i=e.5Q,v(e.bo).3g(i.3k?i.5k+"."+i.3k:i.5k,i.1R,i.3P),15;if(1b e=="25"){1a(s in e)15.3g(s,n,e[s]);14 15}if(n===!1||1b n=="12")r=n,n=t;14 r===!1&&(r=et),15.1h(12(){v.1j.2G(15,e,r,n)})},hQ:12(e,t,n){14 15.2g(e,19,t,n)},hD:12(e,t){14 15.3g(e,19,t)},hC:12(e,t,n){14 v(15.2Z).2g(e,15.1R,t,n),15},hm:12(e,t){14 v(15.2Z).3g(e,15.1R||"**",t),15},ho:12(e,t,n,r){14 15.2g(t,e,n,r)},hp:12(e,t,n){14 1l.18===1?15.3g(e,"**"):15.3g(t,e||"**",n)},2l:12(e,t){14 15.1h(12(){v.1j.2l(e,t,15)})},75:12(e,t){if(15[0])14 v.1j.2l(e,t,15[0],!0)},46:12(e){17 t=1l,n=e.1J||v.1J++,r=0,i=12(n){17 i=(v.1B(15,"bp"+e.1J)||0)%r;14 v.1B(15,"bp"+e.1J,i+1),n.3S(),t[i].1A(15,1l)||!1};i.1J=n;1t(r<t.18)t[r++].1J=n;14 15.49(i)},9b:12(e,t){14 15.6Z(e).70(t||e)}}),v.1h("6X 4Z 6W 8r 5T hz 96 cD 49 hB hy hx hu b7 b6 6Z 70 58 2v 4C iv aQ ik 2c aR".1M(" "),12(e,t){v.fn[t]=12(e,n){14 n==19&&(n=e,e=19),1l.18>0?15.2g(t,19,e,n):15.2l(t)},Q.1c(t)&&(v.1j.6K[t]=v.1j.aS),G.1c(t)&&(v.1j.6K[t]=v.1j.aT)}),12(e,t){12 1y(e,t,n,r){n=n||[],t=t||g;17 i,s,a,f,l=t.1d;if(!e||1b e!="1q")14 n;if(l!==1&&l!==9)14[];a=o(t);if(!a&&!r)if(i=R.1T(e))if(f=i[1]){if(l===9){s=t.42(f);if(!s||!s.1i)14 n;if(s.id===f)14 n.1k(s),n}1x if(t.2h&&(s=t.2h.42(f))&&u(t,s)&&s.id===f)14 n.1k(s),n}1x{if(i[2])14 S.1A(n,x.1f(t.1O(e),0)),n;if((f=i[3])&&Z&&t.4H)14 S.1A(n,x.1f(t.4H(f),0)),n}14 4G(e.1p(j,"$1"),t,n,r,a)}12 4b(e){14 12(t){17 n=t.1m.1w();14 n==="1Y"&&t.1e===e}}12 it(e){14 12(t){17 n=t.1m.1w();14(n==="1Y"||n==="2z")&&t.1e===e}}12 38(e){14 N(12(t){14 t=+t,N(12(n,r){17 i,s=e([],n.18,t),o=s.18;1t(o--)n[i=s[o]]&&(n[i]=!(r[i]=n[i]))})})}12 4M(e,t,n){if(e===t)14 n;17 r=e.2V;1t(r){if(r===t)14-1;r=r.2V}14 1}12 35(e,t){17 n,r,s,o,u,a,f,l=L[d][e+" "];if(l)14 t?0:l.1D(0);u=e,a=[],f=i.aU;1t(u){if(!n||(r=F.1T(u)))r&&(u=u.1D(r[0].18)||u),a.1k(s=[]);n=!1;if(r=I.1T(u))s.1k(n=1n m(r.3y())),u=u.1D(n.18),n.1e=r[0].1p(j," ");1a(o in i.1C)(r=J[o].1T(u))&&(!f[o]||(r=f[o](r)))&&(s.1k(n=1n m(r.3y())),u=u.1D(n.18),n.1e=o,n.3l=r);if(!n)2f}14 t?u.18:u?1y.2c(e):L(e,a).1D(0)}12 at(e,t,r){17 i=t.2L,s=r&&t.2L==="1i",o=w++;14 t.3L?12(t,n,r){1t(t=t[i])if(s||t.1d===1)14 e(t,n,r)}:12(t,r,u){if(!u){17 a,f=b+" "+o+" ",l=f+n;1t(t=t[i])if(s||t.1d===1){if((a=t[d])===l)14 t.6H;if(1b a=="1q"&&a.2e(f)===0){if(t.6H)14 t}1x{t[d]=l;if(e(t,r,u))14 t.6H=!0,t;t.6H=!1}}}1x 1t(t=t[i])if(s||t.1d===1)if(e(t,r,u))14 t}}12 ft(e){14 e.18>1?12(t,n,r){17 i=e.18;1t(i--)if(!e[i](t,n,r))14!1;14!0}:e[0]}12 3p(e,t,n,r,i){17 s,o=[],u=0,a=e.18,f=t!=19;1a(;u<a;u++)if(s=e[u])if(!n||n(s,r,i))o.1k(s),f&&t.1k(u);14 o}12 ct(e,t,n,r,i,s){14 r&&!r[d]&&(r=ct(r)),i&&!i[d]&&(i=ct(i,s)),N(12(s,o,u,a){17 f,l,c,h=[],p=[],d=o.18,v=s||dt(t||"*",u.1d?[u]:u,[]),m=e&&(s||!t)?3p(v,h,e,u,a):v,g=n?i||(s?e:d||r)?[]:o:m;n&&n(m,g,u,a);if(r){f=3p(g,p),r(f,[],u,a),l=f.18;1t(l--)if(c=f[l])g[p[l]]=!(m[p[l]]=c)}if(s){if(i||e){if(i){f=[],l=g.18;1t(l--)(c=g[l])&&f.1k(m[l]=c);i(19,g=[],f,a)}l=g.18;1t(l--)(c=g[l])&&(f=i?T.1f(s,c):h[l])>-1&&(s[f]=!(o[f]=c))}}1x g=3p(g===o?g.2B(d,g.18):g),i?i(19,o,g,a):S.1A(o,g)})}12 ht(e){17 t,n,r,s=e.18,o=i.43[e[0].1e],u=o||i.43[" "],a=o?1:0,f=at(12(e){14 e===t},u,!0),l=at(12(e){14 T.1f(t,e)>-1},u,!0),h=[12(e,n,r){14!o&&(r||n!==c)||((t=n).1d?f(e,n,r):l(e,n,r))}];1a(;a<s;a++)if(n=i.43[e[a].1e])h=[at(ft(h),n)];1x{n=i.1C[e[a].1e].1A(19,e[a].3l);if(n[d]){r=++a;1a(;r<s;r++)if(i.43[e[r].1e])2f;14 ct(a>1&&ft(h),a>1&&e.1D(0,a-1).2t("").1p(j,"$1"),n,a<r&&ht(e.1D(a,r)),r<s&&ht(e=e.1D(r)),r<s&&e.2t(""))}h.1k(n)}14 ft(h)}12 4Y(e,t){17 r=t.18>0,s=e.18>0,o=12(u,a,f,l,h){17 p,d,v,m=[],y=0,w="0",x=u&&[],T=h!=19,N=c,C=u||s&&i.2d.71("*",h&&a.1i||a),k=b+=N==19?1:3O.E;T&&(c=a!==g&&a,n=o.el);1a(;(p=C[w])!=19;w++){if(s&&p){1a(d=0;v=e[d];d++)if(v(p,a,f)){l.1k(p);2f}T&&(b=k,n=++o.el)}r&&((p=!v&&p)&&y--,u&&x.1k(p))}y+=w;if(r&&w!==y){1a(d=0;v=t[d];d++)v(x,m,a,f);if(u){if(y>0)1t(w--)!x[w]&&!m[w]&&(m[w]=E.1f(l));m=3p(m)}S.1A(l,m),T&&!u&&m.18>0&&y+t.18>1&&1y.7N(l)}14 T&&(b=k,c=N),x};14 o.el=0,r?N(o):o}12 dt(e,t,n){17 r=0,i=t.18;1a(;r<i;r++)1y(e,t[r],n);14 n}12 4G(e,t,n,r,s){17 o,u,f,l,c,h=35(e),p=h.18;if(!r&&h.18===1){u=h[0]=h[0].1D(0);if(u.18>2&&(f=u[0]).1e==="5z"&&t.1d===9&&!s&&i.43[u[1].1e]){t=i.2d.5z(f.3l[0].1p($,""),t,s)[0];if(!t)14 n;e=e.1D(u.3y().18)}1a(o=J.aO.1c(e)?-1:u.18-1;o>=0;o--){f=u[o];if(i.43[l=f.1e])2f;if(c=i.2d[l])if(r=c(f.3l[0].1p($,""),z.1c(u[0].1e)&&t.1i||t,s)){u.2B(o,1),e=r.18&&u.2t("");if(!e)14 S.1A(n,x.1f(r,0)),n;2f}}}14 a(e,h)(r,t,s,n,z.1c(e)),n}12 5v(){}17 n,r,i,s,o,u,a,f,l,c,h=!0,p="2S",d=("iq"+3O.aP()).1p(".",""),m=5W,g=e.32,y=g.2E,b=0,w=0,E=[].5r,S=[].1k,x=[].1D,T=[].2e||12(e){17 t=0,n=15.18;1a(;t<n;t++)if(15[t]===e)14 t;14-1},N=12(e,t){14 e[d]=t==19||t,e},C=12(){17 e={},t=[];14 N(12(n,r){14 t.1k(n)>i.aL&&24 e[t.3y()],e[n+" "]=r},e)},k=C(),L=C(),A=C(),O="[\\\\5K\\\\t\\\\r\\\\n\\\\f]",M="(?:\\\\\\\\.|[-\\\\w]|[^\\\\iu-\\\\ii])+",4Q=M.1p("w","w#"),D="([*^$|!~]?=)",P="\\\\["+O+"*("+M+")"+O+"*(?:"+D+O+"*(?:([\'\\"])((?:\\\\\\\\.|[^\\\\\\\\])*?)\\\\3|("+4Q+")|)|)"+O+"*\\\\]",H=":("+M+")(?:\\\\((?:([\'\\"])((?:\\\\\\\\.|[^\\\\\\\\])*?)\\\\2|([^()[\\\\]]*|(?:(?:"+P+")|[^:]|\\\\\\\\.)*|.*))\\\\)|)",B=":(6Y|5U|eq|gt|3p|5F|3L|5J)(?:\\\\("+O+"*((?:-\\\\d)?\\\\d*)"+O+"*\\\\)|)(?=[^-]|$)",j=1n 1L("^"+O+"+|((?:^|[^\\\\\\\\])(?:\\\\\\\\.)*)"+O+"+$","g"),F=1n 1L("^"+O+"*,"+O+"*"),I=1n 1L("^"+O+"*([\\\\5K\\\\t\\\\r\\\\n\\\\f>+~])"+O+"*"),q=1n 1L(H),R=/^(?:#([\\w\\-]+)|(\\w+)|\\.([\\w\\-]+))$/,U=/^:4B/,z=/[\\5K\\t\\r\\n\\f]*[+~]/,W=/:4B\\($/,X=/h\\d/i,V=/1Y|2v|5x|2z/i,$=/\\\\(?!\\\\)/g,J={5z:1n 1L("^#("+M+")"),7R:1n 1L("^\\\\.("+M+")"),aN:1n 1L("^\\\\[2Q=[\'\\"]?("+M+")[\'\\"]?\\\\]"),71:1n 1L("^("+M.1p("w","w*")+")"),7K:1n 1L("^"+P),8g:1n 1L("^"+H),aO:1n 1L(B,"i"),6U:1n 1L("^:(aV|5F|3L|5J)-i5(?:\\\\("+O+"*(6Y|5U|(([+-]|)(\\\\d*)n|)"+O+"*(?:([+-]|)"+O+"*(\\\\d+)|))"+O+"*\\\\)|)","i"),5q:1n 1L("^"+O+"*[>+~]|"+B,"i")},K=12(e){17 t=g.1X("2a");1Q{14 e(t)}1U(n){14!1}b0{t=19}},Q=K(12(e){14 e.2p(g.ic("")),!e.1O("*").18}),G=K(12(e){14 e.1Z="<a 2X=\'#\'></a>",e.1N&&1b e.1N.22!==p&&e.1N.22("2X")==="#"}),Y=K(12(e){e.1Z="<2v></2v>";17 t=1b e.5p.22("6f");14 t!=="3j"&&t!=="1q"}),Z=K(12(e){14 e.1Z="<2a 73=\'2x e\'></2a><2a 73=\'2x\'></2a>",!e.4H||!e.4H("e").18?!1:(e.5p.23="e",e.4H("e").18===2)}),et=K(12(e){e.id=d+0,e.1Z="<a 2Q=\'"+d+"\'></a><2a 2Q=\'"+d+"\'></2a>",y.3G(e,y.1N);17 t=g.5C&&g.5C(d).18===2+g.5C(d+0).18;14 r=!g.42(d),y.2R(e),t});1Q{x.1f(y.3s,0)[0].1d}1U(5f){x=12(e){17 t,n=[];1a(;t=15[e];e++)n.1k(t);14 n}}1y.3l=12(e,t){14 1y(e,19,19,t)},1y.5V=12(e,t){14 1y(t,19,19,[e]).18>0},s=1y.aX=12(e){17 t,n="",r=0,i=e.1d;if(i){if(i===1||i===9||i===11){if(1b e.7z=="1q")14 e.7z;1a(e=e.1N;e;e=e.2V)n+=s(e)}1x if(i===3||i===4)14 e.aK}1x 1a(;t=e[r];r++)n+=s(t);14 n},o=1y.aY=12(e){17 t=e&&(e.2h||e).2E;14 t?t.1m!=="ia":!1},u=1y.2P=y.2P?12(e,t){17 n=e.1d===9?e.2E:e,r=t&&t.1i;14 e===r||!!(r&&r.1d===1&&n.2P&&n.2P(r))}:y.4K?12(e,t){14 t&&!!(e.4K(t)&16)}:12(e,t){1t(t=t.1i)if(t===e)14!0;14!1},1y.3Q=12(e,t){17 n,r=o(e);14 r||(t=t.1w()),(n=i.aM[t])?n(e):r||Y?e.22(t):(n=e.3B(t),n?1b e[t]=="3j"?e[t]?t:19:n.6Q?n.1z:19:19)},i=1y.aW={aL:50,he:N,7X:J,aM:G?{}:{2X:12(e){14 e.22("2X",2)},1e:12(e){14 e.22("1e")}},2d:{5z:r?12(e,t,n){if(1b t.42!==p&&!n){17 r=t.42(e);14 r&&r.1i?[r]:[]}}:12(e,n,r){if(1b n.42!==p&&!r){17 i=n.42(e);14 i?i.id===e||1b i.3B!==p&&i.3B("id").1z===e?[i]:t:[]}},71:Q?12(e,t){if(1b t.1O!==p)14 t.1O(e)}:12(e,t){17 n=t.1O(e);if(e==="*"){17 r,i=[],s=0;1a(;r=n[s];s++)r.1d===1&&i.1k(r);14 i}14 n},aN:et&&12(e,t){if(1b t.5C!==p)14 t.5C(2Q)},7R:Z&&12(e,t,n){if(1b t.4H!==p&&!n)14 t.4H(e)}},43:{">":{2L:"1i",3L:!0}," ":{2L:"1i"},"+":{2L:"5l",3L:!0},"~":{2L:"5l"}},aU:{7K:12(e){14 e[1]=e[1].1p($,""),e[3]=(e[4]||e[5]||"").1p($,""),e[2]==="~="&&(e[3]=" "+e[3]+" "),e.1D(0,4)},6U:12(e){14 e[1]=e[1].1w(),e[1]==="5F"?(e[2]||1y.2c(e[0]),e[3]=+(e[3]?e[4]+(e[5]||1):2*(e[2]==="6Y"||e[2]==="5U")),e[4]=+(e[6]+e[7]||e[2]==="5U")):e[2]&&1y.2c(e[0]),e},8g:12(e){17 t,n;if(J.6U.1c(e[0]))14 19;if(e[3])e[2]=e[3];1x if(t=e[4])q.1c(t)&&(n=35(t,!0))&&(n=t.2e(")",t.18-n)-t.18)&&(t=t.1D(0,n),e[0]=e[0].1D(0,n)),e[2]=t;14 e.1D(0,3)}},1C:{5z:r?12(e){14 e=e.1p($,""),12(t){14 t.22("id")===e}}:12(e){14 e=e.1p($,""),12(t){17 n=1b t.3B!==p&&t.3B("id");14 n&&n.1z===e}},71:12(e){14 e==="*"?12(){14!0}:(e=e.1p($,"").1w(),12(t){14 t.1m&&t.1m.1w()===e})},7R:12(e){17 t=k[d][e+" "];14 t||(t=1n 1L("(^|"+O+")"+e+"("+O+"|$)"))&&k(e,12(e){14 t.1c(e.23||1b e.22!==p&&e.22("73")||"")})},7K:12(e,t,n){14 12(r,i){17 s=1y.3Q(r,e);14 s==19?t==="!=":t?(s+="",t==="="?s===n:t==="!="?s!==n:t==="^="?n&&s.2e(n)===0:t==="*="?n&&s.2e(n)>-1:t==="$="?n&&s.7J(s.18-n.18)===n:t==="~="?(" "+s+" ").2e(n)>-1:t==="|="?s===n||s.7J(0,n.18+1)===n+"-":!1):!0}},6U:12(e,t,n,r){14 e==="5F"?12(e){17 t,i,s=e.1i;if(n===1&&r===0)14!0;if(s){i=0;1a(t=s.1N;t;t=t.2V)if(t.1d===1){i++;if(e===t)2f}}14 i-=r,i===n||i%n===0&&i/n>=0}:12(t){17 n=t;g2(e){8h"aV":8h"3L":1t(n=n.5l)if(n.1d===1)14!1;if(e==="3L")14!0;n=t;8h"5J":1t(n=n.2V)if(n.1d===1)14!1;14!0}}},8g:12(e,t){17 n,r=i.4I[e]||i.7U[e.1w()]||1y.2c("g0 fX: "+e);14 r[d]?r(t):r.18>1?(n=[e,e,"",t],i.7U.b2(e.1w())?N(12(e,n){17 i,s=r(e,t),o=s.18;1t(o--)i=T.1f(e,s[o]),e[i]=!(n[i]=s[o])}):12(e){14 r(e,0,n)}):r}},4I:{4B:N(12(e){17 t=[],n=[],r=a(e.1p(j,"$1"));14 r[d]?N(12(e,t,n,i){17 s,o=r(e,19,i,[]),u=e.18;1t(u--)if(s=o[u])e[u]=!(t[u]=s)}):12(e,i,s){14 t[0]=e,r(t,19,s,n),!n.5r()}}),6m:N(12(e){14 12(t){14 1y(e,t).18>0}}),2P:N(12(e){14 12(t){14(t.7z||t.fZ||s(t)).2e(e)>-1}}),8j:12(e){14 e.2w===!1},2w:12(e){14 e.2w===!0},21:12(e){17 t=e.1m.1w();14 t==="1Y"&&!!e.21||t==="3e"&&!!e.2K},2K:12(e){14 e.1i&&e.1i.5y,e.2K===!0},8v:12(e){14!i.4I.2M(e)},2M:12(e){17 t;e=e.1N;1t(e){if(e.1m>"@"||(t=e.1d)===3||t===4)14!1;e=e.2V}14!0},9O:12(e){14 X.1c(e.1m)},1F:12(e){17 t,n;14 e.1m.1w()==="1Y"&&(t=e.1e)==="1F"&&((n=e.22("1e"))==19||n.1w()===t)},3N:4b("3N"),3Y:4b("3Y"),9j:4b("9j"),7c:4b("7c"),b3:4b("b3"),4C:it("4C"),b4:it("b4"),2z:12(e){17 t=e.1m.1w();14 t==="1Y"&&e.1e==="2z"||t==="2z"},1Y:12(e){14 V.1c(e.1m)},4Z:12(e){17 t=e.2h;14 e===t.b1&&(!t.b5||t.b5())&&!!(e.1e||e.2X||~e.8c)},60:12(e){14 e===e.2h.b1},3L:38(12(){14[0]}),5J:38(12(e,t){14[t-1]}),eq:38(12(e,t,n){14[n<0?n+t:n]}),6Y:38(12(e,t){1a(17 n=0;n<t;n+=2)e.1k(n);14 e}),5U:38(12(e,t){1a(17 n=1;n<t;n+=2)e.1k(n);14 e}),3p:38(12(e,t,n){1a(17 r=n<0?n+t:n;--r>=0;)e.1k(r);14 e}),gt:38(12(e,t,n){1a(17 r=n<0?n+t:n;++r<t;)e.1k(r);14 e})}},f=y.4K?12(e,t){14 e===t?(l=!0,0):(!e.4K||!t.4K?e.4K:e.4K(t)&4)?-1:1}:12(e,t){if(e===t)14 l=!0,0;if(e.7C&&t.7C)14 e.7C-t.7C;17 n,r,i=[],s=[],o=e.1i,u=t.1i,a=o;if(o===u)14 4M(e,t);if(!o)14-1;if(!u)14 1;1t(a)i.3d(a),a=a.1i;a=u;1t(a)s.3d(a),a=a.1i;n=i.18,r=s.18;1a(17 f=0;f<n&&f<r;f++)if(i[f]!==s[f])14 4M(i[f],s[f]);14 f===n?4M(e,s[f],-1):4M(i[f],t,1)},[0,0].4r(f),h=!l,1y.7N=12(e){17 t,n=[],r=1,i=0;l=h,e.4r(f);if(l){1a(;t=e[r];r++)t===e[r-1]&&(i=n.1k(r));1t(i--)e.2B(n[i],1)}14 e},1y.2c=12(e){8X 1n 9t("h4 2c, h1 h0: "+e)},a=1y.gX=12(e,t){17 n,r=[],i=[],s=A[d][e+" "];if(!s){t||(t=35(e)),n=t.18;1t(n--)s=ht(t[n]),s[d]?r.1k(s):i.1k(s);s=A(e,4Y(i,r))}14 s},g.47&&12(){17 e,t=4G,n=/\'|\\\\/g,r=/\\=[\\5K\\t\\r\\n\\f]*([^\'"\\]]*)[\\5K\\t\\r\\n\\f]*\\]/g,i=[":4Z"],s=[":60"],u=y.5V||y.h6||y.hc||y.hd||y.hb;K(12(e){e.1Z="<2v><3e 2K=\'\'></3e></2v>",e.47("[2K]").18||i.1k("\\\\["+O+"*(?:21|2w|ha|6f|82|2K|1z)"),e.47(":21").18||i.1k(":21")}),K(12(e){e.1Z="<p 1c=\'\'></p>",e.47("[1c^=\'\']").18&&i.1k("[*^$]="+O+"*(?:\\"\\"|\'\')"),e.1Z="<1Y 1e=\'2x\'/>",e.47(":8j").18||i.1k(":8j",":2w")}),i=1n 1L(i.2t("|")),4G=12(e,r,s,o,u){if(!o&&!u&&!i.1c(e)){17 a,f,l=!0,c=d,h=r,p=r.1d===9&&e;if(r.1d===1&&r.1m.1w()!=="25"){a=35(e),(l=r.22("id"))?c=l.1p(n,"\\\\$&"):r.3m("id",c),c="[id=\'"+c+"\'] ",f=a.18;1t(f--)a[f]=c+a[f].2t("");h=z.1c(e)&&r.1i||r,p=a.2t(",")}if(p)1Q{14 S.1A(s,x.1f(h.47(p),0)),s}1U(v){}b0{l||r.4J("id")}}14 t(e,r,s,o,u)},u&&(K(12(t){e=u.1f(t,"2a");1Q{u.1f(t,"[1c!=\'\']:gJ"),s.1k("!=",H)}1U(n){}}),s=1n 1L(s.2t("|")),1y.5V=12(t,n){n=n.1p(r,"=\'$1\']");if(!o(t)&&!s.1c(n)&&!i.1c(n))1Q{17 a=u.1f(t,n);if(a||e||t.32&&t.32.1d!==11)14 a}1U(f){}14 1y(n,19,19,[t]).18>0})}(),i.4I.5F=i.4I.eq,i.4g=5v.29=i.4I,i.7U=1n 5v,1y.3Q=v.3Q,v.2d=1y,v.2D=1y.aW,v.2D[":"]=v.2D.4I,v.5R=1y.7N,v.1F=1y.aX,v.67=1y.aY,v.2P=1y.2P}(e);17 1y=/aZ$/,4b=/^(?:9L|7F(?:aZ|gT))/,it=/^.[^:#\\[\\.,]*$/,38=v.2D.7X.5q,4M={9E:!0,4T:!0,9Q:!0,7F:!0};v.fn.1o({2d:12(e){17 t,n,r,i,s,o,u=15;if(1b e!="1q")14 v(e).1C(12(){1a(t=0,n=u.18;t<n;t++)if(v.2P(u[t],15))14!0});o=15.2J("","2d",e);1a(t=0,n=15.18;t<n;t++){r=o.18,v.2d(e,15[t],o);if(t>0)1a(i=r;i<o.18;i++)1a(s=0;s<r;s++)if(o[s]===o[i]){o.2B(i--,1);2f}}14 o},6m:12(e){17 t,n=v(e,15),r=n.18;14 15.1C(12(){1a(t=0;t<r;t++)if(v.2P(15,n[t]))14!0})},4B:12(e){14 15.2J(ft(15,e,!1),"4B",e)},1C:12(e){14 15.2J(ft(15,e,!0),"1C",e)},is:12(e){14!!e&&(1b e=="1q"?38.1c(e)?v(e,15.2Z).7f(15[0])>=0:v.1C(e,15).18>0:15.1C(e).18>0)},by:12(e,t){17 n,r=0,i=15.18,s=[],o=38.1c(e)||1b e!="1q"?v(e,t||15.2Z):0;1a(;r<i;r++){n=15[r];1t(n&&n.2h&&n!==t&&n.1d!==11){if(o?o.7f(n)>-1:v.2d.5V(n,e)){s.1k(n);2f}n=n.1i}}14 s=s.18>1?v.5R(s):s,15.2J(s,"by",e)},7f:12(e){14 e?1b e=="1q"?v.3R(15[0],v(e)):v.3R(e.5g?e[0]:e,15):15[0]&&15[0].1i?15.9T().18:-1},27:12(e,t){17 n=1b e=="1q"?v(e,t):v.4z(e&&e.1d?[e]:e),r=v.34(15.1u(),n);14 15.2J(35(n[0])||35(r[0])?r:v.5R(r))},9G:12(e){14 15.27(e==19?15.6F:15.6F.1C(e))}}),v.fn.gU=v.fn.9G,v.1h({8v:12(e){17 t=e.1i;14 t&&t.1d!==11?t:19},9L:12(e){14 v.2L(e,"1i")},gV:12(e,t,n){14 v.2L(e,"1i",n)},9Q:12(e){14 at(e,"2V")},7F:12(e){14 at(e,"5l")},h7:12(e){14 v.2L(e,"2V")},9T:12(e){14 v.2L(e,"5l")},gC:12(e,t,n){14 v.2L(e,"2V",n)},g9:12(e,t,n){14 v.2L(e,"5l",n)},ga:12(e){14 v.8G((e.1i||{}).1N,e)},9E:12(e){14 v.8G(e.1N)},4T:12(e){14 v.1m(e,"9I")?e.9J||e.9K.32:v.34([],e.3s)}},12(e,t){v.fn[e]=12(n,r){17 i=v.2N(15,t,n);14 1y.1c(e)||(r=n),r&&1b r=="1q"&&(i=v.1C(r,i)),i=15.18>1&&!4M[e]?v.5R(i):i,15.18>1&&4b.1c(e)&&(i=i.gk()),15.2J(i,e,l.1f(1l).2t(","))}}),v.1o({1C:12(e,t,n){14 n&&(e=":4B("+e+")"),t.18===1?v.2d.5V(t[0],e)?[t[0]]:[]:v.2d.3l(e,t)},2L:12(e,n,r){17 i=[],s=e[n];1t(s&&s.1d!==9&&(r===t||s.1d!==1||!v(s).is(r)))s.1d===1&&i.1k(s),s=s[n];14 i},8G:12(e,t){17 n=[];1a(;e;e=e.2V)e.1d===1&&e!==t&&n.1k(e);14 n}});17 ct="ir|i3|hX|hl|hP|hO|1r|hR|hS|hN|hG|hF|9O|hH|fW|fr|7j|ee|54|e5|dZ|c9|dY",ht=/ 3w\\d+="(?:19|\\d+)"/g,4Y=/^\\s+/,dt=/<(?!9R|br|9H|8S|hr|ey|1Y|7m|eB|7u)(([\\w:]+)[^>]*)\\/>/gi,4G=/<([\\w:]+)/,5v=/<2I/i,gt=/<|&#?\\w+;/,9N=/<(?:26|1g|7m)/i,bt=/<(?:26|25|8S|3e|1g)/i,6S=1n 1L("<(?:"+ct+")[\\\\s/>]","i"),8U=/^(?:3Y|3N)$/,7T=/21\\s*(?:[^=]|=\\s*.21.)/i,d9=/\\/(dO|dR)26/i,bz=/^\\s*<!(?:\\[dS\\[|\\-\\-)|[\\]\\-]{2}>\\s*$/g,2u={3e:[1,"<2v 6f=\'6f\'>","</2v>"],dE:[1,"<9P>","</9P>"],9M:[1,"<2i>","</2i>"],3u:[2,"<2i><2I>","</2I></2i>"],4q:[3,"<2i><2I><3u>","</3u></2I></2i>"],9H:[2,"<2i><2I></2I><8e>","</8e></2i>"],9R:[1,"<2N>","</2N>"],3t:[0,"",""]},9w=3p(i),6V=9w.2p(i.1X("2a"));2u.9F=2u.3e,2u.2I=2u.fz=2u.8e=2u.fq=2u.9M,2u.fi=2u.4q,v.1s.8y||(2u.3t=[1,"X<2a>","</2a>"]),v.fn.1o({1F:12(e){14 v.3n(15,12(e){14 e===t?v.1F(15):15.2M().4a((15[0]&&15[0].2h||i).8P(e))},19,e,1l.18)},6J:12(e){if(v.1v(e))14 15.1h(12(t){v(15).6J(e.1f(15,t))});if(15[0]){17 t=v(e,15[0].2h).eq(0).4D(!0);15[0].1i&&t.3G(15[0]),t.2N(12(){17 e=15;1t(e.1N&&e.1N.1d===1)e=e.1N;14 e}).4a(15)}14 15},9S:12(e){14 v.1v(e)?15.1h(12(t){v(15).9S(e.1f(15,t))}):15.1h(12(){17 t=v(15),n=t.4T();n.18?n.6J(e):t.4a(e)})},eR:12(e){17 t=v.1v(e);14 15.1h(12(n){v(15).6J(t?e.1f(15,n):e)})},eG:12(){14 15.8v().1h(12(){v.1m(15,"2r")||v(15).62(15.3s)}).4w()},4a:12(){14 15.4u(1l,!0,12(e){(15.1d===1||15.1d===11)&&15.2p(e)})},df:12(){14 15.4u(1l,!0,12(e){(15.1d===1||15.1d===11)&&15.3G(e,15.1N)})},6w:12(){if(!35(15[0]))14 15.4u(1l,!1,12(e){15.1i.3G(e,15)});if(1l.18){17 e=v.6l(1l);14 15.2J(v.34(e,15),"6w",15.1R)}},88:12(){if(!35(15[0]))14 15.4u(1l,!1,12(e){15.1i.3G(e,15.2V)});if(1l.18){17 e=v.6l(1l);14 15.2J(v.34(15,e),"88",15.1R)}},2G:12(e,t){17 n,r=0;1a(;(n=15[r])!=19;r++)if(!e||v.1C(e,[n]).18)!t&&n.1d===1&&(v.51(n.1O("*")),v.51([n])),n.1i&&n.1i.2R(n);14 15},2M:12(){17 e,t=0;1a(;(e=15[t])!=19;t++){e.1d===1&&v.51(e.1O("*"));1t(e.1N)e.2R(e.1N)}14 15},4D:12(e,t){14 e=e==19?!1:e,t=t==19?e:t,15.2N(12(){14 v.4D(15,e,t)})},2A:12(e){14 v.3n(15,12(e){17 n=15[0]||{},r=0,i=15.18;if(e===t)14 n.1d===1?n.1Z.1p(ht,""):t;if(1b e=="1q"&&!9N.1c(e)&&(v.1s.8y||!6S.1c(e))&&(v.1s.8O||!4Y.1c(e))&&!2u[(4G.1T(e)||["",""])[1].1w()]){e=e.1p(dt,"<$1></$2>");1Q{1a(;r<i;r++)n=15[r]||{},n.1d===1&&(v.51(n.1O("*")),n.1Z=e);n=0}1U(s){}}n&&15.2M().4a(e)},19,e,1l.18)},62:12(e){14 35(15[0])?15.18?15.2J(v(v.1v(e)?e():e),"62",e):15:v.1v(e)?15.1h(12(t){17 n=v(15),r=n.2A();n.62(e.1f(15,t,r))}):(1b e!="1q"&&(e=v(e).bO()),15.1h(12(){17 t=15.2V,n=15.1i;v(15).2G(),t?v(t).6w(e):v(n).4a(e)}))},bO:12(e){14 15.2G(e,!0)},4u:12(e,n,r){e=[].4f.1A([],e);17 i,s,o,u,a=0,f=e[0],l=[],c=15.18;if(!v.1s.7Z&&c>1&&1b f=="1q"&&7T.1c(f))14 15.1h(12(){v(15).4u(e,n,r)});if(v.1v(f))14 15.1h(12(i){17 s=v(15);e[0]=f.1f(15,i,n?s.2A():t),s.4u(e,n,r)});if(15[0]){i=v.7Q(e,15,l),o=i.7k,s=o.1N,o.3s.18===1&&(o=s);if(s){n=n&&v.1m(s,"3u");1a(u=i.8q||c-1;a<c;a++)r.1f(n&&v.1m(15[a],"2i")?dh(15[a],"2I"):15[a],a===u?o:v.4D(o,!0,!0))}o=s=19,l.18&&v.1h(l,12(e,t){t.7D?v.3M?v.3M({1S:t.7D,1e:"7q",5H:"26",3r:!1,4y:!1,"de":!0}):v.2c("f6 3M"):v.8R((t.1F||t.7z||t.1Z||"").1p(bz,"")),t.1i&&t.1i.2R(t)})}14 15}}),v.7Q=12(e,n,r){17 s,o,u,a=e[0];14 n=n||i,n=!n.1d&&n[0]||n,n=n.2h||n,e.18===1&&1b a=="1q"&&a.18<eH&&n===i&&a.7x(0)==="<"&&!bt.1c(a)&&(v.1s.7Z||!7T.1c(a))&&(v.1s.6T||!6S.1c(a))&&(o=!0,s=v.7V[a],u=s!==t),s||(s=n.6t(),v.6l(e,n,s,r),o&&(v.7V[a]=u&&s)),{7k:s,8q:o}},v.7V={},v.1h({d7:"4a",fj:"df",3G:"6w",fy:"88",fw:"62"},12(e,t){v.fn[e]=12(n){17 r,i=0,s=[],o=v(n),u=o.18,a=15.18===1&&15[0].1i;if((a==19||a&&a.1d===11&&a.3s.18===1)&&u===1)14 o[t](15[0]),15;1a(;i<u;i++)r=(i>0?15.4D(!0):15).1u(),v(o[i])[t](r),s=s.4f(r);14 15.2J(s,e,o.1R)}}),v.1o({4D:12(e,t,n){17 r,i,s,o;v.1s.6T||v.67(e)||!6S.1c("<"+e.1m+">")?o=e.5h(!0):(6V.1Z=e.74,6V.2R(o=6V.1N));if((!v.1s.9a||!v.1s.dc)&&(e.1d===1||e.1d===11)&&!v.67(e)){9z(e,o),r=63(e),i=63(o);1a(s=0;r[s];++s)i[s]&&9z(r[s],i[s])}if(t){9s(e,o);if(n){r=63(e),i=63(o);1a(s=0;r[s];++s)9s(r[s],i[s])}}14 r=i=19,o},6l:12(e,t,n,r){17 s,o,u,a,f,l,c,h,p,d,m,g,y=t===i&&9w,b=[];if(!t||1b t.6t=="2S")t=i;1a(s=0;(u=e[s])!=19;s++){1b u=="3a"&&(u+="");if(!u)4m;if(1b u=="1q")if(!gt.1c(u))u=t.8P(u);1x{y=y||3p(t),c=t.1X("2a"),y.2p(c),u=u.1p(dt,"<$1></$2>"),a=(4G.1T(u)||["",""])[1].1w(),f=2u[a]||2u.3t,l=f[0],c.1Z=f[1]+u+f[2];1t(l--)c=c.5p;if(!v.1s.2I){h=5v.1c(u),p=a==="2i"&&!h?c.1N&&c.1N.3s:f[1]==="<2i>"&&!h?c.3s:[];1a(o=p.18-1;o>=0;--o)v.1m(p[o],"2I")&&!p[o].3s.18&&p[o].1i.2R(p[o])}!v.1s.8O&&4Y.1c(u)&&c.3G(t.8P(4Y.1T(u)[0]),c.1N),u=c.3s,c.1i.2R(c)}u.1d?b.1k(u):v.34(b,u)}c&&(u=c=y=19);if(!v.1s.db)1a(s=0;(u=b[s])!=19;s++)v.1m(u,"1Y")?8Y(u):1b u.1O!="2S"&&v.4c(u.1O("1Y"),8Y);if(n){m=12(e){if(!e.1e||d9.1c(e.1e))14 r?r.1k(e.1i?e.1i.2R(e):e):n.2p(e)};1a(s=0;(u=b[s])!=19;s++)if(!v.1m(u,"26")||!m(u))n.2p(u),1b u.1O!="2S"&&(g=v.4c(v.34([],u.1O("26")),m),b.2B.1A(b,[s+1,0].4f(g)),s+=g.18)}14 b},51:12(e,t){17 n,r,i,s,o=0,u=v.2U,a=v.3H,f=v.1s.7d,l=v.1j.2F;1a(;(i=e[o])!=19;o++)if(t||v.56(i)){r=i[u],n=r&&a[r];if(n){if(n.31)1a(s in n.31)l[s]?v.1j.2G(i,s):v.9D(i,s,n.2H);a[r]&&(24 a[r],f?24 i[u]:i.4J?i.4J(u):i[u]=19,v.9c.1k(r))}}}}),12(){17 e,t;v.d6=12(e){e=e.1w();17 t=/(d2)[ \\/]([\\w.]+)/.1T(e)||/(6P)[ \\/]([\\w.]+)/.1T(e)||/(go)(?:.*6M|)[ \\/]([\\w.]+)/.1T(e)||/(hf) ([\\w.]+)/.1T(e)||e.2e("gr")<0&&/(gz)(?:.*? gv:([\\w.]+)|)/.1T(e)||[];14{6R:t[1]||"",6M:t[2]||"0"}},e=v.d6(o.gc),t={},e.6R&&(t[e.6R]=!0,t.6M=e.6M),t.d2?t.6P=!0:t.6P&&(t.gd=!0),v.6R=t,v.7H=12(){12 e(t,n){14 1n e.fn.3b(t,n)}v.1o(!0,e,15),e.gb=15,e.fn=e.29=15(),e.fn.3v=e,e.7H=15.7H,e.fn.3b=12(r,i){14 i&&i 5o v&&!(i 5o e)&&(i=e(i)),v.fn.3b.1f(15,r,i,t)},e.fn.3b.29=e.fn;17 t=e(i);14 e}}();17 2m,4A,4E,76=/cd\\([^)]*\\)/i,c6=/2n=([^)]*)/,bY=/^(1K|g8|h2|1H)$/,bZ=/^(39|2i(?!-c[ea]).+)/,8p=/^3o/,d5=1n 1L("^("+m+")(.*)$","i"),5t=1n 1L("^("+m+")(?!3z)[a-z%]+$","i"),c0=1n 1L("^([-+])=("+m+")","i"),7r={gL:"4x"},c5={3i:"7L",dg:"2x",1P:"4x"},8d={gR:0,c4:dd},$t=["hK","gP","gO","gN"],8m=["gQ","O","gS","7M"],c3=v.fn.46;v.fn.1o({1I:12(e,n){14 v.3n(15,12(e,n,r){14 r!==t?v.1g(e,n,r):v.1I(e,n)},e,n,1l.18>1)},3A:12(){14 89(15,!0)},48:12(){14 89(15)},46:12(e,t){17 n=1b e=="3j";14 v.1v(e)&&v.1v(t)?c3.1A(15,1l):15.1h(12(){(n?e:69(15))?v(15).3A():v(15).48()})}}),v.1o({2T:{2n:{1u:12(e,t){if(t){17 n=2m(e,"2n");14 n===""?"1":n}}}},6I:{gM:!0,c4:!0,gF:!0,2n:!0,gE:!0,gD:!0,gG:!0,5n:!0},5d:{"7G":v.1s.7l?"7l":"gH"},1g:12(e,n,r,i){if(!e||e.1d===3||e.1d===8||!e.1g)14;17 s,o,u,a=v.45(n),f=e.1g;n=v.5d[a]||(v.5d[a]=8i(f,a)),u=v.2T[n]||v.2T[a];if(r===t)14 u&&"1u"in u&&(s=u.1u(e,!1,i))!==t?s:f[n];o=1b r,o==="1q"&&(s=c0.1T(r))&&(r=(s[1]+1)*s[2]+2b(v.1I(e,n)),o="3a");if(r==19||o==="3a"&&bW(r))14;o==="3a"&&!v.6I[a]&&(r+="3z");if(!u||!("1G"in u)||(r=u.1G(e,r,i))!==t)1Q{f[n]=r}1U(l){}},1I:12(e,n,r,i){17 s,o,u,a=v.45(n);14 n=v.5d[a]||(v.5d[a]=8i(e.1g,a)),u=v.2T[n]||v.2T[a],u&&"1u"in u&&(s=u.1u(e,!0,i)),s===t&&(s=2m(e,n)),s==="gW"&&n in 8d&&(s=8d[n]),r||i!==t?(o=2b(s),r||v.86(o)?o||0:s):s},83:12(e,t,n){17 r,i,s={};1a(i in t)s[i]=e.1g[i],e.1g[i]=t[i];r=n.1f(e);1a(i in t)e.1g[i]=s[i];14 r}}),e.4X?2m=12(t,n){17 r,i,s,o,u=e.4X(t,19),a=t.1g;14 u&&(r=u.h9(n)||u[n],r===""&&!v.2P(t.2h,t)&&(r=v.1g(t,n)),5t.1c(r)&&8p.1c(n)&&(i=a.1W,s=a.8n,o=a.8l,a.8n=a.8l=a.1W=r,r=u.1W,a.1W=i,a.8n=s,a.8l=o)),r}:i.2E.4L&&(2m=12(e,t){17 n,r,i=e.4L&&e.4L[t],s=e.1g;14 i==19&&s&&s[t]&&(i=s[t]),5t.1c(i)&&!bY.1c(t)&&(n=s.1H,r=e.7A&&e.7A.1H,r&&(e.7A.1H=e.4L.1H),s.1H=t==="h5"?"gZ":i,i=s.h3+"3z",s.1H=n,r&&(e.7A.1H=r)),i===""?"7y":i}),v.1h(["3F","1W"],12(e,t){v.2T[t]={1u:12(e,n,r){if(n)14 e.4v===0&&bZ.1c(2m(e,"1P"))?v.83(e,c5,12(){14 8a(e,t,r)}):8a(e,t,r)},1G:12(e,n,r){14 8k(e,n,r?en(e,t,r,v.1s.5s&&v.1I(e,"5s")==="2s-3W"):0)}}}),v.1s.2n||(v.2T.2n={1u:12(e,t){14 c6.1c((t&&e.4L?e.4L.1C:e.1g.1C)||"")?.cJ*2b(1L.$1)+"":t?"1":""},1G:12(e,t){17 n=e.1g,r=e.4L,i=v.86(t)?"cd(2n="+t*g7+")":"",s=r&&r.1C||n.1C||"";n.5n=1;if(t>=1&&v.37(s.1p(76,""))===""&&n.4J){n.4J("1C");if(r&&!r.1C)14}n.1C=76.1c(s)?s.1p(76,i):s+" "+i}}),v(12(){v.1s.84||(v.2T.6s={1u:12(e,t){14 v.83(e,{1P:"5u-4x"},12(){if(t)14 2m(e,"6s")})}}),!v.1s.7Y&&v.fn.3i&&v.1h(["1K","1H"],12(e,t){v.2T[t]={1u:12(e,n){if(n){17 r=2m(e,t);14 5t.1c(r)?v(e).3i()[t]+"3z":r}}}})}),v.2D&&v.2D.4g&&(v.2D.4g.2x=12(e){14 e.4v===0&&e.6h===0||!v.1s.ce&&(e.1g&&e.1g.1P||2m(e,"1P"))==="39"},v.2D.4g.cb=12(e){14!v.2D.4g.2x(e)}),v.1h({3o:"",3q:"",2s:"77"},12(e,t){v.2T[e+t]={8o:12(n){17 r,i=1b n=="1q"?n.1M(" "):[n],s={};1a(r=0;r<4;r++)s[e+$t[r]+t]=i[r]||i[r-2]||i[0];14 s}},8p.1c(e)||(v.2T[e+t].1G=8k)});17 bH=/%20/g,c7=/\\[\\]$/,2g=/\\r?\\n/g,bG=/^(?:g6|g5|c8|c8-g1|g4|2x|g3|3a|7c|gf|gg|gw|1F|c9|1S|gx)$/i,an=/^(?:2v|5x)/i;v.fn.1o({gA:12(){14 v.7u(15.ca())},ca:12(){14 15.2N(12(){14 15.bV?v.4z(15.bV):15}).1C(12(){14 15.2Q&&!15.2w&&(15.21||an.1c(15.1m)||bG.1c(15.1e))}).2N(12(e,t){17 n=v(15).5a();14 n==19?19:v.2O(n)?v.2N(n,12(e,n){14{2Q:t.2Q,1z:e.1p(2g,"\\r\\n")}}):{2Q:t.2Q,1z:n.1p(2g,"\\r\\n")}}).1u()}}),v.7u=12(e,n){17 r,i=[],s=12(e,t){t=v.1v(t)?t():t==19?"":t,i[i.18]=8Z(e)+"="+8Z(t)};n===t&&(n=v.4e&&v.4e.cO);if(v.2O(e)||e.5g&&!v.5w(e))v.1h(e,12(){s(15.2Q,15.1z)});1x 1a(r in e)fn(r,e[r],n,s);14 i.2t("&").1p(bH,"+")};17 3V,cn,hn=/#.*$/,bN=/^(.*?):[ \\t]*([^\\r\\n]*)\\r?$/i9,dn=/^(?:i8|bI|bI\\-ie|.+\\-i0|9j|iz|iy):$/,cL=/^(?:7q|il)$/,cg=/^\\/\\//,gn=/\\?/,d0=/<26\\b[^<]*(?:(?!<\\/26>)<[^<]*)*<\\/26>/gi,bn=/([?&])4Q=[^&]*/,8H=/^([\\w\\+\\.\\-]+:)(?:\\/\\/([^\\/?#:]*)(?::(\\d+)|)|)/,9m=v.fn.5T,7t={},91={},8L=["*/"]+["*"];1Q{cn=s.2X}1U(ip){cn=i.1X("a"),cn.2X="",cn=cn.2X}3V=8H.1T(cn.1w())||[],v.fn.5T=12(e,n,r){if(1b e!="1q"&&9m)14 9m.1A(15,1l);if(!15.18)14 15;17 i,s,o,u=15,a=e.2e(" ");14 a>=0&&(i=e.1D(a,e.18),e=e.1D(0,a)),v.1v(n)?(r=n,n=t):n&&1b n=="25"&&(s="io"),v.3M({1S:e,1e:s,5H:"2A",1r:n,3f:12(e,t){r&&u.1h(r,o||[e.9y,t,e])}}).2W(12(e){o=1l,u.2A(i?v("<2a>").4a(e.1p(d0,"")).2d(i):e)}),15},v.1h("cK bM bL hW hw cY".1M(" "),12(e,t){v.fn[t]=12(e){14 15.2g(t,e)}}),v.1h(["1u","hv"],12(e,n){v[n]=12(e,r,i,s){14 v.1v(r)&&(s=s||i,i=r,r=t),v.3M({1e:n,1S:e,1r:r,66:i,5H:s})}}),v.1o({hA:12(e,n){14 v.1u(e,t,n,"26")},hs:12(e,t,n){14 v.1u(e,t,n,"3I")},6L:12(e,t){14 t?98(e,v.4e):(t=e,e=v.4e),98(e,t),e},4e:{1S:cn,9v:dn.1c(3V[1]),4y:!0,1e:"7q",5A:"4P/x-cU-55-cR; cF=hi-8",cN:!0,3r:!0,6g:{3D:"4P/3D, 1F/3D",2A:"1F/2A",1F:"1F/hk",3I:"4P/3I, 1F/6n","*":8L},4T:{3D:/3D/,2A:/2A/,3I:/3I/},bD:{3D:"ck",1F:"9y"},4S:{"* 1F":e.5W,"1F 2A":!0,"1F 3I":v.9r,"1F 3D":v.bJ},bK:{2Z:!0,1S:!0}},8N:9d(7t),8A:9d(91),3M:12(e,n){12 T(e,n,s,a){17 l,y,b,w,S,T=n;if(E===2)14;E=2,u&&bR(u),o=t,i=a||"",x.3h=e>0?4:0,s&&(w=bS(c,x,s));if(e>=7v&&e<hV||e===bT)c.cG&&(S=x.6D("hU-cH"),S&&(v.7a[r]=S),S=x.6D("hT"),S&&(v.7b[r]=S)),e===bT?(T="hM",l=!0):(l=bQ(c,w),T=l.6i,y=l.1r,b=l.2c,l=!b);1x{b=T;if(!T||e)T="2c",e<0&&(e=0)}x.9x=e,x.cs=(n||T)+"",l?d.4s(h,[y,T,x]):d.bP(h,[x,T,b]),x.97(g),g=t,f&&p.2l("3M"+(l?"hE":"9t"),[x,c,l?y:b]),m.6j(h,[x,T]),f&&(p.2l("bL",[x,c]),--v.60||v.1j.2l("bM"))}1b e=="25"&&(n=e,e=t),n=n||{};17 r,i,s,o,u,a,f,l,c=v.6L({},n),h=c.2Z||c,p=h!==c&&(h.1d||h 5o v)?v(h):v.1j,d=v.4k(),m=v.5m("5O 5i"),g=c.97||{},b={},w={},E=0,S="hI",x={3h:0,4o:12(e,t){if(!E){17 n=e.1w();e=w[n]=w[n]||e,b[e]=t}14 15},cj:12(){14 E===2?i:19},6D:12(e){17 n;if(E===2){if(!s){s={};1t(n=bN.1T(i))s[n[1].1w()]=n[2]}n=s[e.1w()]}14 n===t?19:n},8I:12(e){14 E||(c.6y=e),15},40:12(e){14 e=e||S,o&&o.40(e),T(0,e),15}};d.2k(x),x.66=x.2W,x.2c=x.4l,x.3f=m.27,x.97=12(e){if(e){17 t;if(E<2)1a(t in e)g[t]=[g[t],e[t]];1x t=e[x.9x],x.44(t)}14 15},c.1S=((e||c.1S)+"").1p(hn,"").1p(cg,3V[1]+"//"),c.36=v.37(c.5H||"*").1w().1M(y),c.4j==19&&(a=8H.1T(c.1S.1w()),c.4j=!(!a||a[1]===3V[1]&&a[2]===3V[2]&&(a[3]||(a[1]==="ch:"?80:cM))==(3V[3]||(3V[1]==="ch:"?80:cM)))),c.1r&&c.cN&&1b c.1r!="1q"&&(c.1r=v.7u(c.1r,c.cO)),5S(7t,c,n,x);if(E===2)14 x;f=c.4y,c.1e=c.1e.8C(),c.6z=!cL.1c(c.1e),f&&v.60++===0&&v.1j.2l("cK");if(!c.6z){c.1r&&(c.1S+=(gn.1c(c.1S)?"&":"?")+c.1r,24 c.1r),r=c.1S;if(c.3H===!1){17 N=v.2Y(),C=c.1S.1p(bn,"$e1="+N);c.1S=C+(C===c.1S?(gn.1c(c.1S)?"&":"?")+"4Q="+N:"")}}(c.1r&&c.6z&&c.5A!==!1||n.5A)&&x.4o("eg-ex",c.5A),c.cG&&(r=r||c.1S,v.7a[r]&&x.4o("cI-cH-ez",v.7a[r]),v.7b[r]&&x.4o("cI-eu-es",v.7b[r])),x.4o("ej",c.36[0]&&c.6g[c.36[0]]?c.6g[c.36[0]]+(c.36[0]!=="*"?", "+8L+"; q=0.cJ":""):c.6g["*"]);1a(l in c.cP)x.4o(l,c.cP[l]);if(!c.cQ||c.cQ.1f(h,x,c)!==!1&&E!==2){S="40";1a(l in{66:1,2c:1,3f:1})x[l](c[l]);o=5S(91,c,n,x);if(!o)T(-1,"cX eC");1x{x.3h=1,f&&p.2l("cY",[x,c]),c.3r&&c.94>0&&(u=4p(12(){x.40("94")},c.94));1Q{E=1,o.6v(b,T)}1U(k){if(!(E<2))8X k;T(-1,k)}}14 x}14 x.40()},60:0,7a:{},7b:{}});17 99=[],cS=/\\?/,5B=/(=)\\?(?=&|$)|\\?\\?/,cV=v.2Y();v.6L({5D:"dP",4t:12(){17 e=99.5r()||v.2U+"4Q"+cV++;14 15[e]=!0,e}}),v.8N("3I 5D",12(n,r,i){17 s,o,u,a=n.1r,f=n.1S,l=n.5D!==!1,c=l&&5B.1c(f),h=l&&!c&&1b a=="1q"&&!(n.5A||"").2e("4P/x-cU-55-cR")&&5B.1c(a);if(n.36[0]==="5D"||c||h)14 s=n.4t=v.1v(n.4t)?n.4t():n.4t,o=e[s],c?n.1S=f.1p(5B,"$1"+s):h?n.1r=a.1p(5B,"$1"+s):l&&(n.1S+=(cS.1c(f)?"&":"?")+n.5D+"="+s),n.4S["26 3I"]=12(){14 u||v.2c(s+" dM 4B dG"),u[0]},n.36[0]="3I",e[s]=12(){u=1l},i.44(12(){e[s]=o,n[s]&&(n.4t=r.4t,99.1k(s)),u&&v.1v(o)&&o(u[0]),u=o=t}),"26"}),v.6L({6g:{26:"1F/6n, 4P/6n, 4P/8V, 4P/x-8V"},4T:{26:/6n|8V/},4S:{"1F 26":12(e){14 v.8R(e),e}}}),v.8N("26",12(e){e.3H===t&&(e.3H=!1),e.4j&&(e.1e="7q",e.4y=!1)}),v.8A("26",12(e){if(e.4j){17 n,r=i.cT||i.1O("cT")[0]||i.2E;14{6v:12(s,o){n=i.1X("26"),n.3r="3r",e.cE&&(n.cF=e.cE),n.7D=e.1S,n.7n=n.5c=12(e,i){if(i||!n.3h||/dK|3f/.1c(n.3h))n.7n=n.5c=19,r&&n.1i&&r.2R(n),n=t,i||o(7v,"66")},r.3G(n,r.1N)},40:12(){n&&n.7n(0,1)}}}});17 4i,6o=e.7e?12(){1a(17 e in 4i)4i[e](0,1)}:!1,cC=0;v.4e.8s=e.7e?12(){14!15.9v&&8M()||cp()}:8M,12(e){v.1o(v.1s,{3M:!!e,cq:!!e&&"fu"in e})}(v.4e.8s()),v.1s.3M&&v.8A(12(n){if(!n.4j||v.1s.cq){17 r;14{6v:12(i,s){17 o,u,a=n.8s();n.cr?a.8t(n.1e,n.1S,n.3r,n.cr,n.7c):a.8t(n.1e,n.1S,n.3r);if(n.8B)1a(u in n.8B)a[u]=n.8B[u];n.6y&&a.8I&&a.8I(n.6y),!n.4j&&!i["X-cm-6u"]&&(i["X-cm-6u"]="ci");1Q{1a(u in i)a.4o(u,i[u])}1U(f){}a.6v(n.6z&&n.1r||19),r=12(e,i){17 u,f,l,c,h;1Q{if(r&&(i||a.3h===4)){r=t,o&&(a.5c=v.8F,6o&&24 4i[o]);if(i)a.3h!==4&&a.40();1x{u=a.9x,l=a.cj(),c={},h=a.ck,h&&h.2E&&(c.3D=h);1Q{c.1F=a.9y}1U(p){}1Q{f=a.cs}1U(p){f=""}!u&&n.9v&&!n.4j?u=c.1F?7v:eT:u===eV&&(u=eU)}}}1U(d){i||s(-1,d)}c&&s(u,f,c,l)},n.3r?a.3h===4?4p(r,0):(o=++cC,6o&&(4i||(4i={},v(e).cD(6o)),4i[o]=r),a.5c=r):r()},40:12(){r&&r(0,1)}}}});17 4F,6d,cA=/^(?:46|3A|48)$/,cv=1n 1L("^(?:([-+])=|)("+m+")([a-z%]*)$","i"),co=/6q$/,6a=[cz],5e={"*":[12(e,t){17 n,r,i=15.9g(e,t),s=cv.1T(t),o=i.6N(),u=+o||0,a=1,f=20;if(s){n=+s[2],r=s[3]||(v.6I[e]?"":"3z");if(r!=="3z"&&u){u=v.1I(i.1E,e,!0)||n||1;do a=a||".5",u/=a,v.1g(i.1E,e,u+r);1t(a!==(a=i.6N()/ o) && a !== 1 && --f) } i.7S = r, i.3C = u, i.4w = s[1] ? u + (s[1] + 1) * n : n } 14 i }] }; v.fE = v.1o(8f, { fo: 12 (e, t) { v.1v(e) ? (t = e, e = ["*"]) : e = e.1M(" "); 17 n, r = 0, i = e.18; 1a (; r < i; r++) n = e[r], 5e[n] = 5e[n] || [], 5e[n].3d(t) }, eD: 12 (e, t) { t ? 6a.3d(e) : 6a.1k(e) } }), v.cx = 2o, 2o.29 = { 3v: 2o, 3b: 12 (e, t, n, r, i, s) { 15.1E = e, 15.28 = n, 15.4U = i || "bF", 15.4O = t, 15.3C = 15.2Y = 15.6N(), 15.4w = r, 15.7S = s || (v.6I[n] ? "" : "3z") }, 6N: 12 () { 17 e = 2o.33[15.28]; 14 e && e.1u ? e.1u(15) : 2o.33.3t.1u(15) }, 8E: 12 (e) { 17 t, n = 2o.33[15.28]; 14 15.4O.2C ? 15.cy = t = v.4U[15.4U](e, 15.4O.2C * e, 0, 1, 15.4O.2C) : 15.cy = t = e, 15.2Y = (15.4w - 15.3C) * t + 15.3C, 15.4O.5X && 15.4O.5X.1f(15.1E, 15.2Y, 15), n && n.1G ? n.1G(15) : 2o.33.3t.1G(15), 15 } }, 2o.29.3b.29 = 2o.29, 2o.33 = { 3t: { 1u: 12 (e) { 17 t; 14 e.1E[e.28] == 19 || !!e.1E.1g && e.1E.1g[e.28] != 19 ? (t = v.1I(e.1E, e.28, !1, ""), !t || t === "7y" ? 0 : t) : e.1E[e.28] }, 1G: 12 (e) { v.fx.5X[e.28] ? v.fx.5X[e.28](e) : e.1E.1g && (e.1E.1g[v.5d[e.28]] != 19 || v.2T[e.28]) ? v.1g(e.1E, e.28, e.2Y + e.7S) : e.1E[e.28] = e.2Y } } }, 2o.33.53 = 2o.33.4V = { 1G: 12 (e) { e.1E.1d && e.1E.1i && (e.1E[e.28] = e.2Y) } }, v.1h(["46", "3A", "48"], 12 (e, t) { 17 n = v.fn[t]; v.fn[t] = 12 (r, i, s) { 14 r == 19 || 1b r == "3j" || !e && v.1v(r) && v.1v(i) ? n.1A(15, 1l) : 15.7E(65(t, !0), r, i, s) } }), v.fn.1o({ fc: 12 (e, t, n, r) { 14 15.1C(69).1I("2n", 0).3A().4w().7E({ 2n: t }, e, n, r) }, 7E: 12 (e, t, n, r) { 17 i = v.6b(e), s = v.bE(t, n, r), o = 12 () { 17 t = 8f(15, v.1o({}, e), s); i && t.30(!0) }; 14 i || s.1V === !1 ? 15.1h(o) : 15.1V(s.1V, o) }, 30: 12 (e, n, r) { 17 i = 12 (e) { 17 t = e.30; 24 e.30, t(r) }; 14 1b e != "1q" && (r = n, n = e, e = t), n && e !== !1 && 15.1V(e || "fx", []), 15.1h(12 () { 17 t = !0, n = e != 19 && e + "6q", s = v.5E, o = v.1B(15); if (n) o[n] && o[n].30 && i(o[n]); 1x 1a (n in o) o[n] && o[n].30 && co.1c(n) && i(o[n]); 1a (n = s.18; n--;) s[n].1E === 15 && (e == 19 || s[n].1V === e) && (s[n].cW.30(r), t = !1, s.2B(n, 1)); (t || !r) && v.4n(15, e) }) } }), v.1h({ ei: 65("3A"), e0: 65("48"), ed: 65("46"), e7: { 2n: "3A" }, im: { 2n: "48" }, ij: { 2n: "46" } }, 12 (e, t) { v.fn[e] = 12 (e, n, r) { 14 15.7E(t, e, n, r) } }), v.bE = 12 (e, t, n) { 17 r = e && 1b e == "25" ? v.1o({}, e) : { 3f: n || !n && t || v.1v(e) && e, 2C: e, 4U: n && t || t && !v.1v(t) && t }; r.2C = v.fx.3g ? 0 : 1b r.2C == "3a" ? r.2C : r.2C in v.fx.68 ? v.fx.68[r.2C] : v.fx.68.3t; if (r.1V == 19 || r.1V === !0) r.1V = "fx"; 14 r.7W = r.3f, r.3f = 12 () { v.1v(r.7W) && r.7W.1f(15), r.1V && v.4n(15, r.1V) }, r }, v.4U = { i1: 12 (e) { 14 e }, bF: 12 (e) { 14 .5 - 3O.gl(e * 3O.gs)/2}},v.5E=[],v.fx=2o.29.3b,v.fx.c2=12(){17 e,n=v.5E,r=0;4F=v.2Y();1a(;r<n.18;r++)e=n[r],!e()&&n[r]===e&&n.2B(r--,1);n.18||v.fx.30(),4F=t},v.fx.c1=12(e){e()&&v.5E.1k(e)&&!6d&&(6d=gK(v.fx.c2,v.fx.cZ))},v.fx.cZ=13,v.fx.30=12(){ib(6d),6d=19},v.fx.68={hY:ep,dI:7v,3t:dd},v.fx.5X={},v.2D&&v.2D.4g&&(v.2D.4g.dL=12(e){14 v.4c(v.5E,12(t){14 e===t.1E}).18});17 er=/^(?:2r|2A)$/i;v.fn.3x=12(e){if(1l.18)14 e===t?15:15.1h(12(t){v.3x.cc(15,e,t)});17 n,r,i,s,o,u,a,f={1K:0,1H:0},l=15[0],c=l&&l.2h;if(!c)14;14(r=c.2r)===l?v.3x.d3(l):(n=c.2E,v.2P(n,l)?(1b l.d1!="2S"&&(f=l.d1()),i=3u(c),s=n.6r||r.6r||0,o=n.7h||r.7h||0,u=i.cw||n.53,a=i.bA||n.4V,{1K:f.1K+u-s,1H:f.1H+a-o}):f)},v.3x={d3:12(e){17 t=e.d4,n=e.gI;14 v.1s.bX&&(t+=2b(v.1I(e,"cu"))||0,n+=2b(v.1I(e,"cB"))||0),{1K:t,1H:n}},cc:12(e,t,n){17 r=v.1I(e,"3i");r==="7O"&&(e.1g.3i="43");17 i=v(e),s=i.3x(),o=v.1I(e,"1K"),u=v.1I(e,"1H"),a=(r==="7L"||r==="i2")&&v.3R("7y",[o,u])>-1,f={},l={},c,h;a?(l=i.3i(),c=l.1K,h=l.1H):(c=2b(o)||0,h=2b(u)||0),v.1v(t)&&(t=t.1f(e,n,s)),t.1K!=19&&(f.1K=t.1K-s.1K+c),t.1H!=19&&(f.1H=t.1H-s.1H+h),"cl"in t?t.cl.1f(e,f):i.1I(f)}},v.fn.1o({3i:12(){if(!15[0])14;17 e=15[0],t=15.6O(),n=15.3x(),r=er.1c(t[0].1m)?{1K:0,1H:0}:t.3x();14 n.1K-=2b(v.1I(e,"cu"))||0,n.1H-=2b(v.1I(e,"cB"))||0,r.1K+=2b(v.1I(t[0],"eO"))||0,r.1H+=2b(v.1I(t[0],"fe"))||0,{1K:n.1K-r.1K,1H:n.1H-r.1H}},6O:12(){14 15.2N(12(){17 e=15.6O||i.2r;1t(e&&!er.1c(e.1m)&&v.1I(e,"3i")==="7O")e=e.6O;14 e||i.2r})}}),v.1h({4V:"bA",53:"cw"},12(e,n){17 r=/Y/.1c(n);v.fn[e]=12(i){14 v.3n(15,12(e,i,s){17 o=3u(e);if(s===t)14 o?n in o?o[n]:o.32.2E[i]:e[i];o?o.fp(r?v(o).4V():s,r?s:v(o).53()):e[i]=s},e,i,1l.18,19)}}),v.1h({fB:"3F",77:"1W"},12(e,n){v.1h({3q:"dH"+e,6e:n,"":"dN"+e},12(r,i){v.fn[i]=12(i,s){17 o=1l.18&&(r||1b i!="3j"),u=r||(i===!0||s===!0?"3o":"2s");14 v.3n(15,12(n,r,i){17 s;14 v.3U(n)?n.32.2E["bC"+e]:n.1d===9?(s=n.2E,3O.6E(n.2r["96"+e],s["96"+e],n.2r["3x"+e],s["3x"+e],s["bC"+e])):i===t?v.1I(n,r,i,u):v.1g(n,r,i,u)},n,o?i:t,o,19)}})}),e.3w=e.$=v,1b 72=="12"&&72.bB&&72.bB.3w&&72("5g",[],12(){14 v})})(9k);3w.5L=12(n,t,i){17 f,r,e,o,u,s;if(1b t!="2S"){i=i||{},t===19&&(t="",i=$.1o({},i),i.3T=-1),f="",i.3T&&(1b i.3T=="3a"||i.3T.cf)&&(1b i.3T=="3a"?(r=1n 7I,r.gj(r.bU()+i.3T*gy)):r=i.3T,f="; 3T="+r.cf());17 h=i.8b?"; 8b="+i.8b:"",c=i.9q?"; 9q="+i.9q:"",l=i.d8?"; d8":"";32.5L=[n,"=",8Z(t),f,h,c,l].2t("")}1x{if(e=19,32.5L&&32.5L!="")1a(o=32.5L.1M(";"),u=0;u<o.18;u++)if(s=3w.37(o[u]),s.9A(0,n.18+1)==n+"="){e=gu(s.9A(n.18+1));2f}14 e}}',62,1152,'||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||function||return|this||var|length|null|for|typeof|test|nodeType|type|call|style|each|parentNode|event|push|arguments|nodeName|new|extend|replace|string|data|support|while|get|isFunction|toLowerCase|else|nt|value|apply|_data|filter|slice|elem|text|set|left|css|guid|top|RegExp|split|firstChild|getElementsByTagName|display|try|selector|url|exec|catch|queue|width|createElement|input|innerHTML||checked|getAttribute|className|delete|object|script|add|prop|prototype|div|parseFloat|error|find|indexOf|break|on|ownerDocument|table|target|promise|trigger|Dt|opacity|Yn|appendChild|ready|body|border|join|Nt|select|disabled|hidden|opts|button|html|splice|duration|expr|documentElement|special|remove|handle|tbody|pushStack|selected|dir|empty|map|isArray|contains|name|removeChild|undefined|cssHooks|expando|nextSibling|done|href|now|context|stop|events|document|propHooks|merge|ut|dataTypes|trim|st|none|number|init|overflow|unshift|option|complete|off|readyState|position|boolean|namespace|matches|setAttribute|access|margin|lt|padding|async|childNodes|_default|tr|constructor|jQuery|offset|shift|px|show|getAttributeNode|start|xml|cssText|height|insertBefore|cache|json|valHooks|props|first|ajax|radio|Math|handler|attr|inArray|preventDefault|expires|isWindow|ln|box|Array|checkbox|Event|abort|addEventListener|getElementById|relative|always|camelCase|toggle|querySelectorAll|hide|click|append|rt|grep|attrHooks|ajaxSettings|concat|filters|1px|Hn|crossDomain|Deferred|fail|continue|dequeue|setRequestHeader|setTimeout|td|sort|resolveWith|jsonpCallback|domManip|offsetWidth|end|block|global|makeArray|Pt|not|submit|clone|Ht|qn|vt|getElementsByClassName|pseudos|removeAttribute|compareDocumentPosition|currentStyle|ot|attachEvent|options|application|_|removeData|converters|contents|easing|scrollLeft|delegateType|getComputedStyle|pt|focus||cleanData|propFix|scrollTop|progress|form|acceptData|setup|change|tweens|val|fire|onreadystatechange|cssProps|Vn|tt|jquery|cloneNode|memory|teardown|origType|previousSibling|Callbacks|zoom|instanceof|lastChild|needsContext|pop|boxSizing|Ut|inline|mt|isPlainObject|textarea|selectedIndex|ID|contentType|Dn|getElementsByName|jsonp|timers|nth|originalEvent|dataType|simulate|last|x20|cookie|_change|isTrigger|once|removeEventListener|handleObj|unique|kn|load|odd|matchesSelector|String|step|isDefaultPrevented|stopPropagation|active|which|replaceWith|Mt|removeAttr|Zn|success|isXMLDoc|speeds|Gt|Xn|isEmptyObject|unqueued|Rn|content|multiple|accepts|offsetHeight|state|fireWith|triggered|clean|has|javascript|Bn|disable|queueHooks|clientTop|marginRight|createDocumentFragment|With|send|before|_submit|mimeType|hasContent|delegateCount|isReady|false|getResponseHeader|max|prevObject|_queueHooks|sizset|cssNumber|wrapAll|fixHooks|ajaxSetup|version|cur|offsetParent|webkit|specified|browser|wt|html5Clone|CHILD|kt|focusin|blur|even|mouseenter|mouseleave|TAG|define|class|outerHTML|triggerHandler|Bt|Width|enctype|detachEvent|lastModified|etag|password|deleteExpando|ActiveXObject|index|dispatch|clientLeft|readyWait|nav|fragment|cssFloat|link|onload|result|JSON|GET|Wt|bindType|Sn|param|200|relatedTarget|charAt|auto|textContent|runtimeStyle|isPropagationStopped|sourceIndex|src|animate|prev|float|sub|Date|substr|ATTR|absolute|ms|uniqueSort|static|reject|buildFragment|CLASS|unit|St|setFilters|fragments|old|match|pixelPosition|checkClone||Object|readonly|swap|reliableMarginRight|onclick|isNumeric|nn|after|Yt|tn|path|tabIndex|Vt|colgroup|Kn|PSEUDO|case|Qt|enabled|Zt|maxWidth|Jt|minWidth|expand|qt|cacheable|focusout|xhr|open|isImmediatePropagationStopped|parent|timeStamp|onbeforeunload|htmlSerialize|metaKey|ajaxTransport|xhrFields|toUpperCase|_just_changed|run|noop|sibling|wn|overrideMimeType|postDispatch|_submit_bubble|Tn|Fn|ajaxPrefilter|leadingWhitespace|createTextNode|namespace_re|globalEval|embed|olddisplay|Et|ecmascript|hasData|throw|_t|encodeURIComponent|fxshow|xn|clientX|charCode|timeout|pageX|scroll|statusCode|Ln|Mn|noCloneEvent|hover|deletedIds|Cn|isSimulated|inprogress|createTween|shrinkWrapBlocks|sizing|file|window|specialEasing|En|inlineBlockNeedsLayout|4px|boxSizingReliable|domain|parseJSON|At|Error|fix|isLocal|Ct|status|responseText|Ot|substring|removeClass|addClass|removeEvent|children|optgroup|addBack|col|iframe|contentDocument|contentWindow|parents|thead|yt|header|fieldset|next|area|wrapInner|prevAll|_change_attached|parsedAttrs|noData|getSetAttribute|toggleClass|__className__|toJSON|fireEvent|notify|then|lock|notifyWith|hasClass|attributes|optSelected|checkOn|getData|setData|hrefNormalized|contenteditable|optDisabled|radioValue|frameBorder|tabindex|resolve|Function|Jn|Qn|overflowX||overflowY|startTime|dataFilter|clearAttributes|mergeAttributes||defaultValue|defaultChecked|uFEFF|xA0|parsererror|Invalid|DOMContentLoaded|doScroll|Microsoft|DOMParser|true|toArray|parseHTML|parse|changeData|customEvent|nodeValue|cacheLength|attrHandle|NAME|POS|random|keypress|contextmenu|keyHooks|mouseHooks|preFilter|only|selectors|getText|isXML|Until|finally|activeElement|hasOwnProperty|image|reset|hasFocus|mouseout|mouseover|keyCode|fromElement|pageY|clientY|key|currentTarget||defaultView|parentWindow|exclusive|preDispatch|toElement|srcElement|focusinBubbles|one||delegateTarget|lastToggle|changeBubbles||_submit_attached||noBubble|getPreventDefault|returnValue|submitBubbles|closest|Tt|pageXOffset|amd|client|responseFields|speed|swing|un|rn|app|parseXML|flatOptions|ajaxComplete|ajaxStop|pn|detach|rejectWith|On|clearTimeout|An|304|getTime|elements|isNaN|doesNotIncludeMarginInBodyOffset|Ft|It|zt|timer|tick|Kt|fontWeight|Xt|jt|sn|datetime|time|serializeArray|visible|setOffset|alpha|reliableHiddenOffsets|toUTCString|mn|http|XMLHttpRequest|getAllResponseHeaders|responseXML|using|Requested||Wn|In|cors|username|statusText||marginTop|zn|pageYOffset|Tween|pos|Gn|Un|marginLeft|jn|unload|scriptCharset|charset|ifModified|Modified|If|01|ajaxStart|vn|443|processData|traditional|headers|beforeSend|urlencoded|_n|head|www|Pn|anim|No|ajaxSend|interval|yn|getBoundingClientRect|chrome|bodyOffset|offsetTop|Rt|uaMatch|appendTo|secure|xt||appendChecked|noCloneChecked|400|throws|prepend|visibility|Lt|eventPhase|shiftKey|resolved|cancelable|altKey|||bubbles|view|ctrlKey|fired||offsetY|screenX|screenY|offsetX|buttons|relatedNode|locked|stopOnFalse|char|attrChange|legend|pending|called|inner|fast|focusoutblur|loaded|animated|was|outer|java|callback|doctype|ecma|CDATA|write|rejected|attrName|close|XMLHTTP|video|summary|slideUp|1_|cancelBubble|stopImmediatePropagation|Boolean|section|originalProperties|fadeIn|propertychange|propertyName||frameElement|Bubbles|slideToggle|output|Number|Content|moz|slideDown|Accept|from||conversion||focusinfocus|600|||Match||None|defaultPrevented|to|Type|img|Since|beforeunload|meta|Transport|prefilter||removeProp|unwrap|512|compatMode|scoped|defer|loop|required|when|borderTopWidth|property|can|wrap|attrFn|404|204|1223|controls|autoplay|applet|uuid|boxModel|444553540000|96B8|D27CDB6E|AE6D|11cf|no|classid|clearQueue|rea|autofocus|delay|fadeTo|5px|borderLeftWidth|changed|CSS1Compat|setAttributeNode|th|prependTo|createAttribute|coords|parseInt||tweener|scrollTo|caption|meter|encoding||withCredentials|clsid|replaceAll||insertAfter|tfoot||Height|contentEditable|pipe|Animation|cellspacing|cellSpacing|maxLength|maxlength|defaultSelected|readOnly|htmlFor|cellpadding|cellPadding|usemap|useMap|frameborder|colSpan|colspan|rowspan|rowSpan|mouse|mark|pseudo|bfnrt|innerText|unsupported|local|switch|month|email|date|color|100|right|prevUntil|siblings|superclass|userAgent|safari|source|range|search|size||setTime|reverse|cos|noConflict||opera|holdReady|beforeactivate|compatible|PI||decodeURIComponent|rv|tel|week|864e5|mozilla|serialize|toString|nextUntil|widows|orphans|lineHeight|zIndex|styleFloat|offsetLeft|sizzle|setInterval|BODY|fillOpacity|Left|Bottom|Right|Webkit|letterSpacing|Moz|All|andSelf|parentsUntil|normal|compile|navigator|1em|expression|unrecognized|bottom|pixelLeft|Syntax|fontSize|mozMatchesSelector|nextAll|location|getPropertyValue|ismap|msMatchesSelector|webkitMatchesSelector|oMatchesSelector|createPseudo|msie|u00a0|ufeff|UTF|eval|plain|audio|die||delegate|undelegate|execScript||getJSON||mousemove|post|ajaxSuccess|mouseup|mousedown|resize|getScript|dblclick|live|unbind|Success|footer|figure|hgroup|canceled|proxy|Top|regexp|notmodified|figcaption|canvas|bdi|bind|datalist|details|Etag|Last|300|ajaxError|aside|slow|isPrototypeOf|extension|linear|fixed|article|originalOptions|child|isFinite|array|about|mg|HTML|clearInterval|createComment||storage||XMLDOM|parseFromString|xa0|fadeToggle|keyup|HEAD|fadeOut||POST|Nn|sizcache|abbr|||x00|keydown|XML|loadXML|widget|res'.split('|'),0,{}))
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('﻿t d={y:""};4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/p.2.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/h/i/j/w.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/h/i/j/v.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/h/i/j/r.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.s.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.x.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.D.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.z.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.l.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.F.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.G.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.H.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.l.9.0\\"><\\/1>");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/2/f/2.l.9.0\\"><\\/1>");4.8("<k A=\\"B\\" m=\\"n\\" 3=\\"5/g\\" o=\\"6/7/0/2/p.2.9.g\\" />");4.8("<k 3=\\"5/g\\" m=\\"n\\" o=\\"6/7/0/2/h/i/q/C.9.g\\" />");4.8("<k 3=\\"5/g\\" m=\\"n\\" o=\\"6/7/0/2/h/i/q/E.9.g\\" />");4.8("<1 a=\\"c\\" 3=\\"5/e\\" b=\\"6/7/0/u.0\\"><\\/1>");',44,44,'js|script|jqplot|type|document|text|Tpl|main|write|min|language|src|JavaScript||javascript|plugins|css|examples|syntaxhighlighter|scripts|link|dateAxisRenderer|rel|stylesheet|href|jquery|styles|shBrushXml|logAxisRenderer|var|m_jqplot|shBrushJScript|shCore|canvasTextRenderer|base|canvasAxisTickRenderer|class|include|shCoreDefault|canvasAxisLabelRenderer|shThemejqPlot|categoryAxisRenderer|barRenderer|pointLabels'.split('|'),0,{}))
;
// Touché: bringing touch events to non-touch browsers https://github.com/davidcalhoun/touche
(function(){  // shouldn't need to wait DOM readiness (famous last words...)
  
  /**
   * 是否触摸设备
   */
  var isTouchDevice = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/);
  
  if(isTouchDevice) {
      // 'looks like touch events are already present, so return early';
      return;
  }
  
  var isMouseDown = false, // so we don't fire touchmove when the mouse is up
      originator,
      fireTouch,
      mousedown,
      mousemove,
      mouseup;
  
  fireTouch = function(type, e) {
    var target,
        newEvent,
        touchesObj;

    target = originator || e.target;
    newEvent = document.createEvent('MouseEvent');  // trying to create an actual TouchEvent will create an error
    newEvent.initMouseEvent(type, true, true, window, e.detail,
                            e.screenX, e.screenY, e.clientX, e.clientY,
                            e.ctrlKey, e.shiftKey, e.altKey, e.metaKey,
                            e.button, e.relatedTarget);
    
    // touches/targetTouches/changedTouches emulation
    touchesObj = [{
      // identifier: unique id for the touch event (lazy.. just hooking it into the timestamp)
      // not using Date.now() just for greater support
      identifier: (new Date()).getTime(),
      pageX:      e.pageX,
      pageY:      e.pageY,
      clientX:    e.clientX,
      clientY:    e.clientY,
      target:     target,
      screenX:    e.screenX,
      screenY:    e.screenY
    }];
    
    switch(type) {
      case 'touchstart':  // e.touches and e.changedTouches and e.targetTouches
        originator = target;
        newEvent.touches = newEvent.changedTouches = newEvent.targetTouches = touchesObj;
      break;
      
      case 'touchmove':   // e.touches and e.changedTouches and e.targetTouches
        newEvent.touches = newEvent.changedTouches = newEvent.targetTouches = touchesObj;
      break;
      
      case 'touchend':    // e.changedTouches only
        originator = null;
        newEvent.changedTouches = touchesObj;
        newEvent.touches = newEvent.targetTouches = [];
      break;
      default:
      break;
    }
    
    // fire off the event!
    e.target.dispatchEvent(newEvent);
  }
  
  // hook up the mouse->touch mapped listeners
  mousedown = function(e) {
    isMouseDown = true;
    fireTouch('touchstart', e);
  };
  mousemove = function(e) {
    if(!isMouseDown) return;
    fireTouch('touchmove', e);
  };
  mouseup = function(e) {
    isMouseDown = false;
    fireTouch('touchend', e);
  };
  document.addEventListener('mousedown', mousedown, false);
  document.addEventListener('mousemove', mousemove, false);
  document.addEventListener('mouseup', mouseup, false);
  
  // old style handlers - only here to get around feature detection (comment if you need to)
  window.ontouchstart = mousedown;
  window.ontouchmove  = mousemove;
  window.ontouchend   = mouseup;

})();
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(5(){4 e=8,h=8,r=8,p=8,F,q,B=0,9=0,2={$b:$(\'U\'),$7:d,J:s,D:10,i:d,Q:6,z:{P:d,15:d,S:d,N:d,Z:d,}};4 1i=5(u){4 Y={x:u.A||u.1j,y:u.C||u.1h};3 Y};4 o=5(X){e=8,r=8,F,q,B=0;2.$7.I({\'k-f\':-9},(X==8?0:1c),5(){h=8;n(\'Z\')})};4 16=5(a){4 j=g(2.$b.j());4 c=a.E[0],x=g(c.A),y=g(c.C);1(1d(1e)!=\'10\'){4 V=$(\'#12-11-W\').1m().f+$(\'#12-11-W\').m();1(y<V){3 8}}F=x;q=y;1($(H).j()>0){3}1(j>0)3;1(p)3;1(h)3;h=s;r=s};4 1a=5(a){1(p)3;1(!h)3;1(!r)3;4 $7=2.$7,c=a.E[0],x=g(c.A),y=g(c.C),t=y-q;1($(H).j()>0){3}1(!e&&t>2.Q){e=s;n(\'P\')}1(!e)3;1(a.v)a.v();1(t<0){$7.w(\'k-f\',-9)}1(t>0&&t<=9){$7.w(\'k-f\',-(9-t));$7.m(9)}K 1(t>9){$7.w(\'k-f\',0);$7.m(t)}B=t};4 n=5(G,14){1(2.z[G])2.z[G].1n(d,14)};4 13=5(a){1(p)3;1(!e)3;1(!h)3;1(a.v)a.v();1($(H).j()>0){3}e=8;r=8;B=0;4 $7=2.$7,c=a.E[0]||a.1f[0],x=g(c.A),y=g(c.C),t=y-q;1(t<=9){$7.w(\'k-f\',-(9-t));$7.m(9);$7.I({\'k-f\':-9},17,5(){h=8})}K 1(t>9){1(2.19)2.19();$7.I({\'m\':9},17,5(){})}4 i=$.1l(2.i)?2.i():2.i;1(2.D){n(\'15\');$.1s(2.D,i,5(18,1u,1k){n(\'S\',18);1(2.J)o()}).N(5(){n(\'N\');1(2.J)o()})}K{1(2.z)o()}};4 O=5(1b){p=1b||8};4 T=5(){9=2.$7.m();$b=2.$b;$b[0].L(\'1o\',16,8);$b[0].L(\'1r\',1a,8);$b[0].L(\'1g\',13,8)};4 R=5(){T();3{o:o,O:O}};$.M=5(l){$.1p(s,2,(l||{}));2.$b=2.$b||$(\'U\');3 R(l)};$.1t.M=5(l){l.$b=$(1q);3 $.M(l)}})();',62,93,'|if|options|return|var|function||loadingEl|false|loadingH|evt|el|touch|null|isValid|top|parseInt|isTouching|sendData|scrollTop|margin|settings|height|runCb|reset|isDestory|startY|isEfec|true||event|preventDefault|css|||callbacks|pageX|disY|pageY|url|touches|startX|name|document|animate|autoHide|else|addEventListener|pPullRefresh|error|setDestroy|pullStart|startPX|pullDown|success|initlize|body|img_set_top|box|isAnim|pos|end|undefined|adv|index|touchEnd|data|start|touchStart|200|response|cb|touchMove|destroy|800|typeof|is_index_set|changedTouches|touchend|clientY|getPos|clientX|xhr|isFunction|offset|call|touchstart|extend|this|touchmove|post|fn|textStatus'.split('|'),0,{}))
