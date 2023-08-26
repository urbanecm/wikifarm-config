<?php

use GrowthExperiments\AqsEditInfoService;
use MediaWiki\MediaWikiServices;

wfLoadExtension( 'GrowthExperiments' );
$wgGEDeveloperSetup = true;

# Search for tasks on en.wikipedia.org
$wgGENewcomerTasksRemoteApiUrl = 'https://cs.wikipedia.org/w/api.php';

$wgGERestbaseUrl = 'https://cs.wikipedia.org/api/rest_v1';
$wgHooks['MediaWikiServices'][] = function ( MediaWikiServices $services ) {
	$services->redefineService( 'GrowthExperimentsEditInfoService', function ( MediaWikiServices $services ) {
		return new AqsEditInfoService( $services->getHttpRequestFactory(), $services->getMainWANObjectCache(), 'cs.wikipedia' );
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

// new impact
$wgGEUseNewImpactModule = true;
$wgGERefreshUserImpactDataMaintenanceScriptEnabled = true;

// mentorship
$wgGEMentorProvider = 'structured';
$wgGEMentorDashboardUseVue = true;
//$wgGEPersonalizedPraiseEnabled = true;
//$wgGEPersonalizedPraiseBackendEnabled = true;
