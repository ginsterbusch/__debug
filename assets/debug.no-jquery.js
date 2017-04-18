/*!
 * contentloaded.js
 *
 * Author: Diego Perini (diego.perini at gmail.com)
 * Summary: cross-browser wrapper for DOMContentLoaded
 * Updated: 20101020
 * License: MIT
 * Version: 1.2
 *
 * URL:
 * http://javascript.nwbox.com/ContentLoaded/
 * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
 * https://github.com/dperini/ContentLoaded/blob/master/src/contentloaded.js
 *
 */

// @win window reference
// @fn function reference
function contentLoaded(win, fn) {

	var done = false, top = true,

	doc = win.document,
	root = doc.documentElement,
	modern = doc.addEventListener,

	add = modern ? 'addEventListener' : 'attachEvent',
	rem = modern ? 'removeEventListener' : 'detachEvent',
	pre = modern ? '' : 'on',

	init = function(e) {
		if (e.type == 'readystatechange' && doc.readyState != 'complete') return;
		(e.type == 'load' ? win : doc)[rem](pre + e.type, init, false);
		if (!done && (done = true)) fn.call(win, e.type || e);
	},

	poll = function() {
		try { root.doScroll('left'); } catch(e) { setTimeout(poll, 50); return; }
		init('poll');
	};

	if (doc.readyState == 'complete') fn.call(win, 'lazy');
	else {
		if (!modern && root.doScroll) {
			try { top = !win.frameElement; } catch(e) { }
			if (top) poll();
		}
		doc[add](pre + 'DOMContentLoaded', init, false);
		doc[add](pre + 'readystatechange', init, false);
		win[add](pre + 'load', init, false);
	}

}

contentLoaded( window, function( e ) {
		
	var doc = window.document,
	dbg = doc.getElementByClassName('theme__debug' ),
	modern = doc.addEventListener,
	
	add = modern ? 'addEventListener' : 'attachEvent',
	pre = modern ? '' : 'on';
	
	// fire when debug data is being clicked
	dbg[add](pre + 'click', function(e) {
		var debugContent = this.getElementsByClassName('debug-content' ),
		var classList = debugContent[0].getAttribute('class'),
			strClassHideDebug = 'hide-debug-content';
		
		if( classList.indexOf( strClassHideDebug ) > 0 ) { 
			
			debugContent[0].setAttribute('class', classList.replace( strClassHideDebug , '' ).trim() );
		} else { 
			debugContent[0].setAttribute('class', classList + ' ' + strClassHideDebug );
		}
		
	}, false );

	
	// document.getElementsByClassName('theme__debug')[0].addEventListener('click', function() {   var classList = this.getAttribute('class');  if( this.getAttribute('class').indexOf('active') < 0 ) {    this.setAttribute('class', classList + ' active' );  } else {    this.setAttribute('class', classList.replace('active', '' ).trim() );  }}, false );
} );

