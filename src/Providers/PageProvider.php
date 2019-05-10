<?php

namespace Tttptd\GhostAPI\Providers;

class PageProvider extends BaseProvider
{

    protected $entityCode = 'pages';

    protected $entityModelClass = \Tttptd\GhostAPI\Models\Page::class;

    public function includeAuthors()
    {
        return $this->addInclude('authors');
    }

    public function includeTags()
    {
        return $this->addInclude('tags');
    }

}
