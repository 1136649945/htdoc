const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

function strempty(str) {
  if (str != null && str.trim().length>0){
    return false;
  }else{
    return true;
  }
}
//提示
function showtip(title, icon,duration) {
  wx.showToast({
    title: title,
    image: (icon == 1) ? '../../images/iconok.png' :'../../images/iconfail.png',
    duration: duration ? duration : 2000
  });
}
//loading提示
function showLoading(title = "请稍后", duration = 5000) {
  wx.showToast({
    title: title,
    icon: 'loading',
    duration: (duration <= 0) ? 5000 : duration
  });
}
//隐藏提示框
function hideToast() {
  wx.hideToast();
}

//显示带取消按钮的消息提示框
function alertViewWithCancel(title = "提示", content = "消息提示", confirm, showCancel = "true") {
  wx.showModal({
    title: title,
    content: content,
    showCancel: showCancel,
    success: function (res) {
      if (res.confirm) {
        confirm();
      }
    }
  });
}
//显示不取消按钮的消息提示框
function alertView(title = "提示", content = "消息提示", confirm) {
  alertViewWithCancel(title, content, confirm, false);
}
//微信支付
function pay(){
  wx.requestPayment({
    'timeStamp': payResult.timeStamp.toString(),
    'nonceStr': payResult.nonceStr,
    'package': payResult.package,
    'signType': payResult.signType,
    'paySign': payResult.paySign,
    'success': function (succ) {
      success && success(succ);
    },
    'fail': function (err) {
      fail && fail(err);
    },
    'complete': function (comp) {

    }
  })   
}
//微信发送请求
function sendrequest(url, data, scallback, fcallback, session) {
  if (session != null && session != "") {
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'PHPSESSID=' + session
    };
  } else {
    var header = { 'content-type': 'application/x-www-form-urlencoded' };
  }
  wx.request({
    url: this.domain + url,
    data: data,
    method: 'POST',
    header: header,
    success: function (res) {
      scallback(res.data);
    },
    fail: function (e) {
      fcallback(e);
    }
  })
}
//上传文件
function uplodaFile(path, scallback, fcallback, session) {
  if (session != null && session != "") {
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
      'Cookie': 'PHPSESSID=' + session
    };
  } else {
    var header = { 'content-type': 'application/x-www-form-urlencoded' };
  }
  var that = this;
  wx.uploadFile({
    header: header,
    url: this.domain + '/app.php/File/upload', 
    filePath: path,
    name: 'file',
    success: function (res) {
      scallback(JSON.parse(res.data));
    },
    fail: function (e) {
      fcallback(e);
    }
  })
}
//获取opengid
function getOpenid(cb, fcb) {
  var that = this;
  if (wx.getStorageSync("userInfo")) {
    typeof cb == "function" && cb(wx.getStorageSync("userInfo"));
  } else {
    wx.login({
      success: function (res) {
        var code = res.code;// 登录凭证  
        wx.getUserInfo({
          success: function (res) {
            var userInfo = res.userInfo
            wx.request({
              url: that.openidurl + '&appid=' + that.appid + '&secret=' + that.secret + '&js_code=' + code,
              header: {
                'content-type': 'application/json'
              },
              success: function (res) {
                userInfo.openid = res.data.openid;
                wx.setStorageSync("userInfo", userInfo);
                cb(userInfo);
              },
              fail: function (e) {
                fcb(e);
              }
            })
          },
          fail: function (e) {
            fcb(e);
          }
        })
      }
    });
  }
}
//获取系统信息
function getSystemInfo(cb) {
  var that = this
  if (wx.getStorageSync("systemInfo")) {
    typeof cb == "function" && cb(wx.getStorageSync("systemInfo"));
  } else {
    wx.getSystemInfo({
      success: function (res) {
        wx.setStorageSync("systemInfo", res);
        typeof cb == "function" && cb(wx.getStorageSync("systemInfo"));
      }
    })
  }
}
module.exports = {
  formatTime: formatTime,
  strempty: strempty,
  showtip: showtip,
  showLoading: showLoading,
  hideToast: hideToast,
  alertViewWithCancel: alertViewWithCancel,
  alertView: alertView,
  sendrequest: sendrequest,
  uplodaFile: uplodaFile,
  getOpenid: getOpenid,
  getSystemInfo: getSystemInfo,
  domain: "http://localhost:8080/agri",
  openidurl: "https://api.weixin.qq.com/sns/jscode2session?grant_type=authorization_code",
  appid: 'wx2d37a95245474f6d',
  secret: '865960f6902c4400737619164afed798'
}
