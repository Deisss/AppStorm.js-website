each release should have this structure:

appstorm-v{{version}}

	=> compressed-handlebars
		=> appstorm-{{version}}.min.js
		=> other files (like flash/silverlight loader)

	=> compressed-mustache
		=> appstorm-{{version}}.min.js
		=> other files (like flash/silverlight loader)

	=> uncompressed
		=> appstorm here