/*
* waitForImages 1.3
* -----------------
* Provides a callback when all images have loaded in your given selector.
* http://www.alexanderdickson.com/
*
*
* Copyright (c) 2011 Alex Dickson
* Licensed under the MIT licenses.
* See website for more info.
*
*/

;(function($) {
    $.fn.waitForImages = function(finishedCallback, eachCallback) {

        eachCallback = eachCallback || function() {};

        if ( ! $.isFunction(finishedCallback) || ! $.isFunction(eachCallback)) {
            throw {
                name: 'invalid_callback',
                message: 'An invalid callback was supplied.'
            };
        }

        var objs = $(this),
            allImgs = objs.find('img'),
            allImgsLength = allImgs.length,
            allImgsLoaded = 0;

        if (allImgsLength == 0) {
            finishedCallback.call(this);
        }

        return objs.each(function() {
            var obj = $(this);
			var imgs = obj.find('img');

            if (imgs.length == 0) {
                return true;
            }

            imgs.each(function() {
                var image = new Image,
                    imgElement = this;

                image.onload = function() {
                    allImgsLoaded++;
                    eachCallback.call(imgElement, allImgsLoaded, allImgsLength);
                    if (allImgsLoaded == allImgsLength) {
                        finishedCallback.call(obj[0]);
                        return false;
                    }
                }

                image.src = this.src;
            });
        });
    };
})(jQuery);

/**
 * jQuery Dimensions
 *
 * Copyright (c) 2007 Paul Bakaus (paul.bakaus@googlemail.com) and Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $LastChangedDate: 2007-06-22 04:38:37 +0200 (Fr, 22 Jun 2007) $
 * $Rev: 2141 $
 *
 * Version: 1.0b2
 */
(function(a){var b=a.fn.height,c=a.fn.width;a.fn.extend({height:function(){if(this[0]==window)return self.innerHeight||a.boxModel&&document.documentElement.clientHeight||document.body.clientHeight;if(this[0]==document)return Math.max(document.body.scrollHeight,document.body.offsetHeight);return b.apply(this,arguments)},width:function(){if(this[0]==window)return self.innerWidth||a.boxModel&&document.documentElement.clientWidth||document.body.clientWidth;if(this[0]==document)return Math.max(document.body.scrollWidth,document.body.offsetWidth);return c.apply(this,arguments)},innerHeight:function(){return this[0]==window||this[0]==document?this.height():this.is(":visible")?this[0].offsetHeight-d(this,"borderTopWidth")-d(this,"borderBottomWidth"):this.height()+d(this,"paddingTop")+d(this,"paddingBottom")},innerWidth:function(){return this[0]==window||this[0]==document?this.width():this.is(":visible")?this[0].offsetWidth-d(this,"borderLeftWidth")-d(this,"borderRightWidth"):this.width()+d(this,"paddingLeft")+d(this,"paddingRight")},outerHeight:function(){return this[0]==window||this[0]==document?this.height():this.is(":visible")?this[0].offsetHeight:this.height()+d(this,"borderTopWidth")+d(this,"borderBottomWidth")+d(this,"paddingTop")+d(this,"paddingBottom")},outerWidth:function(){return this[0]==window||this[0]==document?this.width():this.is(":visible")?this[0].offsetWidth:this.width()+d(this,"borderLeftWidth")+d(this,"borderRightWidth")+d(this,"paddingLeft")+d(this,"paddingRight")},scrollLeft:function(b){if(b!=undefined)return this.each(function(){if(this==window||this==document)window.scrollTo(b,a(window).scrollTop());else this.scrollLeft=b});if(this[0]==window||this[0]==document)return self.pageXOffset||a.boxModel&&document.documentElement.scrollLeft||document.body.scrollLeft;return this[0].scrollLeft},scrollTop:function(b){if(b!=undefined)return this.each(function(){if(this==window||this==document)window.scrollTo(a(window).scrollLeft(),b);else this.scrollTop=b});if(this[0]==window||this[0]==document)return self.pageYOffset||a.boxModel&&document.documentElement.scrollTop||document.body.scrollTop;return this[0].scrollTop},position:function(b,c){var f=this[0],g=f.parentNode,h=f.offsetParent,b=a.extend({margin:false,border:false,padding:false,scroll:false},b||{}),i=f.offsetLeft,j=f.offsetTop,k=f.scrollLeft,l=f.scrollTop;if(a.browser.mozilla||a.browser.msie){i+=d(f,"borderLeftWidth");j+=d(f,"borderTopWidth")}if(a.browser.mozilla){do{if(a.browser.mozilla&&g!=f&&a.css(g,"overflow")!="visible"){i+=d(g,"borderLeftWidth");j+=d(g,"borderTopWidth")}if(g==h)break}while((g=g.parentNode)&&(g.tagName.toLowerCase()!="body"||g.tagName.toLowerCase()!="html"))}var m=e(f,b,i,j,k,l);if(c){a.extend(c,m);return this}else{return m}},offset:function(b,c){var f=0,g=0,h=0,i=0,j=this[0],k=this[0],l,m,n=a.css(j,"position"),o=a.browser.mozilla,p=a.browser.msie,q=a.browser.safari,r=a.browser.opera,s=false,t=false,b=a.extend({margin:true,border:false,padding:false,scroll:true,lite:false},b||{});if(b.lite)return this.offsetLite(b,c);if(j.tagName.toLowerCase()=="body"){f=j.offsetLeft;g=j.offsetTop;if(o){f+=d(j,"marginLeft")+d(j,"borderLeftWidth")*2;g+=d(j,"marginTop")+d(j,"borderTopWidth")*2}else if(r){f+=d(j,"marginLeft");g+=d(j,"marginTop")}else if(p&&jQuery.boxModel){f+=d(j,"borderLeftWidth");g+=d(j,"borderTopWidth")}}else{do{m=a.css(k,"position");f+=k.offsetLeft;g+=k.offsetTop;if(o||p){f+=d(k,"borderLeftWidth");g+=d(k,"borderTopWidth");if(o&&m=="absolute")s=true;if(p&&m=="relative")t=true}l=k.offsetParent;if(b.scroll||o){do{if(b.scroll){h+=k.scrollLeft;i+=k.scrollTop}if(o&&k!=j&&a.css(k,"overflow")!="visible"){f+=d(k,"borderLeftWidth");g+=d(k,"borderTopWidth")}k=k.parentNode}while(k!=l)}k=l;if(k.tagName.toLowerCase()=="body"||k.tagName.toLowerCase()=="html"){if((q||p&&a.boxModel)&&n!="absolute"&&n!="fixed"){f+=d(k,"marginLeft");g+=d(k,"marginTop")}if(o&&!s&&n!="fixed"||p&&n=="static"&&!t){f+=d(k,"borderLeftWidth");g+=d(k,"borderTopWidth")}break}}while(k)}var u=e(j,b,f,g,h,i);if(c){a.extend(c,u);return this}else{return u}},offsetLite:function(b,c){var d=0,f=0,g=0,h=0,i=this[0],j,b=a.extend({margin:true,border:false,padding:false,scroll:true},b||{});do{d+=i.offsetLeft;f+=i.offsetTop;j=i.offsetParent;if(b.scroll){do{g+=i.scrollLeft;h+=i.scrollTop;i=i.parentNode}while(i!=j)}i=j}while(i&&i.tagName.toLowerCase()!="body"&&i.tagName.toLowerCase()!="html");var k=e(this[0],b,d,f,g,h);if(c){a.extend(c,k);return this}else{return k}}});var d=function(b,c){return parseInt(a.css(b.jquery?b[0]:b,c))||0};var e=function(b,c,e,f,g,h){if(!c.margin){e-=d(b,"marginLeft");f-=d(b,"marginTop")}if(c.border&&(a.browser.safari||a.browser.opera)){e+=d(b,"borderLeftWidth");f+=d(b,"borderTopWidth")}else if(!c.border&&!(a.browser.safari||a.browser.opera)){e-=d(b,"borderLeftWidth");f-=d(b,"borderTopWidth")}if(c.padding){e+=d(b,"paddingLeft");f+=d(b,"paddingTop")}if(c.scroll){g-=b.scrollLeft;h-=b.scrollTop}return c.scroll?{top:f-h,left:e-g,scrollTop:h,scrollLeft:g}:{top:f,left:e}}})(jQuery)

