// pages/reply/reply.js
var util = require('../../utils/util.js');
var app = getApp();
const audioManager = wx.getBackgroundAudioManager();
const recorderManager = wx.getRecorderManager()

recorderManager.onFrameRecorded((res) => {
  console.log('frameBuffer.byteLength', res.byteLength)
})

const options = {
  duration: 60000,//指定录音的时长，单位 ms
  sampleRate: 16000,//采样率
  numberOfChannels: 1,//录音通道数
  encodeBitRate: 96000,//编码码率
  format: 'mp3',//音频格式，有效值 aac/mp3
  frameSize: 50,//指定帧大小，单位 KB
}
Page({
  data: {
    id:null,
    total:200,
    num:0,
    nickname:'',
    photo:'',
    region:'',
    title: '',
    content:'',
    replayImg: null,
    voiceArr: null,
    imageArr: [],
    audioArr: []
  },
  uploadImg: function (e) {
    var that = this;
    wx.chooseImage({
      count: 4, // 默认9
      success: function (res) {
        var tempFilePaths = res.tempFilePaths
        if (tempFilePaths.length > 0) {
          for (var index in res.tempFilePaths) {
            util.uplodaFile(tempFilePaths[index], function (data) {
              if (data.status) {
                that.data.imageArr.push(data.path);
                that.setData({ imageArr: that.data.imageArr });
              }
            }, function (e) {
              wx.showToast({
                title: "服务器连接异常",
                icon: "fail",
                duration: 2000
              });
            }, app.globalData.session);
          }
        }
      }
    })
  },
  bindTextNumInput: function(e){
    var that = this;
    var val = e.detail.value;
    var valLen = val.length;
    that.setData({
      num: valLen
    });
  }, 
  viewImg:function(e){
    wx.previewImage({
      urls: [e.target.dataset.src],
    });
  }, 
  play: function (e) {
    audioManager.src = e.target.dataset.src;
    var id = e.target.id;
    var arr = this.data.voiceArr;
    for (var i = 0; i < arr.length; i++) {
      if(i==id){
        arr[i].stop = false;
        arr[i].play = true;
      }else{
        arr[i].stop = true;
        arr[i].play = false;
      }
    }
    this.setData({ voiceArr: arr });
    audioManager.onStop((res) => {
      for (var i = 0; i < arr.length; i++) {
        arr[i].stop = true;
        arr[i].play = false;
      }
      this.setData({ voiceArr: arr });
    });
  }, 
  stop: function (e) {
    audioManager.stop();
  },
  uplay: function (e) {
    audioManager.src = e.target.dataset.src;
    var id = e.target.id;
    var arr = this.data.audioArr;
    for (var i = 0; i < arr.length; i++) {
      if (i == id) {
        arr[i].stop = false;
        arr[i].play = true;
      } else {
        arr[i].stop = true;
        arr[i].play = false;
      }
    }
    this.setData({ audioArr: arr });
    audioManager.onStop((res) => {
      for (var i = 0; i < arr.length; i++) {
        arr[i].stop = true;
        arr[i].play = false;
      }
      this.setData({ audioArr: arr });
    });
  },
  ustop: function (e) {
    audioManager.stop();
    var arr = this.data.audioArr;
    for (var i = 0; i < arr.length; i++) {
      arr[i].stop = true;
      arr[i].play = false;
    }
    this.setData({ audioArr: arr });
  }, 
  delAudio:function(e){
    var arr = this.data.audioArr;
    var id = e.target.dataset.id;
    var newArr = [];
    if (arr) {
      for (var i = 0; i < arr.length; i++) {
        if (id != i) {
          newArr.push(arr[i]);
        }
      }
    }
    this.setData({ audioArr: newArr });
  }, 
  delImg:function(e){
    var arr = this.data.imageArr;
    var id = e.target.dataset.id;
    var newArr = [];
    if(arr){
      for (var i = 0; i < arr.length; i++) {
       if(id!=i){
         newArr.push(arr[i]);
       }
      }
    }
    this.setData({ imageArr: newArr});
  },
  startAudio: function (e) {
    audioManager.stop();
    var that = this;
    recorderManager.start(options);
    recorderManager.onStop((res) => {
      if (res.tempFilePath) {
        util.uplodaAudio(res.tempFilePath, function (data) {
          if (data.status) {
            that.data.audioArr.push(data.data);
            that.setData({ audioArr:that.data.audioArr})
          }
        }, function (e) {
          wx.showToast({
            title: "服务器连接异常",
            icon: "fail",
            duration: 2000
          });
        }, app.globalData.session);
      }
    })
  },
  stopAudio:function (e) {
    recorderManager.stop();
  },
  formSubmit: function (e) {
    if (util.strempty(e.detail.value.cont) && this.data.imageArr.length == 0 && this.data.audioArr.length==0){
      util.showtip("请输入提交内容", 2);
      return;
    }
    util.sendrequest("/app.php/Problemanswer/answer", { id: this.data.id, cont: e.detail.value.cont, img: JSON.stringify(this.data.imageArr), audio: JSON.stringify(this.data.audioArr)},
      function (data) {
        if (data.status){
          util.showtip("提交成功", 1);
          wx.reLaunch({
            url: '../../pages/system/system',
          });
        }
      }, function (e) {
        console.log(e);
        util.showtip("服务器请求异常", 2);
      }, app.globalData.session);
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    this.data.id = options.id;
    util.sendrequest("/app.php/Problem/detail", { id: options.id},
      function (data) {
        that.setData({ 
          nickname: data.nickname, 
          photo: data.photo, 
          region: data.region,
          title:data.data.title,
          content: data.data.content,
          replayImg: data.data._img,
          voiceArr: data.data._audio
        });
      }, function (e) {
        util.showtip("服务器请求异常", 2);
      }, app.globalData.session);
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