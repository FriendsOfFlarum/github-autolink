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

use s9e\TextFormatter\Plugins\ParserBase;

class Parser extends ParserBase
{
    public function parse($text, array $matches)
    {
        $tagName = $this->config['tagName'];

        foreach ($matches as $m) {
            $tag = $this->parser->addSelfClosingTag(
                $tagName,
                $m[0][1],
                \strlen($m[0][0]),
                -100
            );

            $isComment = isset($m[3]) && \strpos($m[3][0], '#') === 0;
            $isCommit = isset($m[4]) && \strpos($m[4][0], '/commits/') !== false;

            $attributes = [
                'repo'    => $m[1][0],
                'pr'      => $m[2][0],
                'comment' => $isComment ? $m[3][0] : '',
                'commit'  => $isCommit ? \explode('/commits/', $m[4][0])[1] : '',
            ];

            $tag->setAttributes($attributes);
        }
    }
}
