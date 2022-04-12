<?php
# Switching stuff
if ( defined( 'MW_DB' ) ) {
    // Command-line mode and maintenance scripts (e.g. update.php) 
    $wgDBname = MW_DB;
} else {
    // Web server
    $server = $_SERVER['SERVER_NAME'] ?? 'a.wikifarm.cz';
    if ( preg_match( '/^(.*)\.localhost$/', $server, $matches ) ) {
        $wikiname = $matches[1];
    } elseif ( preg_match( '/^(.*)\.wikifarm(\.cz)?$/', $server, $matches ) ) {
            $wikiname = $matches[1];
    } elseif ( preg_match( '/^(.*)\.ngrok.io$/', $server, $matches ) ) {
            $wikiname = 'a';
            $wmgServer = 'https://' . $matches[1] . '.ngrok.io';
    }
    else {
        //die( "Invalid host name, can't determine wiki name\n" );
	$wikiname = 'a';
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

$wgLocalDatabases = $wgConf->getLocalDatabases();
require __DIR__ . "/InitialiseSettings.php";
$confParams = [
    'lang'    => $lang,
    'docRoot' => $_SERVER['DOCUMENT_ROOT'],
    'site'    => $site,
];
$dblists = [];
foreach (['closed', 'fishbowl'] as $dblist) {
    $wikis = DBLists::readDbListFile( $dblist );
    if ( in_array( $wgDBname, $wikis ) ) {
        $dblists[] = $dblist;
    }
}
$globals = $wgConf->getAll( $wgDBname, "wiki", $confParams, $dblists );
extract( $globals );

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
$wgEnableJavaScriptTest = true;
$wgShowExceptionDetails = true;
$wgDebugLogFile = "/var/www/wikis/logs/debug-{$wgDBname}.log";
$wgGroupPermissions['sysop']['deletelogentry'] = true;
$wgGroupPermissions['sysop']['deleterevision'] = true;
$wgGroupPermissions['sysop']['unblockself'] = false;
$wgAllowUserJs = true;
$wgLocaltimezone = 'CET';
$wgMaxNameChars = 85;
$wgJobRunRate = 0;
$wgSessionCacheType = CACHE_DB;
// $wgMainCacheType = CACHE_NONE;
$wgMainCacheType = CACHE_MEMCACHED;
$wgMemCachedServers = [ '127.0.0.1:11211' ];

// load skin
wfLoadSkin( 'Vector' );
wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'MinervaNeue' );
wfLoadSkin( 'Timeless' );

if ( $wmgUseCentralAuth ) {
    // We're a family - CentralAuth
    wfLoadExtension( 'CentralAuth' );
    $wgCentralAuthEnableGlobalRenameRequest = true;
    $wgGlobalRenameBlacklist = "https://a.wikifarm.cz/w/index.php?title=Rename_blacklist&action=raw";
    $wgGlobalRenameBlacklistRegex = true;

    $wgSiteMatrixFishbowlSites = DBLists::readDbListFile( 'fishbowl' );
    function wmgCentralAuthWikiList( &$list ) {
        global $wgLocalDatabases, $wgSiteMatrixFishbowlSites;

        $list = array_diff(
            $wgLocalDatabases,
            $wgSiteMatrixFishbowlSites
        );
        return false;
    }
    $wgHooks['CentralAuthWikiList'][] = 'wmgCentralAuthWikiList';

    if ( $wmgLocalAuthLoginOnly ) {
        // copied from WMF's MW config
        // T57420: prevent creation of local password records for SUL users
        if ( isset( $wgAuthManagerAutoConfig['primaryauth'][\MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class] ) ) {
            $wgAuthManagerAutoConfig['primaryauth'][\MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class]['args'][0]['loginOnly'] = true;
        }
    }
}

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
wfLoadExtension( 'WikimediaMessages' );
wfLoadExtension( 'WikiEditor' );
$wgDefaultUserOptions['usebetatoolbar'] = 1;
wfLoadExtension( 'CodeEditor' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'AntiSpoof' );
wfLoadExtension( 'SpamBlacklist' );
wfLoadExtension( 'PageViewInfo' );
wfLoadExtension( 'GlobalBlocking' );
$wgGlobalBlockingDatabase = "centralauth";
$wgGlobalBlockRemoteReasonUrl = 'https://a.wikifarm.cz/w/api.php';
// wfLoadExtension( 'AbuseFilter' );
wfLoadExtension( 'CheckUser' );
$wgCheckUserEnableSpecialInvestigate = true;
wfLoadExtension( 'Echo' );
wfLoadExtension( 'OATHAuth' );
$wgOATHAuthDatabase = "centralauth";
//wfLoadExtension( 'WebAuthn' );
wfLoadExtension( 'MobileFrontend' );
wfLoadExtension( 'Interwiki' );
wfLoadExtension( 'UploadWizard' );
wfLoadExtension( 'SandboxLink' );
wfLoadExtension( 'ParserFunctions' );
wfLoadExtension( 'cldr' );
wfLoadExtension( 'TorBlock' );
wfLoadExtension( 'TemplateStyles' );
require "growth.php";
require "ContactPages.php";

if ( $wmgUseSecurePoll ) {
    wfLoadExtension( 'SecurePoll' );
    $wgSecurePollUseNamespace = true;
}

if ( $wmgUseVisualEditor ) {
    wfLoadExtension( 'VisualEditor' );
}
if ( $wmgUseDiscussionTools ) {
    wfLoadExtension( 'DiscussionTools' );
    $wgDiscussionToolsEnable = true;
}
if ( $wmgUseFlow ) {
    wfLoadExtension( 'Flow' );
}
if ( $wmgUseConfirmEdit ) {
	wfLoadExtension( 'ConfirmEdit' );
	$wgCaptchaClass = 'SimpleCaptcha';
}
if ( $wmgUseEventLogging ) {
	wfLoadExtension( 'EventLogging' );
	wfLoadExtension( 'EventBus' );
	wfLoadExtension( 'EventStreamConfig' );

	// $wgEventLoggingBaseUri = 'http://localhost:8080/event';

	// per https://gerrit.wikimedia.org/g/mediawiki/extensions/EventLogging/+/54d0f7cb149c07fc3bda47efc0e708c01d6ca177/devserver/README.md
	$wgEventLoggingServiceUri = 'http://localhost:8192/v1/events';
	$wgEventLoggingQueueLingerSeconds = 1;
	$wgEventLoggingStreamNames = false;

	$wgEnableEventBus = 'TYPE_EVENT';
	$wgEventServices = [
		'eventbus' => [
			'url' => $wgEventLoggingServiceUri,
		],
		'eventgate-analytics-external' => [
			'url' => $wgEventLoggingServiceUri,
		],
		'eventgate' => [
			'url' => $wgEventLoggingServiceUri,
		],
	];
	$wgEventGateServiceDefault = 'eventgate';
}

if ( $wmgUseCentralNotice ) {
    wfLoadExtension( 'CentralNotice' );
    $wgCentralDBname = 'awiki';
    $wgCentralHost = '//a.wikifarm.cz';
    $wgCentralSelectedBannerDispatcher = '//a.wikifarm.cz/w/index.php?title=Special:BannerLoader';
    $wgCentralBannerRecorder = '//a.wikifarm.cz/w/index.php?title=Special:RecordImpression';
    $wgNoticeUseTranslateExtension = true;
    $wgNoticeProjects = ['wiki'];
    $wgCentralNoticeMessageProtectRight = 'banner-protect';
    $wgGroupPermissions['sysop']['banner-protect'] = true;
    $wgGroupPermissions['translationadmin']['banner-protect'] = true;
    $wgAvailableRights[] = 'banner-protect';
}

if ( $wmgUseMassMessage ) {
    wfLoadExtension( 'MassMessage' );
}

if ( $wmgUseDynamicPageList ) {
    wfLoadExtension( 'intersection' );
}

if ( $wmgUseWikiLove ) {
    wfLoadExtension( 'WikiLove' );
    $wgDefaultUserOptions['wikilove-enabled'] = 1;
}

if ( $wmgUseTranslate ) {
    wfLoadExtension( 'Translate' );
    wfLoadExtension( 'TranslationNotifications' );
    wfLoadExtension( 'UniversalLanguageSelector' );
    $wgPageLanguageUseDB = true;
    $wgTranslateUsePreSaveTransform = true; // T39304

    $wgEnablePageTranslation = true;
    //$wgTranslateDelayedMessageIndexRebuild = true;
    $wgGroupPermissions['translationadmin']['pagetranslation'] = true;
	$wgGroupPermissions['translationadmin']['translate-manage'] = true;
	$wgGroupPermissions['translationadmin']['translate-import'] = true; // T42341
	$wgGroupPermissions['translationadmin']['pagelang'] = true; // T153209
	$wgGroupPermissions['user']['translate-messagereview'] = true;
	$wgGroupPermissions['user']['translate-groupreview'] = true;
	$wgGroupPermissions['sysop']['pagelang'] = true; // T153209
	$wgAddGroups['bureaucrat'][] = 'translationadmin'; // T178793
	$wgRemoveGroups['bureaucrat'][] = 'translationadmin'; // T178793
	$wgGroupsAddToSelf['sysop'][] = 'translationadmin'; // T178793
	$wgGroupsRemoveFromSelf['sysop'][] = 'translationadmin'; // T178793

    $wgTranslateWorkflowStates = [
		'progress' => [ 'color' => 'd33' ],
		'needs-updating' => [ 'color' => 'fc3' ],
		'proofreading' => [ 'color' => 'fc3' ],
		'ready' => [ 'color' => 'FF0' ],
		'published' => [ 'color' => 'AEA' ],
		'state conditions' => [
			[ 'ready', [ 'PROOFREAD' => 'MAX' ] ],
			[ 'proofreading', [ 'TRANSLATED' => 'MAX' ] ],
			[ 'progress', [ 'UNTRANSLATED' => 'NONZERO' ] ],
		],
	];
    $wgTranslateDocumentationLanguageCode = 'qqq';
    $wgTranslatePageTranslationULS = true;
    $wgTranslateDelayedMessageIndexRebuild = true;
    $wgTranslateStatsProviders = [
        "edits" => "MediaWiki\Extension\Translate\Statistics\TranslatePerLanguageStats",
        "users" => "MediaWiki\Extension\Translate\Statistics\TranslatePerLanguageStats",
        "reviews" => "MediaWiki\Extension\Translate\Statistics\ReviewPerLanguageStats",
        "reviewers" => "MediaWiki\Extension\Translate\Statistics\ReviewPerLanguageStats",
        "registrations" => null,
    ];
}

// Per wiki extension stuff
if ( $wmgUseFlaggedRevs ) {
    $wgAddGroups['bureaucrat'][] = 'sysop';
    $wgRemoveGroups['bureaucrat'][] = 'bot';
    require "flaggedrevs.php";
}

if ( $wmgUseOAuth ) {
	wfLoadExtension( 'OAuth' );
	$wgMWOAuthCentralWiki = 'awiki';
	$wgMWOAuthSharedUserSource = 'CentralAuth';
}

if ( $wmgUseWikiSEO ) {
	wfLoadExtension( 'WikiSEO' );
	$wgWikiSeoEnableAutoDescription = true;
}

# Must be at the end
$wgCdnServersNoPurge[] = '127.0.0.0/8';
$wgEnableDnsBlacklist = true;
if ( isset($wmgServer) ) {
    $wgServer = $wmgServer; // Must be here, to override IS.php
}
$wgGroupPermissions['bureaucrat']['userrights'] = false;
$wgGroupPermissions['user']['editcontentmodel'] = false;
$wgGroupPermissions['sysop']['editcontentmodel'] = true;

$wgGECampaignPattern = '/^growth-advancement-test-2021$/';
$wgLoginLanguageSelector = true;
$wgGroupPermissions['*']['applychangetags'] = true;
