<?php

# Switching stuff
if ( defined( 'MW_DB' ) ) {
    // Command-line mode and maintenance scripts (e.g. update.php) 
    $wgDBname = MW_DB;
} else {
    // Web server
    $server = $_SERVER['SERVER_NAME'];
    if ( preg_match( '/^(.*)\.localhost$/', $server, $matches ) ) {
        $wikiname = $matches[1];
    } else {
        if ( preg_match( '/^(.*)\.wikifarm$/', $server, $matches ) ) {
            $wikiname = $matches[1];
        } else {
            die( "Invalid host name, can't determine wiki name" );
        }
    }
    /*if ( $wikiname === "www" ) {
        // Optional: Override database name of your "main" wiki (otherwise "wwwwiki")
        $wikiname = "pub";
    } else if ( $wikiname === "wiki" ) {
        $wikiname = "inner";
    }*/
    $wgDBname = $wikiname . "wiki";
}

// Load external stuff
require_once __DIR__ . "/PrivateSettings.php";
require_once __DIR__ . "/DBLists.php";

// SiteConfiguration
$wgConf = new SiteConfiguration;
list( $site, $lang ) = $wgConf->siteFromDB( $wgDBname );
$wgConf->suffixes = [
    'wiki',
];
$dbList = 'all';
$wgConf->wikis = DBLists::readDbListFile( $dbList );

$wgLocalDatabases =& $wgConf->getLocalDatabases();
require __DIR__ . "/InitialiseSettings.php";
$confParams = [
    'lang'    => $lang,
    'docRoot' => $_SERVER['DOCUMENT_ROOT'],
    'site'    => $site,
];
$dblists = [];
/*foreach (["private", "separate", "fishbowl"] as $dblist) {
    $wikis = DBLists::readDbListFile( $dblist );
    if ( in_array( $wgDBname, $wikis ) ) {
        $dblists[] = $dblist;
    }
}*/
$globals = $wgConf->getAll( $wgDBname, "wiki", $confParams, $dblists );
extract( $globals );
$wgLocalDatabases =& $wgConf->getLocalDatabases();

// Configure database
$wgDBuser = "wikiuser";
$wgDBserver = "localhost";
$wgDBtype = "mysql";
$wgDBmysql5 = true;
$wgDBprefix = "";
$wgDBTableOptions   = "ENGINE=InnoDB, DEFAULT CHARSET=binary";

// Configure email notifications
$wgEnotifWatchlist = true;
$wgEnotifUserTalk = true;
$wgPasswordSender = "devwiki@martin.urbanec.cz";

// Misc common things
$wgShowExceptionDetails = true;
$wgDebugLogFile = "/var/www/wikis/logs/debug-{$wgDBname}.log";

// load skin
wfLoadSkin( 'Vector' );

// We're a family - CentralAuth
wfLoadExtension( 'CentralAuth' );
$wgCentralAuthEnableGlobalRenameRequest = true;

// Renameuser
wfLoadExtension( 'Renameuser' );
$wgGroupPermissions['bureaucrat']['renameuser'] = false;

// Process permissions
foreach ( $groupOverrides2 as $group => $permissions ) {
	if ( !array_key_exists( $group, $wgGroupPermissions ) ) {
		$wgGroupPermissions[$group] = [];
	}
	$wgGroupPermissions[$group] = $permissions + $wgGroupPermissions[$group];
}

foreach ( $groupOverrides as $group => $permissions ) {
	if ( !array_key_exists( $group, $wgGroupPermissions ) ) {
		$wgGroupPermissions[$group] = [];
	}
	$wgGroupPermissions[$group] = $permissions + $wgGroupPermissions[$group];
}

// Another extensions
wfLoadExtension( 'SpamBlacklist' );
wfLoadExtension( 'GlobalBlocking' );
$wgGlobalBlockingDatabase = "centralauth";
wfLoadExtension( 'AbuseFilter' );
