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
                -10
            );

            $tag->setAttributes(
                [
                    'repo'    => $m[1][1] >= 0 ? $m[1][0] : $m[5][0],
                    'commit'  => $m[2][1] >= 0 ? $m[2][0] : $m[6][0],
                    'comment' => isset($m[3]) && $m[3][1] >= 0 ? $m[3][0] : '',
                    'diff'    => isset($m[4]) && $m[4][0] >= 0 ? $m[4][0] : '',
                ]
            );
        }
    }
}
