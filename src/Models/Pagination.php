<?php
declare(strict_types=1);

namespace Tttptd\GhostAPI\Models;

class Pagination
{

    /**
     * @var int
     */
    public $page;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var int
     */
    public $pages;

    /**
     * @var int
     */
    public $total;

    /**
     * @var int|null
     */
    public $next;

    /**
     * @var int|null
     */
    public $prev;

    /**
     * Pagination constructor.
     * @param array $data
     */
    public function __construct(
        array $data
    ) {
        [
            'page' => $this->page,
            'limit' => $this->limit,
            'pages' => $this->pages,
            'total' => $this->total,
            'next' => $this->next,
            'prev' => $this->prev,
        ] = $data;
    }

}
