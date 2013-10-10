// Root controller system, loading main app system here
(function() {
	/**
	 * Generate function to replace an id by the content given
	 *
	 * @param id {String} The id to perform changes
	*/
	function __replaceById(id) {
		return function(content) {
			a.page.template.replace(document.getElementById(id), content);
		}
	};

	/**
	 * Get the home image content
	 *
	 * @return {Array} The element found
	*/
	function __getHomeImage() {
		var homeContent = document.getElementById("home-menu");
		return homeContent.getElementsByTagName("img");
	};

	/**
	 * Generate a function to show/hide home menu
	 *
	 * @param id {String} The id to show
	*/
	function __homeSubContent(id) {
		return function() {
			var imgContent = __getHomeImage();

			// We unbind every data
			for(var i=0, l=imgContent.length; i<l; ++i) {
				var el = imgContent[i];
				// We replace all sources
				el.src = el.src.replace("_hover", "");

				// Hide related content
				document.getElementById(el.parentNode.id + "-content").style.display = "none";

				if(el.parentNode.id === id) {
					el.src = el.src.replace(".png", "_hover.png");

					// Hide related content
					document.getElementById(id + "-content").style.display = "block";
				}
			}
		};
	};

	// timer timeout content
	var load = null;

	// Start loading message on too much waiting time (loading start)
	a.message.addListener("a.state.begin", function(data) {
		if(load != null) {
			clearTimeout(load);
			load = null;
		}
		load = setTimeout(function() {
			document.getElementById("page-loading").style.display = "block";
		}, 1000);
	});

	// Stop loading message on too much waiting time (loading end)
	a.message.addListener("a.state.end", function(data) {
		if(load != null) {
			clearTimeout(load);
			load = null;
		}
		document.getElementById("page-loading").style.display = "none";
	});







	// Handle the logged part (when user sign in successfully)
	var home = {
		id   : "home",
		hash : "home",
		title: "AppStorm.JS - home",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		include  : {
			html : "site/html/home.html"
		},
		postLoad : function(result) {
			var imgContent  = __getHomeImage();

			// On all a tag, we add a onmouseover event
			for(var i=0, l=imgContent.length; i<l; ++i) {
				imgContent[i].parentNode.onmouseover = function() {
					a.state.loadById(this.id);
				}
			}
			result.done();
		},
		preUnload : function(result) {
			var imgContent  = __getHomeImage();

			// On all a tag, we remove the onmouseover event
			for(var i=0, l=imgContent.length; i<l; ++i) {
				imgContent[i].parentNode.onmouseover = null;
			}
			result.done();
		},
		children : [
			{
				id   : "home-light",
				load : __homeSubContent("home-light")
			},
			{
				id   : "home-fast",
				load : __homeSubContent("home-fast")
			},
			{
				id   : "home-cross-browsers",
				load : __homeSubContent("home-cross-browsers")
			},
			{
				id   : "home-international",
				load : __homeSubContent("home-international")
			},
			{
				id   : "home-easy",
				load : __homeSubContent("home-easy")
			}
		]
	};
	var tour = {
		id   : "tour",
		hash : "tour",
		title: "AppStorm.JS - tour",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		include  : {
			html : "site/html/tour.html"
		}
	};
	var examples = {
		id   : "examples",
		hash : "examples",
		title: "AppStorm.JS - examples",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		include  : {
			html : "site/html/examples.html"
		}
	};
	var documentation = {
		id   : "documentation",
		hash : "doc",
		title: "AppStorm.JS - documentation",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		include  : {
			html : "site/html/documentation.html"
		}
	};
	var contact = {
		id   : "contact",
		hash : "contact",
		title: "AppStorm.JS - contact",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		include  : {
			html : "site/html/contact.html"
		}
	};
	var download = {
		id   : "download",
		hash : "download",
		title: "AppStorm.JS - download",
		bootOnLoad : true,
		load : __replaceById("page-container"),

		data : "download",
		include  : {
			html : "site/html/download.html"
		},

		// We do a better presentation of available download
		converter : function(data) {
			a.storage.memory.setItem("download-content", data);
		},
		postLoad : function(result) {
			// Making a tag a clickable element
			var aList = document.getElementById("form-download").getElementsByTagName("a"),
				i     = aList.length;

			while(i--) {
				aList[i].onclick = function() {
					var j = aList.length;
					// Hiding previous selected item
					while(j--) {
						removeClass(aList[j], "active");
					}
					// Setting class
					a.storage.memory.setItem("download-current", this.className);
					addClass(this, "active");

					// Loading sub content
					a.state.loadById("download-content");
				}
			}

			// We perform a default load
			a.storage.memory.setItem("download-current", "download-mustache");
			a.state.loadById("download-content");

			result.done();
		},

		children : {
			id : "download-content",
			bootOnLoad: true,
			data : "{{memory: download-content}}",
			load : __replaceById("download-template-selected"),

			include : {
				html : "site/html/download-section.html"
			},

			converter : function(data) {
				// We select only applyied data
				var selector = a.storage.memory.getItem("download-current");

				if(a.isNull(selector) || !a.isString(selector)) {
					return;
				}

				// we make a new organisation of data
				var old = data.slice();
				data.current = {};
				data.previous = [];

				for(var i=0, l=old.length; i<l; ++i) {
					if(old[i].version === "current") {
						data.current = old[i].content;
					} else {
						data.previous.push(old[i]);
					}
				}

				// Replacing content
				selector = selector.replace("download-", "");

				// Apply filder
				var current  = a.clone(data.current),
					previous = a.clone(data.previous),
					ci = current.length,
					pi = previous.length;

				// Selector on current
				while(ci--) {
					if(current[ci].search(selector) === -1) {
						current.splice(ci, 1);
					} else {
						// We erase content for letting good download name
						current[ci] = selector;
					}
				}

				// Selector on previous
				while(pi--) {
					var version = previous[pi],
						vi      = version.content.length;
					while(vi--) {
						if(version.content[vi].search(selector) === -1) {
							version.content.splice(vi, 1);
						} else {
							// We erase content for letting good download name
							version.content[vi] = selector;
						}
					}
				}

				// Applying
				data.current  = current;
				data.previous = previous;
			}
		}
	};


	// Finally we add elements to system
	a.state.add(home);
	a.state.add(tour);
	a.state.add(examples);
	a.state.add(documentation);
	a.state.add(contact);
	a.state.add(download);
})();