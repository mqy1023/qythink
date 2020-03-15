<template>
  <div class="search">
    <Row>
      <Col>
        <Card>
          <Row v-show="openSearch" @keydown.enter.native="handleSearch">
            <Form ref="searchForm" :model="searchForm" inline :label-width="70">
              <Form-item  :label-width="0" prop="startDate">
                <DatePicker
                  v-model="searchForm.startDate"
                  type="date"
                  format="yyyy-MM-dd"
                  clearable
                  @on-change="selectDateBegin"
                  placeholder="选择起始时间"
                  style="width: 200px"
                ></DatePicker>
              </Form-item>

              <Form-item  :label-width="0" prop="endDate">
                <DatePicker
                  v-model="searchForm.endDate"
                  type="date"
                  format="yyyy-MM-dd"
                  clearable
                  @on-change="selectDateEnd"
                  placeholder="选择结束时间"
                  style="width: 200px"
                ></DatePicker>
              </Form-item>

              <Form-item :label-width="8" prop="search_text">
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
                <Button @click="refresh" icon="md-refresh">刷新</Button>
              </Form-item>
            </Form>
          </Row>
          <Row class="operation">
            <Button @click="add" type="primary" icon="md-add">添加</Button>
            <Button @click="delAll" icon="md-trash">批量删除</Button>
            <Button type="dashed" @click="openSearch=!openSearch">{{openSearch ? '关闭搜索' : '开启搜索'}}</Button>
            <Button type="dashed" @click="openTip=!openTip">{{openTip ? '关闭提示' : '开启提示'}}</Button>
          </Row>
          <Row v-show="openTip">
            <Alert show-icon>
              已选择
              <span class="select-count">{{selectCount}}</span> 项
              <a class="select-clear" @click="clearSelectAll">清空</a>
            </Alert>
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
    <Modal :title="modalTitle" v-model="modalVisible" :mask-closable="false"  :width="900">
      <Form ref="form" :model="form" :label-width="70" :rules="formValidate">

        <Form-item label="分类">
          <department-tree-choose @on-change="handleSelectDepTree" ref="depTree"></department-tree-choose>
        </Form-item>

        <FormItem label="标题" prop="title">
          <Input v-model="form.title" clearable style="width:100%"/>
        </FormItem>
        <FormItem label="作者" prop="author">
          <Input v-model="form.author" clearable style="width:100%"/>
        </FormItem>
        <FormItem label="标签" prop="label">
          <Input v-model="form.label" clearable style="width:100%"/>
        </FormItem>

        <FormItem label="内容" prop="content">
        <editor ref="editor" :value="content" @on-change="handleChange" />
        </FormItem>

      </Form>
      <div slot="footer">
        <Button type="text" @click="modalVisible=false">取消</Button>
        <Button type="primary" :loading="submitLoading" @click="handleSubmit">提交</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
import Editor from '_c/editor'
import { getListNews, addEditNews, delNews } from '@/api/data'
import departmentChoose from '../my-components/qythink/department-choose'
import departmentTreeChoose from '../my-components/qythink/department-tree-choose'

