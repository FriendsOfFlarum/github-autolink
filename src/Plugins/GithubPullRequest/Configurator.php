<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubPullRequest;

use FoF\GitHubAutolink\Plugins\Github;
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected string $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/pull\/(\d+)(#pullrequestreview-\d+)?(\/(?:commits|changes)\/([0-9a-f]{7,40}))?)/si';
    protected ?string $tagName = 'GITHUBPR';

    protected function getClassName(): string
    {
        return 'github-pr-link';
    }

    protected function getSpecificAttributes(Tag $tag): void
    {
        $tag->attributes->add('pr');
        $tag->attributes->add('comment');
        $tag->attributes->add('commit');
    }

    protected function getTemplateHref(): string
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>/pull/<xsl:value-of select="@pr"/><xsl:if test="string(@comment) and @comment != \'\'"><xsl:value-of select="@comment"/></xsl:if><xsl:if test="string(@commit) and @commit != \'\'">/changes/<xsl:value-of select="@commit"/></xsl:if>';
    }

    protected function getTemplateContent(): string
    {
        return <<<XML
{$this->getRepoNameTemplate()}
<i class="fas fa-code-branch" aria-hidden="true" />
<span class="github-pr-link--number">
    <xsl:value-of select="@pr"/>
</span>

<xsl:if test="string(@comment) and @comment != ''">
    <i class="fas fa-comment" aria-hidden="true" />
</xsl:if>

<xsl:if test="string(@commit) and @commit != ''">
    <i class="fas fa-hashtag" aria-hidden="true" />
    <code class="github-pr-link--commit">
        <xsl:value-of select="substring(@commit, 1, 7)"/>
    </code>
</xsl:if>
XML;
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
