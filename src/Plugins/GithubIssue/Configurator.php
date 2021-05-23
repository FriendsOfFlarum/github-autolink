<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubIssue;

use s9e\TextFormatter\Plugins\ConfiguratorBase;

class Configurator extends ConfiguratorBase
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/(issues|pull)\/(\d+)(#issuecomment-\d+)?|([\w-]+\/[\w-]+)#(\d+))/si';
    protected $tagName = 'GITHUBISSUE';

    protected function setUp()
    {
        if (isset($this->configurator->tags[$this->tagName])) {
            return;
        }

        $tag = $this->configurator->tags->add($this->tagName);

        $tag->attributes->add('repo');
        $tag->attributes->add('type');
        $tag->attributes->add('issue');
        $tag->attributes->add('comment');

        $tag->template = '<a class="github-issue-link">
            <xsl:attribute name="href">'.
                'https://github.com/<xsl:value-of select="@repo"/>/<xsl:value-of select="@type"/>/<xsl:value-of select="@issue"/><xsl:value-of select="@comment"/>'.
            '</xsl:attribute>
            <xsl:value-of select="@repo"/>#<xsl:value-of select="@issue"/>
            <xsl:if test="string(@comment) and @comment != \'\'"> (comment)</xsl:if>
        </a>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
