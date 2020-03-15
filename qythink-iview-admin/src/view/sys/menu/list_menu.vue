<style lang="less">
  @import "../../../styles/tree-common.less";
  @import "../../../styles/common.less";
</style>
<template>
  <div class="search">
    <Card>
      <Row class="operation">
        <Button @click="addMenu" type="primary" icon="md-add">添加子节点</Button>
        <Button @click="addRootMenu" icon="md-add">添加顶部菜单</Button>
        <Button @click="delAll" icon="md-trash">批量删除</Button>
        <Dropdown @on-click="handleDropdown">
          <Button>
            更多操作
            <Icon type="md-arrow-dropdown"></Icon>
          </Button>
          <DropdownMenu slot="list">
            <DropdownItem name="refresh">刷新</DropdownItem>
            <DropdownItem name="expandOne">收合所有</DropdownItem>
            <DropdownItem name="expandTwo">仅展开一级</DropdownItem>
            <DropdownItem name="expandThree">仅展开两级</DropdownItem>
            <DropdownItem name="expandAll">展开所有</DropdownItem>
          </DropdownMenu>
        </Dropdown>
        <i-switch v-model="strict" size="large" style="margin-left:5px">
          <span slot="open">级联</span>
          <span slot="close">单选</span>
        </i-switch>
      </Row>
      <Row type="flex" justify="start">
        <Col :md="8" :lg="8" :xl="6">
          <Alert show-icon>
            当前选择编辑：
            <span class="select-title">{{editTitle}}</span>
            <a class="select-clear" v-if="form.id" @click="cancelEdit">取消选择</a>
          </Alert>
          <Input
            v-model="searchKey"
            suffix="ios-search"
            @on-change="search"
            placeholder="输入菜单名搜索"
            clearable
          />
          <div class="tree-bar" :style="{maxHeight: maxHeight}">
            <Tree
              ref="tree"
              :data="data"
              show-checkbox
              :render="renderContent"
              @on-check-change="changeSelect"
              :check-strictly="!strict"
            ></Tree>
            <Spin size="large" fix v-if="loading"></Spin>
          </div>
        </Col>
        <Col :md="15" :lg="13" :xl="9" style="margin-left:10px;">
          <Form ref="form" :model="form" :label-width="110" :rules="formValidate">
            <FormItem label="类型" prop="type">
              <div v-show="form.type==1">
                <Icon type="ios-navigate-outline" size="16" style="margin-right:5px;"></Icon>
                <span>顶部菜单</span>
              </div>
              <div v-show="form.type==2">
                <Icon type="ios-list-box-outline" size="16" style="margin-right:5px;"></Icon>
                <span>页面菜单</span>
              </div>
              <div v-show="form.type==3">
                <Icon type="md-radio-button-on" size="16" style="margin-right:5px;"></Icon>
                <span>操作按钮</span>
              </div>
            </FormItem>
            <FormItem label="名称" prop="title" v-if="form.type==1||form.type==2">
              <Input v-model="form.title" />
            </FormItem>
            <FormItem label="名称" prop="title" v-if="form.type==3" class="block-tool">
              <Tooltip placement="right" content="操作按钮名称不得重复">
                <Input v-model="form.title" />
              </Tooltip>
            </FormItem>

            <FormItem label="按钮权限类型" prop="buttonType" v-if="form.type==3">
              <Select v-model="form.buttonType" placeholder="请选择或输入搜索" filterable clearable>
                <Option
                  v-for="(item, i) in dictPermissions"
                  :key="i"
                  :value="item.value"
                >{{item.title}}</Option>
              </Select>
            </FormItem>
            <FormItem label="路由英文名" prop="route_english" v-if="form.type==1" class="block-tool">
              <Tooltip placement="right" content="需唯一">
                <Input v-model="form.route_english" />
              </Tooltip>
            </FormItem>
            <FormItem label="路由英文名" prop="route_english" v-if="form.type==2" class="block-tool">
              <Tooltip placement="right" content="需唯一" transfer>
                <Input v-model="form.route_english" />
              </Tooltip>
            </FormItem>
