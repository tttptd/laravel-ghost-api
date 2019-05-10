<?php

namespace Tttptd\GhostAPI\Tests\Unit\Models;

use Tttptd\GhostAPI\Models\Tag;
use Tttptd\GhostAPI\Tests\TestCase;
use Carbon\Carbon;

class TagTest extends TestCase
{
	public function testTagCreation(): void
	{
		$tag = new Tag([
			'id' => 'test-id',
			'created_at' => '2018-12-05T10:00:01.000Z',
			'updated_at' => '2018-12-08T11:00:01.000Z',
		]);

		$this->assertEquals('test-id', $tag->id);
		$this->assertInstanceOf(Carbon::class, $tag->createdAt);
		$this->assertInstanceOf(Carbon::class, $tag->updatedAt);
	}
}
