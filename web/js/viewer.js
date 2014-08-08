!function(){"use strict";function t(t,e){var i;for(i in e)e.hasOwnProperty(i)&&(t[i]=e[i]);return t}function e(t){if(!this||this.find!==e.prototype.find)return new e(t);if(this.length=0,t)if("string"==typeof t&&(t=this.find(t)),t.nodeType||t===t.window)this.length=1,this[0]=t;else{var i=t.length;for(this.length=i;i;)i-=1,this[i]=t[i]}}e.extend=t,e.contains=function(t,e){do if(e=e.parentNode,e===t)return!0;while(e);return!1},e.parseJSON=function(t){return window.JSON&&JSON.parse(t)},t(e.prototype,{find:function(t){var i=this[0]||document;return"string"==typeof t&&(t=i.querySelectorAll?i.querySelectorAll(t):"#"===t.charAt(0)?i.getElementById(t.slice(1)):i.getElementsByTagName(t)),new e(t)},hasClass:function(t){return this[0]?new RegExp("(^|\\s+)"+t+"(\\s+|$)").test(this[0].className):!1},addClass:function(t){for(var e,i=this.length;i;){if(i-=1,e=this[i],!e.className)return e.className=t,this;if(this.hasClass(t))return this;e.className+=" "+t}return this},removeClass:function(t){for(var e,i=new RegExp("(^|\\s+)"+t+"(\\s+|$)"),s=this.length;s;)s-=1,e=this[s],e.className=e.className.replace(i," ");return this},on:function(t,e){for(var i,s,n=t.split(/\s+/);n.length;)for(t=n.shift(),i=this.length;i;)i-=1,s=this[i],s.addEventListener?s.addEventListener(t,e,!1):s.attachEvent&&s.attachEvent("on"+t,e);return this},off:function(t,e){for(var i,s,n=t.split(/\s+/);n.length;)for(t=n.shift(),i=this.length;i;)i-=1,s=this[i],s.removeEventListener?s.removeEventListener(t,e,!1):s.detachEvent&&s.detachEvent("on"+t,e);return this},empty:function(){for(var t,e=this.length;e;)for(e-=1,t=this[e];t.hasChildNodes();)t.removeChild(t.lastChild);return this}}),"function"==typeof define&&define.amd?define(function(){return e}):(window.blueimp=window.blueimp||{},window.blueimp.helper=e)}(),function(t){"use strict";"function"==typeof define&&define.amd?define(["./blueimp-helper"],t):(window.blueimp=window.blueimp||{},window.blueimp.Gallery=t(window.blueimp.helper||window.jQuery))}(function(t){"use strict";function e(t,i){return t&&t.length&&void 0!==document.body.style.maxHeight?this&&this.options===e.prototype.options?(this.list=t,this.num=t.length,this.initOptions(i),this.initialize(),void 0):new e(t,i):!1}return t.extend(e.prototype,{options:{container:"#blueimp-gallery",slidesContainer:"div",titleElement:"h3",displayClass:"blueimp-gallery-display",controlsClass:"blueimp-gallery-controls",singleClass:"blueimp-gallery-single",leftEdgeClass:"blueimp-gallery-left",rightEdgeClass:"blueimp-gallery-right",playingClass:"blueimp-gallery-playing",slideClass:"slide",slideLoadingClass:"slide-loading",slideErrorClass:"slide-error",slideContentClass:"slide-content",toggleClass:"toggle",prevClass:"prev",nextClass:"next",closeClass:"close",playPauseClass:"play-pause",typeProperty:"type",titleProperty:"title",urlProperty:"href",clearSlides:!0,stretchImages:!1,toggleControlsOnReturn:!0,toggleSlideshowOnSpace:!0,enableKeyboardNavigation:!0,closeOnEscape:!0,closeOnSlideClick:!0,closeOnSwipeUpOrDown:!0,emulateTouchEvents:!0,hidePageScrollbars:!0,disableScroll:!0,carousel:!1,continuous:!0,unloadElements:!0,startSlideshow:!1,slideshowInterval:5e3,index:0,preloadRange:2,transitionSpeed:400,slideshowTransitionSpeed:void 0,event:void 0,onopen:void 0,onslide:void 0,onslideend:void 0,onslidecomplete:void 0,onclose:void 0},carouselOptions:{hidePageScrollbars:!1,toggleControlsOnReturn:!1,toggleSlideshowOnSpace:!1,enableKeyboardNavigation:!1,closeOnEscape:!1,closeOnSlideClick:!1,closeOnSwipeUpOrDown:!1,disableScroll:!1,startSlideshow:!0},support:function(t){var e,i,s,n={touch:void 0!==window.ontouchstart||window.DocumentTouch&&document instanceof DocumentTouch},o={webkitTransition:{end:"webkitTransitionEnd",prefix:"-webkit-"},MozTransition:{end:"transitionend",prefix:"-moz-"},OTransition:{end:"otransitionend",prefix:"-o-"},transition:{end:"transitionend",prefix:""}};for(e in o)if(o.hasOwnProperty(e)&&void 0!==t.style[e]){i=o[e],i.name=e,n.transition=i;break}return document.body.appendChild(t),i&&(e=i.name.slice(0,-9)+"ransform",void 0!==t.style[e]&&(t.style[e]="translateZ(0)",s=window.getComputedStyle(t).getPropertyValue(i.prefix+"transform"),n.transform={prefix:i.prefix,name:e,translate:!0,translateZ:s&&"none"!==s})),void 0!==t.style.backgroundSize&&(t.style.backgroundSize="contain",n.backgroundSize={contain:"contain"===window.getComputedStyle(t).getPropertyValue("background-size")}),document.body.removeChild(t),n}(document.createElement("div")),initialize:function(){return this.initStartIndex(),this.initWidget()===!1?!1:(this.initEventListeners(),this.options.onopen&&this.options.onopen.call(this),this.onslide(this.index),this.ontransitionend(),this.options.startSlideshow&&this.play(),void 0)},slide:function(t,e){window.clearTimeout(this.timeout);var i,s,n,o=this.index;if(o!==t&&1!==this.num){if(e||(e=this.options.transitionSpeed),this.support.transition){for(this.options.continuous||(t=this.circle(t)),i=Math.abs(o-t)/(o-t),this.options.continuous&&(s=i,i=-this.positions[this.circle(t)]/this.slideWidth,i!==s&&(t=-i*this.num+t)),n=Math.abs(o-t)-1;n;)n-=1,this.move(this.circle((t>o?t:o)-n-1),this.slideWidth*i,0);t=this.circle(t),this.move(o,this.slideWidth*i,e),this.move(t,0,e),this.options.continuous&&this.move(this.circle(t-i),-(this.slideWidth*i),0)}else t=this.circle(t),this.animate(o*-this.slideWidth,t*-this.slideWidth,e);this.onslide(t)}},getIndex:function(){return this.index},getNumber:function(){return this.num},prev:function(){(this.options.continuous||this.index)&&this.slide(this.index-1)},next:function(){(this.options.continuous||this.index<this.num-1)&&this.slide(this.index+1)},play:function(t){window.clearTimeout(this.timeout),this.interval=t||this.options.slideshowInterval,this.elements[this.index]>1&&(this.timeout=this.setTimeout(this.slide,[this.index+1,this.options.slideshowTransitionSpeed],this.interval)),this.container.addClass(this.options.playingClass)},pause:function(){window.clearTimeout(this.timeout),this.interval=null,this.container.removeClass(this.options.playingClass)},add:function(t){var e;for(this.list=this.list.concat(t),this.num=this.list.length,this.num>2&&null===this.options.continuous&&(this.options.continuous=!0,this.container.removeClass(this.options.leftEdgeClass)),this.container.removeClass(this.options.rightEdgeClass).removeClass(this.options.singleClass),e=this.num-t.length;e<this.num;e+=1)this.addSlide(e),this.positionSlide(e);this.positions.length=this.num,this.initSlides(!0)},resetSlides:function(){this.slidesContainer.empty(),this.slides=[]},close:function(){var t=this.options;this.destroyEventListeners(),this.pause(),this.container[0].style.display="none",this.container.removeClass(t.displayClass).removeClass(t.singleClass).removeClass(t.leftEdgeClass).removeClass(t.rightEdgeClass),t.hidePageScrollbars&&(document.body.style.overflow=this.bodyOverflowStyle),this.options.clearSlides&&this.resetSlides(),this.options.onclose&&this.options.onclose.call(this)},circle:function(t){return(this.num+t%this.num)%this.num},move:function(t,e,i){this.translateX(t,e,i),this.positions[t]=e},translate:function(t,e,i,s){var n=this.slides[t].style,o=this.support.transition,l=this.support.transform;n[o.name+"Duration"]=s+"ms",n[l.name]="translate("+e+"px, "+i+"px)"+(l.translateZ?" translateZ(0)":"")},translateX:function(t,e,i){this.translate(t,e,0,i)},translateY:function(t,e,i){this.translate(t,0,e,i)},animate:function(t,e,i){if(!i)return this.slidesContainer[0].style.left=e+"px",void 0;var s=this,n=(new Date).getTime(),o=window.setInterval(function(){var l=(new Date).getTime()-n;return l>i?(s.slidesContainer[0].style.left=e+"px",s.ontransitionend(),window.clearInterval(o),void 0):(s.slidesContainer[0].style.left=(e-t)*(Math.floor(100*(l/i))/100)+t+"px",void 0)},4)},preventDefault:function(t){t.preventDefault?t.preventDefault():t.returnValue=!1},onresize:function(){this.initSlides(!0)},onmousedown:function(t){t.which&&1===t.which&&(t.preventDefault(),(t.originalEvent||t).touches=[{pageX:t.pageX,pageY:t.pageY}],this.ontouchstart(t))},onmousemove:function(t){this.touchStart&&((t.originalEvent||t).touches=[{pageX:t.pageX,pageY:t.pageY}],this.ontouchmove(t))},onmouseup:function(t){this.touchStart&&(this.ontouchend(t),delete this.touchStart)},onmouseout:function(e){if(this.touchStart){var i=e.target,s=e.relatedTarget;(!s||s!==i&&!t.contains(i,s))&&this.onmouseup(e)}},ontouchstart:function(t){var e=(t.originalEvent||t).touches[0];this.touchStart={x:e.pageX,y:e.pageY,time:Date.now()},this.isScrolling=void 0,this.touchDelta={}},ontouchmove:function(t){var e,i,s=(t.originalEvent||t).touches[0],n=(t.originalEvent||t).scale,o=this.index;if(!(s.length>1||n&&1!==n))if(this.options.disableScroll&&t.preventDefault(),this.touchDelta={x:s.pageX-this.touchStart.x,y:s.pageY-this.touchStart.y},e=this.touchDelta.x,void 0===this.isScrolling&&(this.isScrolling=this.isScrolling||Math.abs(e)<Math.abs(this.touchDelta.y)),this.isScrolling)this.options.closeOnSwipeUpOrDown&&this.translateY(o,this.touchDelta.y+this.positions[o],0);else for(t.preventDefault(),window.clearTimeout(this.timeout),this.options.continuous?i=[this.circle(o+1),o,this.circle(o-1)]:(this.touchDelta.x=e/=!o&&e>0||o===this.num-1&&0>e?Math.abs(e)/this.slideWidth+1:1,i=[o],o&&i.push(o-1),o<this.num-1&&i.unshift(o+1));i.length;)o=i.pop(),this.translateX(o,e+this.positions[o],0)},ontouchend:function(){var t,e,i,s,n,o=this.index,l=this.options.transitionSpeed,r=this.slideWidth,a=Number(Date.now()-this.touchStart.time)<250,h=a&&Math.abs(this.touchDelta.x)>20||Math.abs(this.touchDelta.x)>r/2,d=!o&&this.touchDelta.x>0||o===this.num-1&&this.touchDelta.x<0,c=!h&&this.options.closeOnSwipeUpOrDown&&(a&&Math.abs(this.touchDelta.y)>20||Math.abs(this.touchDelta.y)>this.slideHeight/2);this.options.continuous&&(d=!1),t=this.touchDelta.x<0?-1:1,this.isScrolling?c?this.close():this.translateY(o,0,l):h&&!d?(e=o+t,i=o-t,s=r*t,n=-r*t,this.options.continuous?(this.move(this.circle(e),s,0),this.move(this.circle(o-2*t),n,0)):e>=0&&e<this.num&&this.move(e,s,0),this.move(o,this.positions[o]+s,l),this.move(this.circle(i),this.positions[this.circle(i)]+s,l),o=this.circle(i),this.onslide(o)):this.options.continuous?(this.move(this.circle(o-1),-r,l),this.move(o,0,l),this.move(this.circle(o+1),r,l)):(o&&this.move(o-1,-r,l),this.move(o,0,l),o<this.num-1&&this.move(o+1,r,l))},ontransitionend:function(t){var e=this.slides[this.index];t&&e!==t.target||(this.interval&&this.play(),this.setTimeout(this.options.onslideend,[this.index,e]))},oncomplete:function(e){var i,s=e.target||e.srcElement,n=s&&s.parentNode;s&&n&&(i=this.getNodeIndex(n),t(n).removeClass(this.options.slideLoadingClass),"error"===e.type?(t(n).addClass(this.options.slideErrorClass),this.elements[i]=3):this.elements[i]=2,s.clientHeight>this.container[0].clientHeight&&(s.style.maxHeight=this.container[0].clientHeight),this.interval&&this.slides[this.index]===n&&this.play(),this.setTimeout(this.options.onslidecomplete,[i,n]))},onload:function(t){this.oncomplete(t)},onerror:function(t){this.oncomplete(t)},onkeydown:function(t){switch(t.which||t.keyCode){case 13:this.options.toggleControlsOnReturn&&(this.preventDefault(t),this.toggleControls());break;case 27:this.options.closeOnEscape&&this.close();break;case 32:this.options.toggleSlideshowOnSpace&&(this.preventDefault(t),this.toggleSlideshow());break;case 37:this.options.enableKeyboardNavigation&&(this.preventDefault(t),this.prev());break;case 39:this.options.enableKeyboardNavigation&&(this.preventDefault(t),this.next())}},handleClick:function(e){var i=this.options,s=e.target||e.srcElement,n=s.parentNode,o=function(e){return t(s).hasClass(e)||t(n).hasClass(e)};n===this.slidesContainer[0]?(this.preventDefault(e),i.closeOnSlideClick?this.close():this.toggleControls()):n.parentNode&&n.parentNode===this.slidesContainer[0]?(this.preventDefault(e),this.toggleControls()):o(i.toggleClass)?(this.preventDefault(e),this.toggleControls()):o(i.prevClass)?(this.preventDefault(e),this.prev()):o(i.nextClass)?(this.preventDefault(e),this.next()):o(i.closeClass)?(this.preventDefault(e),this.close()):o(i.playPauseClass)&&(this.preventDefault(e),this.toggleSlideshow())},onclick:function(t){return this.options.emulateTouchEvents&&this.touchDelta&&(Math.abs(this.touchDelta.x)>20||Math.abs(this.touchDelta.y)>20)?(delete this.touchDelta,void 0):this.handleClick(t)},updateEdgeClasses:function(t){t?this.container.removeClass(this.options.leftEdgeClass):this.container.addClass(this.options.leftEdgeClass),t===this.num-1?this.container.addClass(this.options.rightEdgeClass):this.container.removeClass(this.options.rightEdgeClass)},handleSlide:function(t){this.options.continuous||this.updateEdgeClasses(t),this.loadElements(t),this.options.unloadElements&&this.unloadElements(t),this.setTitle(t)},onslide:function(t){this.index=t,this.handleSlide(t),this.setTimeout(this.options.onslide,[t,this.slides[t]])},setTitle:function(t){var e=this.slides[t].firstChild.title,i=this.titleElement;i.length&&(this.titleElement.empty(),e&&i[0].appendChild(document.createTextNode(e)))},setTimeout:function(t,e,i){var s=this;return t&&window.setTimeout(function(){t.apply(s,e||[])},i||0)},imageFactory:function(e,i){var s,n,o,l=this,r=this.imagePrototype.cloneNode(!1),a=e,h=this.options.stretchImages&&this.support.backgroundSize&&this.support.backgroundSize.contain,d=function(e){if(!s){if(e={type:e.type,target:n},!n.parentNode)return l.setTimeout(d,[e]);s=!0,t(r).off("load error",d),h&&"load"===e.type&&(n.style.background='url("'+a+'") center no-repeat',n.style.backgroundSize="contain"),i(e)}};return"string"!=typeof a&&(a=this.getItemProperty(e,this.options.urlProperty),o=this.getItemProperty(e,this.options.titleProperty)),h?n=this.elementPrototype.cloneNode(!1):(n=r,r.draggable=!1),o&&(n.title=o),t(r).on("load error",d),r.src=a,n},createElement:function(e,i){var s=e&&this.getItemProperty(e,this.options.typeProperty),n=s&&this[s.split("/")[0]+"Factory"]||this.imageFactory,o=e&&n.call(this,e,i);return o||(o=this.elementPrototype.cloneNode(!1),this.setTimeout(i,[{type:"error",target:o}])),t(o).addClass(this.options.slideContentClass),o},loadElement:function(e){this.elements[e]||(this.slides[e].firstChild?this.elements[e]=t(this.slides[e]).hasClass(this.options.slideErrorClass)?3:2:(this.elements[e]=1,t(this.slides[e]).addClass(this.options.slideLoadingClass),this.slides[e].appendChild(this.createElement(this.list[e],this.proxyListener))))},loadElements:function(t){var e,i=Math.min(this.num,2*this.options.preloadRange+1),s=t;for(e=0;i>e;e+=1)s+=e*(0===e%2?-1:1),s=this.circle(s),this.loadElement(s)},unloadElements:function(t){var e,i,s;for(e in this.elements)this.elements.hasOwnProperty(e)&&(s=Math.abs(t-e),s>this.options.preloadRange&&s+this.options.preloadRange<this.num&&(i=this.slides[e],i.removeChild(i.firstChild),delete this.elements[e]))},addSlide:function(t){var e=this.slidePrototype.cloneNode(!1);e.setAttribute("data-index",t),this.slidesContainer[0].appendChild(e),this.slides.push(e)},positionSlide:function(t){var e=this.slides[t];e.style.width=this.slideWidth+"px",this.support.transition&&(e.style.left=t*-this.slideWidth+"px",this.move(t,this.index>t?-this.slideWidth:this.index<t?this.slideWidth:0,0))},initSlides:function(e){var i,s;for(e||(this.positions=[],this.positions.length=this.num,this.elements={},this.imagePrototype=document.createElement("img"),this.elementPrototype=document.createElement("div"),this.slidePrototype=document.createElement("div"),t(this.slidePrototype).addClass(this.options.slideClass),this.slides=this.slidesContainer[0].children,i=this.options.clearSlides||this.slides.length!==this.num),this.slideWidth=this.container[0].offsetWidth,this.slideHeight=this.container[0].offsetHeight,this.slidesContainer[0].style.width=this.num*this.slideWidth+"px",i&&this.resetSlides(),s=0;s<this.num;s+=1)i&&this.addSlide(s),this.positionSlide(s);this.options.continuous&&this.support.transition&&(this.move(this.circle(this.index-1),-this.slideWidth,0),this.move(this.circle(this.index+1),this.slideWidth,0)),this.support.transition||(this.slidesContainer[0].style.left=this.index*-this.slideWidth+"px")},toggleControls:function(){var t=this.options.controlsClass;this.container.hasClass(t)?this.container.removeClass(t):this.container.addClass(t)},toggleSlideshow:function(){this.interval?this.pause():this.play()},getNodeIndex:function(t){return parseInt(t.getAttribute("data-index"),10)},getNestedProperty:function(t,e){return e.replace(/\[(?:'([^']+)'|"([^"]+)"|(\d+))\]|(?:(?:^|\.)([^\.\[]+))/g,function(e,i,s,n,o){var l=o||i||s||n&&parseInt(n,10);e&&t&&(t=t[l])}),t},getItemProperty:function(t,e){return t[e]||t.getAttribute&&t.getAttribute("data-"+e)||this.getNestedProperty(t,e)},initStartIndex:function(){var t,e=this.options.index,i=this.options.urlProperty;if(e&&"number"!=typeof e)for(t=0;t<this.num;t+=1)if(this.list[t]===e||this.getItemProperty(this.list[t],i)===this.getItemProperty(e,i)){e=t;break}this.index=this.circle(parseInt(e,10)||0)},initEventListeners:function(){var e=this,i=this.slidesContainer,s=function(t){var i=e.support.transition&&e.support.transition.end===t.type?"transitionend":t.type;e["on"+i](t)};t(window).on("resize",s),t(document.body).on("keydown",s),this.container.on("click",s),this.support.touch?i.on("touchstart touchmove touchend",s):this.options.emulateTouchEvents&&this.support.transition&&i.on("mousedown mousemove mouseup mouseout",s),this.support.transition&&i.on(this.support.transition.end,s),this.proxyListener=s},destroyEventListeners:function(){var e=this.slidesContainer,i=this.proxyListener;t(window).off("resize",i),t(document.body).off("keydown",i),this.container.off("click",i),this.support.touch?e.off("touchstart touchmove touchend",i):this.options.emulateTouchEvents&&this.support.transition&&e.off("mousedown mousemove mouseup mouseout",i),this.support.transition&&e.off(this.support.transition.end,i)},initWidget:function(){return this.container=t(this.options.container),this.container.length?(this.slidesContainer=this.container.find(this.options.slidesContainer),this.slidesContainer.length?(this.titleElement=this.container.find(this.options.titleElement),this.options.hidePageScrollbars&&(this.bodyOverflowStyle=document.body.style.overflow,document.body.style.overflow="hidden"),1===this.num&&this.container.addClass(this.options.singleClass),this.container[0].style.display="block",this.initSlides(),this.container.addClass(this.options.displayClass),void 0):!1):!1},initOptions:function(e){this.options=t.extend({},this.options),(e&&e.carousel||this.options.carousel&&(!e||e.carousel!==!1))&&t.extend(this.options,this.carouselOptions),t.extend(this.options,e),this.num<3&&(this.options.continuous=this.options.continuous?null:!1),this.support.transition||(this.options.emulateTouchEvents=!1),this.options.event&&this.preventDefault(this.options.event)}}),e}),function(t){"use strict";"function"==typeof define&&define.amd?define(["./blueimp-helper","./blueimp-gallery"],t):t(window.blueimp.helper||window.jQuery,window.blueimp.Gallery)}(function(t,e){"use strict";t.extend(e.prototype.options,{fullScreen:!1});var i=e.prototype.initialize,s=e.prototype.close;return t.extend(e.prototype,{getFullScreenElement:function(){return document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement},requestFullScreen:function(t){t.requestFullscreen?t.requestFullscreen():t.webkitRequestFullscreen?t.webkitRequestFullscreen():t.mozRequestFullScreen&&t.mozRequestFullScreen()},exitFullScreen:function(){document.exitFullscreen?document.exitFullscreen():document.webkitCancelFullScreen?document.webkitCancelFullScreen():document.mozCancelFullScreen&&document.mozCancelFullScreen()},initialize:function(){i.call(this),this.options.fullScreen&&!this.getFullScreenElement()&&this.requestFullScreen(this.container[0])},close:function(){this.getFullScreenElement()===this.container[0]&&this.exitFullScreen(),s.call(this)}}),e}),function(t){"use strict";"function"==typeof define&&define.amd?define(["./blueimp-helper","./blueimp-gallery"],t):t(window.blueimp.helper||window.jQuery,window.blueimp.Gallery)}(function(t,e){"use strict";t.extend(e.prototype.options,{indicatorContainer:"ol",activeIndicatorClass:"active",thumbnailProperty:"thumbnail",thumbnailIndicators:!0});var i=e.prototype.initSlides,s=e.prototype.addSlide,n=e.prototype.resetSlides,o=e.prototype.handleClick,l=e.prototype.handleSlide,r=e.prototype.close;return t.extend(e.prototype,{createIndicator:function(e){var i,s,n=this.indicatorPrototype.cloneNode(!1),o=this.getItemProperty(e,this.options.titleProperty),l=this.options.thumbnailProperty;return this.options.thumbnailIndicators&&(s=e.getElementsByTagName&&t(e).find("img")[0],s?i=s.src:l&&(i=this.getItemProperty(e,l)),i&&(n.style.backgroundImage='url("'+i+'")')),o&&(n.title=o),n},addIndicator:function(t){if(this.indicatorContainer.length){var e=this.createIndicator(this.list[t]);e.setAttribute("data-index",t),this.indicatorContainer[0].appendChild(e),this.indicators.push(e)}},setActiveIndicator:function(e){this.indicators&&(this.activeIndicator&&this.activeIndicator.removeClass(this.options.activeIndicatorClass),this.activeIndicator=t(this.indicators[e]),this.activeIndicator.addClass(this.options.activeIndicatorClass))},initSlides:function(t){t||(this.indicatorContainer=this.container.find(this.options.indicatorContainer),this.indicatorContainer.length&&(this.indicatorPrototype=document.createElement("li"),this.indicators=this.indicatorContainer[0].children)),i.call(this,t)},addSlide:function(t){s.call(this,t),this.addIndicator(t)},resetSlides:function(){n.call(this),this.indicatorContainer.empty(),this.indicators=[]},handleClick:function(t){var e=t.target||t.srcElement,i=e.parentNode;if(i===this.indicatorContainer[0])this.preventDefault(t),this.slide(this.getNodeIndex(e));else{if(i.parentNode!==this.indicatorContainer[0])return o.call(this,t);this.preventDefault(t),this.slide(this.getNodeIndex(i))}},handleSlide:function(t){l.call(this,t),this.setActiveIndicator(t)},close:function(){this.activeIndicator&&this.activeIndicator.removeClass(this.options.activeIndicatorClass),r.call(this)}}),e}),function(t){"use strict";"function"==typeof define&&define.amd?define(["./blueimp-helper","./blueimp-gallery"],t):t(window.blueimp.helper||window.jQuery,window.blueimp.Gallery)}(function(t,e){"use strict";return t.extend(e.prototype.options,{videoContentClass:"video-content",videoLoadingClass:"video-loading",videoPlayingClass:"video-playing",videoPosterProperty:"poster",videoSourcesProperty:"sources"}),e.prototype.videoFactory=function(e,i){var s,n,o,l,r,a=this,h=this.options,d=this.elementPrototype.cloneNode(!1),c=t(d),u=[{type:"error",target:d}],p=document.createElement("video"),m=this.getItemProperty(e,h.urlProperty),f=this.getItemProperty(e,h.typeProperty),g=this.getItemProperty(e,h.titleProperty),v=this.getItemProperty(e,h.videoPosterProperty),y=this.getItemProperty(e,h.videoSourcesProperty);if(c.addClass(h.videoContentClass),g&&(d.title=g),p.canPlayType)if(m&&f&&p.canPlayType(f))p.src=m;else if(y)for("string"==typeof y&&(y=t.parseJSON(y));y&&y.length;)if(n=y.shift(),m=this.getItemProperty(n,h.urlProperty),f=this.getItemProperty(n,h.typeProperty),m&&f&&p.canPlayType(f)){p.src=m;break}return v&&(p.setAttribute("poster",v),s=this.imagePrototype.cloneNode(!1),t(s).addClass(h.toggleClass),s.src=v,s.draggable=!1,d.appendChild(s)),o=document.createElement("a"),o.setAttribute("target","_blank"),o.setAttribute("download",g),o.href=m,p.src&&(p.controls=!0,t(p).on("error",function(){a.setTimeout(i,u)}).on("pause",function(){l=!1,c.removeClass(a.options.videoLoadingClass).removeClass(a.options.videoPlayingClass),r&&a.container.addClass(a.options.controlsClass),a.interval&&a.play()}).on("playing",function(){l=!1,c.removeClass(a.options.videoLoadingClass).addClass(a.options.videoPlayingClass),a.container.hasClass(a.options.controlsClass)?(r=!0,a.container.removeClass(a.options.controlsClass)):r=!1}).on("play",function(){window.clearTimeout(a.timeout),l=!0,c.addClass(a.options.videoLoadingClass)}),t(o).on("click",function(t){t.preventDefault(),l?p.pause():p.play()}),d.appendChild(p)),d.appendChild(o),this.setTimeout(i,[{type:"load",target:d}]),d},e});