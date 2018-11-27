/*function getPageComponent(){
    return '<nav aria-label="Page navigation">'+
  '<ul class="pagination pagination-lg"> '+
    '<li><a v-on:click="pagePrevious"  aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a> </li> '+
    '<li v-for="page_number in pages" v-bind:class="{active : page_number.is_active}" v-on:click="changePage(page_number.page_index)"><a  v-bind:class="{hidden : page_number.is_hidden}">{{page_number.page_index}}</a></li>'+
    '<li><a v-on:click="pageNext" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>'+
  '</ul></nav>';
}*/
function getPageList(pn,ps,funCallBack,strUrl,searchKey,searchValue) {
        var resData = {};
        resData.pn = pn;
        resData.ps = ps;
        if('' != searchKey && '' != searchValue){
            resData[searchKey] = searchValue;
        }
        var strData = JSON.stringify(resData);
        $.ajax({  type: 'POST',
                         contentType: "application/json;charset=utf-8",
                         url: strUrl, 
                         data: strData,
                         dataType:'json',
                         success: function(data){
                            funCallBack(pn,ps,data);
                         },
                         error: function() {
                             alert('失败');
                         }
          })
    }
function setPagination(intPageNumber,intCurrentPage,intActivePage) {
    pageList.pages = [];
    for(var i=1;i<=intPageNumber;i++){
        if(i <= intCurrentPage+4 && i >= intCurrentPage-4){
            if(intActivePage == i ) {
                pageList.pages.push({page_index:i,is_hidden:false,is_active:true});
                continue;
            }
            pageList.pages.push({page_index:i,is_hidden:false});
        }else{
            pageList.pages.push({page_index:i,is_hidden:true});
        }
    }
    
    
}

function instrumentCallBack(pn,ps,data) {
    var arrData = data;
    var intInstrumentCount = arrData['count'];
    instrumentList.$data.instrument_count = intInstrumentCount;
    pageList.$data.pages_sum = Math.ceil(intInstrumentCount/pageList.$data.page_size)
    instrumentList.instruments = [];
    for(intIndex in arrData['instrument']){
        instrumentList.instrument.push({instrument_id:arrData['instrument'][intIndex]['instrument_id'],instrument_name:arrData['instrument'][intIndex]['instrument_name'],
        });
    }
    setPagination(pageList.$data.pages_sum,pn,pn);
}

function searchUser() {
    var strUserId = document.getElementById("search_user_id").value;
    var strUserName = document.getElementById("search_user_name").value;
    if(strUserId != ''){
        var strSearchKey = 'id';
        var strSearchValue = strUserId;
    } else if(strUserName != '') {
        var strSearchKey = 'name';
        var strSearchValue = strUserName;
    }else{
        alert('请输入查询条件!');
        return;
    }
    pageList.$data.search_key = strSearchKey;
    pageList.$data.search_value = strSearchValue;
    getPageList(1,pageList.$data.page_size,userListCallBack,strUserSearchUrl,pageList.$data.search_key,pageList.$data.search_value);
}
