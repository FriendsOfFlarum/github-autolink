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

use FoF\GitHubAutolink\Plugins\Github;
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/commit\/([0-9a-f]{7,40})(#commitcomment-\w+)?(#diff-[\w-]+)?|([\w-]+\/[\w-]+)@([0-9a-f]{7,40}))/si';

    protected $tagName = 'GITHUBCOMMIT';

    protected function getClassName()
    {
        return 'github-commit-link';
    }

    protected function getIcon()
    {
        return 'fas fa-hashtag';
    }

    protected function getSpecificAttributes(Tag $tag)
    {
        $tag->attributes->add('commit');
        $tag->attributes->add('comment');
        $tag->attributes->add('diff');
    }

    protected function getTemplateHref()
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>/commit/<xsl:value-of select="@commit"/><xsl:value-of select="@comment"/><xsl:value-of select="@diff"/>';
    }

    protected function getTemplateContent()
    {
        return '<xsl:value-of select="@repo"/><i class="fas fa-hashtag" aria-hidden="true" /><code><xsl:value-of select="substring(@commit, 1, 7)"/></code>
        <xsl:if test="string(@comment) and @comment != \'\'"><i class="fas fa-comment" aria-hidden="true" /></xsl:if>
        <xsl:if test="string(@diff) and @diff != \'\'"> (diff)</xsl:if>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
