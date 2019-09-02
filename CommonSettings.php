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
    } elseif ( preg_match( '/^(.*)\.wikifarm$/', $server, $matches ) ) {
            $wikiname = $matches[1];
    } elseif ( preg_match( '/^(.*)\.ngrok.io$/', $server, $matches ) ) {
            $wikiname = 'a';
            $wmgServer = 'https://' . $matches[1] . '.ngrok.io';
    }
    else {
        die( "Invalid host name, can't determine wiki name\n" );
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
foreach (["wikibaserepo"] as $dblist) {
    $wikis = DBLists::readDbListFile( $dblist );
    if ( in_array( $wgDBname, $wikis ) ) {
        $dblists[] = $dblist;
    }
}
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
$wgGroupPermissions['sysop']['deletelogentry'] = true;
$wgGroupPermissions['sysop']['deleterevision'] = true;
$wgGroupPermissions['oversight']['suppressrevision'] = true;
$wgGroupPermissions['oversight']['suppressionlog'] = true;
$wgGroupPermissions['oversight']['hideuser'] = true;

// load skin
wfLoadSkin( 'Vector' );

// We're a family - CentralAuth
wfLoadExtension( 'CentralAuth' );
$wgCentralAuthEnableGlobalRenameRequest = true;
$wgGlobalRenameBlacklist = "http://a.wikifarm/mw/index.php?title=Rename_blacklist&action=raw";
$wgGlobalRenameBlacklistRegex = true;

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

// Extensions enabled on all wikis
wfLoadExtension( 'SpamBlacklist' );
wfLoadExtension( 'GlobalBlocking' );
$wgGlobalBlockingDatabase = "centralauth";
wfLoadExtension( 'AbuseFilter' );
wfLoadExtension( 'CheckUser' );
wfLoadExtension( 'Echo' );
require "growth.php";

// Per wiki extension stuff
if ( $wmgUseFlaggedRevs ) {
    require "flaggedrevs.php";
}

// Commonswiki mirror, based on operations/mediawiki-config
if ( $wmgUseUploadWizard ) {
	wfLoadExtension( 'UploadWizard' );
	$wgUploadWizardConfig = [
		# 'debug' => true,
		// Normally we don't include API keys in CommonSettings, but this key
		// isn't private since it's used on the client-side, i.e. anyone can see
		// it in the outgoing AJAX requests to Flickr.
		'flickrApiKey' => 'e9d8174a79c782745289969a45d350e8',
		// Slowwwwwwww
		'campaignExpensiveStatsEnabled' => false,
		'licensing' => [
			'thirdParty' => [
				'licenseGroups' => [
					[
						// This should be a list of all CC licenses we can reasonably expect to find around the web
						'head' => 'mwe-upwiz-license-cc-head',
						'subhead' => 'mwe-upwiz-license-cc-subhead',
						'licenses' => [
							'cc-by-sa-4.0',
							'cc-by-sa-3.0',
							'cc-by-sa-2.5',
							'cc-by-4.0',
							'cc-by-3.0',
							'cc-by-2.5',
							'cc-zero'
						]
					],
					[
						// n.b. as of April 2011, Flickr still uses CC 2.0 licenses.
						// The White House also has an account there, hence the Public Domain US Government license
						'head' => 'mwe-upwiz-license-flickr-head',
						'subhead' => 'mwe-upwiz-license-flickr-subhead',
						'prependTemplates' => [ 'flickrreview' ],
						'licenses' => [
							'cc-by-sa-2.0',
							'cc-by-2.0',
							'pd-usgov',
						]
					],
					[
						'head' => 'mwe-upwiz-license-public-domain-usa-head',
						'subhead' => 'mwe-upwiz-license-public-domain-usa-subhead',
						'licenses' => [
							'pd-us',
							'pd-old-70-1923',
							'pd-art',
						]
					],
					[
						'head' => 'mwe-upwiz-license-usgov-head',
						'licenses' => [
							'pd-usgov',
							'pd-usgov-nasa'
						]
					],
					[
						'head' => 'mwe-upwiz-license-custom-head',
						'special' => 'custom',
						'licenses' => [ 'custom' ],
					],
					[
						'head' => 'mwe-upwiz-license-none-head',
						'licenses' => [ 'none' ]
					],
				],
			],
		],
		'licenses' => [
			'pd-old-70-1923' => [
				'msg' => 'mwe-upwiz-license-pd-old-70-1923',
				'templates' => [ 'PD-old-70-1923' ],
			],
		],
	];

	$wgUploadWizardConfig['enableChunked'] = 'opt-in';
	$wgUploadWizardConfig['altUploadForm'] = $wmgAltUploadForm; // T35513

	if ( $wgDBname === 'commonswiki' ) {
		$wgUploadWizardConfig['feedbackPage'] = 'Commons:Upload_Wizard_feedback'; # Set by neilk, 2011-11-01, per erik
		$wgUploadWizardConfig["missingCategoriesWikiText"] = "{{subst:unc}}";
		$wgUploadWizardConfig['flickrBlacklistPage'] = 'User:FlickreviewR/bad-authors';
		$wgUploadWizardConfig['customLicenseTemplate'] = 'Template:License_template_tag';
	}

	// Enable Structured Data captions on upload
	if ( $wmgUseWikibaseMediaInfo ) {
		$wgUploadWizardConfig['wikibase']['enabled'] = true;
		$wgUploadWizardConfig['wikibase']['statements'] = $wmgMediaInfoEnableUploadWizardStatements;
	}
}

# Wikibase
if ( $wmgUseWikibaseRepo || $wmgUseWikibaseClient || $wmgUseWikibaseMediaInfo ) {
	include "Wikibase.php";
}

# USL
if ( $wmgUseUniversalLanguageSelector ) {
	wfLoadExtension( 'UniversalLanguageSelector' );
	$wgULSGeoService = false;
	$wgULSAnonCanChangeLanguage = false;
	$wgULSPosition = $wmgULSPosition;
	$wgULSIMEEnabled = $wmgULSIMEEnabled;
	$wgULSWebfontsEnabled = $wmgULSWebfontsEnabled;
	if ( $wmgUseCodeEditorForCore || $wmgUseScribunto ) {
		$wgULSNoImeSelectors[] = '.ace_editor textarea';
	}
	if ( $wmgUseTranslate && $wmgULSPosition === 'personal' ) {
		$wgTranslatePageTranslationULS = true;
	}

	$wgULSEventLogging = true;

	// Compact Language Links …

	// … as a beta feature (see T136677 for beta to stable)
	$wgULSCompactLanguageLinksBetaFeature = $wmgULSCompactLanguageLinksBetaFeature;

	// … as a stable feature
	$wgULSCompactLinksEnableAnon = $wmgULSCompactLinksEnableAnon;
	$wgULSCompactLinksForNewAccounts = $wmgULSCompactLinksForNewAccounts;
	$wgDefaultUserOptions['compact-language-links'] = 1;
}

# Must be at the end
$wgCdnServersNoPurge[] = '127.0.0.0/8';
$wgEnableDnsBlacklist = true;
if ( isset($wmgServer) ) {
    $wgServer = $wmgServer; // Must be here, to override IS.php
}
