// pages/problem/problem.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
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
    proArr: [
      {
        id: 0,
        imgUrl: '../../images/problem01.jpg',
        title: '请问老师韭菜什么时候种最好',
        sign: 1,
        addr: '北京诺亚庄园',
        date: '2018-04-25'
      },
      {
        id: 1,
        imgUrl: '../../images/problem02.jpg',
        title: '当前处于7月末，水稻已经孕穗，需要在何时追施穗',
        sign: 0,
        addr: '',
        date: '2018-04-25'
      },
      {
        id: 2,
        imgUrl: '../../images/problem03.jpg',
        title: '母羊消瘦、腹泻，喜啃土，怎么治',
        sign: 0,
        addr: '',
        date: '2018-04-25'
      },
      {
        id: 3,
        imgUrl: '../../images/problem01.jpg',
        title: '请问老师韭菜什么时候种最好',
        sign: 0,
        addr: '',
        date: '2018-04-25'
      },
      {
        id: 4,
        imgUrl: '../../images/problem02.jpg',
        title: '当前处于7月末，水稻已经孕穗，需要在何时追施穗',
        sign: 0,
        addr: '',
        date: '2018-04-25'
      }
    ]
  },
  swiperChange: function (e) {
    this.setData({
      swiperCurrent: e.detail.current
    })
  },
  goreply: function(){
    wx.navigateTo({
      url: '../replay/replay',
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
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