<?php

/*
 * This file is part of fof/github-autolink.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\GitHubAutolink\tests\integration;

use Carbon\Carbon;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Psr\Http\Message\ResponseInterface;

class FormatterTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabase([
            'discussions' => [
                ['id' => 1, 'title' => __CLASS__, 'created_at' => Carbon::now()->toDateTimeString(), 'user_id' => 2, 'first_post_id' => 1],
            ],
            'posts' => [
                ['id' => 1, 'discussion_id' => 1, 'number' => 1, 'created_at' => Carbon::now()->subDay()->toDateTimeString(), 'user_id' => 2, 'type' => 'comment', 'content' => '<t></t>'],
            ],
            'users' => [
                $this->normalUser(),
            ],
        ]);

        $this->extension('fof-github-autolink');
    }

    protected function postContent(string $content): ResponseInterface
    {
        return $this->send(
            $this->request(
                'POST',
                '/api/posts',
                [
                    'authenticatedAs' => 2,
                    'json'            => [
                        'data' => [
                            'attributes' => [
                                'content' => $content,
                            ],
                            'relationships' => [
                                'discussion' => ['data' => ['id' => 1]],
                            ],
                        ],
                    ],
                ]
            )
        );
    }

    /**
     * @test
     */
    public function it_renders_a_github_pr_link()
    {
        $response = $this->postContent("Here's a PR https://github.com/flarum/framework/pull/3876");

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-pr-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3876"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_github_pr_comment()
    {
        $response = $this->postContent('PR Comment: https://github.com/flarum/framework/pull/3872#pullrequestreview-1585769864');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-pr-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3872#pullrequestreview-1585769864"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_commit_inside_a_pr()
    {
        $response = $this->postContent('Commit inside a PR: https://github.com/flarum/framework/pull/3877/commits/7a94f011df1a3c3f9f32f72c11b1efa9ca17acd2');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-pr-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3877/commits/7a94f011df1a3c3f9f32f72c11b1efa9ca17acd2"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_normal_commit()
    {
        $response = $this->postContent('Normal commit: https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-commit-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_commit_comment()
    {
        $response = $this->postContent('Commit comment: https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c#commitcomment-129448475');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-commit-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c#commitcomment-129448475"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_an_issue()
    {
        $response = $this->postContent('Issue: https://github.com/flarum/framework/issues/3895');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-issue-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/issues/3895"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_repository()
    {
        $response = $this->postContent('Repo: https://github.com/imorland/flarum-ext-twofactor');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-repo-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/imorland/flarum-ext-twofactor"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_a_compare_link()
    {
        $response = $this->postContent('Compare: https://github.com/flarum/framework/compare/v1.8.2...v1.8.3');

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-compare-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/compare/v1.8.2...v1.8.3"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_all_links_together()
    {
        $content = <<<'EOT'
Here's a PR https://github.com/flarum/framework/pull/3876
PR Comment: https://github.com/flarum/framework/pull/3872#pullrequestreview-1585769864
Commit inside a PR: https://github.com/flarum/framework/pull/3877/commits/7a94f011df1a3c3f9f32f72c11b1efa9ca17acd2
Normal commit: https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c
Commit comment: https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c#commitcomment-129448475
Issue: https://github.com/flarum/framework/issues/3895
Repo: https://github.com/imorland/flarum-ext-twofactor
Compare: https://github.com/flarum/framework/compare/v1.8.2...v1.8.3
EOT;

        $response = $this->postContent($content);

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);

        // Assert all the strings for each link type
        $this->assertStringContainsString('github-pr-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3876"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3872#pullrequestreview-1585769864"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('href="https://github.com/flarum/framework/pull/3877/commits/7a94f011df1a3c3f9f32f72c11b1efa9ca17acd2"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('github-commit-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('href="https://github.com/FriendsOfFlarum/default-group/commit/ca783dee209fe126b677ec73a18d1ed4ccc4e76c#commitcomment-129448475"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('github-issue-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/issues/3895"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('github-repo-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/imorland/flarum-ext-twofactor"', $post['attributes']['contentHtml']);

        $this->assertStringContainsString('github-compare-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="https://github.com/flarum/framework/compare/v1.8.2...v1.8.3"', $post['attributes']['contentHtml']);
    }

    /**
     * @test
     */
    public function it_renders_undefined_github_links()
    {
        $url = 'https://github.com/EsotericSoftware/spine-runtimes/tree/4.1/spine-sdl#spine-version';
        $response = $this->postContent($url);

        $this->assertEquals(201, $response->getStatusCode());

        $post = json_decode($response->getBody()->getContents(), true)['data'];

        // Should render the normal repo link, with the full href supplied in the content

        $this->assertStringContainsString('Github-embed', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('github-repo-link', $post['attributes']['contentHtml']);
        $this->assertStringContainsString('href="'.$url.'"', $post['attributes']['contentHtml']);
    }
}
