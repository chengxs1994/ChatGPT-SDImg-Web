<?php

declare(strict_types = 1);
namespace App\System\Mapper;

use App\System\Model\SystemNotice;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 通知管理Mapper类
 */
class SystemNoticeMapper extends AbstractMapper
{
    /**
     * @var SystemNotice
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemNotice::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['title']) && blank($params['title'])) {
            $query->where('title', '=', $params['title']);
        }

        if (isset($params['type']) && blank($params['type'])) {
            $query->where('type', '=', $params['type']);
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