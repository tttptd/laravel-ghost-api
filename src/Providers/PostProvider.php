<?php

namespace Tttptd\GhostAPI\Providers;

class PostProvider extends BaseProvider
{

    protected const SHORT_FIELDS = [
        'id',
        'uuid',
        'title',
        'slug',
        'primary_tag',
        'feature_image',
        'custom_excerpt',
        'published_at',
    ];

    protected $entityCode = 'posts';

    protected $entityModelClass = \Tttptd\GhostAPI\Models\Post::class;

    // private $formats = ['html', 'plaintext'];
    // private $formats = [];

    public function includeAuthors()
    {
        return $this->addInclude('authors');
    }

    public function includeTags()
    {
        return $this->addInclude('tags');
    }

    /**
     * @return $this
     */
    public function short()
    {
        return $this->setFields(implode(',', self::SHORT_FIELDS));
    }

    // protected function modifyQuery(array $queryData):array
    // {
    //     $queryData['query']['formats'] = $this->formats;
    //
    //     return $queryData;
    // }

}
