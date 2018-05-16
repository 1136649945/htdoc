var util = require('../../utils/util.js')
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    verimg:""
  },
  gologin: function(){
    wx.navigateBack(1);
  }, 
  formSubmit: function (e) {

    console.log("提交表单");
    console.log(e);
    var username = e.detail.value.username;
    var password = e.detail.value.password;
    var verify = e.detail.value.verify;
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
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({ verimg: util.domain +"/app.php/Public/verify?random="+Math.random()});
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})