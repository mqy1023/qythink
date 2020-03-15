<template>
  <Tabs value="name1">
    <TabPane label="网站配置" name="name1">
      <Form ref="form1" :model="form1" :label-width="80">
        <Row>
          <Col span="8">
            <FormItem label="网站名称" prop="title">
              <Input v-model="form1.website" clearable style="width:100%"/>
            </FormItem>
          </Col>
          <Col span="8">
            <FormItem label="网站网址" prop="author">
              <Input v-model="form1.http" clearable style="width:100%"/>
            </FormItem>
          </Col>
          <Col span="8">
            <FormItem label="备案号" prop="label">
              <Input v-model="form1.icp" clearable style="width:100%"/>
            </FormItem>
          </Col>
        </Row>
        <Row>
          <Col span="8">
            <FormItem label="QQ" prop="label">
              <Input v-model="form1.qq" clearable style="width:100%"/>
            </FormItem>
          </Col>
          <Col span="8">
            <FormItem label="E-Mail" prop="label">
              <Input v-model="form1.email" clearable style="width:100%"/>
            </FormItem>
          </Col>
          <Col span="8">
            <FormItem label="手机" prop="label">
              <Input v-model="form1.phone" clearable style="width:100%"/>
            </FormItem>
          </Col>
        </Row>
        <Row>
          <Col span="8">
            <FormItem label="公司名称" prop="label">
              <Input v-model="form1.com_name" clearable style="width:100%"/>
            </FormItem>
          </Col>
          <Col span="8">
            <FormItem label="公司地址" prop="label">
              <Input v-model="form1.com_address" clearable style="width:100%"/>
            </FormItem>
          </Col>
        </Row>
        <FormItem label="网站关键字" prop="label">
          <Input v-model="form1.keyword" type="textarea" :autosize="{minRows: 3,maxRows: 6}" style="width:100%" />
        </FormItem>
        <FormItem label="网站描述" prop="label">
          <Input v-model="form1.describe" type="textarea" :autosize="{minRows: 3,maxRows: 6}" style="width:100%" />
        </FormItem>
        <FormItem label="公司简介" prop="label">
          <Input v-model="form1.com_intro" type="textarea" :autosize="{minRows: 3,maxRows: 6}" style="width:100%" />
        </FormItem>

        <div style="text-align: center">
          <Button type="primary" @click="handleSubmit(1)">保存并启用</Button>
        </div>
      </Form>
    </TabPane>
    <TabPane label="公众号配置" name="name2">
      <Form ref="form2" :model="form2" :label-width="120">
        <FormItem label="公众号名称" prop="wx_name">
          <Input v-model="form2.wx_name" clearable style="width:350px"/>
        </FormItem>

        <Form-item label="公众号二维码" prop="avatar">
          <upload-pic-input v-model="form2.wx_img" style="width: 350px;"></upload-pic-input>
        </Form-item>

        <FormItem label="微信号" prop="number">
          <Input v-model="form2.number" clearable style="width:350px"/>
        </FormItem>
        <FormItem label="AppId" prop="appid">
          <Input v-model="form2.appid" clearable style="width:350px"/>
        </FormItem>
        <FormItem label="AppSecret" prop="appsecret">
          <Input v-model="form2.appsecret" clearable style="width:350px"/>
        </FormItem>
        <FormItem label="token" prop="token">
          <Input v-model="form2.token" disabled style="width:350px"/>
        </FormItem>
        <FormItem label="获取token时间" prop="token_time">
          <Input v-model="form2.token_time" disabled style="width:350px"/>
        </FormItem>
        <FormItem label="微信验证TOKEN" prop="token_url">
          <Input v-model="form2.token_url" clearable style="width:350px"/>
        </FormItem>
        <FormItem label="EncodingAESKey" prop="author">
          <Input v-model="form2.encoding_aeskey" clearable placeholder="如公众号中消息加解密方式为安全模式，此项必填" style="width:350px"/>
        </FormItem>

          <Button type="primary" @click="handleSubmit(2)" style="margin-left: 180px;">保存并启用</Button>
      </Form>
    </TabPane>
    <TabPane label="文件存储配置" name="name3">
      <Form ref="form3" :model="form3" :label-width="120">
        <FormItem label="服务提供商" prop="provider">
          <Input v-model="form3.provider" clearable style="width:350px"/>
        </FormItem>

        <Form-item label="accessKey" prop="accesskey">
          <Input v-model="form3.accesskey" clearable style="width:350px"/>
        </Form-item>

        <FormItem label="secretkey" prop="secretkey">
          <Input v-model="form3.secretkey" clearable style="width:350px"/>
        </FormItem>
        <FormItem label="endpoint域名" prop="endpoint">
          <Input v-model="form3.endpoint" clearable style="width:350px"/>
        </FormItem>
        <Button type="primary" @click="handleSubmit(3)" style="margin-left: 180px;">保存并启用</Button>
      </Form>
    </TabPane>
  </Tabs>
</template>

<script>
import { editConfig, getSysConfig } from '@/api/data'
import uploadPicInput from '../../my-components/qythink/upload-pic-input'
export default {
  name: 'setting',
  components: { uploadPicInput },
  data () {
    return {
      form1: {
        website: '',
        http: '',
        icp: '',
        qq: '',
        email: '',
        phone: '',
        com_name: '',
        com_address: '',
        keyword: '',
        describe: '',
        com_intro: ''
      },
      form2: {
        wx_name: '',
        number: '',
        appid: '',
        appsecret: '',
        token_url: '',
        encoding_aeskey: '',
        wx_img: ''
      },
      form3: {
        provider: '',
        accesskey: '',
        secretkey: '',
        endpoint: ''
      }
    }
  },
  methods: {
    init () {
      this.getDataList()
    },
    getDataList () {
      // 带多条件搜索参数获取表单数据 请自行修改接口
      getSysConfig().then(res => {
        this.form1 = res.data.one
        this.form2 = res.data.two
        this.form3 = res.data.three
      })
    },
    handleSubmit (type) {
      let form
      if (type == 1) {
        this.form1.type = 1
        this.form = this.form1
      }
      if (type == 2) {
        this.form2.type = 2
        this.form = this.form2
      }
      if (type == 3) {
        this.form3.type = 3
        this.form = this.form3
      }

      editConfig(this.form).then(res => {
        if (res.data.status) {
          this.$Message.success('修改成功')
        } else {
          this.$Message.error(res.data.msg)
        }
      })
    }
  },
  mounted () {
    this.init()
  }
}
</script>

<style scoped>

</style>
