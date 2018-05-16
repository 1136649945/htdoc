var util = require('../../utils/util.js')
var app = getApp()
Page({
  data: {
    verimg: ""
  },
  onLoad() {
    var that = this;
    that.setData({ verimg: util.domain + "/app.php/Public/verify?random=" + Math.random() });
    util.sendrequest("/app.php/Public/session", null,
      function (data) {
        app.globalData.session = data.session;
      }, function (e) {
        wx.showToast({
          title: "服务器连接异常",
          icon: "fail",
          duration: 2000
        });
      });
  },

  onPullDownRefresh() {
    wx.stopPullDownRefresh()
  },

  formSubmit: function (e) {
    var username = e.detail.value.username;
    var password = e.detail.value.password;
    var verify = e.detail.value.verify;
    if (util.strempty(username)) {
      util.showtip("请输入用户名",2);
      return;
    }
    if (util.strempty(password)) {
      util.showtip("请输入密码", 2);
      return;
    }
    if (util.strempty(verify)) {
      util.showtip("请输入验证码", 2);
      return;
    }
    util.sendrequest("/app.php/Public/login", { username: username, password: password, verify: verify},
      function (data) {
        if (data['status']) {
          if ((form && form.rember)) {
            wx.setStorageSync(
              "loginfo", {
                "username": form.username,
                "password": form.password,
                "rember": form.rember
              });
          } else {
            wx.setStorageSync(
              "loginfo",
              {
                "username": null,
                "password": null,
                "rember": false
              });
          }
        } else {
          wx.showToast({
            title: "用户名或密码错误",
            icon: "warn",
            //image: 'warn',自定义图标的本地路径，image 的优先级高于 icon
            duration: 1000
          });
        }
      }, function (e) {
        console.log(e);
      });
  }
})
