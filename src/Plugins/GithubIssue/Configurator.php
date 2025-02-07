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

use FoF\GitHubAutolink\Plugins\Github;
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/(issues)\/(\d+)(#issuecomment-\d+)?|([\w-]+\/[\w-]+)#(\d+))/si';
    protected $tagName = 'GITHUBISSUE';

    protected function getClassName()
    {
        return 'github-issue-link';
    }

    protected function getSpecificAttributes(Tag $tag)
    {
        $tag->attributes->add('type');
        $tag->attributes->add('issue');
        $tag->attributes->add('comment');
    }

    protected function getTemplateHref()
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>/<xsl:value-of select="@type"/>/<xsl:value-of select="@issue"/><xsl:value-of select="@comment"/>';
    }

    protected function getTemplateContent()
    {
        return '<xsl:value-of select="@repo"/><i class="fas fa-exclamation-circle" aria-hidden="true" /><xsl:value-of select="@issue"/>
               <xsl:if test="string(@comment) and @comment != \'\'"><i class="fas fa-comment" aria-hidden="true" /></xsl:if>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
