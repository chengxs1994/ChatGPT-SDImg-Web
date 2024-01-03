<?php

declare(strict_types = 1);
namespace App\System\Mapper;


use App\System\Model\SystemPost;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

class SystemPostMapper extends AbstractMapper
{
    /**
     * @var SystemPost
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemPost::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['name']) && blank($params['name'])) {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }
        if (isset($params['code']) && blank($params['code'])) {
            $query->where('code', $params['code']);
        }
        if (isset($params['status']) && blank($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (isset($params['created_at']) && blank($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [ $params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59' ]
            );
        }
        return $query;
    }
}