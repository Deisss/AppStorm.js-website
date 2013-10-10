--------------------------------------
  - ARCHIVE TOOLS
--------------------------------------

to use google closure (need java):

	java -jar compiler.jar --compilation_level ADVANCED_OPTIMIZATIONS --js hello.js



to use uglify.js (need Node.JS and nom, then 'npm install uglify-js -g' ):
And then (uglifyjs [input files] [options]):

	uglifyjs file1.js file2.js



--------------------------------------
  - ARCHIVE TOOLS
--------------------------------------
First install git, and add the bin the folder in PATH.


to use JsDoc3:
	- install node.js
	- install java
	- npm install -g git://github.com/jsdoc3/jsdoc.git
	./jsdoc youSourceCodeFile.js

to use YuiDoc3:
	- install node.js
	- npm -g install yuidocjs
	./yuidoc /folder