// This part allow system to startup (decide what to startup, in which situation)

/*
-------------------------------
  THE FUNCTIONS FROM JQUERY NEEDED...
-------------------------------
*/
/**
 * Get the element no matter if it's and id or the element
 *
 * @param el {String | DOMElement} The element to get
 * @return {DOMElement | null} The element found
*/
function elOrId(el) {
	if(a.isString(el)) {
		return document.getElementById(el);
	}
	return el;
};

/**
 * Detect if element got classname or not
 *
 * @param el {String | DOMElement} The element to check
 * @param cls {String} The classname to check
 * @return {Boolean} True if element got classname, false in other case
*/
function hasClass(el, cls) {
	return elOrId(el).className.match(new RegExp("(\\s|^)" + cls + "(\\s|$)"));
};

/**
 * Add a class to given element
 *
 * @param el {String | DOMElement} The element to change
 * @param cls {String} The classname to add
*/
function addClass(el,cls) {
	if(!this.hasClass(el,cls)) {
		elOrId(el).className += " " + cls;
	}
};

/**
 * Remove a class from given element
 *
 * @param el {String | DOMElement} The element to change
 * @param cls {String} The classname to remove
*/
function removeClass(el,cls) {
	el = elOrId(el);
	if(hasClass(el,cls)) {
		var reg = new RegExp("(\\s|^)" + cls + "(\\s|$)");
		el.className = el.className.replace(reg, "");
	}
};

/**
 * Toggle a class on a given element
 *
 * @param el {String | DOMElement} The element to change
 * @param cls {String} The classname to add/remove
*/
function toggleClass(el, cls) {
	el = elOrId(el);
	if(hasClass(el, cls)) {
		removeClass(el, cls);
	} else {
		addClass(el, cls);
	}
};


// Handling start
(function() {
	a.environment.set("console", "warn");
	a.environment.set("verbose", 1);

	var currentHash = a.page.event.hash.getHash(),
		timerId = null,
		max = 1000;

	// Initialise page event hash system
	a.page.event.hash.setPreviousHash("");
	window.location.href = "#loading_application";

	/**
	 * handle "state change" for every browser
	*/
	function firstHandle() {
		if(a.page.event.hash.getHash() !== currentHash) {
			window.location.href = "#" + currentHash;
			max = 0;
		}
		if(max-- <= 0) {
			a.timer.remove(timerId);
		}
	};

	// The main starter is here, we will customize it soon
	if(currentHash === null || currentHash === "" || !a.state.hashExists(currentHash)) {
		currentHash = "home";
	}

	// Some browser don't get hash change with onHashchange event, so we decide to use timer
	// Note : a.page.event.hash is more complex, here is little example
	timerId = a.timer.add(firstHandle, null, 10);
})();