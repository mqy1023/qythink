<template>
  <div class="search">
    <Row>
      <Col>
        <Card>
          <Row @keydown.enter.native="handleSearch">
            <Form ref="searchForm" :model="searchForm" inline :label-width="70">
              <Form-item prop="search_text" :label-width="8">
                <Input
                  type="text"
                  v-model="searchForm.search_text"
                  placeholder="请输入关键字"
                  clearable
                  style="width: 200px"
                />
              </Form-item>
              <Form-item style="margin-left:-35px;" class="br">
                <Button @click="handleSearch" type="primary" icon="ios-search">搜索</Button>
<!--                <Button @click="handleReset">重置</Button>-->
              </Form-item>
              <Button @click="handleReset" icon="md-refresh" style="float: right">刷新</Button>
            </Form>
          </Row>
          <Row class="operation">
            <Button @click="add" type="primary" icon="md-add">添加</Button>
            <Button @click="delAll" icon="md-trash">批量删除</Button>
<!--            <Button @click="getDataList" icon="md-refresh">刷新</Button>-->
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
              :current="searchForm.page"
              :total="total"
              :page-size="searchForm.pageSize"
              @on-change="changePage"
              @on-page-size-change="changePageSize"
              :page-size-opts="[10,20,50]"
              show-total
              show-elevator
              show-sizer
            ></Page>
          </Row>
        </Card>
      </Col>
    </Row>
    <Modal :title="modalTitle" v-model="modalVisible" :mask-closable="false"  :width="500">
      <Form ref="form" :model="form" :label-width="70" :rules="formValidate">

        <FormItem label="用户名" prop="name">
          <Input v-model="form.name" clearable style="width:100%"/>
        </FormItem>
        <FormItem label="账号" prop="account">
          <Input v-model="form.account" clearable style="width:100%"/>
        </FormItem>
        <FormItem label="修改密码" prop="is_pwd" v-if="form.op=='edit'">
          <RadioGroup v-model="is_pwd">
            <Radio label="1">是</Radio>
            <Radio label="0">否</Radio>
          </RadioGroup>
        </FormItem>

        <FormItem  label="密码" prop="password" :error="errorPass" v-if="form.op=='add' || is_pwd=='1'">
          <Input type="password" password v-model="form.password" autocomplete="off" />
        </FormItem>
        <FormItem label="确认密码" prop="password2" :error="errorPass" v-if="form.op=='add' || is_pwd=='1'">
          <Input type="password" password v-model="form.password2" autocomplete="off" />
        </FormItem>

        <FormItem label="手机" prop="mobile">
          <Input v-model="form.mobile" clearable style="width:100%"/>
        </FormItem>
        <FormItem label="邮箱" prop="email">
          <Input v-model="form.email" clearable style="width:100%"/>
        </FormItem>

        <Form-item label="性别" prop="sex">
          <RadioGroup v-model="form.sex">
            <Radio label="0">
              <span>男</span>
            </Radio>
            <Radio label="1">
              <span>女</span>
            </Radio>
          </RadioGroup>
        </Form-item>

        <Form-item label="角色" prop="role_id">
          <Select v-model="form.role_id">
            <Option v-for="item in roleList" :value="item.value" :key="item.value">{{ item.label }}</Option>
          </Select>
        </Form-item>

      </Form>
      <div slot="footer">
        <Button type="text" @click="modalVisible=false">取消</Button>
        <Button type="primary" :loading="submitLoading" @click="handleSubmit">提交</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
