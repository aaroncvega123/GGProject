﻿var pageSize = 3232;
function SubmitCheckCode(index,title,content,pageIndex){
	    //提示
        $('#spanrecordcount').html('(加载中...)');
        $('#hspanrecordcount').html('(加载中...)');
        $('#table_con0 .table-main>.table-list tbody').html('<tr><td colspan="5">数据加载中...</td></tr>');
        var hd_type = $('#hd_type').val();
        if (hd_type == '1') {
            $('#start_year_' + index).val('2015');
            setEndYear_0();
        }
        //var pageSize = $($('.sizes select')[index]).val(); 
         var indusName = $('#search_input').val();
        var countryId = $('#country_'+index).val();
        var provinceId = $('#province_'+index).val();
        var cityId = $('#city_'+index).val();
        var startYear = $('#start_year_'+index).val();
        var endYear = $('#end_year_'+index).val();
        var professionId = $('#industry_'+index).val();
        var companyType = 0; //企业类型
        var itemType = 0; //违规类型
        var hasvg=0;
        var fengxian = $('.filter.current .scree-opion input[name=fengxian]:checked').val();
        if (!$('.filter.current .scree-opion input[name=enterprise][data-role=all]').is(':checked')) {
            $('.filter.current .scree-opion input[name=enterprise]').each(function () {
                if ($(this).is(':checked')) {
                    companyType += parseInt($(this).val());
                }
            });
        }
        if (!$('.filter.current .scree-opion input[name=violation][data-role=all]').is(':checked')) {
            $('.filter.current .scree-opion input[name=violation]').each(function () {
                if ($(this).is(':checked')) {
                    itemType += parseInt($(this).val());
                }
            });
        }
        if (!$('.filter.current .scree-opion input[name=hasvg][data-role=all]').is(':checked')) {
            $('.filter.current .scree-opion input[name=hasvg]').each(function () {
                if ($(this).is(':checked')) {
                    hasvg += parseInt($(this).val());
                }
            });
        }
             var ishistory=0;
         if (!$('.filter.current .scree-opion input[name=vv_history][data-role=all]').is(':checked')) {
            $('.filter.current .scree-opion input[name=vv_history]').each(function () {
                if ($(this).is(':checked')) {
                    ishistory += parseInt($(this).val());
                }
            });
        }
         
         
        //   
//        $("input[name=vv_history]").each(function(){
//           
//         if($(this).is(':checked'))
//        {
//   
//           ishistory=ishistory+ parseInt( $(this).val());
//        }
//        })
         PopCheckCode = new Popwin({
        data: {
            title: title,
            content: content
        },
        _theme: "modify-password",
        //确定按钮事件
        onsure: function () {
        var code=$("#txtCode").val();
        //$(".pop-win").html('');
        $(".pop-win").hide();
        $(".pop-win").remove();
            
             
            $.post('../data_ashx/GetAirData.ashx', { headers: { 'Cookie': document.cookie }, cmd: 'getRecords', pageSize: pageSize, pageIndex: pageIndex, countryId: countryId, provinceId: provinceId, cityId: cityId, startYear: startYear, endYear: endYear, professionId: professionId, itemType: itemType, companyType: companyType, indusName: indusName, fengxian: fengxian,ishistory:ishistory,hasvg:hasvg,code:code,hasCode:1 }, function (data) {
            var jsonData = eval('(' + data + ')');
           
            if (jsonData.isSuccess == '200') {
                window.location.href = window.location.href;
            }
            else if (jsonData.isSuccess == '1') {

               if(jsonData.recordCount=='0')
               {
                  $('#industry_id').hide();
               }
               else
               {
                $('#spanrecordcount').html(jsonData.recordCount);
                }

                
                $('#hspanrecordcount').html(jsonData.hrecordCount);

                //$('.uitab-item.current').removeClass('current');
                $('#table_con0 .table-main>.table-list tbody').html(unescape(jsonData.content)); //.parent('.uitab-item').addClass('current'); //添加总数及列表
                $('div.pagers').html(jsonData.pager); //添加页码  全改了
            }else if (jsonData.isSuccess == '2') {
                $('#spanrecordcount').html('0');
                $('#hspanrecordcount').html('0');
                $('#table_con0 .table-main>.table-list tbody').html('<tr><td colspan="5">验证码错误</td></tr>');
                $('div.pagers').html('');
            } 
            else {
                $('#spanrecordcount').html('0');
                      $('#hspanrecordcount').html('0');
                $('#table_con0 .table-main>.table-list tbody').html('<tr><td colspan="5">'+jsonData.Msg+'</td></tr>');
                $('div.pagers').html('');
            }
             
        });
            
             
        },
        oncancel: function () {
            $(".pop-win").hide();
//            new_prtr_1.show();
        },
        //确定中断操作接口
        onabort: function () {
            this.setMemory(false); //移除记忆模式
            //做其他事
        },
        //取消中断接口
        onabortcancel: function () {
            //this.show(); //再打开
            this.setMemory(false); //移除记忆模式
        },
        onclose: function () {
            //history.go(0);
        }
    });
    PopCheckCode.show();
}
ser(1,pageSize);