/*
 * jQuery Tooltip plugin 1.3
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-tooltip/
 * http://docs.jquery.com/Plugins/Tooltip
 *
 * Copyright (c) 2006 - 2008 JÃƒÂ¶rn Zaefferer
 *
 * $Id: jquery.tooltip.js 5741 2008-06-21 15:22:16Z joern.zaefferer $
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
;(function($){var helper={},current,title,tID,IE=$.browser.msie&&/MSIE\s(5\.5|6\.)/.test(navigator.userAgent),track=false;$.tooltip={blocked:false,defaults:{delay:200,fade:false,showURL:true,extraClass:"",top:15,left:15,id:"tooltip"},block:function(){$.tooltip.blocked=!$.tooltip.blocked;}};$.fn.extend({tooltip:function(settings){settings=$.extend({},$.tooltip.defaults,settings);createHelper(settings);return this.each(function(){$.data(this,"tooltip",settings);this.tOpacity=helper.parent.css("opacity");this.tooltipText=this.title;$(this).removeAttr("title");this.alt="";}).mouseover(save).mouseout(hide).click(hide);},fixPNG:IE?function(){return this.each(function(){var image=$(this).css('backgroundImage');if(image.match(/^url\(["']?(.*\.png)["']?\)$/i)){image=RegExp.$1;$(this).css({'backgroundImage':'none','filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='"+image+"')"}).each(function(){var position=$(this).css('position');if(position!='absolute'&&position!='relative')$(this).css('position','relative');});}});}:function(){return this;},unfixPNG:IE?function(){return this.each(function(){$(this).css({'filter':'',backgroundImage:''});});}:function(){return this;},hideWhenEmpty:function(){return this.each(function(){$(this)[$(this).html()?"show":"hide"]();});},url:function(){return this.attr('href')||this.attr('src');}});function createHelper(settings){if(helper.parent)return;helper.parent=$('<div id="'+settings.id+'"><h3></h3><div class="body"></div><div class="url"></div></div>').appendTo(document.body).hide();if($.fn.bgiframe)helper.parent.bgiframe();helper.title=$('h3',helper.parent);helper.body=$('div.body',helper.parent);helper.url=$('div.url',helper.parent);}function settings(element){return $.data(element,"tooltip");}function handle(event){if(settings(this).delay)tID=setTimeout(show,settings(this).delay);else
show();track=!!settings(this).track;$(document.body).bind('mousemove',update);update(event);}function save(){if($.tooltip.blocked||this==current||(!this.tooltipText&&!settings(this).bodyHandler))return;current=this;title=this.tooltipText;if(settings(this).bodyHandler){helper.title.hide();var bodyContent=settings(this).bodyHandler.call(this);if(bodyContent.nodeType||bodyContent.jquery){helper.body.empty().append(bodyContent)}else{helper.body.html(bodyContent);}helper.body.show();}else if(settings(this).showBody){var parts=title.split(settings(this).showBody);helper.title.html(parts.shift()).show();helper.body.empty();for(var i=0,part;(part=parts[i]);i++){if(i>0)helper.body.append("<br/>");helper.body.append(part);}helper.body.hideWhenEmpty();}else{helper.title.html(title).show();helper.body.hide();}if(settings(this).showURL&&$(this).url())helper.url.html($(this).url().replace('http://','')).show();else
helper.url.hide();helper.parent.addClass(settings(this).extraClass);if(settings(this).fixPNG)helper.parent.fixPNG();handle.apply(this,arguments);}function show(){tID=null;if((!IE||!$.fn.bgiframe)&&settings(current).fade){if(helper.parent.is(":animated"))helper.parent.stop().show().fadeTo(settings(current).fade,current.tOpacity);else
helper.parent.is(':visible')?helper.parent.fadeTo(settings(current).fade,current.tOpacity):helper.parent.fadeIn(settings(current).fade);}else{helper.parent.show();}update();}function update(event){if($.tooltip.blocked)return;if(event&&event.target.tagName=="OPTION"){return;}if(!track&&helper.parent.is(":visible")){$(document.body).unbind('mousemove',update)}if(current==null){$(document.body).unbind('mousemove',update);return;}helper.parent.removeClass("viewport-right").removeClass("viewport-bottom");var left=helper.parent[0].offsetLeft;var top=helper.parent[0].offsetTop;if(event){left=event.pageX+settings(current).left;top=event.pageY+settings(current).top;var right='auto';if(settings(current).positionLeft){right=$(window).width()-left;left='auto';}helper.parent.css({left:left,right:right,top:top});}var v=viewport(),h=helper.parent[0];if(v.x+v.cx<h.offsetLeft+h.offsetWidth){left-=h.offsetWidth+20+settings(current).left;helper.parent.css({left:left+'px'}).addClass("viewport-right");}if(v.y+v.cy<h.offsetTop+h.offsetHeight){top-=h.offsetHeight+20+settings(current).top;helper.parent.css({top:top+'px'}).addClass("viewport-bottom");}}function viewport(){return{x:$(window).scrollLeft(),y:$(window).scrollTop(),cx:$(window).width(),cy:$(window).height()};}function hide(event){if($.tooltip.blocked)return;if(tID)clearTimeout(tID);current=null;var tsettings=settings(this);function complete(){helper.parent.removeClass(tsettings.extraClass).hide().css("opacity","");}if((!IE||!$.fn.bgiframe)&&tsettings.fade){if(helper.parent.is(':animated'))helper.parent.stop().fadeTo(tsettings.fade,0,complete);else
helper.parent.stop().fadeOut(tsettings.fade,complete);}else
complete();if(settings(this).fixPNG)helper.parent.unfixPNG();}})(jQuery);

/*
 * SimpleModal 1.4.1 - jQuery Plugin
 * http://www.ericmmartin.com/projects/simplemodal/
 * Copyright (c) 2010 Eric Martin (http://twitter.com/ericmmartin)
 * Dual licensed under the MIT and GPL licenses
 * Revision: $Id: jquery.simplemodal.js 261 2010-11-05 21:16:20Z emartin24 $
 */
(function(d){var k=d.browser.msie&&parseInt(d.browser.version)===6&&typeof window.XMLHttpRequest!=="object",m=d.browser.msie&&parseInt(d.browser.version)===7,l=null,f=[];d.modal=function(a,b){return d.modal.impl.init(a,b)};d.modal.close=function(){d.modal.impl.close()};d.modal.focus=function(a){d.modal.impl.focus(a)};d.modal.setContainerDimensions=function(){d.modal.impl.setContainerDimensions()};d.modal.setPosition=function(){d.modal.impl.setPosition()};d.modal.update=function(a,b){d.modal.impl.update(a,
b)};d.fn.modal=function(a){return d.modal.impl.init(this,a)};d.modal.defaults={appendTo:"body",focus:true,opacity:50,overlayId:"simplemodal-overlay",overlayCss:{},containerId:"simplemodal-container",containerCss:{},dataId:"simplemodal-data",dataCss:{},minHeight:null,minWidth:null,maxHeight:null,maxWidth:null,autoResize:false,autoPosition:true,zIndex:1E3,close:true,closeHTML:'<a class="modalCloseImg" title="Close"></a>',closeClass:"simplemodal-close",escClose:true,overlayClose:false,position:null,
persist:false,modal:true,onOpen:null,onShow:null,onClose:null};d.modal.impl={d:{},init:function(a,b){var c=this;if(c.d.data)return false;l=d.browser.msie&&!d.boxModel;c.o=d.extend({},d.modal.defaults,b);c.zIndex=c.o.zIndex;c.occb=false;if(typeof a==="object"){a=a instanceof jQuery?a:d(a);c.d.placeholder=false;if(a.parent().parent().size()>0){a.before(d("<span></span>").attr("id","simplemodal-placeholder").css({display:"none"}));c.d.placeholder=true;c.display=a.css("display");if(!c.o.persist)c.d.orig=
a.clone(true)}}else if(typeof a==="string"||typeof a==="number")a=d("<div></div>").html(a);else{alert("SimpleModal Error: Unsupported data type: "+typeof a);return c}c.create(a);c.open();d.isFunction(c.o.onShow)&&c.o.onShow.apply(c,[c.d]);return c},create:function(a){var b=this;f=b.getDimensions();if(b.o.modal&&k)b.d.iframe=d('<iframe src="javascript:false;"></iframe>').css(d.extend(b.o.iframeCss,{display:"none",opacity:0,position:"fixed",height:f[0],width:f[1],zIndex:b.o.zIndex,top:0,left:0})).appendTo(b.o.appendTo);
b.d.overlay=d("<div></div>").attr("id",b.o.overlayId).addClass("simplemodal-overlay").css(d.extend(b.o.overlayCss,{display:"none",opacity:b.o.opacity/100,height:b.o.modal?f[0]:0,width:b.o.modal?f[1]:0,position:"fixed",left:0,top:0,zIndex:b.o.zIndex+1})).appendTo(b.o.appendTo);b.d.container=d("<div></div>").attr("id",b.o.containerId).addClass("simplemodal-container").css(d.extend(b.o.containerCss,{display:"none",position:"fixed",zIndex:b.o.zIndex+2})).append(b.o.close&&b.o.closeHTML?d(b.o.closeHTML).addClass(b.o.closeClass):
"").appendTo(b.o.appendTo);b.d.wrap=d("<div></div>").attr("tabIndex",-1).addClass("simplemodal-wrap").css({height:"100%",outline:0,width:"100%"}).appendTo(b.d.container);b.d.data=a.attr("id",a.attr("id")||b.o.dataId).addClass("simplemodal-data").css(d.extend(b.o.dataCss,{display:"none"})).appendTo("body");b.setContainerDimensions();b.d.data.appendTo(b.d.wrap);if(k||l)b.fixIE()},bindEvents:function(){var a=this;d("."+a.o.closeClass).bind("click.simplemodal",function(b){b.preventDefault();a.close()});
a.o.modal&&a.o.close&&a.o.overlayClose&&a.d.overlay.bind("click.simplemodal",function(b){b.preventDefault();a.close()});d(document).bind("keydown.simplemodal",function(b){if(a.o.modal&&b.keyCode===9)a.watchTab(b);else if(a.o.close&&a.o.escClose&&b.keyCode===27){b.preventDefault();a.close()}});d(window).bind("resize.simplemodal",function(){f=a.getDimensions();a.o.autoResize?a.setContainerDimensions():a.o.autoPosition&&a.setPosition();if(k||l)a.fixIE();else if(a.o.modal){a.d.iframe&&a.d.iframe.css({height:f[0],
width:f[1]});a.d.overlay.css({height:f[0],width:f[1]})}})},unbindEvents:function(){d("."+this.o.closeClass).unbind("click.simplemodal");d(document).unbind("keydown.simplemodal");d(window).unbind("resize.simplemodal");this.d.overlay.unbind("click.simplemodal")},fixIE:function(){var a=this,b=a.o.position;d.each([a.d.iframe||null,!a.o.modal?null:a.d.overlay,a.d.container],function(c,h){if(h){var g=h[0].style;g.position="absolute";if(c<2){g.removeExpression("height");g.removeExpression("width");g.setExpression("height",
'document.body.scrollHeight > document.body.clientHeight ? document.body.scrollHeight : document.body.clientHeight + "px"');g.setExpression("width",'document.body.scrollWidth > document.body.clientWidth ? document.body.scrollWidth : document.body.clientWidth + "px"')}else{var e;if(b&&b.constructor===Array){c=b[0]?typeof b[0]==="number"?b[0].toString():b[0].replace(/px/,""):h.css("top").replace(/px/,"");c=c.indexOf("%")===-1?c+' + (t = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"':
parseInt(c.replace(/%/,""))+' * ((document.documentElement.clientHeight || document.body.clientHeight) / 100) + (t = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"';if(b[1]){e=typeof b[1]==="number"?b[1].toString():b[1].replace(/px/,"");e=e.indexOf("%")===-1?e+' + (t = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft) + "px"':parseInt(e.replace(/%/,""))+' * ((document.documentElement.clientWidth || document.body.clientWidth) / 100) + (t = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft) + "px"'}}else{c=
'(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (t = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"';e='(document.documentElement.clientWidth || document.body.clientWidth) / 2 - (this.offsetWidth / 2) + (t = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft) + "px"'}g.removeExpression("top");g.removeExpression("left");g.setExpression("top",
c);g.setExpression("left",e)}}})},focus:function(a){var b=this;a=a&&d.inArray(a,["first","last"])!==-1?a:"first";var c=d(":input:enabled:visible:"+a,b.d.wrap);setTimeout(function(){c.length>0?c.focus():b.d.wrap.focus()},10)},getDimensions:function(){var a=d(window);return[d.browser.opera&&d.browser.version>"9.5"&&d.fn.jquery<"1.3"||d.browser.opera&&d.browser.version<"9.5"&&d.fn.jquery>"1.2.6"?a[0].innerHeight:a.height(),a.width()]},getVal:function(a,b){return a?typeof a==="number"?a:a==="auto"?0:
a.indexOf("%")>0?parseInt(a.replace(/%/,""))/100*(b==="h"?f[0]:f[1]):parseInt(a.replace(/px/,"")):null},update:function(a,b){var c=this;if(!c.d.data)return false;c.d.origHeight=c.getVal(a,"h");c.d.origWidth=c.getVal(b,"w");c.d.data.hide();a&&c.d.container.css("height",a);b&&c.d.container.css("width",b);c.setContainerDimensions();c.d.data.show();c.o.focus&&c.focus();c.unbindEvents();c.bindEvents()},setContainerDimensions:function(){var a=this,b=k||m,c=a.d.origHeight?a.d.origHeight:d.browser.opera?
a.d.container.height():a.getVal(b?a.d.container[0].currentStyle.height:a.d.container.css("height"),"h");b=a.d.origWidth?a.d.origWidth:d.browser.opera?a.d.container.width():a.getVal(b?a.d.container[0].currentStyle.width:a.d.container.css("width"),"w");var h=a.d.data.outerHeight(true),g=a.d.data.outerWidth(true);a.d.origHeight=a.d.origHeight||c;a.d.origWidth=a.d.origWidth||b;var e=a.o.maxHeight?a.getVal(a.o.maxHeight,"h"):null,i=a.o.maxWidth?a.getVal(a.o.maxWidth,"w"):null;e=e&&e<f[0]?e:f[0];i=i&&i<
f[1]?i:f[1];var j=a.o.minHeight?a.getVal(a.o.minHeight,"h"):"auto";c=c?a.o.autoResize&&c>e?e:c<j?j:c:h?h>e?e:a.o.minHeight&&j!=="auto"&&h<j?j:h:j;e=a.o.minWidth?a.getVal(a.o.minWidth,"w"):"auto";b=b?a.o.autoResize&&b>i?i:b<e?e:b:g?g>i?i:a.o.minWidth&&e!=="auto"&&g<e?e:g:e;a.d.container.css({height:c,width:b});a.d.wrap.css({overflow:h>c||g>b?"auto":"visible"});a.o.autoPosition&&a.setPosition()},setPosition:function(){var a=this,b,c;b=f[0]/2-a.d.container.outerHeight(true)/2;c=f[1]/2-a.d.container.outerWidth(true)/
2;if(a.o.position&&Object.prototype.toString.call(a.o.position)==="[object Array]"){b=a.o.position[0]||b;c=a.o.position[1]||c}else{b=b;c=c}a.d.container.css({left:c,top:b})},watchTab:function(a){var b=this;if(d(a.target).parents(".simplemodal-container").length>0){b.inputs=d(":input:enabled:visible:first, :input:enabled:visible:last",b.d.data[0]);if(!a.shiftKey&&a.target===b.inputs[b.inputs.length-1]||a.shiftKey&&a.target===b.inputs[0]||b.inputs.length===0){a.preventDefault();b.focus(a.shiftKey?"last":
"first")}}else{a.preventDefault();b.focus()}},open:function(){var a=this;a.d.iframe&&a.d.iframe.show();if(d.isFunction(a.o.onOpen))a.o.onOpen.apply(a,[a.d]);else{a.d.overlay.show();a.d.container.show();a.d.data.show()}a.o.focus&&a.focus();a.bindEvents()},close:function(){var a=this;if(!a.d.data)return false;a.unbindEvents();if(d.isFunction(a.o.onClose)&&!a.occb){a.occb=true;a.o.onClose.apply(a,[a.d])}else{if(a.d.placeholder){var b=d("#simplemodal-placeholder");if(a.o.persist)b.replaceWith(a.d.data.removeClass("simplemodal-data").css("display",
a.display));else{a.d.data.hide().remove();b.replaceWith(a.d.orig)}}else a.d.data.hide().remove();a.d.container.hide().remove();a.d.overlay.hide();a.d.iframe&&a.d.iframe.hide().remove();setTimeout(function(){a.d.overlay.remove();a.d={}},10)}}}})(jQuery);

