<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubRepository;

use FoF\GitHubAutolink\Plugins\Github;

class Configurator extends Github
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+))/si';
    protected $tagName = 'GITHUBREPO';

    protected function getClassName()
    {
        return 'github-repo-link';
    }

    protected function getSpecificAttributes($tag)
    {
        // None
    }

    protected function getTemplateHref()
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>';
    }

    protected function getTemplateContent()
    {
        return '<xsl:value-of select="@repo"/>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
