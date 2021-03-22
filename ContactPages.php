<?php

wfLoadExtension( 'ContactPage' );

$wgContactConfig['globalblockappeal'] = [
    'RecipientUser' => 'Martin Urbanec',
    'SenderName' => '',
    'IncludeIP' => true,
    'RequireDetails' => true,
    'AdditionalFields' => [
        'Username' => [
			'type' => 'text',
			'required' => false,
			'label-message' => 'contactpage-globalblockappeal-username',
            'help-messages' => [ 'contactpage-globalblockappeal-username-help' ],
		],
        'BlockMessage' => [
            'type' => 'textarea',
            'required' => true,
            'label-message' => 'contactpage-globalblockappeal-blockmessage',
            'rows' => 3,
        ],
        'AdditionalInformation' => [
			'label-message' => 'contactpage-globalblockappeal-information',
			'type' => 'textarea',
			'rows' => 3,
			'required' => false
		],
    ],
];

$wgContactConfig['openproxyexemptionrequest'] = [
    'RecipientUser' => 'Martin Urbanec',
    'SenderName' => '',
    'IncludeIP' => true,
    'RequireDetails' => true,
    'AdditionalFields' => [
        'Username' => [
			'type' => 'text',
			'required' => true,
			'label-message' => 'contactpage-openproxyexemptionrequest-username',
            'help-messages' => [ 'contactpage-openproxyexemptionrequest-username-help' ],
		],
        'Justification' => [
            'type' => 'radio',
            'label-message' => 'contactpage-openproxyexemptionrequest-justification',
            'required' => true,
            'options-messages' => [
                'contactpage-openproxyexemptionrequest-justification-gfw' => 'Great China Firewall',
                'contactpage-openproxyexemptionrequest-justification-other' => 'Other',
            ],
        ],
        'Projects' => [
			'type' => 'textarea',
			'required' => true,
            'rows' => 3,
			'label-message' => 'contactpage-openproxyexemptionrequest-projects',
            'help-messages' => [ 'contactpage-openproxyexemptionrequest-projects-help' ],
		],
    ],
];