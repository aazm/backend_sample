<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 27/03/2019
 * Time: 18:39
 */

namespace Turing\Decorators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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

    public function getById($id): Model
    {
        $key = $this->getKey(__FUNCTION__, ['id' => $id]);

        $model = cache()->remember($key, config('turing.cache_ttl'), function () use ($id) {

            try {

                return $this->service->getById($id);

            } catch (ModelNotFoundException $e) {
                return false;
            }
        });

        if(!$model) {
            throw new ModelNotFoundException();
        }

        return $model;
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
        $key = sprintf('%s:%s:%s',get_class($this), $prefix, $this->normalize($dt));
        Log::info($key);
        return $key;
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