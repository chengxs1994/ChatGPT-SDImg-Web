<?php

declare(strict_types=1);
namespace App\System\Controller\Api;

use App\System\Request\SystemApiColumnRequest;
use App\System\Service\SystemApiColumnService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Mine\Annotation\Auth;
use Mine\Annotation\OperationLog;
use Mine\Annotation\Permission;
use Mine\Annotation\RemoteState;
use Mine\MineCollection;
use Mine\MineController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 接口控制器
 * Class SystemApiColumnController
 */
#[Controller(prefix: "system/apiColumn"), Auth]
class SystemApiColumnController extends MineController
{
    #[Inject]
    protected SystemApiColumnService $service;

    /**
     * 列表
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("index"), Permission("system:api, system:api:index")]
    public function index(): ResponseInterface
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }

    /**
     * 回收站列表
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("recycle"), Permission("system:api:recycle")]
    public function recycle(): ResponseInterface
    {
        return $this->success($this->service->getPageListByRecycle($this->request->all()));
    }

    /**
     * 新增
     * @param SystemApiColumnRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("save"), Permission("system:api:save"), OperationLog("新增接口参数")]
    public function save(SystemApiColumnRequest $request): ResponseInterface
    {
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 读取数据
     * @param int $id
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("read/{id}"), Permission("system:api:read")]
    public function read(int $id): ResponseInterface
    {
        return $this->success($this->service->read($id));
    }

    /**
     * 更新
     * @param int $id
     * @param SystemApiColumnRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("update/{id}"), Permission("system:api:update"), OperationLog("更新接口参数")]
    public function update(int $id, SystemApiColumnRequest $request): ResponseInterface
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[DeleteMapping("delete"), Permission("system:api:delete")]
    public function delete(): ResponseInterface
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量真实删除数据 （清空回收站）
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[DeleteMapping("realDelete"), Permission("system:api:realDelete"), OperationLog("真实删除接口参数")]
    public function realDelete(): ResponseInterface
    {
        return $this->service->realDelete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量恢复在回收站的数据
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("recovery"), Permission("system:api:recovery")]
    public function recovery(): ResponseInterface
    {
        return $this->service->recovery((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 字段导出
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @return ResponseInterface
     */
    #[PostMapping("export")]
    public function export(): ResponseInterface
    {
        return $this->service->export($this->request->all(), \App\System\Dto\ApiColumnDto::class, '字段列表');
    }

    /**
     * 字段导入
     * @return ResponseInterface
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("import")]
    public function import(): ResponseInterface
    {
        return $this->service->import(\App\System\Dto\ApiColumnDto::class) ? $this->success() : $this->error();
    }

    /**
     * 下载导入模板
     * @return ResponseInterface
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("downloadTemplate")]
    public function downloadTemplate(): ResponseInterface
    {
        return (new MineCollection)->export(\App\System\Dto\ApiColumnDto::class, '模板下载', []);
    }

    /**
     * 更改状态
     * @param SystemApiColumnRequest $request
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[PutMapping("changeStatus"), Permission("system:api:update"), OperationLog("更改接口状态")]
    public function changeStatus(SystemApiColumnRequest $request): ResponseInterface
    {
        return $this->service->changeStatus((int) $this->request->input('id'), (string) $this->request->input('status'))
            ? $this->success() : $this->error();
    }

    /**
     * 远程万能通用列表接口
     * @return ResponseInterface
     */
    #[PostMapping("remote"), RemoteState(true)]
    public function remote(): ResponseInterface
    {
        return $this->success($this->service->getRemoteList($this->request->all()));
    }
}