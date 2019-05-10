<?php

namespace Tttptd\GhostAPI\Providers;

class UserProvider extends BaseProvider
{

    protected $entityCode = 'users';

    protected $entityModelClass = \Tttptd\GhostAPI\Models\User::class;

    public function includePostsCount()
    {
        return $this->addInclude('count.posts');
    }
}
