import { request } from '@/utils/request.js'

export default {

  /**
   * 获取接收消息列表
   * @returns
   */
  getReceiveList(params = {}) {
    return request({
      url: 'system/queueMessage/receiveList',
      method: 'get',
      params
    })
  },

  /**
   * 获取发送消息列表
   * @returns
   */
  getSendList(params = {}) {
    return request({
      url: 'system/queueMessage/sendList',
      method: 'get',
      params
    })
  },

  /**
   * 获取接收人列表
   * @returns
   */
  getReceiveUser(params = {}) {
    return request({
      url: 'system/queueMessage/getReceiveUser',
      method: 'get',
      params
    })
  },

  /**
   * 删除消息
   * @returns
   */
  deletes(data = {}) {
    return request({
      url: 'system/queueMessage/deletes',
      method: 'delete',
      data
    })
  },

  /**
   * 更新读取状态
   * @returns
   */
  updateReadStatus(data = {}) {
    return request({
      url: 'system/queueMessage/updateReadStatus',
      method: 'put',
      data
    })
  },

  /**
   * 发私信
   * @returns
   */
  sendPrivateMessage(data = {}) {
    return request({
      url: 'system/queueMessage/sendPrivateMessage',
      method: 'post',
      data
    })
  },
}