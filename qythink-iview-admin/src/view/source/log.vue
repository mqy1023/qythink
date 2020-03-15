<template>
  <div class="search">
    <Row>
      <Col>
        <Card>
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
  </div>
</template>

<script>
import Editor from '_c/editor'
import { getListLogs, addEditNews, delLog } from '@/api/data'

export default {
  name: 'log',
  components: { Editor },
  data () {
    return {
      openSearch: true, // 显示搜索
      openTip: true, // 显示提示
      loading: true, // 表单加载状态
      modalType: 0, // 添加或编辑标识
      modalVisible: false, // 添加或编辑显示
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
        add_user_id: ''
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
        { title: '行为', key: 'action', minWidth: 120 },
        { title: '创建时间', key: 'add_time', minWidth: 120, sortable: true, sortType: 'desc' }
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
      getListLogs(this.searchForm).then(res => {
        this.loading = false
        if (res.data.status) {
          this.data = res.data.data
          this.total = res.data.total
        }
      })
    }
  },
  mounted () {
    // 当前用户id
    this.searchForm.add_user_id = this.$store.state.user.userId
    this.init()
  }
}
</script>
<style lang="less">
  // 建议引入通用样式 可删除下面样式代码
  @import "../../styles/table-common.less";
  @import "../../styles/common.less";
</style>
