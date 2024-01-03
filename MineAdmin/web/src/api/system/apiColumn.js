import { request } from '@/utils/request.js'

/**
 * 接口字段管理 API JS
 */

export default {

  /**
   * 获取接口字段管理分页列表
   * @returns
   */
  getList(params = {}) {
    return request({
      url: 'system/apiColumn/index',
      method: 'get',
      params
    })
  },

  /**
   * 从回收站获取接口字段管理数据列表
   * @returns
   */
  getRecycleList(params = {}) {
    return request({
      url: 'system/apiColumn/recycle',
      method: 'get',
      params
    })
  },

  /**
   * 添加接口字段管理
   * @returns
   */
  save(data = {}) {
    return request({
      url: 'system/apiColumn/save',
      method: 'post',
      data
    })
  },

  /**
   * 读取接口字段管理
   * @returns
   */
  read(id) {
    return request({
      url: 'system/apiColumn/read/' + id,
      method: 'post',
    })
  },

  /**
   * 将接口字段管理移到回收站
   * @returns
   */
  deletes(data) {
    return request({
      url: 'system/apiColumn/delete',
      method: 'delete',
      data
    })
  },

  /**
   * 恢复接口字段管理数据
   * @returns
   */
  recoverys(data) {
    return request({
      url: 'system/apiColumn/recovery',
      method: 'put',
      data
    })
  },

  /**
   * 真实删除接口字段管理
   * @returns
   */
  realDeletes(data) {
    return request({
      url: 'system/apiColumn/realDelete',
      method: 'delete',
      data
    })
  },

  /**
   * 更新接口字段管理数据
   * @returns
   */
  update(id, data = {}) {
    return request({
      url: 'system/apiColumn/update/' + id,
      method: 'put',
      data
    })
  },

  /**
   * 更改部门状态
   * @returns
   */
  changeStatus(data = {}) {
    return request({
      url: 'system/apiColumn/changeStatus',
      method: 'put',
      data
    })
  },
}
