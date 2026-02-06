<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins\GithubCompare;

use FoF\GitHubAutolink\Plugins\Github;
use s9e\TextFormatter\Configurator\Items\Tag;

class Configurator extends Github
{
    protected string $regexp = '/(?:^|\b)(?:https?\:\/\/github\.com\/([\w-]+\/[\w-]+)\/compare\/([\w\-\.]+)\.\.\.([\w\-\.]+))/si';

    protected ?string $tagName = 'GITHUBCOMPARE';

    protected function getClassName(): string
    {
        return 'github-compare-link';
    }

    protected function getSpecificAttributes(Tag $tag): void
    {
        $tag->attributes->add('base');
        $tag->attributes->add('head');
    }

    protected function getTemplateHref(): string
    {
        return 'https://github.com/<xsl:value-of select="@repo"/>/compare/<xsl:value-of select="@base"/>...<xsl:value-of select="@head"/>';
    }

    protected function getTemplateContent(): string
    {
        return '<xsl:value-of select="@repo"/><i class="fas fa-arrow-right" aria-hidden="true" /><code><xsl:value-of select="@base"/> → <xsl:value-of select="@head"/></code>';
    }

    public function getJSParser()
    {
        return \file_get_contents(realpath(__DIR__.'/Parser.js'));
    }
}
