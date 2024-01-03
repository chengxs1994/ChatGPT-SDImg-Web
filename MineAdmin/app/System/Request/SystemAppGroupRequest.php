<?php
declare(strict_types=1);
namespace App\System\Request;

use Mine\MineFormRequest;

class SystemAppGroupRequest extends MineFormRequest
{
    /**
     * 公共规则
     */
    public function commonRules(): array
    {
        return [];
    }

    /**
     * 新增数据验证规则
     * return array
     */
    public function saveRules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     * 更新数据验证规则
     * return array
     */
    public function updateRules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     * 修改状态数据验证规则
     * return array
     */
    public function changeStatusRules(): array
    {
        return [
            'id' => 'required',
            'status' => 'required'
        ];
    }

    /**
     * 字段映射名称
     * return array
     */
    public function attributes(): array
    {
        return [
            'id' => '组ID',
            'name' => '组名称',
            'status' => '组状态',
        ];
    }

}