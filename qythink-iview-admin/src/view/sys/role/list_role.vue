<style lang="less">
@import "../../../styles/table-common.less";
@import "../../../styles/common.less";
</style>
<template>
  <div class="search">
    <Card>
      <Row class="operation">
        <Button @click="addEditRole" type="primary" icon="md-add">添加角色</Button>
        <Button @click="init" icon="md-refresh">刷新</Button>
      </Row>
      <Row>
        <Table
          class="table"
          :loading="loading"
          border
          :columns="columns"
          :data="data"
          ref="table"
          sortable="custom"
          @on-sort-change="changeSort"
          @on-selection-change="changeSelect"
        ></Table>
      </Row>
      <Row type="flex" justify="end" class="page">
        <Page
          :current="page"
          :total="total"
          :page-size="pageSize"
          @on-change="changePage"
          @on-page-size-change="changePageSize"
          :page-size-opts="[10,20,50]"
          show-total
          show-elevator
          show-sizer
        ></Page>
      </Row>
    </Card>

    <!-- 编辑 -->
    <Modal :title="modalTitle" v-model="roleModalVisible" :mask-closable="false" :width="500">
      <Form ref="roleForm" :model="roleForm" :label-width="80" :rules="roleFormValidate">
        <FormItem label="角色名称" prop="name">
          <Input v-model="roleForm.name" placeholder="角色名称" />
        </FormItem>
        <FormItem label="备注" prop="remark">
          <Input v-model="roleForm.remark" />
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="text" @click="cancelRole">取消</Button>
        <Button type="primary" :loading="submitLoading" @click="submitRole">提交</Button>
      </div>
    </Modal>
    <!-- 菜单权限 -->
    <Modal
      :title="modalTitle"
      v-model="permModalVisible"
      :mask-closable="false"
      :width="500"
      :styles="{top: '30px'}"
      class="permModal"
    >
      <div style="position:relative">
        <Tree
          ref="tree"
          :data="permData"
          show-checkbox
          :render="renderContent"
          :check-strictly="true"
        ></Tree>
        <Spin size="large" fix v-if="treeLoading"></Spin>
      </div>
      <div slot="footer">
        <Button type="text" @click="cancelPermEdit">取消</Button>
        <Select
          v-model="openLevel"
          @on-change="changeOpen"
          style="width:110px;text-align:left;margin-right:10px"
        >
          <Option value="0">展开所有</Option>
          <Option value="1">收合所有</Option>
          <Option value="2">仅展开一级</Option>
          <Option value="3">仅展开两级</Option>
        </Select>
        <Button @click="selectTreeAll">全选/反选</Button>
        <Button type="primary" :loading="submitPermLoading" @click="submitPermEdit">提交</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
