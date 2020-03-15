<style lang="less">
  @import './login.less';
</style>

<template>
  <div class="login">
    <div class="ivu-row-flex ivu-row-flex-middle ivu-row-flex-center" style="padding-top:10%;">
      <div class="login-con">
        <Card icon="log-in" :bordered="true" dis-hover style="padding:16px;">
          <div class="form-img">
            <img src="@/assets/images/logo-min.png">
          </div>
          <div class="form-con">
            <login-form ref="loginForm" @on-success-valid="handleSubmit"></login-form>
            <div class="form-tip">
              <div class="login-tip">其他方式登录
                <div class="login-icon">
                  <svg t="1562827806343" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2309" width="20" height="20"><path d="M794.5 721.9c-9.7 56.1-35.7 106.6-73.3 146.4 40.8 18.9 77.6 47.7 77.6 90.3H225c0-42.6 36.9-71.3 77.6-90.3-37.6-39.8-63.7-90.3-73.3-146.4-43.1 63.2-106.8 94.5-106.8 94.5 0-164 102.5-386 102.5-386v-81.2c0-157.1 128.5-284.4 286.9-284.4 158.5 0 286.9 127.3 286.9 284.4v81.2s102.4 222 102.4 386c0.1 0-63.7-31.3-106.7-94.5z m0 0" p-id="2310"></path></svg>
                </div>
                <div class="login-icon">
                  <svg t="1562827166553" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1969" width="20" height="20"><path d="M692.699238 336.887706c11.619123 0 23.117414 0.831898 34.517504 2.108006C696.19497 194.549965 541.769728 87.227597 365.488742 87.227597 168.405197 87.227597 6.977229 221.535539 6.977229 392.107418c0 98.493235 53.707366 179.306803 143.459123 242.033357l-35.857101 107.840102 125.329408-62.837146c44.84311 8.861798 80.827085 18.002022 125.578138 18.002022 11.250688 0 22.40215-0.561766 33.469645-1.428582-7.001702-23.95351-11.06647-49.054208-11.06647-75.120947C387.891917 463.976243 522.3936 336.887706 692.699238 336.887706zM497.405542 232.406118c30.611456 0 55.425536 24.815206 55.425536 55.427379s-24.814182 55.426355-55.425536 55.426355c-30.613504 0-55.427584-24.815206-55.427584-55.426355S466.794086 232.406118 497.405542 232.406118zM246.567526 344.377344c-30.611456 0-55.427584-24.815206-55.427584-55.426355 0-30.611149 24.81623-55.426355 55.427584-55.426355 30.613504 0 55.428608 24.815206 55.428608 55.426355C301.996134 319.561114 277.18103 344.377344 246.567526 344.377344zM1017.379942 617.455821c0-143.330406-143.423283-260.165325-304.515686-260.165325-170.58089 0-304.924979 116.834918-304.924979 260.165325 0 143.57801 134.34409 260.158157 304.924979 260.158157 35.697459 0 71.712154-9.0112 107.569254-17.99895l98.340659 53.861683-26.969293-89.592525C963.769856 769.897677 1017.379942 698.309222 1017.379942 617.455821zM619.184947 577.275699c-21.799322 0-39.469466-17.673523-39.469466-39.471002 0-21.799526 17.671168-39.468954 39.469466-39.468954s39.469466 17.670451 39.469466 39.468954C658.656563 559.6032 640.983347 577.275699 619.184947 577.275699zM816.270541 579.514675c-21.80137 0-39.47049-17.672499-39.47049-39.468954 0-21.80055 17.670144-39.468954 39.47049-39.468954 21.798298 0 39.469466 17.669427 39.469466 39.468954C855.741133 561.842176 838.068941 579.514675 816.270541 579.514675z" p-id="1970" ></path></svg>
                </div>
              </div>
               <router-link to="/register"  class="register-url">
                 <a>注册账号</a>&nbsp;&nbsp;
                 <a>忘记密码</a>
              </router-link>
            </div>
          </div>
        </Card>
      </div>
      <div class="foot">
        <span>XXX科技有限公司</span>
        <span style="margin:0 12px;">© 2017-2020</span>
        <a href="http://beian.miit.gov.cn/">粤ICP备18005917号-1</a>
      </div>
    </div>
  </div>
</template>

<script>
import LoginForm from '_c/login-form'
import { mapActions } from 'vuex'
// import { login,getConfig } from '@/api/user'
import { login } from '@/api/user'
export default {
  components: {
    LoginForm
  },
  data () {
    return {
      // selectModel:false,
      loading: false,
      login_logo: '',
      com_name: '',
      record_num: ''
    }
  },
  methods: {
    ...mapActions([
      'handleLogin',
      'getUserInfo'
    ]),
    // toRegister(){
    //   const route = {
    //     name: 'register',
    //   }
    //   this.$router.push(route)
    // },
    handleSubmit ({ userName, password }) {
      this.$refs.loginForm.loading = true
      // let Base64 = require("js-base64").Base64
      // userName = Base64.encode(userName)
      // password = Base64.encode(password)
      login({ userName, password }).then(res => {
        const data = res.data
        if (data.status === true) {
          // this.handleLogin({ userName, password }).then(res => {
          this.handleLogin({ data }).then(res => {
            this.getUserInfo().then(res => {
              var latestUrl = localStorage.getItem('latestUrl')
              if (latestUrl) {
                this.$router.go(-1)
              }
              // else if(this.$store.state.user.user.group_type == 'super_admin'){
              //   this.$router.push({
              //     name: 'storehouseSelect'
              //   })
              // }
              else {
                this.$router.push({
                  name: this.$config.homeName
                })
              }
              // this.$router.push({
              //   name: this.$config.homeName
              // })
              this.$Message.success('登录成功')
              this.$refs.loginForm.loading = false
            })
          })
        } else {
          this.$Message.error(data.msg)
          this.$refs.loginForm.loading = false
        }
      }).catch(err => {
        reject(err)
      })

      // 向后台发账号密码并获取用户信息
    },
    getConfig () {
      var host = window.location.host
      getConfig({ host: host }).then(res => {
        if (res.data == null) {
          localStorage.setItem('webtitle', '')
          localStorage.setItem('webico', '')
        } else {
          this.login_logo = res.data.login_logo
          this.com_name = res.data.com_name
          this.record_num = res.data.record_num
          localStorage.setItem('webtitle', res.data.title)
          localStorage.setItem('webico', this.COMMON.baseUrl + res.data.icon)
        }
      })
    }
  },
  mounted () {
    // this.getConfig()
  }
}
</script>

<style>

</style>
