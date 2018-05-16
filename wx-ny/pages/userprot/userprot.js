var app = getApp();
var util = require('../../utils/util.js');
var WxParse = require('../wxParse/wxParse.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: "",
    article: "",
    date :""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    util.sendrequest("/index.php/Public/userProtocol", null,
      function (data) {
        that.setData({
          title : data["title"],
          article : data["content"],
          date : data["create_time"]
        });
        WxParse.wxParse('article', 'html', that.data.article, that, 5);
      }, function (e) {
        wx.showToast({
          title: "服务器连接异常",
          icon: "fail",
          duration: 2000
        });
      }, app.globalData.session);
    
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