export default {
  name: 'news',
  components: { departmentTreeChoose, departmentChoose, Editor },
  data () {
    return {
      openSearch: true, // 显示搜索
      openTip: true, // 显示提示
      loading: true, // 表单加载状态
      modalType: 0, // 添加或编辑标识
      modalVisible: false, // 添加或编辑显示
      modalTitle: '', // 添加或编辑标题
      submitLoading: false, // 添加或编辑提交状态
      selectList: [], // 多选数据
      selectCount: 0, // 多选计数
      data: [], // 表单数据
      total: 0, // 表单数据总数
      searchForm: {
        // 搜索框初始化对象
        page: 1, // 当前页数
        pageSize: 10, // 页面大小
        sort: 'add_time', // 默认排序字段
        order: 'desc', // 默认排序方式
        startDate: '', // 起始时间
        endDate: '', // 终止时间
        search_text: '' // 关键字
      },
      form: {
        // 添加或编辑表单对象初始化数据
        type: '',
        title: '',
        author: '',
        label: '',
        // departmentId: "",
        op: 'add'
      },
      content: '',
      // 表单验证规则
      formValidate: {
        type: [{ required: true, message: '不能为空', trigger: 'blur' }],
        title: [{ required: true, message: '不能为空', trigger: 'blur' }],
        author: [{ required: true, message: '不能为空', trigger: 'blur' }]
      },
      columns: [
        // 表头
        { type: 'selection', width: 60, align: 'center' },
        { title: '标题', key: 'title', minWidth: 120 },
        { title: '分类', key: 'type_name', minWidth: 120 },
        { title: '作者', key: 'author', minWidth: 120 },
        { title: '标签', key: 'label', minWidth: 120 },
        { title: '创建时间', key: 'add_time', minWidth: 120, sortable: true, sortType: 'desc' },
        { title: '操作',
          key: 'action',
          align: 'center',
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
      ]
    }
  },
  methods: {
    init () {
      this.getDataList()
    },
    changePage (v) {
      this.searchForm.page = v
      this.getDataList()
      this.clearSelectAll()
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
      this.$refs.searchForm.resetFields()
      this.searchForm.page = 1
      this.searchForm.pageSize = 10
      this.searchForm.startDate = ''
      this.searchForm.endDate = ''
      // 重新加载数据
      this.getDataList()
    },
    // 刷新
    refresh () {
      this.$refs.searchForm.resetFields()
      this.init()
    },
    changeSort (e) {
      this.searchForm.sort = e.key
      this.searchForm.order = e.order
      if (e.order === 'normal') {
        this.searchForm.order = ''
      }
      this.getDataList()
    },
    clearSelectAll () {
      this.$refs.table.selectAll(false)
    },
    changeSelect (e) {
      this.selectList = e
      this.selectCount = e.length
    },
    selectDateBegin (v) {
      if (v) {
        this.searchForm.startDate = v
      }
    },
    selectDateEnd (v) {
      if (v) {
        this.searchForm.endDate = v
      }
    },
    handleSelectDepTree (v) {
      this.form.type = v
    },

    handleSelectDep (v) {
      this.searchForm.type = v
    },
    // 获取编辑器内容
    handleChange (html, text) {
      this.form.content = html
    },
    getDataList () {
      this.loading = true
      // 带多条件搜索参数获取表单数据 请自行修改接口
      getListNews(this.searchForm).then(res => {
        this.loading = false
        if (res.data.status) {
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
            addEditNews(this.form).then(res => {
              this.submitLoading = false
              if (res.data.status) {
                this.$Message.success('操作成功')
                this.getDataList()
                this.modalVisible = false
              }
            })
          } else {
            // 编辑
            this.form.op = 'edit'
            addEditNews(this.form).then(res => {
              this.submitLoading = false
              if (res.data.status) {
                this.$Message.success('操作成功')
                this.getDataList()
                this.modalVisible = false
              }
            })
          }
        }
      })
    },
    add () {
      this.modalType = 0
      this.modalTitle = '添加'
      this.$refs.form.resetFields()
      delete this.form.id
      this.$refs.editor.setHtml('')
      // 清空分类
      this.$refs.depTree.setData('', '')
      this.modalVisible = true
    },
    edit (v) {
      this.modalType = 1
      this.modalTitle = '编辑' + v.title
      this.$refs.form.resetFields()
      // 转换null为""
      for (let attr in v) {
        if (v[attr] === null) {
          v[attr] = ''
        }
      }
      let str = JSON.stringify(v)
      let data = JSON.parse(str)
      this.form = data

      // 获取编辑器内容
      this.$refs.editor.setHtml(data.content)

      this.$refs.depTree.setData(data.type, data.type_name)

      this.modalVisible = true
    },
    remove (v) {
      this.$Modal.confirm({
        title: '确认删除',
        // 记得确认修改此处
        content: '您确认要删除 ' + v.title + ' ?',
        loading: true,
        onOk: () => {
          // 删除
          delNews({ id: v.id }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('删除成功')
              this.getDataList()
            }
          })
        }
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
          delNews({ id: ids }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('操作成功')
              this.clearSelectAll()
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
  @import "../../styles/table-common.less";
  @import "../../styles/common.less";
</style>
