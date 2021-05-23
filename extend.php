<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink;

use Flarum\Extend;
use s9e\TextFormatter\Configurator;

return [
    (new Extend\Frontend('forum'))
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Formatter())
        ->configure(function (Configurator $configurator) {
            $configurator->plugins->set('GithubIssueAutolink', Plugins\GithubIssue\Configurator::class);
            $configurator->plugins->set('GithubCommitAutolink', Plugins\GithubCommit\Configurator::class);
        }),
];
