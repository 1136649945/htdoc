//app.js
App({
  globalData: {
    session: null,
    userInfo:null,
    tabBar: {
      "color": "#848484",
      "selectedColor": "#4ac372",
      "backgroundColor": "#f4f4f4",
      "position": "bottom",
      "borderStyle": "white",
      "list": [{
        "pagePath": "../../pages/system/system",
        "text": "专家系统",
        "iconPath": "../../images/system.png",
        "selectedIconPath": "../../images/system_cur.png"
      }, {
        "pagePath": "../../pages/ask/ask",
        "text": "提问",
        "iconPath": "../../images/ask.png",
        "selectedIconPath": "../../images/ask_cur.png"
      }, {
        "pagePath": "../../pages/problem/problem",
        "text": "问题中心",
        "iconPath": "../../images/problem.png",
        "selectedIconPath": "../../images/problem_cur.png"
      }, {
        "pagePath": "../../pages/learn/learn",
        "text": "学习园地",
        "iconPath": "../../images/learn.png",
        "selectedIconPath": "../../images/learn_cur.png"
      }, {
        "pagePath": "../../pages/personal/personal",
        "text": "个人中心",
        "iconPath": "../../images/personal.png",
        "selectedIconPath": "../../images/personal_cur.png"
      }]
    }
  },
  //会员底部  
  ntabBar: function (tabBar) {
    var _curPageArr = getCurrentPages();
    var _curPage = _curPageArr[_curPageArr.length - 1];
    var _pagePath = _curPage.__route__;
    if (_pagePath.indexOf('/') != 0) {
      _pagePath = '../../' + _pagePath;
    }
    for (var i = 0; i < tabBar.list.length; i++) {
      tabBar.list[i].active = false;
      if (tabBar.list[i].pagePath == _pagePath) {
        tabBar.list[i].active = true;//根据页面地址设置当前页面状态    
      }
    }
    _curPage.setData({
      tabBar: tabBar
    });
  }
})