<!--            <FormItem label="路径" prop="path" v-if="form.type==2">-->
<!--              <Input v-model="form.path"/>-->
<!--            </FormItem>-->
            <FormItem label="请求后台路径" prop="path" class="block-tool">
              <Tooltip
                placement="right"
                :max-width="230"
                transfer
                content="填写后台请求URL，后台将作权限拦截，若无可填写'无'或其他"
              >
                <Input v-model="form.path" />
              </Tooltip>
            </FormItem>
            <FormItem label="图标" prop="icon" v-if="form.type==1||form.type==2">
              <icon-choose v-model="form.icon"></icon-choose>
            </FormItem>
            <FormItem label="前端组件" prop="component" v-if="form.type==2">
              <Input v-model="form.component" />
            </FormItem>
            <FormItem label="第三方链接" prop="url" v-if="form.type==2&&form.level==2" class="block-tool">
              <Tooltip
                placement="right"
                content="前端组件需为 sys/monitor/monitor 时生效"
                max-width="300"
                transfer
              >
                <Input v-model="form.url" placeholder="http://" @on-change="changeEditUrl"/>
              </Tooltip>
            </FormItem>
            <FormItem label="排序值" prop="seq">
              <Tooltip trigger="hover" placement="right" content="值越小越靠前，支持小数">
                <InputNumber :max="1000" :min="0" v-model="form.seq"></InputNumber>
              </Tooltip>
            </FormItem>
            <FormItem label="是否启用" prop="status">
              <i-switch size="large" v-model="form.status" :true-value="0" :false-value="-1">
                <span slot="open">启用</span>
                <span slot="close">禁用</span>
              </i-switch>
            </FormItem>
            <Form-item>
              <Button
                style="margin-right:5px"
                @click="submitEdit"
                :loading="submitLoading"
                type="primary"
                icon="ios-create-outline"
              >修改并保存</Button>
              <Button @click="handleReset">重置</Button>
            </Form-item>
          </Form>
        </Col>
      </Row>
    </Card>

    <Modal
      draggable
      :title="modalTitle"
      v-model="menuModalVisible"
      :mask-closable="false"
      :width="500"
      :styles="{top: '30px'}"
    >
      <Form ref="formAdd" :model="formAdd" :label-width="100" :rules="formValidate">
        <div v-if="showParent">
          <FormItem label="上级节点：">{{parentTitle}}</FormItem>
        </div>
        <FormItem label="类型" prop="type">
          <div v-show="formAdd.type==1">
            <Icon type="ios-navigate-outline" size="16" style="margin-right:5px;"></Icon>
            <span>顶部菜单</span>
          </div>
          <div v-show="formAdd.type==2">
            <Icon type="ios-list-box-outline" size="16" style="margin-right:5px;"></Icon>
            <span>页面菜单</span>
          </div>
          <div v-show="formAdd.type==3">
            <Icon type="md-radio-button-on" size="16" style="margin-right:5px;"></Icon>
            <span>操作按钮</span>
          </div>
        </FormItem>
        <FormItem label="名称" prop="title" v-if="formAdd.type==1||formAdd.type==2">
          <Input v-model="formAdd.title" />
        </FormItem>
        <FormItem label="名称" prop="title" v-if="formAdd.type==3" class="block-tool">
          <Tooltip placement="right" content="操作按钮名称不得重复">
            <Input v-model="formAdd.title"/>
          </Tooltip>
        </FormItem>
        <FormItem label="按钮权限类型" prop="buttonType" v-if="formAdd.type==3">
          <Select v-model="formAdd.buttonType" placeholder="请选择或输入搜索" filterable clearable>
            <Option v-for="(item, i) in dictPermissions" :key="i" :value="item.value">{{item.title}}</Option>
          </Select>
        </FormItem>
        <FormItem label="英文名" prop="route_english" v-if="formAdd.type==1" class="block-tool">
          <Tooltip placement="right" content="需唯一">
            <Input v-model="formAdd.route_english" />
          </Tooltip>
        </FormItem>
        <FormItem label="路由英文名" prop="route_english" v-if="formAdd.type==2" class="block-tool">
          <Tooltip placement="right" content="需唯一">
            <Input v-model="formAdd.route_english"/>
          </Tooltip>
        </FormItem>
        <FormItem label="请求后台路径" prop="path">
          <Input v-model="formAdd.path" />
        </FormItem>
