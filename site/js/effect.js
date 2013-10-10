// This part allow system to startup (decide what to startup, in which situation)

// Handling "tiny" effect on hash change, the menu change too...
(function() {
	/**
	 * Binding from hashtag change to a given element class
	 *
	 * @param element {DOMObject} An element to check classname on hashtag changes
	*/
	function bindHash(element) {
		return function(data) {
			if(element.innerHTML.toLowerCase() === data.value) {
				element.className = "active";
			} else {
				element.className = "";
			}
		}
	};

	var nodeList = document.getElementById("page-menu").childNodes;

	// We bind hashtag changes to active icon
	for(var i in nodeList) {
		if(nodeList[i].tagName && nodeList[i].tagName.toUpperCase() === "LI") {
			a.message.addListener("a.page.event.hash", bindHash(nodeList[i].firstChild));
		}
	}
})();