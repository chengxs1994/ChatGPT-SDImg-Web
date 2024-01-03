<?php

declare(strict_types=1);
namespace App\System\Service;


use App\System\Mapper\SystemMenuMapper;
use App\System\Model\SystemMenu;
use Mine\Abstracts\AbstractService;
use Mine\Annotation\DependProxy;
use Mine\Interfaces\ServiceInterface\MenuServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[DependProxy(values: [ MenuServiceInterface::class ])]
class SystemMenuService extends AbstractService implements MenuServiceInterface
{
    /**
     * @var SystemMenuMapper
     */
    public $mapper;

    /**
     * SystemMenuMapper constructor.
     * @param SystemMenuMapper $mapper
     */
    public function __construct(SystemMenuMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getTreeList(?array $params = null, bool $isScope = true): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        return parent::getTreeList($params, $isScope);
    }

    /**
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getTreeListByRecycle(?array $params = null, bool $isScope = true): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        return parent::getTreeListByRecycle($params, $isScope);
    }

    /**
     * 获取前端选择树
     * @param array $data
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \RedisException
     */
    public function getSelectTree(array $data): array
    {
        return $this->mapper->getSelectTree($data);
    }

    /**
     * 通过code获取菜单名称
     * @param string $code
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function findNameByCode(string $code): string
    {
        if (strlen($code) < 1) {
            return t('system.undefined_menu');
        }
        $name = $this->mapper->findNameByCode($code);
        return $name ?? t('system.undefined_menu');
    }

    /**
     * 新增菜单
     * @param array $data
     * @return mixed
     */
    public function save(array $data): mixed
    {
        $id = $this->mapper->save($this->handleData($data));

        // 生成RESTFUL按钮菜单
        if ($data['type'] == SystemMenu::MENUS_LIST && $data['restful'] == '1') {
            $model = $this->mapper->model::find($id, ['id', 'name', 'code']);
            $this->genButtonMenu($model);
        }

        return $id;
    }

    /**
     * 生成按钮菜单
     * @param SystemMenu $model
     * @return bool
     */
    public function genButtonMenu(SystemMenu $model): bool
    {
        $buttonMenus = [
            ['name' => $model->name.'列表', 'code' => $model->code.':index'],
            ['name' => $model->name.'回收站', 'code' => $model->code.':recycle'],
            ['name' => $model->name.'保存', 'code' => $model->code.':save'],
            ['name' => $model->name.'更新', 'code' => $model->code.':update'],
            ['name' => $model->name.'删除', 'code' => $model->code.':delete'],
            ['name' => $model->name.'读取', 'code' => $model->code.':read'],
            ['name' => $model->name.'恢复', 'code' => $model->code.':recovery'],
            ['name' => $model->name.'真实删除', 'code' => $model->code.':realDelete'],
            ['name' => $model->name.'导入', 'code' => $model->code.':import'],
            ['name' => $model->name.'导出', 'code' => $model->code.':export']
        ];

        foreach ($buttonMenus as $button) {
            $this->save(
                array_merge(
                    ['parent_id' => $model->id, 'type' => SystemMenu::BUTTON],
                    $button
                )
            );
        }

        return true;
    }

    /**
     * 更新菜单
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function update(mixed $id, array $data): bool
    {
        return $this->mapper->update($id, $this->handleData($data));
    }

    /**
     * 处理数据
     * @param $data
     * @return array
     */
    protected function handleData($data): array
    {
        if (empty($data['parent_id']) || $data['parent_id'] == 0) {
            $data['level'] = '0';
            $data['parent_id'] = 0;
            $data['type'] = $data['type'] === SystemMenu::BUTTON ? SystemMenu::MENUS_LIST : $data['type'];
        } else {
            $parentMenu = $this->mapper->read((int) $data['parent_id']);
            $data['level'] = $parentMenu['level'] . ',' . $parentMenu['id'];
        }
        return $data;
    }

    /**
     * 真实删除菜单
     * @param array $ids
     * @return array|null
     */
    public function realDel(array $ids): ?array
    {
        // 跳过的菜单
        $ctuIds = [];
        if (count($ids)) foreach ($ids as $id) {
            if (!$this->checkChildrenExists( (int) $id)) {
                $this->mapper->realDelete([$id]);
            } else {
                $ctuIds[] = $id;
            }
        }
        return count($ctuIds) ? $this->mapper->getMenuName($ctuIds) : null;
    }

    /**
     * 检查子菜单是否存在
     * @param int $id
     * @return bool
     */
    public function checkChildrenExists(int $id): bool
    {
        return $this->mapper->checkChildrenExists($id);
    }
}