<?php
# NOTE: This file is based on file wmf-config/flaggedrevs.php,
# residing in operations/mediawiki-config on Wikimedia Gerrit.

$wgFlaggedRevsAutopromote = false;

$wgFlaggedRevsStatsAge = false;

$wgExtensionFunctions[] = function () {
	global $wgAddGroups, $wgDBname, $wgDefaultUserOptions, $wgFlaggedRevsAutoconfirm,
		$wgFlaggedRevsAutopromote, $wgFlaggedRevsNamespaces, $wgFlaggedRevsRestrictionLevels,
		$wgFlaggedRevsStatsAge, $wgFlaggedRevsTags, $wgFlaggedRevsTagsRestrictions,
        $wgFlaggedRevsWhitelist, $wgGroupPermissions, $wgRemoveGroups;
    
    	///////////////////////////////////////
	// Common configuration
	// DO NOT CHANGE without hard-coding these values into the relevant wikis first.
	///////////////////////////////////////

	$wgFlaggedRevsNamespaces[] = 828; // NS_MODULE
	$wgFlaggedRevsTags = [
		'accuracy' => [ 'levels' => 2, 'quality' => 2, 'pristine' => 4 ],
	];
	$wgFlaggedRevsTagsRestrictions = [
		'accuracy' => [ 'review' => 1, 'autoreview' => 1 ],
	];
	$wgGroupPermissions['autoconfirmed']['movestable'] = true; // T16166

	$wmfStandardAutoPromote = [
		'days'                  => 60, # days since registration
		'edits'                 => 250, # total edit count
		'excludeLastDays'       => 1, # exclude the last X days of edits from below edit counts
		'benchmarks'            => 15, # number of "spread out" edits
		'spacing'               => 3, # number of days between these edits (the "spread")
		'totalContentEdits'     => 300, # edits to pages in $wgContentNamespaces
		'totalCheckedEdits'     => 200, # edits before the stable version of pages
		'uniqueContentPages'    => 14, # unique pages in $wgContentNamespaces edited
		'editComments'          => 50, # number of manual edit summaries used
		'userpageBytes'         => 0, # size of userpage (use 0 to not require a userpage)
		'neverBlocked'          => true, # username was never blocked before?
		'maxRevertedEditRatio'  => 0.03, # max fraction of edits reverted via "rollback"/"undo"
	];

	$wgGroupPermissions['sysop']['stablesettings'] = false; // -aaron 3/20/10

	$allowSysopsAssignEditor = true;
	$allowSysopsAssignAutoreview = true;
	$allowBureaucratsAssignReviewer = true;

	///////////////////////////////////////
	// Wiki-specific configurations
	///////////////////////////////////////
    if ( $wgDBname === "awiki" ) {
        # same as dewiki
        $wgFlaggedRevsNamespaces[] = NS_CATEGORY;
        $wgFlaggedRevsTags['accuracy']['levels'] = 1;

        $wgFlaggedRevsAutopromote = $wmfStandardAutoPromote;
        $wgFlaggedRevsAutopromote['edits'] = 300;
        $wgFlaggedRevsAutopromote['recentContentEdits'] = 5;
        $wgFlaggedRevsAutopromote['editComments'] = 30;

        $wgFlaggedRevsAutoconfirm = [
            'days'                => 30, # days since registration
            'edits'               => 50, # total edit count
            'spacing'             => 3, # spacing of edit intervals
            'benchmarks'          => 7, # how many edit intervals are needed?
            'excludeLastDays'     => 2, # exclude the last X days of edits from edit counts
            // Either totalContentEdits reqs OR totalCheckedEdits requirements needed
            'totalContentEdits'   => 150, # $wgContentNamespaces edits OR...
            'totalCheckedEdits'   => 50, # ...Edits before the stable version of pages
            'uniqueContentPages'  => 8, # $wgContentNamespaces unique pages edited
            'editComments'        => 20, # how many edit comments used?
            'email'               => false, # user must be emailconfirmed?
            'neverBlocked'        => true, # Can users that were blocked be promoted?
        ];

        $wgGroupPermissions['sysop']['stablesettings'] = true; // -aaron 3/20/10
    }

    # All wikis...

	# Rights for Bureaucrats (b/c)
	if ( isset( $wgGroupPermissions['reviewer'] ) && $allowBureaucratsAssignReviewer ) {
		if ( !in_array( 'reviewer', $wgAddGroups['bureaucrat'] ) ) {
			$wgAddGroups['bureaucrat'][] = 'reviewer'; // promote to full reviewers
		}
		if ( !in_array( 'reviewer', $wgRemoveGroups['bureaucrat'] ) ) {
			$wgRemoveGroups['bureaucrat'][] = 'reviewer'; // demote from full reviewers
		}
	}

	# Rights for Sysops
	if ( isset( $wgGroupPermissions['editor'] ) && $allowSysopsAssignEditor ) {
		if ( !in_array( 'editor', $wgAddGroups['sysop'] ) ) {
			$wgAddGroups['sysop'][] = 'editor'; // promote to basic reviewer (established editors)
		}
		if ( !in_array( 'editor', $wgRemoveGroups['sysop'] ) ) {
			$wgRemoveGroups['sysop'][] = 'editor'; // demote from basic reviewer (established editors)
		}
	}

	if ( isset( $wgGroupPermissions['autoreview'] ) && $allowSysopsAssignAutoreview ) {
		if ( !in_array( 'autoreview', $wgAddGroups['sysop'] ) ) {
			$wgAddGroups['sysop'][] = 'autoreview'; // promote to basic auto-reviewer (semi-trusted users)
		}
		if ( !in_array( 'autoreview', $wgRemoveGroups['sysop'] ) ) {
			$wgRemoveGroups['sysop'][] = 'autoreview'; // demote from basic auto-reviewer (semi-trusted users)
		}
	}
};
