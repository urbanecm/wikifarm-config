<?php

global $wgConf;

$wgConf->settings = [
    // wgScriptPath and wgArticlePath are the same for all wikis, but they have to be here
    // because some things (e.g. WikiMap) retrieve these values from $wgConf
    'wgScriptPath' => [
        'default' => '/mw',
    ],
    'wgArticlePath' => [
        'default' => '/wiki/$1',
    ],
    'wgServer' => [
        'awiki' => 'http://a.wikifarm',
        'bwiki' => 'http://b.wikifarm',
        'cwiki' => 'http://c.wikifarm',
    ],
    'wgCanonicalServer' => [
        'awiki' => 'http://a.wikifarm',
        'bwiki' => 'http://b.wikifarm',
        'cwiki' => 'http://c.wikifarm',
    ],
    'wgDefaultSkin' => [
        'default' => 'vector',
    ],
    'wgLanguageCode' => [
        'default' => 'en',
    ],
    'wmgUseFlaggedRevs' => [
        'default' => false,
	'awiki' => true,
    ],
    'wgUseInstantCommons' => [
        'default' => true,
    ],
    'wgEnableUploads' => [
        'default' => true,
    ],
    'wmgAltUploadForm' => [ // T35513
        'default' => 'Special:Upload',
    ],
    'wmgUseUploadWizard' => [
        'default' => false,
    ],

    'wgEnablePartialBlocks' => [
        'default' => false,
        'flaggedrevswiki' => true,
    ],
    'groupOverrides2' => [
        'default' => [
            'sysop' => [
                'deleterevision' => true,
                'deletelogentry' => true,
            ],
            // Sysadmin stuff for bureaucrats
            'bureaucrat' => [
                'interwiki' => true,
                'userrights' => true,
            ],
            'steward' => [
                'centralauth-rename' => true,
                'userrights-interwiki' => true,
                'globalgroupmembership' => true,
                'globalgrouppermissions' => true,
                'centralauth-lock' => true,
                'centralauth-oversight' => true,
                'centralauth-unmerge' => true,
                'globalblock' => true,
            ],
            'ipblock-exempt' => [
                'ipblock-exempt' => true
            ],
	    'test' => [
	    	'read' => true
            ],
        ],
        // Read-only (except stewards)
        'closed' => [
            '*' => [
                'edit' => false,
                'createaccount' => false,
            ],
            'user' => [
                'edit' => false,
                'move' => false,
                'move-rootuserpages' => false,
                'move-subpages' => false,
                'upload' => false,
            ],
            'autoconfirmed' => [
                'upload' => false,
                'move' => false,
            ],
            'sysop' => [
                'block' => false,
                'delete' => false,
                'undelete' => false,
                'import' => false,
                'move' => false,
                'move-subpages' => false,
                'move-rootuserpages' => false,
                'patrol' => false,
                'protect' => false,
                'editprotected' => false,
                'rollback' => false,
                'trackback' => false,
                'upload' => false,
                'movefile' => false,
            ],
            'steward' => [
                'edit' => true,
                'move' => true,
                'delete' => true,
                'upload' => true,
            ],
        ],
    ],
    'groupOverrides' => [
        'default' => [],
        'awiki' => [
            'global-renamer' => [
                'centralauth-rename' => true,
                'editrenamerprotected' => true,
            ],
            'sysop' => [
                'securepoll-create-poll' => true,
            ],
        ]
    ],
    'wgRestrictionLevels' => [
        'default' => [ '', 'autoconfirmed', 'sysop' ],
        'awiki' => [ '', 'autoconfirmed', 'editrenamerprotected', 'sysop' ],
    ],
    'wgApplyGlobalBlocks' => [
        'default' => true,
        'awiki' => false,
    ],
    'wgMFAdvancedMobileContributions' => [
        'default' => true,
    ],
    'wmgUseTranslate' => [
        'default' => false,
    ],
    'wgNoticeInfrastructure' => [
        'default' => false,
        'awiki' => true,
    ],
    'wgNoticeProject' => [
        'default' => '$site'
    ],
    'wmgUseCentralNotice' => [
        'default' => false,
    ],
    'wmgUseVisualEditor' => [
        'default' => false
    ],
    'wmgUseDiscussionTools' => [
        'default' => false,
    ],
    'wmgUseMassMessage' => [
        'default' => true,
    ],
    'wgNamespacesToPostIn' => [
        'default' => [ NS_PROJECT, NS_MAIN ]
    ],
    'wgMetaNamespace' => [
        'default' => 'Project',
        'bwiki' => 'Wikipedia'
    ],
    'wmgUseDynamicPageList' =>[
        'default' => false,
        'bwiki' => true,
    ],
    'wgDLPQueryCacheTime' => [
        'default' => 20
    ],
    'wmgUseWikiLove' => [
        'default' => false,
        'awiki' => true
    ],
    'wmgUseOAuth' => [
    	'default' => false
    ],
    'wmgUseSecurePoll' => [
        'default' => false,
        'awiki' => true,
    ],
    'wgImportSources' => [
        'default' => ['cs']
    ],
    'wgUseInstantCommons' => [
        'default' => true,
    ],
    'wgAutoConfirmAge' => [
        'default' => 86400 * 4,
    ],
    'wgAutoConfirmCount' => [
        'default' => 1,
    ]
];
