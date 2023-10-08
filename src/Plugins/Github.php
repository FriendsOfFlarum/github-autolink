<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\Plugins;

use s9e\TextFormatter\Plugins\ConfiguratorBase;

abstract class Github extends ConfiguratorBase
{
    protected $tagName = null;

    abstract protected function getClassName();

    abstract protected function getSpecificAttributes($tag);

    protected function setUp()
    {
        if (isset($this->configurator->tags[$this->tagName])) {
            return;
        }

        $tag = $this->configurator->tags->add($this->tagName);

        $this->setCommonAttributes($tag);
        $this->getSpecificAttributes($tag);
        $this->setTemplate($tag);
    }

    protected function setCommonAttributes($tag)
    {
        $tag->attributes->add('repo');
    }

    protected function setTemplate($tag)
    {
        $tag->template = sprintf(
            '<a class="Github-embed %1$s">
            <xsl:attribute name="href">%2$s</xsl:attribute>
            <xsl:attribute name="target">_blank</xsl:attribute>
            <xsl:attribute name="rel">noopener noreferrer</xsl:attribute>
            <i class="fab fa-github" aria-hidden="true" />
            %3$s
        </a>',
            $this->getClassName(),
            $this->getTemplateHref(),
            $this->getTemplateContent()
        );
    }

    abstract protected function getTemplateHref();

    abstract protected function getTemplateContent();
}
