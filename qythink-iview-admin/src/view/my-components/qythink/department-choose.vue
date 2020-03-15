<template>
  <div>
    <Cascader
      v-model="selectDep"
      :load-data="loadData"
      :data="department"
      @on-change="handleChangeDep"
      change-on-select
      filterable
      clearable
      placeholder="请选择或输入搜索分类"
    ></Cascader>
  </div>
</template>

<script>
import { linkNewsType, getListNewsType } from '@/api/data'
export default {
  name: 'departmentChoose',
  props: {

  },
  data () {
    return {
      selectDep: [],
      department: []
    }
  },
  methods: {
    initDepartmentData () {
      getListNewsType().then(res => {
        if (res.status) {
          res.data.data.forEach(function (e) {
            if (e.p_id !== 0) {
              e.value = e.id
              e.label = e.title
              e.loading = false
              e.children = []
            } else {
              e.value = e.id
              e.label = e.title
            }
            // if (e.status == -1) {
            //   e.label = "[已禁用] " + e.label;
            //   e.disabled = true;
            // }
          })
          this.department = res.data.data
        }
      })
    },

    loadData (item, callback) {
      item.loading = true
      getListNewsType(item.value).then(res => {
        item.loading = false
        if (res.success) {
          res.result.forEach(function (e) {
            if (e.isParent) {
              e.value = e.id
              e.label = e.title
              e.loading = false
              e.children = []
            } else {
              e.value = e.id
              e.label = e.title
            }
            if (e.status == -1) {
              e.label = '[已禁用] ' + e.label
              e.disabled = true
            }
          })
          item.children = res.result
          callback()
        }
      })
    },
    handleChangeDep (value, selectedData) {
      let departmentId = ''
      // 获取最后一个值
      if (value && value.length > 0) {
        departmentId = value[value.length - 1]
      }
      this.$emit('on-change', departmentId)
    },
    clearSelect () {
      this.selectDep = []
    }
  },
  created () {
    this.initDepartmentData()
  }
}
</script>

<style lang="less">
</style>
