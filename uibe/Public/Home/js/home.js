/*** Created by Administrator on 2017/7/28.*/
$(function(){
    var init = {
        show: 'show',
        hide: 'hide',
        hotLine: 'hotline_cur',
        checkCur: 'check_cur',
        checkboxImg: ['images/checkbox.png','images/checked.png'],
        removeImg: ['images/shop_close.png','images/shop_close_cur.png'],
        reduceImg: ['images/reduce.png','images/reduce_cur.png'],
        addImg: ['images/add.png','images/add_cur.png'],
        shopCheckImg: ['images/shop_check.png','images/shop_checked.png'],
        Login: {},
        Register: {},
        Address: {},
        dataTr: $('table#shop tbody'),
        isEmpty: function (dataTr) {
            if(dataTr.length == 0){
                $('.shop_box').empty();
                $('.shop_null').show();
            }
        },
        load:function () {
            var _tr = this.dataTr.find('tr');
            this.isEmpty(_tr);
            _tr.each(function () {
                var _this = $(this);
                var _unit = parseFloat(_this.find('td.unit em').text());
                var _num = _this.find('.num').text();
                var _total = init.toFixed(_unit,_num);
                _this.attr('data-num',_num);
                _this.find('td.total em').text(_total);
                _this.attr('data-total',_total);
            });
            $('#totalPrice').text('0.00');
        },
        total: function () {
            var num = 0,total = 0;
            $("input[name='checkname']").each(function () {
                if($(this).prop("checked") == true){
                    var _this = $(this).parents('tr');
                    num += parseInt(_this.attr('data-num'));
                    total += parseFloat(_this.attr('data-total'));
                }
            });
            if(num != 0){
                //$('#totalNum').text(num);
                total = init.toFixed(total,1);
                $('#totalPrice').text(total);
            }else{
                $('#totalPrice').text('0.00');
            }
        },
        removeShop: function (removeArr) {
            for(var i=0,remLen=removeArr.length;i<remLen;i++){
                removeArr[i].remove();
            }
            var _tr = this.dataTr.find('tr');
            this.isEmpty(_tr);
        },
        toFixed: function (unit,num) {
            var result = 0;
            if(parseInt(unit)==unit){
                result = (parseInt(unit)*num).toString()+".00";
            }else{
                result = (parseFloat(unit)*num).toFixed(2);
            }
            return result;
        },
        imgHover: function (ele, imgCur) {
            ele.hover(function(){
                $(this).attr('src',imgCur[1]);
            },function(){
                $(this).attr('src',imgCur[0]);
            });
        },
        shopCheck: function (ele, imgCur) {
            if(ele.prop('checked') == true){
                ele.siblings('img').attr('src',imgCur[1]);
            }else{
                ele.siblings('img').attr('src',imgCur[0]);
            }
        },
        stopEvent: function (event){ //阻止冒泡事件
            //取消事件冒泡
            var e=arguments.callee.caller.arguments[0]||event;
            //若省略此句，下面的e改为event，IE运行可以，但是其他浏览器就不兼容
            if (e && e.stopPropagation) {
                // this code is for Mozilla and Opera
                e.stopPropagation();
            } else if (window.event) {
                // this code is for IE
                window.event.cancelBubble = true;
            }
        }
        // ,
        // Variable:function (ele) {
        //     var eleL = ele.text();
        //     var timer = null;
        //     var n = 0;
        //     timer = setInterval(function () {
        //         n ++;
        //         if(n >= eleL){
        //             clearInterval(timer);
        //         }
        //         ele.text(n);
        //     },10);
        // }
    };
    //phone--menu
    var Flog = false;
    $('.mouble').click(function(){
        if(!Flog){
            $('.mouble_nav').stop().show();
            Flog = true;
        }else{
            $('.mouble_nav').stop().hide();
            Flog = false;
        }
    });

    //顶部导航
    $('#nav em:last-child').hide();

    //通知公告
    if($(window).width()<480){
        for(var i=0;i<$('.anno_cont li').length;i++){
            var liCur = $('.anno_cont li').eq(i);
            liCur.css('padding','10px 0');
        }
    }else if($(window).width()<=767){
        for(var i=0;i<$('.anno_cont li').length;i++){
            var liCur = $('.anno_cont li').eq(i);
            //左右padding
            liCur.css('padding','10px');
            if (i % 2 == 0) {
                liCur.css('padding-left', 0);
            } else if (i % 2 == 1) {
                liCur.css('padding-right', 0);
            }
        }
    }else{
        for (var i = 0; i < $('.anno_cont li').length; i++) {
            var liCur = $('.anno_cont li').eq(i);
            var pW = liCur.find('.anno_tit p span').width();
            //console.log(pW);
            liCur.find('.anno_tit h3').width(pW + 'px');

            //左右padding
            if (i % 3 == 0) {
                liCur.css('padding-left', 0);
            } else if (i % 3 == 2) {
                liCur.css('padding-right', 0);
            } else {
                liCur.css('padding', '30px 15px');
            }
        }
    }

    //item
    //$('#ilCur').val(0);
    var item_len = $('.il_list li').length;
    $('#itemL').bind('click',function(){
        var num = $('#ilCur').val();
        $('.il_list li').hide();
        if(num==0){
            num = item_len-1;
        }else{
            num --;
        }
        $('.il_list li').eq(num).fadeIn();
        $('#ilCur').val(num);
    });
    $('#itemR').bind('click',function(){
        var num = $('#ilCur').val();
        $('.il_list li').hide();
        if(num==item_len-1){
            num = 0;
        }else{
            num ++;
        }
        $('.il_list li').eq(num).fadeIn();
        $('#ilCur').val(num);
    });
    //language
    if("zh"!=LANG_SET){
    	$('.languageBox span').removeClass('current');
    	var lang = $('.languageMore li');
    	for(var i=0;i<lang.length;i++){
    		var lang_d = $(lang[i]).attr('data-language');
    		 if(LANG_SET==lang_d){
				 var text = $(lang[i]).text();
				 $(this).parent().css('height','0');
	             $('.languageBox span:last-child').text(text);
	             $('.languageBox span:last-child').attr('data-language',lang_d);
	             $('.languageBox span:last-child').addClass('current');
	             break;
    		 }
    	}
    }
 
    var url = window.location.href;
    $('.languageBox span').bind('click',function(){
        $('.languageBox span').removeClass('current');
        $(this).addClass('current');
        var l = $(this).attr("data-language");
        if(url){
        	if(url.indexOf("?")==-1){
        		url = url+"?l="+l;
        	}else{
        		var idx =url.indexOf("l=");
        		if(idx==-1){
        			url = url+"&l="+l;
        		}else{
        			url = url.substr(0,idx+2)+l;
        		}
        	}
        	window.location.href = url;
        }
    });
    $('#push').bind('click',function(){
        $('.languageMore').css('height','auto');
    });
    $('.languageMore li').bind('click',function(){
        init.stopEvent(event);
        var lang = $(this).text();
        var lang_d = $(this).attr('data-language');//console.log(lang_d);
        $(this).parent().css('height','0');
        $('.languageBox span:last-child').text(lang);
        $('.languageBox span:last-child').attr('data-language',lang_d);
        if(url){
        	if(url.indexOf("?")==-1){
        		url = url+"?l="+lang_d;
        	}else{
        		var idx =url.indexOf("l=");
        		if(idx==-1){
        			url = url+"&l="+lang_d;
        		}else{
        			url = url.substr(0,idx+2)+lang_d;
        		}
        	}
        	window.location.href = url;
        }
    });

    //左侧菜单
    if($(window).width()<768) {
        var navL_h = $(window).height()-50;
        $('.navL').height(navL_h+'px');
    }
    $(window).resize(function () {
        if($(window).width()<768) {
            var navL_h = $(window).height()-50;
            $('.navL').height(navL_h+'px');
        }
    });
    $('#Floating').bind('click',function(){
        $('.navL').animate({left:'0px'});
        $('#Floating').hide();
    });
    $('#goBack').bind('click',function() {
        $('.navL').animate({left: '-300px'});
        $('#Floating').show();
    });

    //关于我们--部门设置
    //默认
    $('.setup li:first-child .set_tit span').html('-').attr('mark','0');
    $('.setup li:first-child .set_type').show();
    $('.set_tit span').bind('click',function(){
        var fg = $(this).parents('li');
        var idx = fg.length;
        //alert($(this).attr('mark'));
        if($(this).attr('mark')==1){
            $(this).html('-').attr('mark','0');
            $(this).parent().siblings('.set_type').show();
        }else{
            $(this).html('+').attr('mark','1');
            $(this).parent().siblings('.set_type').hide();
        }

    });

    /*//左侧菜单
    $('ul.navUl>li a.navUl_a').bind('click',function(){
        var _this = $(this).parent();
        if(_this.hasClass('current') && _this.find('ol').attr('class') == 'navOl'){//收回子菜单
            _this.removeClass('current');
            _this.find('ol.navOl').fadeOut();
        }else if(_this.hasClass('current') && _this.find('ol').attr('class') != 'navOl'){//收回主菜单
            if($(window).width()<768){
                $('.navL').animate({'left':'-300px'});
                $('#Floating').show();
            }
        }else if(!_this.hasClass('current') && _this.find('ol').attr('class') == 'navOl'){//展开子菜单
            _this.addClass('current').siblings('li').removeClass('current');
            $('ul.navUl>li ol.navOl').hide();
            _this.find('ol.navOl').fadeIn();
        }else if(!_this.hasClass('current') && _this.find('ol').attr('class') != 'navOl'){//收回主菜单
            _this.addClass('current').siblings('li').removeClass('current');
            $('ul.navUl>li ol.navOl').hide();
            if($(window).width()<768){
                $('.navL').animate({'left':'-300px'});
                $('#Floating').show();
            }
        }
    });*/
    $('ol.navOl li').bind('click',function () {
        if($(window).width()<768){
            $('.navL').animate({'left':'-300px'});
            $('#Floating').show();
        }
    });

    //新闻分类
    $('.newsType a').bind('click',function(){
        var ths = $(this);
        ths.addClass('current').siblings('a').removeClass('current');
    });

    //新闻通告--轮播
    autoPlay($('.meet_imgs ul'),$('.meet_btn'));
    //autoPlay($('.organ_imgs ul'),$('.meet_btn'));
    function autoPlay(ul,btn) {
        clearInterval(timer);
        var lis = ul.find('li');
        var liL = lis.length;
        ul.css('width',100*liL+'%');
        lis.css('width',100/liL+'%');
        var liW = lis.width();
        var n = 0;
        var timer = null;
        timer = setInterval(function () {
            n ++;
            if(n>=liL){
                n = 0;
            }
            ul.animate({'margin-left':-liW*n});
            btn.find('span').eq(n).addClass('current').siblings().removeClass('current');
            //console.log(n);
        },3000);
        for(var i=0;i<liL;i++){
            btn.append('<span></span>');
        }
        btn.find('span').eq(0).addClass('current');
        btn.find('span').bind('click',function () {
            var idx = $(this).index();
            n = idx-1;
            ul.animate({'margin-left':-liW*n});
            $(this).addClass('current').siblings().removeClass('current');
        });
    }

    //右侧内容高度
    if($(window).width()>=768) {
        //var minH = $('.navL').height() - $('.bannerR').height();
        var minH = $('.navL').height();
        $('.matterBox').css('min-height', minH);
    }
});

/*$(function(){
 document.addEventListener('touchstart',touchstart,false);
 function touchstart(d){
 //                console.log(e.touches[0].pageX);
 //                console.log(e.touches[0].pageY);
 //不兼容的浏览器过滤掉
 try{
 if(d.touches[0]){
 startx=d.touches[0].pageX;
 starty=d.touches[0].pageY;
 }
 }catch(e){
 console.log(e);
 }
 }
 document.addEventListener('touchmove',touchmove,false);
 function touchmove(d){
 try{
 if(d.touches[0]){
 endx=d.touches[0].pageX;
 endy=d.touches[0].pageY;
 }
 }catch(e){
 console.log(e);
 }
 }
 document.addEventListener('touchend',touchend,false);
 function touchend(){
 //比较两个是决定滑动方向
 if(endx<startx && (startx-endx)>40){//左滑
 $('.navL').animate({'left':'-300px'});
 }else if(endx>startx && (endx-startx)>40){//右滑
 $('.navL').animate({'left':'0px'});
 }
 }
 });
 function scoll(){
 var arr = $(".new_scoll li");
 }*/
