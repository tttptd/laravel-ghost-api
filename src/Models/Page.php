<?php

namespace Tttptd\GhostAPI\Models;

use Carbon\Carbon;

/**
 * Class Page
 * @package Tttptd\GhostAPI\Models
 * @property-read string $uuid
 * @property-read string $title
 * @property-read string $html
 * @property-read string $page
 * @property-read string $status
 * @property-read string $locale
 * @property-read string $author
 * @property-read string $url
 * @property-read string $language
 * @property-read string $featureImage
 * @property-read Carbon $publishedAt
 * @property-read string $publishedBy
 * @property-read array  $authors
 * @property-read array  $tags
 * @property-read array  $primaryAuthor
 * @property-read array  $primaryTag
 * @property-read string $customExcerpt
 */
class Page extends BaseModel
{

    protected $casts = [
        'publishedAt' => 'datetime',
    ];

}
