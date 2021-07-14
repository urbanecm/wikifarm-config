<?php
wfLoadExtension( 'GrowthExperiments' );
$wgGEDeveloperSetup = true;

// Welcome survey
$wgWelcomeSurveyEnabled = true;
$wgWelcomeSurveyPrivacyPolicyUrl = 'https://martin.urbanec.cz';
// Homepage
$wgGEHomepageEnabled = true;
$wgGEHomepageNewAccountEnablePercentage = 100;
$wgGEHomepageTutorialTitle = 'Tutorial'; // MediaWiki tutorial title
$wgGEHomepageLoggingEnabled = false; // Disable event logging
$wgGEHomepageMentorsList = 'Mentors'; // MediaWiki title with list of mentors
$wgGEHomepageManualAssignmentMentorsList = '';
$wgGEHomepageClaimMenteeAllowedList = 'Mentors/ClaimMentee';
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
$wgGEMentorDashboardEnabled = true;
