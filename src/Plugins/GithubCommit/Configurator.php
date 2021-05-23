<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubCommit;

use s9e\TextFormatter\Plugins\ConfiguratorBase;

class Configurator extends ConfiguratorBase
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/commit\/([0-9a-f]{7,40})(#commitcomment-\w+)?(#diff-[\w-]+)?|([\w-]+\/[\w-]+)@([0-9a-f]{7,40}))/si';
    protected $tagName = 'GITHUBCOMMIT';

    protected function setUp()
    {
        if (isset($this->configurator->tags[$this->tagName])) {
            return;
        }

        $tag = $this->configurator->tags->add($this->tagName);

        $tag->attributes->add('repo');
        $tag->attributes->add('commit');
        $tag->attributes->add('comment');
        $tag->attributes->add('diff');

        $tag->template = '<a class="github-commit-link">
            <xsl:attribute name="href">'.
                'https://github.com/<xsl:value-of select="@repo"/>/commit/<xsl:value-of select="@commit"/><xsl:value-of select="@comment"/><xsl:value-of select="@diff"/>'.
            '</xsl:attribute>
            <xsl:value-of select="@repo"/>@<code><xsl:value-of select="substring(@commit, 1, 7)"/></code>
            <xsl:if test="string(@comment) and @comment != \'\'"> (comment)</xsl:if>
            <xsl:if test="string(@diff) and @diff != \'\'"> (diff)</xsl:if>
        </a>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
