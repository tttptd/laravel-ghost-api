<?php

namespace Tttptd\GhostAPI\Providers;

class TagProvider extends BaseProvider
{

    protected $entityCode = 'tags';

    protected $entityModelClass = \Tttptd\GhostAPI\Models\Tag::class;

    public function includePostsCount()
    {
        return $this->addInclude('count.posts');
    }
}
