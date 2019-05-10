<?php

namespace Tttptd\GhostAPI\Tests\Unit\Models;

use Tttptd\GhostAPI\Models\User;
use Tttptd\GhostAPI\Tests\TestCase;

class UserTest extends TestCase
{
	public function testUserCreation(): void
	{
		$user = new User([
			'id' => 'test-id'
		]);

		$this->assertEquals('test-id', $user->id);
	}
}