import { getListRole, addEditRole, delRole, getEditMenu, EditMenu } from '@/api/data'
import util from '@/libs/util.js'
export default {
  name: 'list_role',
  data () {
    return {
      openTip: true,
      openLevel: '0',
      loading: true,
      treeLoading: true,
      depTreeLoading: true,
      submitPermLoading: false,
      submitDepLoading: false,
      searchKey: '',
      sortColumn: 'createTime',
      sortType: 'desc',
      modalType: 0,
      roleModalVisible: false,
      permModalVisible: false,
      depModalVisible: false,
      modalTitle: '',
      roleForm: {
        name: '',
        remark: ''
      },
      roleFormValidate: {
        name: [{ required: true, message: '角色名称不能为空', trigger: 'blur' }]
      },
      submitLoading: false,
      selectList: [],
      selectCount: 0,
      columns: [
        { title: '角色名称', key: 'name', width: 150 },
        { title: '备注', key: 'remark', minWidth: 150 },
        { title: '创建时间', key: 'add_time', width: 170, sortable: true, sortType: 'desc' },
        { title: '操作',
          key: 'action',
          align: 'center',
          fixed: 'right',
          width: 300,
          render: (h, params) => {
            return h('div', [
              h(
                'Button',
                {
                  props: {
                    type: 'warning',
                    size: 'small'
                  },
                  style: {
                    marginRight: '5px'
                  },
                  on: {
                    click: () => {
                      this.editPerm(params.row)
                    }
                  }
                },
                '菜单权限'
              ),

              h(
                'Button',
                {
                  props: {
                    size: 'small'
                  },
                  style: {
                    marginRight: '5px'
                  },
                  on: {
                    click: () => {
                      this.edit(params.row)
                    }
                  }
                },
                '编辑'
              ),
              h(
                'Button',
                {
                  props: {
                    type: 'error',
                    size: 'small'
                  },
                  on: {
                    click: () => {
                      this.remove(params.row)
                    }
                  }
                },
                '删除'
              )
            ])
          }
        }
      ],
      data: [],
      page: 1,
      pageSize: 10,
      total: 0,
      permData: [],
      editRolePermId: '',
      selectAllFlag: false,
      depData: [],
      dataType: 0,
      editDepartments: []
    }
  },
  methods: {
    init () {
      this.getListRole()
      // 获取所有菜单权限树
      // this.getPermList()
    },
    renderContent (h, { root, node, data }) {
      return h(
        'span',
        {
          style: {
            display: 'inline-block',
            cursor: 'pointer'
          },
          on: {
            click: () => {
              data.checked = !data.checked
            }
          }
        },
        [
          h('span', [
            h('span', { class: 'ivu-tree-title' }, data.title)
          ])
        ]
      )
    },
    changePage (v) {
      this.page = v
      this.getListRole()
      this.clearSelectAll()
    },
    changePageSize (v) {
      this.pageSize = v
      this.getListRole()
    },
    changeSort (e) {
      this.sortColumn = e.key
      this.sortType = e.order
      if (e.order == 'normal') {
        this.sortType = ''
      }
      this.getListRole()
    },
    getListRole () {
      this.loading = true
      let params = {
        page: this.page,
        pageSize: this.pageSize,
        sort: this.sortColumn,
        order: this.sort
      }
      getListRole(params).then(res => {
        this.loading = false
        if (res.status) {
          this.data = res.data.data
          this.total = res.data.total
        }
      })
    },

    cancelRole () {
      this.roleModalVisible = false
    },
    submitRole () {
      this.$refs.roleForm.validate(valid => {
        if (valid) {
          if (this.modalType == 0) {
            // 添加
            this.submitLoading = true
            addEditRole(this.roleForm).then(res => {
              this.submitLoading = false
              if (res.status) {
                this.$Message.success('操作成功')
                this.getListRole()
                this.roleModalVisible = false
              }
            })
          } else {
            this.submitLoading = true
            this.roleForm.op = 'edit'
            addEditRole(this.roleForm).then(res => {
              this.submitLoading = false
              if (res.status) {
                this.$Message.success('操作成功')
                this.getListRole()
                this.roleModalVisible = false
              }
            })
          }
        }
      })
    },
    addEditRole () {
      this.modalType = 0
      this.modalTitle = '添加角色'
      this.$refs.roleForm.resetFields()
      delete this.roleForm.id
      this.roleModalVisible = true
    },
    edit (v) {
      this.modalType = 1
      this.modalTitle = '编辑角色'
      this.$refs.roleForm.resetFields()
      // 转换null为""
      for (let attr in v) {
        if (v[attr] == null) {
          v[attr] = ''
        }
      }
      let str = JSON.stringify(v)
      let roleInfo = JSON.parse(str)
      this.roleForm = roleInfo
      this.roleModalVisible = true
    },
    remove (v) {
      this.$Modal.confirm({
        title: '确认删除',
        content: '您确认要删除角色 ' + v.name + ' ?',
        loading: true,
        onOk: () => {
          delRole({ id: v.id }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('删除成功')
              this.getListRole()
            }
          })
        }
      })
    },
    clearSelectAll () {
      this.$refs.table.selectAll(false)
    },
    changeSelect (e) {
      this.selectList = e
      this.selectCount = e.length
    },
    editPerm (v) {
      this.editRolePermId = v.id
      this.modalTitle = '分配 ' + v.name + ' 的菜单权限'
      this.permModalVisible = true
      getEditMenu({ id: this.editRolePermId }).then(res => {
        // this.loading = false
        this.treeLoading = false
        if (res.status) {
          this.permData = res.data.data
        }
        // this.changeOpen(0)
      })
    },
    // 全选反选
    selectTreeAll () {
      this.selectAllFlag = !this.selectAllFlag
      let select = this.selectAllFlag
      this.selectedTreeAll(this.permData, select)
    },
    // 递归全选节点
    selectedTreeAll (permData, select) {
      let that = this
      permData.forEach(function (e) {
        e.checked = select
        if (e.children && e.children.length > 0) {
          that.selectedTreeAll(e.children, select)
        }
      })
    },

    submitPermEdit () {
      this.submitPermLoading = true
      let permIds = ''
      let selectedNodes = this.$refs.tree.getCheckedNodes()
      selectedNodes.forEach(function (e) {
        permIds += e.id + ','
      })
      permIds = permIds.substring(0, permIds.length - 1)
      EditMenu({
        id: this.editRolePermId,
        role_ids: permIds
      }).then(res => {
        this.submitPermLoading = false
        if (res.status) {
          this.$Message.success('操作成功')
          // 标记重新获取菜单数据
          // this.$store.commit('setAdded', false)
          // util.initRouter(this)
          this.getListRole()
          this.permModalVisible = false
        }
      })
    },
    cancelPermEdit () {
      this.permModalVisible = false
    },
    changeOpen (v) {
      if (v == '0') {
        this.permData.forEach(e => {
          e.expand = true
          if (e.children && e.children.length > 0) {
            e.children.forEach(c => {
              c.expand = true
              if (c.children && c.children.length > 0) {
                c.children.forEach(function (b) {
                  b.expand = true
                })
              }
            })
          }
        })
      } else if (v == '1') {
        this.permData.forEach(e => {
          e.expand = false
          if (e.children && e.children.length > 0) {
            e.children.forEach(c => {
              c.expand = false
              if (c.children && c.children.length > 0) {
                c.children.forEach(function (b) {
                  b.expand = false
                })
              }
            })
          }
        })
      } else if (v == '2') {
        this.permData.forEach(e => {
          e.expand = true
          if (e.children && e.children.length > 0) {
            e.children.forEach(c => {
              c.expand = false
              if (c.children && c.children.length > 0) {
                c.children.forEach(function (b) {
                  b.expand = false
                })
              }
            })
          }
        })
      } else if (v == '3') {
        this.permData.forEach(e => {
          e.expand = true
          if (e.children && e.children.length > 0) {
            e.children.forEach(c => {
              c.expand = true
              if (c.children && c.children.length > 0) {
                c.children.forEach(function (b) {
                  b.expand = false
                })
              }
            })
          }
        })
      }
    }
  },
  mounted () {
    this.init()
  }
}
</script>
