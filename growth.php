<?php

use GrowthExperiments\AqsEditInfoService;
use MediaWiki\MediaWikiServices;

wfLoadExtension( 'GrowthExperiments' );
$wgGEDeveloperSetup = true;

// Welcome survey
$wgWelcomeSurveyEnabled = true;
$wgWelcomeSurveyPrivacyPolicyUrl = 'https://martin.urbanec.cz';
// Homepage
$wgGEHomepageEnabled = true;
$wgGEHomepageNewAccountEnablePercentage = 100;
$wgGEHomepageTutorialTitle = 'Tutorial'; // MediaWiki tutorial title
$wgGENewcomerTasksRemoteApiUrl = 'https://cs.wikipedia.org/w/api.php';
$wgGENewcomerTasksTopicType = 'ores';
$wgGENewcomerTasksOresTopicConfigTitle = 'mw:MediaWiki:NewcomerTopicsOres.json';
// Help panel
$wgGEHelpPanelEnabled = true;
$wgGEHelpPanelAskMentor = true;
$wgGEHelpPanelLoggingEnabled = false; // disable event logging
$wgGEHelpPanelNewAccountEnablePercentage = 100;
$wgGEHelpPanelHelpDeskTitle = 'Help_Desk'; // MW title for the help desk
//$wgGEHelpPanelHelpDeskTitle = null;
$wgGEHelpPanelViewMoreTitle = 'Help'; // MW title for additional help links
// List of links to help topics
$wgGEHelpPanelLinks = [ 
[ 'title' => 'Test', 'text' => 'Help Text 1', 'id' => 'Test' ]
];
$wgGEHelpPanelSearchEnabled = true;
// Email confirmation changes
$wgGEConfirmEmailEnabled = true;


// Stuff for T228212
$wgGEOutreachDashboardCampaigns = [
    "https://outreachdashboard.wmflabs.org/campaigns/studenti/users.json"
];

//$wgGENewcomerTasksConfigTitle = 'mw:Growth/Personalized_first_day/Newcomer_tasks/Prototype/templates/en.json';

# Search for tasks on en.wikipedia.org
$wgGENewcomerTasksRemoteApiUrl = 'https://en.wikipedia.org/w/api.php';

$wgGERestbaseUrl = 'https://en.wikipedia.org/api/rest_v1';
$wgHooks['MediaWikiServices'][] = function ( MediaWikiServices $services ) {
	$services->redefineService( 'GrowthExperimentsEditInfoService', function ( MediaWikiServices $services ) {
		return new AqsEditInfoService( $services->getHttpRequestFactory(), $services->getMainWANObjectCache(), 'en.wikipedia' );
	} );
};

// link recommendation
$wgGELinkRecommendationServiceWikiIdMasquerade = 'cswiki';
$wgGELinkRecommendationServiceUrl = 'https://api.wikimedia.org/service/linkrecommendation';
$wgGELinkRecommendationFallbackOnDBMiss = true;

// image recommendation
$wgGEImageRecommendationServiceUrl = 'https://image-suggestion.discovery.wmnet:30443';
$wgGEImageRecommendationApiHandler = "production";
$wgGEImageRecommendationServiceWikiIdMasquerade = 'cswiki';

// mentorship
$wgGEMentorProvider = 'structured';
$wgGEMentorDashboardUseVue = true;

// test
$wgGEHomepageImpactModuleEnabled = false;
