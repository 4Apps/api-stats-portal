<?php

// Available themes
$config['color_themes'] = ['default', 'amethyst', 'city', 'flat', 'modern', 'smooth'];

// Versioning
$config['version'] = 'v1.0.0';
$config['git_commit_hash'] = '9ecb125bfd28b70720ab1d106da4588ba8b2b6ac';
$config['git_commit_date'] = '22.02.2019 13:15';

$config['asset_version'] = (
    $config['environment'] === 'dev'
        ? time()
        : substr($config['git_commit_hash'], 0, 7)
);