<!--                <FormItem label="请求后台路径" prop="path" v-if="formAdd.type==3" class="block-tool">-->
<!--                  <Tooltip-->
<!--                    placement="right"-->
<!--                    :max-width="230"-->
<!--                    transfer-->
<!--                    content="填写后台请求URL，后台将作权限拦截，若无可填写'无'或其他"-->
<!--                  >-->
<!--                    <Input v-model="formAdd.path"/>-->
<!--                  </Tooltip>-->
<!--                </FormItem>-->
        <FormItem label="图标" prop="icon" v-if="formAdd.type==1||formAdd.type==2">
          <icon-choose v-model="formAdd.icon"></icon-choose>
        </FormItem>
        <FormItem label="前端组件" prop="component" v-if="formAdd.type==2">
          <Input v-model="formAdd.component" />
        </FormItem>
        <FormItem label="第三方链接" prop="url" v-if="formAdd.type==2&&formAdd.level==2" class="block-tool">
          <Tooltip
            placement="right"
            content="前端组件需为 sys/monitor/monitor 时生效"
            max-width="300"
            transfer
          >
            <Input v-model="formAdd.url" placeholder="http://" @on-change="changeAddUrl"/>
          </Tooltip>
        </FormItem>
        <FormItem label="排序值" prop="seq">
          <Tooltip trigger="hover" placement="right" content="值越小越靠前，支持小数">
            <InputNumber :max="1000" :min="0" v-model="formAdd.seq"></InputNumber>
          </Tooltip>
        </FormItem>

        <FormItem label="是否启用" prop="status">
          <i-switch size="large" v-model="formAdd.status" :true-value="0" :false-value="-1">
            <span slot="open">启用</span>
            <span slot="close">禁用</span>
          </i-switch>
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="text" @click="menuModalVisible = false">取消</Button>
        <Button type="primary" :loading="submitLoading" @click="submitAdd">提交</Button>
      </div>
    </Modal>
  </div>
</template>

