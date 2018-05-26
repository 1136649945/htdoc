var util = require('../../utils/util.js')
var app = getApp()
Page({
  data: {
    verimg: ""
  },
  onLoad() {
    var that = this;
    util.sendrequest("/app.php/Public/session", null,
      function (data) {
        app.globalData.session= data.session;
        that.refreVeify();
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
    var that = this;
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
        if (data.status == -2) {
          util.showtip(data.info, 2);
          return;
        }
        if (data.status<0){
          util.showtip(data.info,2);
          that.refreVeify();
        }
        if (data.status) {
          app.globalData.userInfo = data;
          util.showtip(data.info, 1);
          app.globalData.tabBar = JSON.parse(data.menu);
          wx.redirectTo({
            url: '/pages/learn/learn',
          });
        }
      }, function (e) {
        util.showtip("服务器请求异常", 2);
      }, app.globalData.session);
  },
  refreVeify: function (e) {
    this.setData({ verimg: util.domain + "/app.php/Public/verify?session_id=" + app.globalData.session +"&random=" + Math.random() });
  }
})