/**
 * photonic.js - Contains all custom JavaScript functions required by Photonic
 */
$j = jQuery.noConflict();

function photonicHtmlEncode(value){
	return $j('<div/>').text(value).html();
}

function photonicHtmlDecode(value){
	return $j('<div/>').html(value).text();
}

$j(document).ready(function() {
	// JQuery Cycle stops if there is only one image in it. The following snippet fixes the issue.
	$j('#sliderContent, .sliderContent').each(function() {
		if ($j(this).children().length == 1) {
			var single = this.firstChild;
			$j(single).show();
		}
	});

	if (Photonic_JS.slideshow_library == 'fancybox' && Photonic_JS.slideshow_mode) {
		setInterval($j.fancybox.next, parseInt(Photonic_JS.slideshow_interval, 10));
	}

	$j('a.launch-gallery-fancybox').each(function() {
		$j(this).fancybox({
			transitionIn	:	'elastic',
			transitionOut	:	'elastic',
			speedIn		:	600,
			speedOut		:	200,
			overlayShow	:	true,
			overlayColor:	'#000',
			overlayOpacity: 0.8,
			titleShow	: Photonic_JS.fbox_show_title,
			titlePosition	: Photonic_JS.fbox_title_position
		});
	});

	if ($j.prettyPhoto) {
		$j("a[rel^='photonic-prettyPhoto']").prettyPhoto({
			theme: Photonic_JS.pphoto_theme,
			autoplay_slideshow: Photonic_JS.slideshow_mode,
			slideshow: Photonic_JS.slideshow_interval,
			show_title: false,
			social_tools: '',
			deeplinking: false
		});
	}

	$j('a.launch-gallery-colorbox').each(function() {
		$j(this).colorbox({
			opacity: 0.8,
			maxWidth: '95%',
			maxHeight: '95%',
			slideshow: Photonic_JS.slideshow_mode,
			slideshowSpeed: Photonic_JS.slideshow_interval
		});
	});

	$j('.photonic-flickr-set-thumb').live('click', function() {
		photonicDisplaySetPopup(this);
		return false;
	});

	$j('.photonic-flickr-gallery-thumb').live('click', function() {
		photonicDisplayGalleryPopup(this);
		return false;
	});

	$j('.photonic-picasa-album-thumb').live('click', function(e) {
		var thumb_id = this.id;
		var href = this.href;
		var panel_id = thumb_id.substr(28);
		var panel = '#photonic-picasa-panel-' + panel_id;

		var loading = document.createElement('div');
		loading.className = 'photonic-loading';
		$j(loading).appendTo($j('body')).show();

		if ($j(panel).length == 0) {
			$j.post(Photonic_JS.ajaxurl, "action=photonic_picasa_display_album&panel=" + thumb_id + "&href=" + href, function(data) {
				var div = $j(data);
				//div.id = 'photonic-picasa-panel-' + panel_id;

				var ul = div.find('ul');
				var screens = ul.find('li').length;
				var prev = document.createElement('a');
				prev.id = 'photonic-picasa-album-' + panel_id + '-prev';
				prev.href = '#';
				prev.className = 'panel-previous';
				prev.innerHTML = '&nbsp;';

				var next = document.createElement('a');
				next.id = 'photonic-picasa-album-' + panel_id + '-next';
				next.href = '#';
				next.className = 'panel-next';
				next.innerHTML = '&nbsp;';

				$j(ul).first('li').waitForImages(function() {
					div.attr('id', 'photonic-picasa-panel-' + panel_id).appendTo($j('#photonic-picasa-album-' + panel_id)).show();
					if (screens > 1) {
						$j(ul).before(prev)
							.after(next)
							.cycle({
								timeout: 0,
								slideResize: false,
								prev: 'a#photonic-picasa-album-' + panel_id + '-prev',
								next: 'a#photonic-picasa-album-' + panel_id + '-next',
								sync: false
							});
					}
					else {
						$j(this).cycle({
							timeout: 0,
							slideResize: false,
							sync: false
						});
					}

					$j(panel).modal({
						autoPosition: false,
						dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
						overlayCss: { background: '#000' },
						closeClass: 'photonic-picasa-panel-' + panel_id,
						opacity: 90,
						close: true,
						escClose: false,
						containerId: 'photonic-picasa-panel-container-' + panel_id,
						onClose: function(dialog) { $j.modal.close(); $j('#photonic-picasa-panel-' + panel_id).css({ display: 'none' }) },
						onShow: modalOnPicasaShow,
						onOpen: modalOpen
					});

					var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
					var target = {};

					target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-picasa-panel-container-' + panel_id).height() - 40) * 0.5)), 10);
					target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-picasa-panel-container-' + panel_id).width() - 40) * 0.5)), 10);

					$j('#photonic-picasa-panel-container-' + panel_id).css({top: target.top, left: target.left });
					$j(loading).hide();
				});
			});
		}
		else {
			$j(loading).hide();
			$j(panel).modal({
				autoPosition: false,
				dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
				overlayCss: { background: '#000' },
				opacity: 90,
				close: true,
				escClose: false,
				containerId: 'photonic-picasa-panel-container-' + panel_id,
				onClose: modalClose
			});
			var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
			var target = {};
			target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-picasa-panel-' + panel_id).height() - 40) * 0.5)), 10);
			target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-picasa-panel-' + panel_id).width() - 40) * 0.5)), 10);
			$j('#' + 'photonic-picasa-panel-container-' + panel_id).css({top: target.top, left: target.left});
			$j('.slideshow-grid-panel').cycle({timeout: 0, prev: 'a#photonic-picasa-album-' + panel_id + '-prev', next: 'a#photonic-picasa-album-' + panel_id + '-next'});
		}

		return false;
	});

	$j('a.photonic-smug-album-thumb').live('click', function(e) {
		if ($j(this).hasClass('photonic-smug-passworded')) {
			return false;
		}

		var thumb_id = this.id;
		var href = this.href;
		var panel_id = thumb_id.substr(26);
		var panel = '#photonic-smug-panel-' + panel_id;

		var loading = document.createElement('div');
		loading.className = 'photonic-loading';
		$j(loading).appendTo($j('body')).show();

		if ($j(panel).length == 0) {
			$j.post(Photonic_JS.ajaxurl, "action=photonic_smug_display_album&panel=" + thumb_id + "&href=" + href, function(data) {
				var div = $j(data);

				var ul = div.find('ul');
				var screens = ul.find('li').length;
				var prev = document.createElement('a');
				prev.id = 'photonic-smug-album-' + panel_id + '-prev';
				prev.href = '#';
				prev.className = 'panel-previous';
				prev.innerHTML = '&nbsp;';

				var next = document.createElement('a');
				next.id = 'photonic-smug-album-' + panel_id + '-next';
				next.href = '#';
				next.className = 'panel-next';
				next.innerHTML = '&nbsp;';

				$j(ul).first('li').waitForImages(function() {
					div.attr('id', 'photonic-smug-panel-' + panel_id).appendTo($j('#photonic-smug-album-' + panel_id)).show();
					if (screens > 1) {
						$j(ul).before(prev)
							.after(next)
							.cycle({
								timeout: 0,
								slideResize: false,
								prev: 'a#photonic-smug-album-' + panel_id + '-prev',
								next: 'a#photonic-smug-album-' + panel_id + '-next',
								sync: false
							});
					}
					else {
						$j(this).cycle({
							timeout: 0,
							slideResize: false,
							sync: false
						});
					}

					$j(panel).modal({
						autoPosition: false,
						dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
						overlayCss: { background: '#000' },
						closeClass: 'photonic-smug-panel-' + panel_id,
						opacity: 90,
						close: true,
						escClose: false,
						containerId: 'photonic-smug-panel-container-' + panel_id,
						onClose: function(dialog) { $j.modal.close(); $j('#photonic-smug-panel-' + panel_id).css({ display: 'none' }) },
						onShow: modalOnSmugShow,
						onOpen: modalOpen
					});

					var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
					var target = {};

					target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-smug-panel-container-' + panel_id).height() - 40) * 0.5)), 10);
					target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-smug-panel-container-' + panel_id).width() - 40) * 0.5)), 10);

					$j('#photonic-smug-panel-container-' + panel_id).css({top: target.top, left: target.left });
					$j(loading).hide();
				});
			});
		}
		else {
			$j(loading).hide();
			$j(panel).modal({
				autoPosition: false,
				dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
				overlayCss: { background: '#000' },
				opacity: 90,
				close: true,
				escClose: false,
				containerId: 'photonic-smug-panel-container-' + panel_id,
				onClose: modalClose
			});
			var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
			var target = {};
			target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-smug-panel-' + panel_id).height() - 40) * 0.5)), 10);
			target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-smug-panel-' + panel_id).width() - 40) * 0.5)), 10);
			$j('#' + 'photonic-smug-panel-container-' + panel_id).css({top: target.top, left: target.left});
			$j('.slideshow-grid-panel').cycle({timeout: 0, prev: 'a#photonic-smug-album-' + panel_id + '-prev', next: 'a#photonic-smug-album-' + panel_id + '-next'});
		}

		return false;
	});

	$j('a.modalCloseImg').live('click', function() {
		var thisClass = this.className;
		thisClass = thisClass.substr(14);
		$j('#' + thisClass).hide();
	});

	$j('.photonic-flickr-stream a, a.photonic-flickr-set-thumb, a.photonic-flickr-gallery-thumb, .photonic-picasa-stream a, .photonic-post-gallery-nav a, .photonic-500px-stream a, .photonic-smug-stream a').each(function() {
		if (!($j(this).parent().hasClass('photonic-header-title'))) {
			$j(this).data('title', $j(this).attr('title'));
			var tempTitle = $j(this).data('title');
			if (typeof tempTitle != 'undefined' && tempTitle != '') {
				var tempIndex = tempTitle.indexOf('|');
				tempTitle = tempTitle.substr(0, tempIndex);
				$j(this).attr('title', tempTitle);
			}
		}
	});

	if (Photonic_JS.flickr_photo_title_display == 'tooltip' || Photonic_JS.flickr_collection_set_title_display == 'tooltip' || Photonic_JS.flickr_gallery_title_display == 'tooltip' ||
			Photonic_JS.picasa_photo_title_display == 'tooltip' || Photonic_JS.picasa_photo_pop_title_display == 'tooltip' ||
			Photonic_JS.wp_thumbnail_title_display == 'tooltip' ||
			Photonic_JS.Dpx_photo_title_display == 'tooltip' ||
			Photonic_JS.smug_photo_title_display == 'tooltip' || Photonic_JS.smug_photo_pop_title_display == 'tooltip' || Photonic_JS.smug_albums_album_title_display == 'tooltip') {
		var tooltipObj = Photonic_JS.flickr_photo_title_display == 'tooltip' ? '.photonic-flickr-stream .photonic-flickr-photo a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.flickr_collection_set_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.flickr_collection_set_title_display == 'tooltip' ? 'a.photonic-flickr-set-thumb' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.flickr_gallery_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.flickr_gallery_title_display == 'tooltip' ? 'a.photonic-flickr-gallery-thumb' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.picasa_photo_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.picasa_photo_title_display == 'tooltip' ? '.photonic-picasa-stream a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.picasa_photo_pop_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.picasa_photo_pop_title_display == 'tooltip' ? '.photonic-picasa-panel a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.wp_thumbnail_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.wp_thumbnail_title_display == 'tooltip' ? '.photonic-post-gallery-nav a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.Dpx_photo_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.Dpx_photo_title_display == 'tooltip' ? '.photonic-500px-stream a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.smug_photo_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.smug_photo_title_display == 'tooltip' ? '.photonic-smug-stream a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.smug_photo_pop_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.smug_photo_pop_title_display == 'tooltip' ? '.photonic-smug-panel a' : '';
		tooltipObj += (tooltipObj != '' && Photonic_JS.smug_albums_album_title_display == 'tooltip') ? ',' : '';
		tooltipObj += Photonic_JS.smug_albums_album_title_display == 'tooltip' ? '.photonic-smug-album-thumb a' : '';

		$j(tooltipObj).each(function() {
			if (!($j(this).parent().hasClass('photonic-header-title'))) {
				var iTitle = $j(this).find('img').attr('alt');
				$j(this).tooltip({
					bodyHandler: function() {
						return iTitle;
					},
					showURL: false
				});
			}
		});
	}

	if ($j.jcarousel) {
		$j('.photonic-carousel').jcarousel({
			// Configuration goes here
		});
	}

	$j('.auth-button').click(function (){
		var provider = '';
		if ($j(this).hasClass('auth-button-flickr')) {
			provider = 'flickr';
		}
		else if ($j(this).hasClass('auth-button-500px')) {
			provider = '500px';
		}
		else if ($j(this).hasClass('auth-button-smug')) {
			provider = 'smug';
		}
		var callbackId = $j(this).attr('rel');

		$j.post(Photonic_JS.ajaxurl, "action=photonic_authenticate&provider=" + provider + '&callback_id=' + callbackId, function(data) {
			if (provider == 'flickr') {
				window.location.replace(data);
			}
			else if (provider == '500px') {
				window.location.replace(data);
			}
			else if (provider == 'smug') {
				window.open(data);
			}
		});
		return false;
	});

	/**
	 * Displays all photos in a Flickr Set. Invoked when the Set is being fetched for the first time for display in a popup.
	 *
	 * @param rsp
	 */
	function photonicDisplaySetImages(rsp) {
		if (rsp.stat != "ok") {
			$j('.photonic-loading').hide();
			return;
		}
		var photoset = rsp.photoset;
		var photos = photoset.photo;
		var owner = photoset.owner;
		var main_size = Photonic_JS.flickr_main_size == 'none' ? '' : '_' + Photonic_JS.flickr_main_size;

		if (typeof photos != 'undefined' && photos.length > 0) {
			var col_class = '';
			var columns = eval('photonic_flickr_columns_' + current_position);

			if (Photonic_JS.flickr_photos_pop_per_row_constraint == 'padding') {
				col_class = 'photonic-pad-photos';
			}
			else {
				col_class = 'photonic-gallery-' + Photonic_JS.flickr_photos_pop_constrain_by_count + 'c';
			}

			var div = document.createElement('div');
			div.className = 'photonic-flickr-panel photonic-panel';
			div.id = 'photonic-flickr-panel-' + current_panel;

			var flickr_url = 'http://www.flickr.com/photos/' + owner + '/sets/' + photoset.id;

			if (!(Photonic_JS.flickr_hide_set_pop_thumbnail && Photonic_JS.flickr_hide_set_pop_title && Photonic_JS.flickr_hide_set_pop_photo_count)) {
				var div_header = document.createElement('div');
				div_header.className = 'photonic-flickr-panel-header fix';
				var header_html = '';

				if (!Photonic_JS.flickr_hide_set_pop_thumbnail) {
					var thumbnail = current_thumbnail.attr('src');
					header_html += '<a href="' + flickr_url + '" class="photonic-header-thumb photonic-flickr-set-pop-thumb" title="' + photonicHtmlEncode(current_title) + '"><img src="' + thumbnail + '" alt="' + current_title + '" /></a>';
				}
				if (!(Photonic_JS.flickr_hide_set_pop_title && Photonic_JS.flickr_hide_set_pop_photo_count)) {
					header_html += '<div class="photonic-header-details photonic-set-pop-details">';
					if (typeof current_title != 'undefined') {
						header_html += '<span class="photonic-header-title photonic-set-pop-title"><a href="' + flickr_url + '">' + current_title + '</a></span>';
					}
					header_html += ' <span class="photonic-header-info photonic-set-pop-info">' + Photonic_JS.flickr_photo_count.replace('{#}', photos.length) + '</span> ';
					header_html += "</div>";
				}

				div_header.innerHTML = header_html;
				div.appendChild(div_header);
			}

 			var div_content = document.createElement('div');
			div_content.className = 'photonic-flickr-panel-content photonic-panel-content fix';
			div_content.id = 'photonic-flickr-panel-content-' + current_panel;

			var script;
			if (Photonic_JS.slideshow_library != 'none') {
				script = document.createElement('script');
				script.type = 'text/javascript';
				if (Photonic_JS.slideshow_library == 'fancybox') {
					script.text = "$j('a.launch-gallery-fancybox').each(function() { $j(this).fancybox({ transitionIn:'elastic', transitionOut:'elastic',speedIn:600,speedOut:200,overlayShow:true,overlayOpacity:0.8,overlayColor:\"#000\",titleShow:Photonic_JS.fbox_show_title,titlePosition:Photonic_JS.fbox_title_position});});";
				}
				else if (Photonic_JS.slideshow_library == 'colorbox') {
					script.text = "$j('a.launch-gallery-colorbox').each(function() { $j(this).colorbox({ opacity: 0.8, maxWidth: '95%', maxHeight: '95%', slideshow: Photonic_JS.slideshow_mode, slideshowSpeed: Photonic_JS.slideshow_interval });});";
				}
				else if (Photonic_JS.slideshow_library == 'prettyphoto') {
					script.text = "$j(\"a[rel^='photonic-prettyPhoto']\").prettyPhoto({ theme: Photonic_JS.pphoto_theme, autoplay_slideshow: Photonic_JS.slideshow_mode, slideshow: parseInt(Photonic_JS.slideshow_interval, 10), show_title: false, social_tools: '', deeplinking: false }); ";
				}
				div_content.appendChild(script);
			}

			if (Photonic_JS.flickr_photo_pop_title_display == 'tooltip') {
				script = document.createElement('script');
				script.type = 'text/javascript';
				script.text = "$j('.photonic-flickr-panel a').each(function() { $j(this).data('title', $j(this).attr('title')); }); $j('.photonic-flickr-panel a').each(function() { if (!($j(this).parent().hasClass('photonic-header-title'))) { var iTitle = $j(this).find('img').attr('alt'); $j(this).tooltip({ bodyHandler: function() { return iTitle; }, showURL: false });}})";
				div_content.appendChild(script);
			}

			var ul = document.createElement('ul');
			ul.className = 'slideshow-grid-panel lib-' + Photonic_JS.slideshow_library;

			var rows = 4;
			var li_count = Photonic_JS.gallery_panel_items;
			var li;

			for (var i = 0; i < photos.length; i++) {
				var photo = photos[ i ];
				var thumb = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
				var orig = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + main_size + ".jpg";

				if (typeof photo.owner != 'undefined') {
					owner = photo.owner;
				}

				var url = "http://www.flickr.com/photos/" + owner + "/" + photo.id;

				if (i%li_count == 0) {
					li = document.createElement('li');
					li.className = "photonic-flickr-image photonic-flickr-photo ";
				}

				var a = document.createElement('a');
				a.rel = 'lightbox-' + div_content.id;
				if (Photonic_JS.slideshow_library == 'prettyphoto') {
					a.rel = 'photonic-prettyPhoto[' + a.rel + ']';
				}
				if (Photonic_JS.slideshow_library != 'none') {
					a.className = 'launch-gallery-' + Photonic_JS.slideshow_library + " " + Photonic_JS.slideshow_library + " " + col_class;
					a.href = orig;
					var encodedFlickrView = "<a href='" + url + "'>" + Photonic_JS.flickr_view + "</a>";
					a.title = photo.title == '' ? encodedFlickrView : photo.title + " | " + encodedFlickrView;
				}
				else {
					a.href = url;
					a.title = photo.title;
				}

				var img = document.createElement('img');
				img.alt = photo.title;
				img.src = thumb;

				a.appendChild(img);
				if (Photonic_JS.flickr_photo_pop_title_display == 'below') {
					var span = document.createElement('span');
					span.className = 'photonic-photo-pop-title';
					span.innerHTML = photo.title;
					a.appendChild(span);
				}

				li.appendChild(a);

				if (i%li_count == 0 || i == photos.length - 1) {
					ul.appendChild(li);
				}
			}

			var screens = ul.children.length;
			var prev = document.createElement('a');
			prev.id = 'photonic-flickr-set-' + current_panel + '-prev';
			prev.href = '#';
			prev.className = 'panel-previous';
			prev.innerHTML = '&nbsp;';

			var next = document.createElement('a');
			next.id = 'photonic-flickr-set-' + current_panel + '-next';
			next.href = '#';
			next.className = 'panel-next';
			next.innerHTML = '&nbsp;';

			div_content.appendChild(ul);
			div.appendChild(div_content);

			$j(ul).first('li').waitForImages(function() {
				$j(div).appendTo($j('#photonic-flickr-set-' + current_panel)).show();
				if (screens > 1) {
					$j(this).before(prev)
							.after(next)
							.cycle({
								//fx: 'scrollHorz',
								timeout: 0,
								slideResize: false,
								prev: 'a#photonic-flickr-set-' + current_panel + '-prev',
								next: 'a#photonic-flickr-set-' + current_panel + '-next',
								sync: false
							});
				}
				else {
					$j(this).cycle({
						//fx: 'scrollHorz',
						timeout: 0,
						slideResize: false,
						sync: false
					});
				}

				$j('#photonic-flickr-panel-' + current_panel).modal({
					autoPosition: false,
					dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
					overlayCss: { background: '#000' },
					closeClass: 'photonic-flickr-panel-' + current_panel,
					opacity: 90,
					close: true,
					escClose: false,
					containerId: 'photonic-flickr-panel-container-' + current_panel,
					onClose: function(dialog) { $j.modal.close(); $j('#photonic-flickr-panel-' + current_panel).css({ display: 'none' }) },
					onShow: modalOnShow,
					onOpen: modalOpen
				});
				var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
				var target = {};

				target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-flickr-panel-container-' + current_panel).height() - 40) * 0.5)), 10);
				target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-flickr-panel-container-' + current_panel).width() - 40) * 0.5)), 10);

				$j('#photonic-flickr-panel-container-' + current_panel).css({top: target.top, left: target.left });
				$j('.photonic-loading').hide();
			});
		}
	}

	/**
	 * Displays all photos in a Flickr Gallery. Invoked when the Gallery is being fetched for the first time for display in a popup.
	 *
	 * @param rsp
	 */
	function photonicDisplayGalleryImages(rsp) {
		if (rsp.stat != "ok") {
			$j('.photonic-loading').hide();
			return;
		}
		var gallery = rsp.photos;
		var photos = gallery.photo;
		var main_size = Photonic_JS.flickr_main_size == 'none' ? '' : '_' + Photonic_JS.flickr_main_size;

		if (typeof photos != 'undefined' && photos.length > 0) {
			var first_photo = photos[0];
			var owner = first_photo.owner;

			var col_class = '';
			var columns = eval('photonic_flickr_columns_' + current_position);

			if (Photonic_JS.flickr_photos_pop_per_row_constraint == 'padding') {
				col_class = 'photonic-pad-photos';
			}
			else {
				col_class = 'photonic-gallery-' + Photonic_JS.flickr_photos_pop_constrain_by_count + 'c';
			}

			var div = document.createElement('div');
			div.className = 'photonic-flickr-panel photonic-panel';
			div.id = 'photonic-flickr-panel-' + current_panel;

			var gallery_id = current_panel.substr(current_panel.lastIndexOf('-') + 1);
			var flickr_url = 'http://www.flickr.com/photos/' + owner + '/galleries/' + gallery_id;

			if (!(Photonic_JS.flickr_hide_gallery_pop_thumbnail && Photonic_JS.flickr_hide_gallery_pop_title && Photonic_JS.flickr_hide_gallery_pop_photo_count)) {
				var div_header = document.createElement('div');
				div_header.className = 'photonic-flickr-panel-header fix';
				var header_html = '';

				if (!Photonic_JS.flickr_hide_gallery_pop_thumbnail) {
					var thumbnail = current_thumbnail.attr('src');
					header_html += '<a href="' + flickr_url + '" class="photonic-header-thumb photonic-flickr-gallery-pop-thumb" title="' + photonicHtmlEncode(current_title) + '"><img src="' + thumbnail + '" alt="' + current_title + '" /></a>';
				}
				if (!(Photonic_JS.flickr_hide_gallery_pop_title && Photonic_JS.flickr_hide_gallery_pop_photo_count)) {
					header_html += '<div class="photonic-header-details photonic-gallery-pop-details">';
					if (!Photonic_JS.flickr_hide_gallery_pop_title && typeof current_title != 'undefined') {
						header_html += '<span class="photonic-header-title photonic-gallery-pop-title"><a href="' + flickr_url + '">' + current_title + '</a></span>';
					}
					if (!Photonic_JS.flickr_hide_gallery_pop_photo_count) {
						header_html += ' <span class="photonic-header-info photonic-gallery-pop-info">' + Photonic_JS.flickr_photo_count.replace('{#}', photos.length) + '</span> ';
					}
					header_html += "</div>";
				}

				div_header.innerHTML = header_html;
				div.appendChild(div_header);
			}

 			var div_content = document.createElement('div');
			div_content.className = 'photonic-flickr-panel-content photonic-panel-content fix';
			div_content.id = 'photonic-flickr-panel-content-' + current_panel;

			var script;
			if (Photonic_JS.slideshow_library != 'none') {
				script = document.createElement('script');
				script.type = 'text/javascript';
				if (Photonic_JS.slideshow_library == 'fancybox') {
					script.text = "$j('a.launch-gallery-fancybox').each(function() { $j(this).fancybox({ transitionIn:'elastic', transitionOut:'elastic',speedIn:600,speedOut:200,overlayShow:true,overlayOpacity:0.8,overlayColor:\"#000\",titleShow:Photonic_JS.fbox_show_title,titlePosition:Photonic_JS.fbox_title_position});});";
				}
				else if (Photonic_JS.slideshow_library == 'colorbox') {
					script.text = "$j('a.launch-gallery-colorbox').each(function() { $j(this).colorbox({ opacity: 0.8, maxWidth: '95%', maxHeight: '95%', slideshow: Photonic_JS.slideshow_mode, slideshowSpeed: Photonic_JS.slideshow_interval });});";
				}
				else if (Photonic_JS.slideshow_library == 'prettyphoto') {
					script.text = "$j(\"a[rel^='photonic-prettyPhoto']\").prettyPhoto({ theme: Photonic_JS.pphoto_theme, autoplay_slideshow: Photonic_JS.slideshow_mode, slideshow: parseInt(Photonic_JS.slideshow_interval, 10), show_title: false, social_tools: '', deeplinking: false }); ";
				}
				div_content.appendChild(script);
			}

			if (Photonic_JS.flickr_photo_pop_title_display == 'tooltip') {
				script = document.createElement('script');
				script.type = 'text/javascript';
				script.text = "$j('.photonic-flickr-panel a').each(function() { $j(this).data('title', $j(this).attr('title')); }); $j('.photonic-flickr-panel a').each(function() { if (!($j(this).parent().hasClass('photonic-header-title'))) { var iTitle = $j(this).find('img').attr('alt'); $j(this).tooltip({ bodyHandler: function() {	return iTitle; }, showURL: false });}})";
				div_content.appendChild(script);
			}

			var ul = document.createElement('ul');
			ul.className = 'slideshow-grid-panel lib-' + Photonic_JS.slideshow_library;

			var rows = 4;
			var li_count = Photonic_JS.gallery_panel_items;
			var li;

			for (var i = 0; i < photos.length; i++) {
				var photo = photos[ i ];
				var thumb = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
				var orig = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + main_size + ".jpg";

				if (typeof photo.owner != 'undefined') {
					owner = photo.owner;
				}

				var url = "http://www.flickr.com/photos/" + owner + "/" + photo.id;

				if (i%li_count == 0) {
					li = document.createElement('li');
					li.className = "photonic-flickr-image photonic-flickr-photo ";
				}

				var a = document.createElement('a');
				a.rel = 'lightbox-' + div_content.id;
				if (Photonic_JS.slideshow_library == 'prettyphoto') {
					a.rel = 'photonic-prettyPhoto[' + a.rel + ']';
				}
				if (Photonic_JS.slideshow_library != 'none') {
					a.className = 'launch-gallery-' + Photonic_JS.slideshow_library + " " + Photonic_JS.slideshow_library + " " + col_class;
					a.href = orig;
					var encodedFlickrView = "<a href='" + url + "'>" + Photonic_JS.flickr_view + "</a>";
					a.title = photo.title == '' ? encodedFlickrView : photo.title + " | " + encodedFlickrView;
				}
				else {
					a.href = url;
					a.title = photo.title;
				}

				var img = document.createElement('img');
				img.alt = photo.title;
				img.src = thumb;

				a.appendChild(img);
				if (Photonic_JS.flickr_photo_pop_title_display == 'below') {
					var span = document.createElement('span');
					span.className = 'photonic-photo-pop-title';
					span.innerHTML = photo.title;
					a.appendChild(span);
				}

				li.appendChild(a);

				if (i%li_count == 0 || i == photos.length - 1) {
					ul.appendChild(li);
				}
			}

			var screens = ul.children.length;
			var prev = document.createElement('a');
			prev.id = 'photonic-flickr-gallery-' + current_panel + '-prev';
			prev.href = '#';
			prev.className = 'panel-previous';
			prev.innerHTML = '&nbsp;';

			var next = document.createElement('a');
			next.id = 'photonic-flickr-gallery-' + current_panel + '-next';
			next.href = '#';
			next.className = 'panel-next';
			next.innerHTML = '&nbsp;';

			div_content.appendChild(ul);
			div.appendChild(div_content);

			$j(ul).first('li').waitForImages(function() {
				$j(div).appendTo($j('#photonic-flickr-gallery-' + current_panel)).show();
				if (screens > 1) {
					$j(this).before(prev)
							.after(next)
							.cycle({
								timeout: 0,
								slideResize: false,
								prev: 'a#photonic-flickr-gallery-' + current_panel + '-prev',
								next: 'a#photonic-flickr-gallery-' + current_panel + '-next',
								sync: false
							});
				}
				else {
					$j(this).cycle({
						timeout: 0,
						slideResize: false,
						sync: false
					});
				}

				$j('#photonic-flickr-panel-' + current_panel).modal({
					autoPosition: false,
					dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
					overlayCss: { background: '#000' },
					closeClass: 'photonic-flickr-panel-' + current_panel,
					opacity: 90,
					close: true,
					escClose: false,
					containerId: 'photonic-flickr-panel-container-' + current_panel,
					onClose: function(dialog) { $j.modal.close(); $j('#photonic-flickr-panel-' + current_panel).css({ display: 'none' }) },
					onShow: modalOnShow,
					onOpen: modalOpen
				});
				var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
				var target = {};

				target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-flickr-panel-container-' + current_panel).height() - 40) * 0.5)), 10);
				target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-flickr-panel-container-' + current_panel).width() - 40) * 0.5)), 10);

				$j('#photonic-flickr-panel-container-' + current_panel).css({top: target.top, left: target.left });
				$j('.photonic-loading').hide();
			});
		}
	}

	// callback function
	function modalOnShow(dialog) {
		var s = this; // refers to the simplemodal object
		$j('.photonic-flickr-set-thumb', dialog.data[0]).click(function () { // use the modal data context
			photonicDisplaySetPopup(this);
			var id = '#' + this.id + '-modal'; // dynamically determine the modal content id based on the link id
			id = '#photonic-flickr-panel-' + this.id.substr(21);

			setTimeout(function () { // wait for 6/10ths of a second, then open the next dialog
				s.close(); // close the current dialog
				$j(id).modal({
					onShow: modalOnShow,
					onOpen: modalOpen,
					onClose: modalClose
				});
			}, 600);

			return false;
		});
	}

	function modalOnPicasaShow(dialog) {
		var s = this; // refers to the simplemodal object
		$j('.photonic-picasa-album-thumb', dialog.data[0]).click(function () { // use the modal data context
			//photonicDisplaySetPopup(this);
			var id = '#photonic-picasa-panel-' + this.id.substr(21);

			setTimeout(function () { // wait for 6/10ths of a second, then open the next dialog
				s.close(); // close the current dialog
				$j(id).modal({
					onShow: modalOnPicasaShow,
					onOpen: modalOpen,
					onClose: modalClose
				});
			}, 600);

			return false;
		});
	}

	function modalOnSmugShow(dialog) {
		var s = this; // refers to the simplemodal object
		$j('.photonic-smug-album-thumb', dialog.data[0]).click(function () { // use the modal data context
			if (!$j(this).hasClass('photonic-smug-passworded')) {
				var id = '#photonic-smug-panel-' + this.id.substr(19);

				setTimeout(function () { // wait for 6/10ths of a second, then open the next dialog
					s.close(); // close the current dialog
					$j(id).modal({
						onShow: modalOnSmugShow,
						onOpen: modalOpen,
						onClose: modalClose
					});
				}, 600);
			}

			return false;
		});
	}

	// callback function
	function modalOpen(dialog) {
		dialog.overlay.fadeIn(200, function () {
			dialog.data.hide();
			dialog.container.fadeIn(100, function () {
				dialog.data.fadeIn(100);
				var panel = dialog.data.attr('id');
				$j('.slideshow-grid-panel').cycle({/*fx: 'scrollHorz', */timeout: 0, prev: 'a#' + panel + '-prev', next: 'a#' + panel + '-next'});
			});
		});
	}

	// callback function
	function modalClose(dialog) {
		dialog.data.fadeOut(100, function () {
			dialog.container.fadeOut(100, function () {
				dialog.overlay.fadeOut(200, function () {
					$j.modal.close();
				});
			});
		});
	}

	function photonicDisplaySetPopup(setPanel) {
		var panel = setPanel.id;
		panel = panel.substr(26);
		current_panel = panel;
		var containerId = 'photonic-flickr-panel-container-' + panel;
		panel = '#photonic-flickr-panel-' + panel;
		current_title = setPanel.title;
		if (current_title == '') {
			current_title = $j(setPanel).data('title');
		}

		current_thumbnail = $j(setPanel).find('img');

		var loading = document.createElement('div');
		loading.className = 'photonic-loading';
		$j(loading).appendTo($j('body')).show();

		if ($j(panel).length == 0) {
			var photoset_id = current_panel.substr(current_panel.lastIndexOf('-') + 1);
			if (Photonic_JS.flickr_auth_call) {
				$j.post(Photonic_JS.ajaxurl, "action=photonic_flickr_sign&method=flickr.photosets.getPhotos&photoset_id=" + photoset_id, function(data) {
					$j.getJSON(data, photonicDisplaySetImages);
				});
			}
			else {
				var url = 'http://api.flickr.com/services/rest/?format=json&api_key=' + (Photonic_JS.flickr_api_key) + '&method=flickr.photosets.getPhotos&photoset_id=' + photoset_id + '&jsoncallback=?';
				// A quicker call, avoids server round-trip
				$j.getJSON(url, photonicDisplaySetImages);
			}
		}
		else {
			$j(loading).hide();
			$j(panel).modal({
				autoPosition: false,
				dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
				overlayCss: { background: '#000' },
				opacity: 90,
				close: true,
				escClose: false,
				containerId: containerId,
//				onClose: function(dialog) { $j.modal.close(); $j(panel).css({ display: 'none' }) }
				onClose: modalClose
			});
			var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
			var target = {};
			target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-flickr-panel-' + current_panel).height() - 40) * 0.5)), 10);
			target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-flickr-panel-' + current_panel).width() - 40) * 0.5)), 10);
			$j('#' + containerId).css({top: target.top, left: target.left});
			$j('.slideshow-grid-panel').cycle({timeout: 0, prev: 'a#photonic-flickr-set-' + current_panel + '-prev', next: 'a#photonic-flickr-set-' + current_panel + '-next'});
		}

		return false;
	}

	function photonicDisplayGalleryPopup(setPanel) {
		var panel = setPanel.id;
		panel = panel.substr(30);
		current_panel = panel;
		var containerId = 'photonic-flickr-panel-container-' + panel;
		panel = '#photonic-flickr-panel-' + panel;
		current_title = setPanel.title;
		if (current_title == '') {
			current_title = $j(setPanel).data('title');
		}

		current_thumbnail = $j(setPanel).find('img');

		var loading = document.createElement('div');
		loading.className = 'photonic-loading';
		$j(loading).appendTo($j('body')).show();

		if ($j(panel).length == 0) {
			var gallery_id = current_panel.substr(current_panel.lastIndexOf('-') + 1);
			var remainder = current_panel.substr(0, current_panel.lastIndexOf('-'));
			remainder = remainder.substr(remainder.lastIndexOf('-') + 1);
			gallery_id = remainder + '-' + gallery_id;

			if (Photonic_JS.flickr_auth_call) {
				$j.post(Photonic_JS.ajaxurl, "action=photonic_flickr_sign&method=flickr.galleries.getPhotos&gallery_id=" + gallery_id, function(data) {
					$j.getJSON(data, photonicDisplayGalleryImages);
				});
			}
			else {
				var url = 'http://api.flickr.com/services/rest/?format=json&api_key=' + (Photonic_JS.flickr_api_key) + '&method=flickr.galleries.getPhotos&gallery_id=' + gallery_id + '&jsoncallback=?';
				$j.getJSON(url, photonicDisplayGalleryImages);
			}
		}
		else {
			$j(loading).hide();
			$j(panel).modal({
				autoPosition: false,
				dataCss: { width: '' + Photonic_JS.gallery_panel_width + 'px' },
				overlayCss: { background: '#000' },
				opacity: 90,
				close: true,
				escClose: false,
				containerId: containerId,
//				onClose: function(dialog) { $j.modal.close(); $j(panel).css({ display: 'none' }) }
				onClose: modalClose
			});
			var viewport = [$j(window).width(), $j(window).height(), $j(document).scrollLeft(), $j(document).scrollTop()];
			var target = {};
			target.top = parseInt(Math.max(viewport[3] - 20, viewport[3] + ((viewport[1] - $j('#photonic-flickr-panel-' + current_panel).height() - 40) * 0.5)), 10);
			target.left = parseInt(Math.max(viewport[2] - 20, viewport[2] + ((viewport[0] - $j('#photonic-flickr-panel-' + current_panel).width() - 40) * 0.5)), 10);
			$j('#' + containerId).css({top: target.top, left: target.left});
			$j('.slideshow-grid-panel').cycle({timeout: 0, prev: 'a#photonic-flickr-gallery-' + current_panel + '-prev', next: 'a#photonic-flickr-gallery-' + current_panel + '-next'});
		}

		return false;
	}
});