<script>
import {
  getListMenu,
  addEditMenu,
  deltMenu
} from '@/api/data'
import IconChoose from '@/view/my-components/qythink/icon-choose'
import util from '@/libs/util.js'
export default {
  name: 'menu_mange',
  components: {
    IconChoose
  },
  data () {
    return {
      loading: true,
      strict: true,
      maxHeight: '500px',
      expandLevel: 1,
      menuModalVisible: false,
      iconModalVisible: false,
      selectList: [],
      selectCount: 0,
      showParent: false,
      searchKey: '',
      parentTitle: '',
      editTitle: '',
      modalTitle: '',
      form: {
        id: '',
        title: '',
        name: '',
        icon: '',
        path: '',
        component: '',
        p_id: '',
        buttonType: '',
        type: 1,
        seq: 0,
        level: 1,
        status: 0,
        url: ''
      },
      formAdd: {
        buttonType: ''
      },
      formValidate: {
        title: [{ required: true, message: '名称不能为空', trigger: 'blur' }],
        route_english: [
          { required: true, message: '路由英文名不能为空', trigger: 'blur' }
        ],
        icon: [{ required: true, message: '图标不能为空', trigger: 'click' }],
        // path: [{ required: true, message: "路径不能为空", trigger: "blur" }],
        component: [
          { required: true, message: '前端组件不能为空', trigger: 'blur' }
        ],
        seq: [
          {
            required: true,
            type: 'number',
            message: '排序值不能为空',
            trigger: 'blur'
          }
        ]
      },
      submitLoading: false,
      data: [],
      dictPermissions: []
    }
  },
  methods: {
    init () {
      this.getListMenu()
    },
    renderContent (h, { root, node, data }) {
      let icon = ''
      if (data.level == 0) {
        icon = 'ios-navigate'
      } else if (data.level == 1) {
        icon = 'md-list-box'
      } else if (data.level == 2) {
        icon = 'md-list'
      } else if (data.level == 3) {
        icon = 'md-radio-button-on'
      } else {
        icon = 'md-radio-button-off'
      }
      return h(
        'span',
        {
          style: {
            display: 'inline-block',
            cursor: 'pointer'
          },
          on: {
            click: () => {
              this.selectTree(data)
            }
          }
        },
        [
          h('span', [
            h('Icon', {
              props: {
                type: icon,
                size: '16'
              },
              style: {
                'margin-right': '8px',
                'margin-bottom': '3px'
              }
            }),
            h(
              'span',
              {
                class: {
                  'ivu-tree-title': true,
                  'ivu-tree-title-selected': data.id == this.form.id
                }
              },
              data.title
            )
          ])
        ]
      )
    },
    handleDropdown (name) {
      if (name == 'expandOne') {
        this.expandLevel = 1
        this.getListMenu()
      } else if (name == 'expandTwo') {
        this.expandLevel = 2
        this.getListMenu()
      } else if (name == 'expandThree') {
        this.expandLevel = 3
        this.getListMenu()
      }
      if (name == 'expandAll') {
        this.expandLevel = 4
        this.getListMenu()
      } else if (name == 'refresh') {
        this.getListMenu()
      }
    },
    getListMenu () {
      this.loading = true
      getListMenu(this.searchForm).then(res => {
        this.loading = false
        if (res.status) {
          // 仅展开指定级数 默认后台已展开所有
          let expandLevel = this.expandLevel
          res.data.forEach(function (e) {
            if (expandLevel == 1) {
              if (e.level == 0) {
                e.expand = false
              }
              if (e.children && e.children.length > 0) {
                e.children.forEach(function (c) {
                  if (c.level == 1) {
                    c.expand = false
                  }
                  if (c.children && c.children.length > 0) {
                    c.children.forEach(function (b) {
                      if (b.level == 2) {
                        b.expand = false
                      }
                    })
                  }
                })
              }
            } else if (expandLevel == 2) {
              if (e.level == 0) {
                e.expand = true
              }
              if (e.children && e.children.length > 0) {
                e.children.forEach(function (c) {
                  if (c.level == 1) {
                    c.expand = false
                  }
                  if (c.children && c.children.length > 0) {
                    c.children.forEach(function (b) {
                      if (b.level == 2) {
                        b.expand = false
                      }
                    })
                  }
                })
              }
            } else if (expandLevel == 3) {
              if (e.level == 0) {
                e.expand = true
              }
              if (e.children && e.children.length > 0) {
                e.children.forEach(function (c) {
                  if (c.level == 1) {
                    c.expand = true
                  }
                  if (c.children && c.children.length > 0) {
                    c.children.forEach(function (b) {
                      if (b.level == 2) {
                        b.expand = false
                      }
                    })
                  }
                })
              }
            }
          })
          this.data = res.data
        }
      })
    },
    search () {
      if (this.searchKey) {
        this.loading = true
        getListMenu({ search_text: this.searchKey }).then(res => {
          this.loading = false
          if (res.status) {
            this.data = res.data
          }
        })
      } else {
        this.getListMenu()
      }
    },
    selectTree (v) {
      if (v && v.id != this.form.id) {
        // 转换null为""
        for (let attr in v) {
          if (v[attr] == null) {
            v[attr] = ''
          }
        }
        let str = JSON.stringify(v)
        let menu = JSON.parse(str)
        this.form = menu
        this.editTitle = menu.title
      } else {
        this.cancelEdit()
      }
    },
    cancelEdit () {
      let data = this.$refs.tree.getSelectedNodes()[0]
      if (data) {
        data.selected = false
      }
      this.$refs.form.resetFields()
      this.form.id = ''
      this.editTitle = ''
    },
    handleReset () {
      let type = this.form.type
      this.$refs.form.resetFields()
      this.form.icon = ''
      this.form.component = ''
      this.form.type = type
    },
    submitEdit () {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (!this.form.id) {
            this.$Message.warning('请先点击选择要修改的菜单节点')
            return
          }
          this.submitLoading = true
          if (this.form.seq == null) {
            this.form.seq = ''
          }
          if (this.form.buttonType == null) {
            this.form.buttonType = ''
          }
          if (this.form.type == 3) {
            this.form.name = ''
            this.form.icon = ''
            this.form.component = ''
          }
          this.form.op = 'edit'
          addEditMenu(this.form).then(res => {
            this.submitLoading = false
            if (res.data.status) {
              this.$Message.success('编辑成功')
              // 标记重新获取菜单数据
              // this.$store.commit("setAdded", false);
              // util.initRouter(this);
              this.init()
              this.menuModalVisible = false
            } else {
              this.$Message.error(res.data.msg)
            }
          })
        }
      })
    },
    submitAdd () {
      this.$refs.formAdd.validate(valid => {
        if (valid) {
          this.submitLoading = true
          if (this.formAdd.type == 3) {
            this.formAdd.route_english = ''
            this.formAdd.icon = ''
            this.formAdd.component = ''
          }
          addEditMenu(this.formAdd).then(res => {
            this.submitLoading = false
            if (res.data.status) {
              this.$Message.success('添加成功')
              // 标记重新获取菜单数据
              // this.$store.commit("setAdded", false);
              // util.initRouter(this);
              this.init()
              this.menuModalVisible = false
            } else {
              this.$Message.error(res.data.msg)
            }
          })
        }
      })
    },
    changeEditUrl (e) {
      let v = e.target.value
      if (v.indexOf('http') > -1) {
        this.form.component = 'sys/monitor/monitor'
      }
    },
    changeAddUrl (e) {
      let v = e.target.value
      if (v.indexOf('http') > -1) {
        this.formAdd.component = 'sys/monitor/monitor'
      }
    },
    addMenu () {
      if (!this.form.id) {
        this.$Message.warning('请先点击选择一个菜单权限节点')
        return
      }
      this.parentTitle = this.form.title
      this.modalTitle = '添加子节点(可拖动)'
      this.showParent = true
      let type = 2
      if (this.form.level == 1) {
        type = 2
      } else if (this.form.level == 2) {
        type = 3
      } else if (this.form.level == 3) {
        this.$Modal.warning({
          title: '抱歉，不能添加啦',
          content: '仅支持2级菜单目录'
        })
        return
      } else {
        type = 2
      }
      let component = ''
      this.formAdd = {
        icon: '',
        type: type,
        p_id: this.form.id,
        level: Number(this.form.level) + 1,
        seq: 0,
        buttonType: '',
        status: 0
      }
      if (this.form.level == 0) {
        this.formAdd.path = '/'
        this.formAdd.component = 'Main'
      }
      this.menuModalVisible = true
    },
    addRootMenu () {
      this.modalTitle = '添加顶部菜单(可拖动)'
      this.showParent = false
      this.formAdd = {
        p_id: 0,
        type: 1,
        level: 1,
        seq: 0,
        status: 0
      }
      this.menuModalVisible = true
    },
    changeSelect (v) {
      this.selectCount = v.length
      this.selectList = v
    },
    delAll () {
      if (this.selectCount <= 0) {
        this.$Message.warning('您还未勾选要删除的数据')
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
          deltMenu({ id: ids }).then(res => {
            this.$Modal.remove()
            if (res.status) {
              this.$Message.success('删除成功')
              // 标记重新获取菜单数据
              // this.$store.commit("setAdded", false);
              // util.initRouter(this);
              this.selectList = []
              this.selectCount = 0
              this.cancelEdit()
              this.init()
            }
          })
        }
      })
    }
  },
  mounted () {
    // 计算高度
    let height = document.documentElement.clientHeight
    this.maxHeight = Number(height - 287) + 'px'
    this.init()
  }
}
</script>
