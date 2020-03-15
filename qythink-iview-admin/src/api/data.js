import axios from '@/libs/api.request'
import config from '@/config'
const baseUrl = process.env.NODE_ENV === 'development' ? config.baseUrl.dev : config.baseUrl.pro

export const getTableData = () => {
  return axios.request({
    url: 'get_table_data',
    method: 'get'
  })
}

export const getDragList = () => {
  return axios.request({
    url: 'get_drag_list',
    method: 'get'
  })
}

export const errorReq = () => {
  return axios.request({
    url: 'error_url',
    method: 'post'
  })
}

export const saveErrorLogger = info => {
  return axios.request({
    url: 'save_error_logger',
    data: info,
    method: 'post'
  })
}

export const uploadImg = formData => {
  return axios.request({
    url: 'image/upload',
    data: formData
  })
}

export const getOrgData = () => {
  return axios.request({
    url: 'get_org_data',
    method: 'get'
  })
}

export const getTreeSelectData = () => {
  return axios.request({
    url: 'get_tree_select_data',
    method: 'get'
  })
}

// 新闻列表
export const getListNews = (params) => {
  return axios.request({
    url: 'admin/news/lst',
    method: 'get',
    params: params
  })
}
// 添加修改新闻
export const addEditNews = (data) => {
  return axios.request({
    url: 'admin/news/addEdit',
    method: 'post',
    data: data
  })
}
// 删除新闻
export const delNews = (data) => {
  return axios.request({
    url: 'admin/news/del',
    method: 'post',
    data: data
  })
}

// 新闻分类列表
export const getListNewsType = (params) => {
  return axios.request({
    url: 'admin/news/lstType',
    method: 'get',
    params: params
  })
}
// 搜索新闻分类
export const searchNewsType = (params) => {
  return axios.request({
    url: 'admin/news/searchNewsType',
    method: 'get',
    params: params
  })
}
// 搜索栏目分类
export const linkNewsType = (params) => {
  return axios.request({
    url: 'admin/news/linkNewsType',
    method: 'get',
    params: params
  })
}
// 获取分类详情
export const getNewsType = (params) => {
  return axios.request({
    url: 'admin/news/addEditType',
    method: 'get',
    params: params
  })
}

// 添加修改新闻分类
export const addEditNewsType = (data) => {
  return axios.request({
    url: 'admin/news/addEditType',
    method: 'post',
    data: data
  })
}

// 删除新闻分类
export const delNewsType = (data) => {
  return axios.request({
    url: 'admin/news/delType',
    method: 'post',
    data: data
  })
}

// 获取角色列表
export const getListRole = (params) => {
  return axios.request({
    url: 'admin/SysRole/lst',
    method: 'get',
    params: params
  })
}

// 添加修改角色
export const addEditRole = (data) => {
  return axios.request({
    url: 'admin/SysRole/addEdit',
    method: 'post',
    data: data
  })
}

// 删除角色
export const delRole = (data) => {
  return axios.request({
    url: 'admin/SysRole/del',
    method: 'post',
    data: data
  })
}
// 获取角色菜单权限
export const getEditMenu = (params) => {
  return axios.request({
    url: 'admin/SysRole/editMenu',
    method: 'get',
    params: params
  })
}
// 修改角色菜单权限
export const EditMenu = (data) => {
  return axios.request({
    url: 'admin/SysRole/editMenu',
    method: 'post',
    data: data
  })
}

// 一级 菜单权限列表
export const getListMenu = (params) => {
  return axios.request({
    url: 'admin/SysMenu/lst',
    method: 'get',
    params: params
  })
}

// 添加修改菜单权限
export const addEditMenu = (data) => {
  return axios.request({
    url: 'admin/SysMenu/addEdit',
    method: 'post',
    data: data
  })
}

// 删除菜单权限
export const deltMenu = (data) => {
  return axios.request({
    url: 'admin/SysMenu/del',
    method: 'post',
    data: data
  })
}

// 用户列表
export const getLisUser = (params) => {
  return axios.request({
    url: 'admin/SysUser/lst',
    method: 'get',
    params: params
  })
}

// 添加修改用户
export const addEditUser = (data) => {
  return axios.request({
    url: 'admin/SysUser/addEdit',
    method: 'post',
    data: data
  })
}

// 获取角色添加修改用户
export const getRoleUser = (params) => {
  return axios.request({
    url: 'admin/SysUser/addEdit',
    method: 'get',
    params: params
  })
}

// 删除用户
export const delUser = (data) => {
  return axios.request({
    url: 'admin/SysUser/del',
    method: 'post',
    data: data
  })
}

// 列表字典
export const getlstDict = (params) => {
  return axios.request({
    url: 'admin/SysDict/lstDict',
    method: 'get',
    params: params
  })
}

// 添加修改字典
export const addEditDict = (data) => {
  return axios.request({
    url: 'admin/SysDict/addEditDict',
    method: 'post',
    data: data
  })
}

// 删除字典
export const delDict = (data) => {
  return axios.request({
    url: 'admin/SysDict/delDict',
    method: 'post',
    data: data
  })
}

// 列表数据
export const getlstDictDetail = (params) => {
  return axios.request({
    url: 'admin/SysDict/lstDictDetail',
    method: 'get',
    params: params
  })
}

// 添加修改数据
export const addEditDictDetail = (data) => {
  return axios.request({
    url: 'admin/SysDict/addEditDictDetail',
    method: 'post',
    data: data
  })
}

// 删除数据
export const delDictDetail = (data) => {
  return axios.request({
    url: 'admin/SysDict/delDictDetail',
    method: 'post',
    data: data
  })
}

// 系统配置
export const editConfig = (data) => {
  return axios.request({
    url: 'admin/Config/editConfig',
    method: 'post',
    data: data
  })
}

// 获取系统配置
export const getSysConfig = (params) => {
  return axios.request({
    url: 'admin/Config/getSysConfig',
    method: 'get',
    params: params
  })
}

// 操作日志列表
export const getListLogs = (params) => {
  return axios.request({
    url: 'admin/SysLog/lst',
    method: 'get',
    params: params
  })
}

// 删除日志
export const delLog = (data) => {
  return axios.request({
    url: 'admin/SysLog/del',
    method: 'post',
    data: data
  })
}

export const uploadFile = baseUrl + '/index/index/uploadFile'

// 图片上传
// export const uploadFile = (data) => {
//   return axios.request({
//     url: '/index/index/uploadFile',
//     method: 'post',
//     data: data
//   })
// }
