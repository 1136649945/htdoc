// pages/system/system.js
var util = require('../../utils/util.js')
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navArr: [
      {
        cont: '我的回答',
        navUrl: '/pages/reply/reply',
        imgSrc: '../../images/_icon.png'
      }, {
        cont: '我的积分',
        navUrl: '/pages/integral/integral',
        imgSrc: '../../images/_icon2.png'
      }, {
        cont: '我的文章',
        navUrl: '/pages/article/article',
        imgSrc: '../../images/_icon3.png'
      }, {
        cont: '推荐企业',
        navUrl: '/pages/company/company',
        imgSrc: '../../images/_icon4.png'
      }
    ],
    imgUrls: [
      '../../images/banner.jpg',
      '../../images/banner.jpg',
      '../../images/banner.jpg'
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    swiperCurrent: 0,
    proArr: null
  },
  goproblem: function(){
    wx.switchTab({
      url: '../problem/problem',
    })
  },
  swiperChange: function (e) {
    this.setData({
      swiperCurrent: e.detail.current
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    app.ntabBar(app.globalData.tabBar);
    util.sendrequest("/app.php/Problem/toBeAnswered",null,
      function (data) {
        that.setData({ proArr: data});
      }, function (e) {
        util.showtip("服务器连接异常",2);
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