<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubRelease;

use FoF\GitHubAutolink\Plugins\Github;
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/releases\/tag\/([\w\-\.]+))/si';
    protected $tagName = 'GITHUBRELEASE';

    protected function getClassName()
    {
        return 'github-release-link';
    }

    protected function getSpecificAttributes(Tag $tag)
    {
        $tag->attributes->add('tag');
    }

    protected function getTemplateHref()
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>/releases/tag/<xsl:value-of select="@tag"/>';
    }

    protected function getTemplateContent()
    {
        return '<xsl:value-of select="@repo"/><i class="fas fa-tag" aria-hidden="true" /><xsl:value-of select="@tag"/>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
