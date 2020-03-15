<template>
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
</template>
<script>
export default {
  name: 'tablePage',
  data () {
    return {
      searchForm: {
        // 搜索框初始化对象
        page: 1, // 当前页数
        pageSize: 10 // 页面大小
      },
      total: 0 // 表单数据总数
    }
  },
  methods: {
    changePage (v) {
      this.searchForm.page = v
      this.getDataList()
    },
    changePageSize (v) {
      this.searchForm.pageSize = v
      this.getDataList()
    }
  }
}
</script>
