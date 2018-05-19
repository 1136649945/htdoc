// pages/learn/learn.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    learnNav: [
      { id: 0, navName: "农业百科"},
      { id: 1, navName: "知识库" },
      { id: 2, navName: "动态新闻" },
      { id: 3, navName: "政策法规" }
    ],
    currentItem: "0",
    learnArr: [
      {
        id:1,
        imgUrl:"../../images/learn01.jpg",
        title:"我省春季玉米生产中应注意的问题",
        date: "2018-05-01",
        cont:"李老师：春季低温干旱，年活动积温不足是影响我 省玉米生产的重要限制因素....."
      }, {
        id: 2,
        imgUrl: "../../images/learn02.jpg",
        title: "家庭养花植株死亡的常见原因",
        date: "2018-05-01",
        cont: "刘老师：很多情况下病虫害并不是造成植株死亡的主要原因，栽培管理不当....."
      }, {
        id: 3,
        imgUrl: "../../images/learn03.jpg",
        title: "室内常见盆栽绿化植物——吊兰",
        date: "2018-05-01",
        cont: "李老师：春季低温干旱，年活动积温不足是影响我省玉米生产的重要限制因素....."
      }, {
        id: 4,
        imgUrl: "../../images/learn04.jpg",
        title: "节能日光温室绿色食品蔬菜专家系统",
        date: "2018-05-01",
        cont: "李老师：本系统介绍了绿色蔬菜生产标准及技术规程、申请绿标的程序....."
      }, {
        id: 5,
        imgUrl: "../../images/learn05.jpg",
        title: "马铃薯“双保险”让农民吃下“定心丸”",
        date: "2018-05-01",
        cont: "李老师：本地秋季的马铃薯个都不大，而同期的内蒙产马铃薯，品相都较好....."
      }
    ]
  },

  navLearn: function (options) {
    var that = this;
    var id = options.currentTarget.dataset.id;
    console.log(id);
    //设置当前样式
    that.setData({
      currentItem: id
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.ntabBar(app.globalData.tabBar);
    var that = this;
    //设置当前样式
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