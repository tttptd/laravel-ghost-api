<?php

namespace Tttptd\GhostAPI\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Tttptd\GhostAPI\Exceptions\DataException;
use Tttptd\GhostAPI\Models\Pagination;
use function array_key_exists;
use function is_array;

abstract class BaseProvider
{

    protected $entityCode;

    protected $entityModelClass;

    protected $fields = [];

    protected $limit = 'all';

    protected $page = 1;

    protected $filter;

    protected $simpleFilterDelimeter = '+';

    protected $includes = [];

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var Client
     */
    protected $client;

    protected $postUrl = null;

    /**
     * BaseProvider constructor.
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function first()
    {
        return $this->get()->first();
    }

    /**
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get():Collection
    {
        return $this->request($this->entityCode);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById(string $id)
    {
        return $this->request(sprintf('%s/%s', $this->entityCode, $id))->first();
    }

    /**
     * @param string $slug
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBySlug(string $slug)
    {
        return $this->request(sprintf('%s/slug/%s', $this->entityCode, $slug))->first();
    }

    public function page(int $page)
    {
        $this->page = $page;

        return $this;
    }

    public function limit(int $limit = 15)
    {
        $this->limit = $limit;

        return $this;
    }

    public function addFilter($filterData)
    {
        if(\is_array($filterData) || \is_string($filterData)) {
            $this->filter = $filterData;
        }
        else {
            throw new \InvalidArgumentException('Filter data is invalid. Use array for simple filter or string for custom filter.');
        }

        return $this;
    }

    public function filterAndMode()
    {
        $this->simpleFilterDelimeter = '+';

        return $this;
    }

    public function filterOrMode()
    {
        $this->simpleFilterDelimeter = ',';

        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function addField(string $field)
    {
        $this->fields[] = trim($field);
        return $this;
    }

    /**
     * @return $this
     */
    public function clearFields()
    {
        $this->fields = [];
        return $this;
    }

    /**
     * @param string $fields 'id,slug,excerpt'
     * @return $this
     */
    public function setFields(string $fields)
    {
        $this->fields = explode(',', $fields);
        return $this;
    }

    /**
     * @return Pagination
     */
    public function getPagination():Pagination
    {
        return $this->pagination;
    }

    protected function modifyQuery(array $queryData):array
    {
        return $queryData;
    }

    protected function addInclude(string $include)
    {
        $this->includes[] = $include;

        return $this;
    }

    /**
     * @param string $url
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $url):Collection
    {
        $results = new Collection();

        $queryData = $this->modifyQuery($this->buildBaseQuery());
        // dd($queryData);
        $data = $this->client->request($url, $queryData);
        // dd($data);

        if(isset($data['meta']['pagination']) && is_array($data['meta']['pagination'])) {
            $this->pagination = new Pagination($data['meta']['pagination']);
        }

        if(array_key_exists($this->entityCode, $data) && \is_array($data[ $this->entityCode ])) {
            foreach($data[ $this->entityCode ] as $postData) {
                $results->push(new $this->entityModelClass($postData));
            }

            return $results;
        }

        throw DataException::noResultsFound($this->entityCode);
    }

    private function buildBaseQuery():array
    {
        $options = [
            'query' => [
                // 'absolute_urls' => true,
                'limit' => $this->limit,
                'page' => $this->page,
                'include' => array_unique($this->includes),
            ],
        ];

        // set filtering for results
        if($this->filter !== null) {
            $readyFilter = null;

            if(\is_array($this->filter)) {
                $readyFilter = [];

                foreach($this->filter as $filterParameter => $filterValue) {
                    $readyFilter[] = $filterParameter . ':' . $this->prepareFilterValue($filterValue);
                }

                $readyFilter = implode($this->simpleFilterDelimeter, $readyFilter);
            }
            elseif(\is_string($this->filter)) {
                $readyFilter = $this->filter;
            }

            if($readyFilter !== null) {
                $options['query']['filter'] = $readyFilter;
            }
        }

        // filtering the fields we want to get
        if(\count($this->fields) > 0) {
            $options['query']['fields'] = implode(',', $this->fields);
        }

        return $options;
    }

    private function prepareFilterValue($value)
    {
        $resultValues = [];

        if(\is_array($value)) {
            foreach($value as $singleValue) {
                $resultValues[] = $this->prepareFilterValue($singleValue);
            }
        }
        elseif(\is_bool($value)) {
            $resultValues[] = $value ? 'true' : 'false';
        }
        elseif($value === null) {
            $resultValues[] = 'null';
        }
        else {
            $resultValues[] = "'$value'";
        }

        return \count($resultValues) === 1
            ? $resultValues[0]
            : sprintf('[%s]', implode(',', $resultValues));
    }

}
