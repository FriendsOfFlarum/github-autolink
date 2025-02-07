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
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)(\/[^\s]*)?)/si';
    protected $tagName = 'GITHUBREPO';

    protected function getClassName()
    {
        return 'github-repo-link';
    }

    protected function getSpecificAttributes(Tag $tag)
    {
        $tag->attributes->add('repopath');
    }

    protected function getTemplateHref()
    {
        return 'https://github.com/<xsl:value-of select="@repo"/><xsl:value-of select="@repopath"/>';
    }

    protected function getTemplateContent()
    {
        return '<xsl:value-of select="@repo"/><xsl:if test="string(@repopath) and @repopath != \'\'"><span class="github-repo-link--path"><xsl:value-of select="substring(@repopath, 2)"/></span></xsl:if>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
