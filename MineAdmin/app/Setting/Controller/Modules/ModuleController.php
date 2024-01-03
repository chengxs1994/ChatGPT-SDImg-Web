<?php

declare(strict_types=1);
namespace App\Setting\Controller\Modules;

use App\Setting\Request\ModuleRequest;
use App\Setting\Service\ModuleService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Mine\Annotation\Auth;
use Mine\Annotation\OperationLog;
use Mine\Annotation\Permission;
use Mine\MineController;
use Psr\Http\Message\ResponseInterface;

/**
 * 本地模块管理
 * Class ModuleController
 * @package App\Setting\Controller\Modules
 */
#[Controller(prefix: "setting/module"), Auth]
class ModuleController extends MineController
{
    #[Inject]
    protected ModuleService $service;

    /**
     * 本地模块列表
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[GetMapping("index"), Permission("setting:module, setting:module:index")]
    public function index(): ResponseInterface
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }

    /**
     * 新增本地模块
     * @param ModuleRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("save"), Permission("setting:module:save"), OperationLog]
    public function save(ModuleRequest $request): ResponseInterface
    {
        $this->service->createModule($request->validated());
        return $this->success();
    }

    /**
     * 启停用模块
     * @param ModuleRequest $request
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PostMapping("modifyStatus"), Permission("setting:module:status"), OperationLog]
    public function modifyStatus(ModuleRequest $request): ResponseInterface
    {
        return $this->service->modifyStatus($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 安装模块
     * @param string $name
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[PutMapping("install"), Permission("setting:module:install"), OperationLog]
    public function install(): ResponseInterface
    {
        return $this->service->installModuleData($this->request->input('name')) ? $this->success() : $this->error();
    }

    /**
     * 卸载删除模块
     * @param string $name
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     */
    #[DeleteMapping("delete"), Permission("setting:module:delete"), OperationLog]
    public function delete(): ResponseInterface
    {
        return $this->service->uninstallModule($this->request->input('name')) ? $this->success() : $this->error();
    }

}