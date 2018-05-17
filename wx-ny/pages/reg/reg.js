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
    var username = e.detail.value.username;
    var password = e.detail.value.password;
    var verify = e.detail.value.verify;
    var pcode = e.detail.value.pcode;
    if (util.strempty(username)) {
      util.showtip("请输入用户名", 2);
      return;
    }
    var email = new RegExp("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+", "g");
    var mobile = new RegExp("0?(13|14|15|16|17|18|19)[0-9]{9}", "g");
    var user = new RegExp("^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$", "g");
    var chian = new RegExp("[\\u4E00-\\u9FFF]+", "g");
    if (chian.exec(username) || username.length>32 || (!email.exec(username) && !mobile.exec(username) && !user.exec(username))) {
      util.showtip("用户名不合法", 2);
      return;
    }
    if (util.strempty(password)) {
      util.showtip("请输入密码", 2);
      return;
    }
    if (password.length < 6 || password.length>16) {
      util.showtip("密码不合法", 2);
      return;
    }
    if (util.strempty(verify)) {
      util.showtip("请输入验证码", 2);
      return;
    }
    if (!util.strempty(pcode)) {
      if (!user.exec(pcode)){
        util.showtip("非法的邀请码", 2);
        return;
      }
    }
    util.sendrequest("/app.php/Public/regist", e.detail.value,
      function (data) {
        console.log(data);
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