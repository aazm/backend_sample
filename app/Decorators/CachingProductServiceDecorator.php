<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:39
 */

namespace Turing\Decorators;

use Illuminate\Support\Collection;
use Turing\Helpers\DataSet;
use Turing\Services\Impl\ProductService;
use Turing\Services\ProductServiceInterface;

class CachingProductServiceDecorator implements ProductServiceInterface
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function search(array $criteria, int $offset = 0): DataSet
    {
        $key = $this->getKey(__FUNCTION__, $criteria + ['offset' => $offset]);

        return cache()->remember($key, config('turing.cache_ttl'), function () use ($criteria, $offset) {
                return $this->service->search($criteria, $offset);
        });
    }

    public function getById($id)
    {
        $key = $this->getKey(__FUNCTION__, compact('id'));

        return cache()->remember($key, config('turing.cache_ttl'), function () use ($id) {
            return $this->service->getById($id);
        });

    }

    public function getCategories(): Collection
    {
        $key = $this->getKey(__FUNCTION__, []);

        return cache()->remember($key, config('turing.cache_ttl'), function () {
            return $this->service->getCategories();
        });
    }

    public function getDepartments(): Collection
    {
        $key = $this->getKey(__FUNCTION__, []);

        return cache()->remember($key, config('turing.cache_ttl'), function () {
            return $this->service->getDepartments();
        });
    }

    private function getKey($prefix, array $dt)
    {
        return sprintf('%s:%s:%s',get_class($this), $prefix, $this->normalize($dt));
    }


    private function normalize($payload): string
    {
        if (is_array($payload)) {
            ksort($payload);
            foreach ($payload as $key => &$val) {
                if (is_array($val)) {
                    ksort($val);
                }
            }
            return md5(\json_encode($payload));
        }

        return md5($payload);
    }


}