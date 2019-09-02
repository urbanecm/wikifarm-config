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
        'flaggedrevswiki' => 'http://flaggedrevs.wikifarm',
        'commonswiki' => 'http://commons.wikifarm',
    ],
    'wgCanonicalServer' => [
        'awiki' => 'http://a.wikifarm',
        'bwiki' => 'http://b.wikifarm',
        'cwiki' => 'http://c.wikifarm',
        'flaggedrevswiki' => 'http://flaggedrevs.wikifarm',
        'commonswiki' => 'http://commons.wikifarm',
    ],
    'wgDefaultSkin' => [
        'default' => 'vector',
    ],
    'wgLanguageCode' => [
        'default' => 'en',
    ],
    'wmgUseFlaggedRevs' => [
        'default' => false,
        'flaggedrevswiki' => true,
    ],
    'wgEnableUploads' => [
        'default' => true,
    ],
    'wmgAltUploadForm' => [ // T35513
        'default' => 'Special:Upload',
    ],
    'wmgUseUploadWizard' => [
        'default' => false,
        'commonswiki' => true,
    ],
    'wmgUseWikibaseMediaInfo' => [
        'default' => false,
        'commonswiki' => true,
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
                'hideuser' => true,
                'suppressionlog' => true,
                'suppressrevision' => true,
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
        ]
    ],
    'groupOverrides' => [
        'default' => [],
        'awiki' => [
            'global-renamer' => [
                'centralauth-rename' => true,
            ]
        ]
    ],
];
