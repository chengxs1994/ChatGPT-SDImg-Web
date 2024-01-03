<?php

declare(strict_types = 1);
namespace App\System\Mapper;

use App\System\Model\SystemQueueLog;
use Hyperf\Database\Model\Builder;
use Mine\Abstracts\AbstractMapper;

/**
 * 队列管理Mapper类
 */
class SystemQueueLogMapper extends AbstractMapper
{
    /**
     * @var SystemQueueLog
     */
    public $model;

    public function assignModel()
    {
        $this->model = SystemQueueLog::class;
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        // 交换机名称
        if (isset($params['exchange_name']) && blank($params['exchange_name'])) {
            $query->where('exchange_name', '=', $params['exchange_name']);
        }

        // 路由名称
        if (isset($params['routing_key_name']) && blank($params['routing_key_name'])) {
            $query->where('routing_key_name', '=', $params['routing_key_name']);
        }

        // 队列名称
        if (isset($params['queue_name']) && blank($params['queue_name'])) {
            $query->where('queue_name', '=', $params['queue_name']);
        }

        // 生产状态 0:未生产 1:生产中 2:生产成功 3:生产失败 4:生产重复
        if (isset($params['produce_status']) && blank($params['produce_status'])) {
            $query->where('produce_status', '=', $params['produce_status']);
        }

        // 消费状态 0:未消费 1:消费中 2:消费成功 3:消费失败 4:消费重复
        if (isset($params['consume_status']) && blank($params['consume_status'])) {
            $query->where('consume_status', '=', $params['consume_status']);
        }

        return $query;
    }
}