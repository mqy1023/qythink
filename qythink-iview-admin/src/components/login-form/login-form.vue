<template>
  <Form ref="loginForm" :model="form" :rules="rules" @keydown.enter.native="handleSubmit">
    <FormItem prop="userName">
      <Input v-model="form.userName" size="large" placeholder="请输入登录账号">
        <Icon type="md-person" slot="prefix" />
      </Input>
    </FormItem>
    <FormItem prop="password" style="margin-bottom: 30px;">
      <Input :type="showPwd ? 'text':'password'" size="large" v-model="form.password" :icon="showPwd ? 'md-eye-off':'md-eye'" placeholder="请输入密码" @on-click="showPwd = !showPwd">
        <Icon type="md-key" slot="prefix" />
      </Input>
    </FormItem>
    <FormItem>
      <Button @click="handleSubmit" type="primary" size="large" long :loading="loading">登录</Button>
    </FormItem>
  </Form>
</template>
<script>
export default {
  name: 'LoginForm',
  props: {
    userNameRules: {
      type: Array,
      default: () => {
        return [
          { required: true, message: '账号不能为空', trigger: 'blur' }
        ]
      }
    },
    passwordRules: {
      type: Array,
      default: () => {
        return [
          { required: true, message: '密码不能为空', trigger: 'blur' }
        ]
      }
    }
  },
  data () {
    return {
      loading: false,
      showPwd: false,
      form: {
        userName: 'test',
        password: '123456'
      }
    }
  },
  computed: {
    rules () {
      return {
        userName: this.userNameRules,
        password: this.passwordRules
      }
    }
  },
  methods: {
    handleSubmit () {
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          this.$emit('on-success-valid', {
            userName: this.form.userName,
            password: this.form.password
          })
        }
      })
    }
  }
}
</script>
