// pages/reply/reply.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    replayImg: [
      '../../images/ask01.jpg',
      '../../images/ask02.jpg',
      '../../images/ask03.jpg',
      '../../images/ask04.jpg'
    ],
    total: 200,
    num: 0,
    voiceArr: [
      {
        date: '5分钟前',
        num: 12,
        total: 60
      },{
        date: '10分钟前',
        num: 10,
        total: 60
      }
    ]
  },
  bindTextNumInput: function(e){
    var that = this;
    var val = e.detail.value;
    var valLen = val.length;
    that.setData({
      num: valLen
    });
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