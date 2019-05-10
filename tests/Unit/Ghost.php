<?php

namespace Tttptd\GhostAPI\Tests\Unit;

use Tttptd\GhostAPI\Exceptions\DataException;
use Tttptd\GhostAPI\Tests\TestCase;

class Ghost extends TestCase
{
	public function testPostsFetch(): void
	{
		// simple fetch
		$posts = \Tttptd\GhostAPI\Facades\Ghost::posts()
			->limit()->get();

		$this->assertNotEmpty($posts);

		// custom limit
		$posts = \Tttptd\GhostAPI\Facades\Ghost::posts()
			->limit(1)->get();

		$this->assertCount(1, $posts);

		// with included data
		$posts = \Tttptd\GhostAPI\Facades\Ghost::posts()
			->includeAuthors()->includeTags()->limit(1)->get();

		$this->assertNotEmpty($posts[0]->authors);
		$this->assertNotEmpty($posts[0]->tags);
	}

	public function testTagsFetch(): void
	{
		// simple fetch
		$tags = \Tttptd\GhostAPI\Facades\Ghost::tags()
			->limit()->get();

		$this->assertNotEmpty($tags);
	}

	public function testUsersFetch(): void
	{
		// simple fetch
		$users = \Tttptd\GhostAPI\Facades\Ghost::users()
			->limit()->get();

		$this->assertNotEmpty($users);
	}

	public function testExceptions(): void
	{
		$this->expectException(DataException::class);

		\Tttptd\GhostAPI\Facades\Ghost::posts()
			->getById('abracadabra');
	}
}
