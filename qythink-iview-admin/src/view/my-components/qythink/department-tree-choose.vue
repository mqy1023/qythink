<template>
  <div>
    <div style="display:flex;">
      <Input
        v-model="type_name"
        readonly
        style="margin-right:10px;"
        :placeholder="placeholder"
        :clearable="clearable"
        @on-clear="clearSelect"
      />
      <Poptip transfer trigger="click" placement="right" title="选择分类" width="250">
        <Button icon="md-list">选择分类</Button>
        <div slot="content">
          <Input
            v-model="searchKey"
            suffix="ios-search"
            @on-change="searchDep"
            placeholder="输入分类名搜索"
            clearable
          />
          <div class="dep-tree-bar">
            <Tree
              :data="dataDep"
              @on-select-change="selectTree"
              :multiple="multiple"
            ></Tree>
            <Spin size="large" fix v-if="depLoading"></Spin>
          </div>
        </div>
      </Poptip>
    </div>
  </div>
</template>

<script>
import { searchNewsType } from '@/api/data'
export default {
  name: 'departmentTreeChoose',
  props: {
    multiple: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: true
    },
    placeholder: {
      type: String,
      default: '点击选择分类'
    }
  },
  data () {
    return {
      depLoading: false,
      type_name: '',
      searchKey: '',
      dataDep: [],
      selectDep: [],
      type: []
    }
  },
  methods: {
    initDepartmentData () {
      searchNewsType().then(res => {
        if (res.status) {
          // res.result.forEach(function(e) {
          //   if (e.isParent) {
          //     e.loading = false;
          //     e.children = [];
          //   }
          //   if (e.status == -1) {
          //     e.title = "[已禁用] " + e.title;
          //     e.disabled = true;
          //   }
          // });
          this.dataDep = res.data.data
        }
      })
    },
    // loadData(item, callback) {
    //   getListNewsType(item.id).then(res => {
    //     if (res.status) {
    //       res.result.forEach(function(e) {
    //         if (e.isParent) {
    //           e.loading = false;
    //           e.children = [];
    //         }
    //         if (e.status == -1) {
    //           e.title = "[已禁用] " + e.title;
    //           e.disabled = true;
    //         }
    //       });
    //       callback(res.result);
    //     }
    //   });
    // },
    searchDep () {
      // 搜索部门
      if (this.searchKey) {
        this.depLoading = true
        searchNewsType({ search_text: this.searchKey }).then(res => {
          this.depLoading = false
          if (res.status) {
            // res.result.forEach(function(e) {
            //   if (e.status == -1) {
            //     e.title = "[已禁用] " + e.title;
            //     e.disabled = true;
            //   }
            // });
            this.dataDep = res.data.data
          }
        })
      } else {
        this.initDepartmentData()
      }
    },
    selectTree (v) {
      let ids = [],
        title = ''
      v.forEach(e => {
        ids.push(e.id)
        if (title == '') {
          title = e.title
        } else {
          title = title + '、' + e.title
        }
      })
      this.type = ids
      this.type_name = title
      if (this.multiple) {
        this.$emit('on-change', this.type)
      } else {
        this.$emit('on-change', this.type[0])
      }
    },
    clearSelect () {
      this.type = []
      this.type_name = ''
      this.initDepartmentData()
      if (this.multiple) {
        this.$emit('on-change', [])
      } else {
        this.$emit('on-change', '')
      }
      this.$emit('on-clear')
    },
    setData (ids, title) {
      this.type_name = title
      if (this.multiple) {
        this.type = ids
      } else {
        this.type = []
        this.type.push(ids)
      }
      this.$emit('on-change', this.type)
    }
  },
  created () {
    this.initDepartmentData()
  }
}
</script>

<style lang="less">
.dep-tree-bar {
  position: relative;
  min-height: 80px;
  max-height: 500px;
  overflow: auto;
  margin-top: 5px;
}

.dep-tree-bar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

.dep-tree-bar::-webkit-scrollbar-thumb {
  border-radius: 4px;
  -webkit-box-shadow: inset 0 0 2px #d1d1d1;
  background: #e4e4e4;
}
</style>
