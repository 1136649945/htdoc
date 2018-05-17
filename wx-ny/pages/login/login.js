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
        wx.setStorageSync('session', data.session);
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
    util.sendrequest("/app.php/Public/login", e.detail.value,
      function (data) {
        console.log(data);
      }, function (e) {
        console.log(e);
      });
  }
})