import { getLisUser, addEditUser, delUser, getRoleUser } from '@/api/data'
export default {
  name: 'user',
  // inject:['reload'],
  data () {
    return {
      loading: true, // 表单加载状态
      modalType: 0, // 添加或编辑标识
      modalVisible: false, // 添加或编辑显示
      modalTitle: '', // 添加或编辑标题
      errorPass: '',
      showPwd: true,
      roleList: [],
      searchForm: {
        // 搜索框初始化对象
        page: 1, // 当前页数
        pageSize: 10 // 页面大小
      },
      is_pwd: '0',
      form: {
        // 添加或编辑表单对象初始化数据
        status: '0',
        name: '',
        account: '',
        password: '',
        password2: '',
        role_id: '',
        mobile: '',
        email: '',
        sex: '0',
        head_img: '',
        depart_id: '',
        op: 'add'
      },
      content: '',
      // 表单验证规则
      formValidate: {
        name: [{ required: true, message: '名称不能为空', trigger: 'blur' }],
        account: [{ required: true, message: '账号不能为空', trigger: 'blur' }],
        password: [{ required: true, message: '密码不能为空', trigger: 'blur' }],
        password2: [{ required: true, message: '密码不能为空', trigger: 'blur' }]
        // role_id: [{ required: true, message: '角色为空', trigger: 'blur' }],
      },
      submitLoading: false, // 添加或编辑提交状态
      selectList: [], // 多选数据
      selectCount: 0, // 多选计数
      columns: [
        // 表头
        {
          type: 'selection',
          width: 60,
          align: 'center'
        },
        {
          title: '用户名',
          key: 'name',
          minWidth: 120
        },
        {
          title: '账号',
          key: 'account',
          minWidth: 120
        },
        {
          title: '角色',
          key: 'role_name',
          minWidth: 120
        },
        {
          title: '手机',
          key: 'mobile',
          minWidth: 120
        },
        {
          title: '邮箱',
          key: 'email',
          minWidth: 120
        },
        {
          title: '性别',
          key: 'sex',
          minWidth: 60,
          render: (h, params) => {
            let re = ''
            if (params.row.sex == 0) {
              re = '男'
            } else if (params.row.sex == 1) {
              re = '女'
            }
            return h('div', re)
          }
        },
        {
          title: '创建时间',
          key: 'add_time',
          minWidth: 120
        },
        {
          title: '操作',
          key: 'action',
          width: 200,
          fixed: 'right',
          render: (h, params) => {
            return h('div', [
              h(
                'Button',
                {
                  props: {
                    type: 'primary',
                    size: 'small',
                    icon: 'ios-create-outline'
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
                    size: 'small',
                    icon: 'md-trash'
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
      data: [], // 表单数据
      total: 0 // 表单数据总数
    }
  },
  methods: {
    init () {
      this.getDataList()
      this.getRoleUser()
    },
    changePage (v) {
      this.searchForm.page = v
      this.getDataList()
    },
    changePageSize (v) {
      this.searchForm.pageSize = v
      this.getDataList()
    },
    handleSearch () {
      this.searchForm.page = 1
      this.searchForm.pageSize = 10
      this.getDataList()
    },
    handleReset () {
      this.reload()
      // this.$refs.searchForm.resetFields()
      // this.searchForm.page = 1
      // this.searchForm.pageSize = 10
      // this.searchForm.startDate = ''
      // this.searchForm.endDate = ''
      // // 重新加载数据
      // this.getDataList()
    },
    changeSort (e) {
      this.searchForm.sort = e.key
      this.searchForm.order = e.order
      if (e.order === 'normal') {
        this.searchForm.order = ''
      }
      this.getDataList()
    },
    changeSelect (e) {
      this.selectList = e
      this.selectCount = e.length
    },
    getDataList () {
      this.loading = true
      // 带多条件搜索参数获取表单数据 请自行修改接口
      getLisUser(this.searchForm).then(res => {
        this.loading = false
        if (res.status) {
          this.data = res.data.data
          this.total = res.data.total
        }
      })
    },
    handleSubmit () {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.submitLoading = true
          if (this.modalType === 0) {
            // 添加 避免编辑后传入id等数据 记得删除
            delete this.form.id
            addEditUser(this.form).then(res => {
              this.submitLoading = false
              if (res.data.status) {
                this.$Message.success('操作成功')
                this.getDataList()
                this.modalVisible = false
              } else {
                this.$Message.error(res.data.msg)
              }
            })
          } else {
            this.form.is_pwd = this.is_pwd
            // 编辑
            addEditUser(this.form).then(res => {
              this.submitLoading = false
              if (res.data.status) {
                this.$Message.success('修改成功')
                this.getDataList()
                this.modalVisible = false
              } else {
                this.$Message.error(res.data.msg)
              }
            })
          }
        }
      })
    },
    add () {
      this.modalType = 0
      this.is_pwd = '0'
      this.modalTitle = '添加'
      this.$refs.form.resetFields()
      delete this.form.id
      this.form.op = 'add'
      this.modalVisible = true
    },
    edit (v) {
      this.modalType = 1
      this.is_pwd = '0'
      this.modalTitle = '编辑' + v.name
      this.$refs.form.resetFields()
      // 转换null为""
      for (let attr in v) {
        if (v[attr] === null) {
          v[attr] = ''
        }
      }
      let str = JSON.stringify(v)
      let data = JSON.parse(str)
      delete data.password
      this.form = data
      this.form.op = 'edit'
      this.modalVisible = true
    },
    remove (v) {
      this.$Modal.confirm({
        title: '确认删除',
        // 记得确认修改此处
        content: '您确认要删除 ' + v.name + ' ?',
        loading: true,
        onOk: () => {
          // 删除
          delUser({ id: v.id }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('删除成功')
              this.getDataList()
            }
          })
        }
      })
    },
    getRoleUser () {
      // 带多条件搜索参数获取表单数据 请自行修改接口
      getRoleUser().then(res => {
        this.roleList = res.data.role
      })
    },

    delAll () {
      if (this.selectCount <= 0) {
        this.$Message.warning('您还未选择要删除的数据')
        return
      }
      this.$Modal.confirm({
        title: '确认删除',
        content: '您确认要删除所选的 ' + this.selectCount + ' 条数据?',
        loading: true,
        onOk: () => {
          let ids = ''
          this.selectList.forEach(function (e) {
            ids += e.id + ','
          })
          ids = ids.substring(0, ids.length - 1)
          // 批量删除
          delUser({ id: ids }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('操作成功')
              this.getDataList()
            }
          })
        }
      })
    }
  },
  mounted () {
    this.init()
  }
}
</script>
<style lang="less">
  // 建议引入通用样式 可删除下面样式代码
  @import "../../../styles/table-common.less";
  @import "../../../styles/common.less";
</style>
