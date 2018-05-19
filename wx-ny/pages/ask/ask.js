// pages/ask/ask.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    imgUrl: '../../images/portrait.jpg',
    name: '现代农业',
    title: '甜宝草莓苗种苗繁殖能力过低',
    type: '水果',
    species: '草莓',
    cont: '主要危害草莓苗匍匐茎、叶柄、叶片、托叶，染病后的明显特征是草莓株叶受害可造成局部病斑和全株萎蔫枯死',
    city: '北京市',
    area: '海淀区',
    date: '5分钟前',
    askArr: [
      {
        id: 1,
        imgUrl: '../../images/portrait.jpg',
        name: '宣景宏',
        post: '教授，博士生导师',
        good: '生产技术·果树',
        unit: '北京市农林科学院林果所',
        num: 1483
      }, {
        id: 2,
        imgUrl: '../../images/portrait.jpg',
        name: '周鹏飞',
        post: '教授，博士生导师',
        good: '政策咨询·农机',
        unit: '北京市农林科学院林果所',
        num: 1100
      }, {
        id: 3,
        imgUrl: '../../images/portrait.jpg',
        name: '李明',
        post: '教授，博士生导师',
        good: '生产技术·果树',
        unit: '北京市农林科学院林果所',
        num: 600
      }
    ],
    dateW: '',
    
    index: 0,
    areaArr: ['地区', '北京市', '上海市', '天津市', '南京市'],
    index2: 0,
    categoryArr: ['品类', '品类配料', '品类', '品类4', '品类5'],
    index3: 0,
    varietiesArr: ['品种', '品种果子', '品种香蕉', '品种4', '品种5'],
    index4: 0,
    goodArr: ['擅长', '擅长删除', '擅长添加', '擅长修改', '擅长5'],
  },
  goexperts: function(){
    wx.navigateTo({
      url: '../experts/experts',
    })
  },

  bindPickerChange: function (e) {
    this.setData({
      index: e.detail.value
    })
  },
  bindPicker2Change: function (e) {
    this.setData({
      index2: e.detail.value
    })
  },
  bindPicker3Change: function (e) {
    this.setData({
      index3: e.detail.value
    })
  },
  bindPicker4Change: function (e) {
    this.setData({
      index4: e.detail.value
    })
  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.ntabBar(app.globalData.tabBar);
    var that = this;
    that.setData({
      dateW: 55
    });
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