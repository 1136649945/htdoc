var util = require('../../utils/util.js')
var app = getApp()
Page({
  data: {
   
  },
  onLoad() {
    var that = this;
    util.sendrequest("/app.php/Public/randCode", null,
      function (data) {
        app.globalData.verify = data.verify;
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
    
    console.log("提交表单");
    console.log(e);
    var username = e.detail.value.username;
    var password = e.detail.value.password;
    if (username.length < 1) {
      util.alertViewWithCancel("提示", "请输入用户名", function () {
        console.log("点击确定按钮");
      }, "true");
      return;
    }
    if (password.length < 1) {
      util.alertView("提示", "请输入密码", function () {
        console.log("点击确定按钮");
      });
      return;
    }
    util.alertView("提示", "登录成功", function () {
      //跳转到农业专家系统页面
      wx.switchTab({
        url: '../system/system',
      })
    })
    

    if (!this.data.end) {
      return;
    }
    var form = e.detail.value;
    if (!(form && form.username)) {
      wx.showToast({
        title: "用户名不能为空",
        icon: "fail",
        duration: 1000
      });
      return;
    }
    if (!(form && form.password)) {
      wx.showToast({
        title: "密码不能为空",
        icon: "warn",
        //image: 'warn',自定义图标的本地路径，image 的优先级高于 icon
        duration: 1000
      });
      return;
    }
    util.sendrequest("/admin.php/Weixin/applogin", form,
      function (data) {
        console.log(form);
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