/**
 * This request is invoked if there are multiple Flickr API calls and the first deals with header information for that section.
 * E.g. If Photoset thumbnails for a collection are being retrieved, this call will get the information about the collection itself.
 * This is required because we cannot make chained calls to the Flickr API.
 *
 * @param rsp
 */
function photonicJsonFlickrHeaderApi(rsp) {
	var position = parseInt(Photonic_JS.flickr_position, 10);
	if (rsp.stat != "ok") {
		return;
	}
	position++;
	Photonic_JS.flickr_position = position;

	var s = "";
	var thumb, owner, image, cleanTitle, flickr_link, anchor;
	if (typeof rsp.photoset != 'undefined') {  // Photo sets
		var photoset = rsp.photoset;
		owner = photoset.owner;
		thumb = "http://farm" + photoset.farm + ".static.flickr.com/" + photoset.server + "/" + photoset.primary + "_" + photoset.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
		cleanTitle = photonicHtmlEncode(photoset.title._content);

		// Roundabout way to ensure that titles are appropriately escaped.
		image = "<img src='" + thumb + "' alt='" + cleanTitle + "' />";
		flickr_link = 'http://www.flickr.com/photos/' + owner + '/sets/' + photoset.id;
		anchor = "<a href='" + flickr_link + "' class='photonic-header-thumb photonic-flickr-set-solo-thumb' " + cleanTitle + "'>" + image + "</a>";

		if (!(Photonic_JS.flickr_hide_set_thumbnail && Photonic_JS.flickr_hide_set_title && Photonic_JS.flickr_hide_set_photo_count)) {
			// Have to make use of "li" because we are in a "ul"
			s += "<li class='photonic-flickr-set'>";

			if (!Photonic_JS.flickr_hide_set_thumbnail) {
				s += anchor;
			}
			if (!(Photonic_JS.flickr_hide_set_title && Photonic_JS.flickr_hide_set_photo_count)) {
				s += "<div class='photonic-header-details photonic-set-details'>";
				if (!Photonic_JS.flickr_hide_set_title) {
					s += "<div class='photonic-header-title photonic-set-title'><a href='" + flickr_link + "'>" + cleanTitle + '</a></div>';
				}
				if (!Photonic_JS.flickr_hide_set_photo_count) {
					var photo_count = Photonic_JS.flickr_photo_count.replace('{#}', photoset.photos);
					s += "<span class='photonic-header-info photonic-set-photos'>" + photo_count + '</span>';
				}
				s += "</div><!-- .photonic-collection-details -->";
			}
			s += "</li>";
		}
		document.writeln(s);
	}
	else if (typeof rsp.gallery != 'undefined') { // Gallery
		var gallery = rsp.gallery;
		owner = gallery.owner;
		thumb = "http://farm" + gallery.primary_photo_farm + ".static.flickr.com/" + gallery.primary_photo_server + "/" + gallery.primary_photo_id + "_" + gallery.primary_photo_secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
		cleanTitle = photonicHtmlEncode(gallery.title._content);

		// Roundabout way to ensure that titles are appropriately escaped.
		image = "<img src='" + thumb + "' alt='" + cleanTitle + "' />";
		flickr_link = gallery.url + '/';
		anchor = "<a href='" + flickr_link + "' class='photonic-header-thumb photonic-flickr-gallery-solo-thumb' " + cleanTitle + "'>" + image + "</a>";

		if (!(Photonic_JS.flickr_hide_gallery_thumbnail && Photonic_JS.flickr_hide_gallery_title && Photonic_JS.flickr_hide_gallery_photo_count)) {
			// Have to make use of "li" because we are in a "ul"
			s += "<li class='photonic-flickr-gallery'>";

			if (!Photonic_JS.flickr_hide_gallery_thumbnail) {
				s += anchor;
			}
			if (!(Photonic_JS.flickr_hide_gallery_title && Photonic_JS.flickr_hide_gallery_photo_count)) {
				s += "<div class='photonic-header-details photonic-gallery-details'>";
				if (!Photonic_JS.flickr_hide_gallery_title) {
					s += "<div class='photonic-header-title photonic-gallery-title'><a href='" + flickr_link + "'>" + cleanTitle + '</a></div>';
				}
				if (!Photonic_JS.flickr_hide_gallery_photo_count) {
					var photo_count = Photonic_JS.flickr_photo_count.replace('{#}', gallery.count_photos);
					s += "<span class='photonic-header-info photonic-gallery-photos'>" + photo_count + '</span>';
				}
				s += "</div><!-- .photonic-header-details -->";
			}
			s += "</li>";
		}
		document.writeln(s);
	}
}

