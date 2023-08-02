<?php

/**
 * SCSS Snippet
 * @author    Tobias Battenbberg <mountbatt@gmail.com> - inspired by Bart van de Biezen
 * @link      https://github.com/mountbatt/kirby-scssphp
 * @return    CSS and HTML
 * @version   1.0.4
 */

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

// Using realpath seems to work best in different situations.
$root = realpath(__DIR__ . '/../..');

// Set file paths. Used for checking and updating CSS file for current template.
$template     = $page->template();
$SCSS         = $root . '/assets/scss/' . $template . '.scss';
$CSS          = $root . '/assets/css/'  . $template . '.css';
$MAP          = $root . '/assets/css/'  . $template . '.map';
$MAP_FILE			= 'default.map';
$CSSKirbyPath = 'assets/css/' . $template . '.css';

// Set default SCSS if there is no SCSS for current template. If template is default, skip check.
if ($template == 'default' or !file_exists($SCSS)) {
	$SCSS         = $root . '/assets/scss/default.scss';
	$CSS          = $root . '/assets/css/default.css';
	$MAP          = $root . '/assets/css/default.map';
	$MAP_FILE			= 'default.map';
	$CSSKirbyPath = 'assets/css/default.css';
}
// If the CSS file doesn't exist create it so we can write to it
if (!file_exists($CSS)) {
	if (!file_exists($root . '/assets/css/')) {
			mkdir($root . '/assets/css/', 0755, true);
	}
	touch($CSS,  mktime(0, 0, 0, date("m"), date("d"),  date("Y")-10));
}
// For when the plugin should check if partials are changed. If any partial is newer than the main SCSS file, the main SCSS file will be 'touched'. This will trigger the compiler later on, on this server and also on another environment when synced.
if ($kirby->option('scssNestedCheck')) {
	$SCSSDirectory = $root . '/assets/scss/';
	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($SCSSDirectory));
	foreach ($files as $file) {
		if (pathinfo($file, PATHINFO_EXTENSION) == "scss" && filemtime($file) > filemtime($SCSS)) {
			touch ($SCSS);
			clearstatcache();
			break;
		}
	}
}

// Get file modification times. Used for checking if update is required and as version number for caching.
$SCSSFileTime = filemtime($SCSS);
$CSSFileTime = filemtime($CSS);

// Update CSS when needed.
if (!file_exists($CSS) or $SCSSFileTime > $CSSFileTime ) { 

	// Activate library.
	require_once $root . '/site/plugins/scssphp/scss.inc.php';
	$parser = new Compiler();

	// Setting compression provided by library.
	$parser->setOutputStyle(OutputStyle::COMPRESSED);
	

	// Setting relative @import paths.
	$importPath = $root . '/assets/scss';
	$parser->addImportPath($importPath);

	// Place SCSS file in buffer.
	$buffer = file_get_contents($SCSS);

	
	// source Map
	$parser->setSourceMap(Compiler::SOURCE_MAP_FILE);
	$parser->setSourceMapOptions([
			// relative or full url to the above .map file
			'sourceMapURL' => $MAP_FILE,
			
			// (optional) relative or full url to the .css file
			//'sourceMapFilename' => 'my-style.css',
			
			// partial path (server root) removed (normalized) to create a relative url
			//'sourceMapBasepath' => '/var/www/vhost',
			
			// (optional) prepended to 'source' field entries for relocating source files
			//'sourceRoot' => '/',
	]);
	
	// Compile all
	$result = $parser->compileString($buffer);
	
	

	// Update CSS file.
	file_put_contents($CSS, $result->getCss());
	file_put_contents($MAP, $result->getSourceMap());
}

?>
<link rel="stylesheet" property="stylesheet" href="<?php echo url($CSSKirbyPath) ?>?version=<?php echo $CSSFileTime ?>">
