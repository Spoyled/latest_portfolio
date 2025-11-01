<?php

return [
    'default_locale' => 'en',

    'nodes' => [
        'intro' => [
            'message' => 'Hi there! I\'m ProSnap Assistant. Choose a topic and I\'ll guide you.',
            'options' => [
                ['id' => 'candidate_welcome', 'label' => 'Candidate: Build my profile'],
                ['id' => 'employer_welcome', 'label' => 'Employer: Hire with ProSnap'],
                ['id' => 'human_support', 'label' => 'Contact support'],
            ],
        ],
        'candidate_welcome' => [
            'message' => 'Start with a compelling summary and a skills snapshot. Upload achievements and we\'ll help tailor a CV.',
            'options' => [
                ['id' => 'candidate_cv', 'label' => 'Generate or update my CV'],
                ['id' => 'candidate_visibility', 'label' => 'Showcase my work'],
                ['id' => 'intro', 'label' => 'Back to main menu'],
            ],
        ],
        'candidate_cv' => [
            'message' => 'Keep your experience fresh, align skills to the role, then request ATS analysis before you apply.',
            'options' => [
                ['id' => 'intro', 'label' => 'Main menu'],
            ],
        ],
        'candidate_visibility' => [
            'message' => 'Publish posts with metrics, attach visuals, and keep your CV versions aligned with each opportunity.',
            'options' => [
                ['id' => 'intro', 'label' => 'Main menu'],
            ],
        ],
        'employer_welcome' => [
            'message' => 'Publish roles, invite shortlisted talent, and compare ATS readiness in one dashboard.',
            'options' => [
                ['id' => 'employer_post', 'label' => 'Post a role'],
                ['id' => 'employer_manage', 'label' => 'Manage applicants'],
                ['id' => 'intro', 'label' => 'Back to main menu'],
            ],
        ],
        'employer_post' => [
            'message' => 'Share a clear role summary, stack-ranked skills, and expected timeline. Talent sees it instantly.',
            'options' => [
                ['id' => 'intro', 'label' => 'Main menu'],
            ],
        ],
        'employer_manage' => [
            'message' => 'Review candidate CV snapshots, chat in real-time, and mark stages to keep your hiring team aligned.',
            'options' => [
                ['id' => 'intro', 'label' => 'Main menu'],
            ],
        ],
        'human_support' => [
            'message' => 'Drop us a line at support@prosnap.io or submit a ticket. We reply within one business day.',
            'options' => [
                ['id' => 'intro', 'label' => 'Back to main menu'],
            ],
        ],
    ],
];