/**
 * This is the main JSON API call, invoked directly by the PHP code. It deals with all types of Photonic Flickr requests that are invoked while the page is loading up.
 *
 * @param rsp
 */
function photonicJsonFlickrStreamApi(rsp) {
	var position = parseInt(Photonic_JS.flickr_position, 10);
	position++;
	Photonic_JS.flickr_position = position;
	var main_size = Photonic_JS.flickr_main_size == 'none' ? '' : '_' + Photonic_JS.flickr_main_size;
	var a_rel = 'lightbox-photonic-flickr-stream-' + position;
	if (Photonic_JS.slideshow_library == 'prettyphoto') {
		a_rel = 'photonic-prettyPhoto[' + a_rel + ']';
	}

	if (rsp.stat != "ok") {
		return;
	}

	var s = "";

	if (typeof rsp.photos != 'undefined') { // Photos, galleries
		var photos = rsp.photos;
	}
	else if (typeof rsp.photoset != 'undefined') {  // Photo sets
		var photos = rsp.photoset;
		owner = photos.owner;
	}
	else if (typeof rsp.photosets != 'undefined') {   // Multiple Photo Sets
		var photosets = rsp.photosets;
	}
	else if (typeof rsp.galleries != 'undefined') {   // Multiple Photo Sets
		var galleries = rsp.galleries;
	}
	else if (typeof rsp.collections != 'undefined') {   // Collections
		var collections = rsp.collections;
	}
	else if (typeof rsp.collection != 'undefined') {   // Collections
		var collection = rsp.collection;
	}
	else if (typeof rsp.photo != 'undefined') {
		var single_photo = rsp.photo;
	}

	if (typeof photos != 'undefined') {
		var a_class = '';
		if (Photonic_JS.slideshow_library != 'none') {
			a_class = 'launch-gallery-' + Photonic_JS.slideshow_library + " " + Photonic_JS.slideshow_library;
		}
		var col_class = '';
		var columns = eval('photonic_flickr_columns_' + position);
		if (typeof columns != 'undefined' && columns != 'auto') {
			col_class = 'photonic-gallery-' + columns + 'c';
		}

		if (typeof photos.photo != 'undefined') { // When a photoset contains photos, or if multiple photos are returned
			if (col_class == '' && Photonic_JS.flickr_photos_per_row_constraint == 'padding') {
				col_class = 'photonic-pad-photos';
			}
			else if (col_class == '') {
				col_class = 'photonic-gallery-' + Photonic_JS.flickr_photos_constrain_by_count + 'c';
			}

			for (var i = 0; i < photos.photo.length; i++) {
				var photo = photos.photo[ i ];
				var thumb = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
				var orig = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + main_size + ".jpg";

				if (typeof photo.owner != 'undefined') {
					owner = photo.owner;
				}

				var url = "http://www.flickr.com/photos/" + owner + "/" + photo.id;
				orig = Photonic_JS.slideshow_library == 'none' ? url : orig;

				var origEncodedTitle = photonicHtmlEncode(photo.title);
				origEncodedTitle = $j('<div/>').text(origEncodedTitle).html().replace(/"/g, "&quot;");
				var encodedFlickrView = "<a href='" + url + "'>" + Photonic_JS.flickr_view + "</a>";
				var encodedTitle = Photonic_JS.slideshow_library == 'none' ? origEncodedTitle : (origEncodedTitle == '' ? encodedFlickrView : origEncodedTitle + " | " + encodedFlickrView);
				var showTitle = '';
				if (Photonic_JS.flickr_photo_title_display == 'below') {
					showTitle = '<span class="photonic-photo-title">' + origEncodedTitle + '</span>';
				}

				s += '<li class="photonic-flickr-image photonic-flickr-photo ' + col_class + '"><a href="' + orig + '" class="' + a_class + '" rel="' + a_rel + '" title="' + encodedTitle + '">' + '<img alt="' +
						origEncodedTitle + '"src="' + thumb + '"/>' + '</a>' + showTitle + '</li>';
			}
		}
		else { // When a photoset just contains photoset information, no photos, typically the result of a GetInfo call
			if (col_class == '' && Photonic_JS.flickr_collection_set_per_row_constraint == 'padding') {
				col_class = 'photonic-pad-photosets';
			}
			else if (col_class == '') {
				col_class = 'photonic-gallery-' + Photonic_JS.flickr_collection_set_constrain_by_count + 'c';
			}

			var photoset = photos;
			var id = photoset.id;
			var thumb = "http://farm" + photoset.farm + ".static.flickr.com/" + photoset.server + "/" + photoset.primary + "_" + photoset.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
			var cleanTitle = photonicHtmlEncode(photoset.title._content);
			var owner = photoset.owner;

			// Roundabout way to ensure that titles are appropriately escaped.
			var image = "<img src='" + thumb + "' alt='" + cleanTitle + "' />";
			var anchor = "<a href='http://www.flickr.com/photos/" + owner + '/sets/' + photoset.id + "' class='photonic-flickr-set-thumb' " +
					" id='photonic-flickr-set-thumb-" + id + '-' + position + '-' + photoset.id + "' title='" + cleanTitle + "'>" + image + "</a>";

			var text = '';
			if (Photonic_JS.flickr_collection_set_title_display == 'below') {
				text = "<span class='photonic-photoset-title'><a href='http://www.flickr.com/photos/" + owner + '/sets/' + photoset.id + "' title='" + cleanTitle + "'>" + photoset.title._content + "</a></span>";
				if (!Photonic_JS.flickr_hide_collection_set_photos_count_display) {
					text += '<span class="photonic-photoset-photo-count">' + Photonic_JS.flickr_photo_count.replace('{#}', photoset.photos) + '</span>';
				}
			}

			s += "<li class='photonic-flickr-image photonic-flickr-set-thumb " + col_class + "' id='photonic-flickr-set-" + id + '-' + position + "-" + photoset.id + "'>" + anchor + text + "</li>";
		}
	}
	else if (typeof collections != 'undefined') {
		for (var i=0; i < collections.collection.length; i++) {
			var inner_collection = collections.collection[i];
			current_position = position;
			s += photonicGetFlickrCollectionHTML(inner_collection);
		}
	}
	else if (typeof collection != 'undefined') {
		current_position = position;
		s += photonicGetFlickrCollectionHTML(collection);
	}
	else if (typeof photosets != 'undefined') {
		current_position = position;
		s += photonicGetFlickrPhotosetsHTML(photosets);
	}
	else if (typeof galleries != 'undefined') {
		current_position = position;
		s += photonicGetFlickrGalleriesHTML(galleries);
	}
	else if (typeof single_photo != 'undefined') {
		current_position = position;
		s += photonicGetFlickrSinglePhotoHTML(single_photo);
	}

	document.writeln(s);
}

/**
 * Prints a collection out on the page. This is invoked while using <code>[gallery type='flickr' user_id='abc' view='collections']</code> or <code>[gallery type='flickr' user_id='abc' collection_id='xyz']</code>
 * @param collection
 */
function photonicGetFlickrCollectionHTML(collection) {
	var position = current_position;
	var id = collection.id.substr(collection.id.indexOf('-') + 1);
	var current_id = id + '-' + position;
	if (typeof current_panel != 'undefined') {
		current_id = current_panel + '-' + id;
	}
	var user_id = eval('photonic_flickr_user_' + current_position);
	var collection_a = "http://www.flickr.com/photos/" + user_id + "/collections/" + id;

	var col_class = '';
	var columns = eval('photonic_flickr_columns_' + position);
	if (typeof columns != 'undefined' && columns != 'auto') {
		col_class = 'photonic-gallery-' + columns + 'c';
	}
	else if (Photonic_JS.flickr_collection_set_per_row_constraint == 'padding') {
		col_class = 'photonic-pad-photosets';
	}
	else {
		col_class = 'photonic-gallery-' + Photonic_JS.flickr_collection_set_constrain_by_count + 'c';
	}

	var s = "<li class='photonic-flickr-image photonic-flickr-collection photonic-flickr-collection-" + current_id + "' id='photonic-flickr-collection-" + current_id+ "'>";
	if (!(Photonic_JS.flickr_hide_collection_thumbnail && Photonic_JS.flickr_hide_collection_title && Photonic_JS.flickr_hide_collection_set_count)) {
//		s += "<div class='photonic-collection'>";
		if (!Photonic_JS.flickr_hide_collection_thumbnail) {
			s += "<a href='" + collection_a + "' class='photonic-header-thumb photonic-flickr-collection-thumb'><img src='" + collection.iconsmall + "' /></a>";
		}
		if (!(Photonic_JS.flickr_hide_collection_title && Photonic_JS.flickr_hide_collection_set_count)) {
			s += "<div class='photonic-header-details photonic-collection-details'>";
			if (!Photonic_JS.flickr_hide_collection_title) {
				s += "<div class='photonic-header-title photonic-collection-title'><a href='" + collection_a + "'>" + collection.title + '</a></div>';
			}
			if (!Photonic_JS.flickr_hide_collection_set_count) {
				var photosets = collection.set;
				if (typeof photosets != 'undefined' && photosets.length > 0) {
					var set_count = Photonic_JS.flickr_set_count.replace('{#}', photosets.length);
					s += "<span class='photonic-header-info photonic-collection-sets'>" + set_count + '</span>';
				}
			}
			s += "</div><!-- .photonic-collection-details -->";
		}
//		s += "</div><!-- .photonic-collection -->";
	}
	s += "</li>";
	return s;
}

/**
 * This is invoked when a list of photosets is printed on the page. <code>[gallery type='flickr' user_id='abc' view='photosets']</code>
 * @param photosets
 */
function photonicGetFlickrPhotosetsHTML(photosets) {
	var position = current_position;
	var s = '';
	var user_id = eval('photonic_flickr_user_' + position);

	var col_class = '';
	var columns = eval('photonic_flickr_columns_' + position);
	if (typeof columns != 'undefined' && columns != 'auto') {
		col_class = 'photonic-gallery-' + columns + 'c';
	}
	else if (Photonic_JS.flickr_collection_set_per_row_constraint == 'padding') {
		col_class = 'photonic-pad-photosets';
	}
	else {
		col_class = 'photonic-gallery-' + Photonic_JS.flickr_collection_set_constrain_by_count + 'c';
	}

	for (var i=0; i<photosets.photoset.length; i++) {
		var photoset = photosets.photoset[i];
		var id = photoset.id;
		var thumb = "http://farm" + photoset.farm + ".static.flickr.com/" + photoset.server + "/" + photoset.primary + "_" + photoset.secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
		var cleanTitle = photonicHtmlEncode(photoset.title._content);
		var owner = typeof photoset.owner == 'undefined' ? user_id : photoset.owner;

		// Roundabout way to ensure that titles are appropriately escaped.
		var image = "<img src='" + thumb + "' alt='" + cleanTitle + "' />";
		var anchor = "<a href='http://www.flickr.com/photos/" + owner + '/sets/' + photoset.id + "' class='photonic-flickr-set-thumb' " +
				" id='photonic-flickr-set-thumb-" + id + '-' + position + '-' + photoset.id + "' title='" + cleanTitle + "'>" + image + "</a>";

		var text = '';
		if (Photonic_JS.flickr_collection_set_title_display == 'below') {
			text = "<span class='photonic-photoset-title'><a href='http://www.flickr.com/photos/" + owner + '/sets/' + photoset.id + "' title='" + cleanTitle + "'>" + photoset.title._content + "</a></span>";
			if (!Photonic_JS.flickr_hide_collection_set_photos_count_display) {
				text += '<span class="photonic-photoset-photo-count">' + Photonic_JS.flickr_photo_count.replace('{#}', photoset.photos) + '</span>';
			}
		}

		s += "<li class='photonic-flickr-image photonic-flickr-set-thumb " + col_class + "' id='photonic-flickr-set-" + id + '-' + position + "-" + photoset.id + "'>" + anchor + text + "</li>";
	}
	return s;
}

/**
 * This is invoked when a list of galleries is printed on the page. <code>[gallery type='flickr' user_id='abc' view='galleries']</code>
 * @param galleries
 */
function photonicGetFlickrGalleriesHTML(galleries) {
	var position = current_position;
	var s = '';
	var user_id = eval('photonic_flickr_user_' + position);

	var col_class = '';
	var columns = eval('photonic_flickr_columns_' + position);
	if (typeof columns != 'undefined' && columns != 'auto') {
		col_class = 'photonic-gallery-' + columns + 'c';
	}
	else if (Photonic_JS.flickr_galleries_per_row_constraint == 'padding') {
		col_class = 'photonic-pad-galleries';
	}
	else {
		col_class = 'photonic-gallery-' + Photonic_JS.flickr_galleries_constrain_by_count + 'c';
	}

	for (var i=0; i<galleries.gallery.length; i++) {
		var gallery = galleries.gallery[i];
		var id = gallery.id;
		var thumb = "http://farm" + gallery.primary_photo_farm + ".static.flickr.com/" + gallery.primary_photo_server + "/" + gallery.primary_photo_id + "_" + gallery.primary_photo_secret + "_" + Photonic_JS.flickr_thumbnail_size + ".jpg";
		var cleanTitle = photonicHtmlEncode(gallery.title._content);
		var owner = typeof gallery.owner == 'undefined' ? user_id : gallery.owner;

		// Roundabout way to ensure that titles are appropriately escaped.
		var image = "<img src='" + thumb + "' alt='" + cleanTitle + "' />";
		var anchor = "<a href='" + gallery.url + "/' class='photonic-flickr-gallery-thumb' " +
				" id='photonic-flickr-gallery-thumb-" + id + '-' + position + '-' + gallery.id + "' title='" + cleanTitle + "'>" + image + "</a>";

		var text = '';
		if (Photonic_JS.flickr_gallery_title_display == 'below') {
			text = "<span class='photonic-gallery-title'><a href='" + gallery.url + "/' title='" + cleanTitle + "'>" + gallery.title._content + "</a></span>";
			if (!Photonic_JS.flickr_hide_gallery_photos_count_display) {
				text += '<span class="photonic-photoset-photo-count">' + Photonic_JS.flickr_photo_count.replace('{#}', gallery.count_photos) + '</span>';
			}
		}

		s += "<li class='photonic-flickr-image photonic-flickr-gallery-thumb " + col_class + "' id='photonic-flickr-gallery-" + id + '-' + position + "-" + gallery.id + "'>" + anchor + text + "</li>";
	}
	return s;
}

function photonicGetFlickrSinglePhotoHTML(photo) {
	var s = '';
	var main_size = Photonic_JS.flickr_main_size == 'none' ? '' : '_' + Photonic_JS.flickr_main_size;
	var orig = "http://farm" + photo.farm + ".static.flickr.com/" + photo.server + "/" + photo.id + "_" + photo.secret + main_size + ".jpg";
	s += "<img src='" + orig + "'>";
	if (typeof photo.urls != 'undefined' && typeof photo.urls.url != 'undefined' && typeof photo.urls.url[0] != 'undefined') {
		s = "<a href='" + photo.urls.url[0]._content + "'>" + s + "</a>";
	}

	if (typeof photo.description != 'undefined' && photo.description._content != '') {
		s = "<div class='wp-caption'>" + s + "<div class='wp-caption-text'>" + photo.description._content + "</div>" + "</div>";
	}

	if (typeof photo.title != 'undefined') {
		s = "<h3 class='photonic-single-photo-header photonic-single-flickr-photo-header'>" + photo.title._content + "</h3>" + s;
	}
	return s;
}
