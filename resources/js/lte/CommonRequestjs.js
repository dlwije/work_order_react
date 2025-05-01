var WebAppBaseUrl = "";
var mainDir = window.location.pathname.split('/');

// var WebApiBaseUrl = window.location.protocol + "//" + window.location.host + "/"+""+mainDir[1]+"/";
var WebApiBaseUrl = BASE_URL;
var WebAppRequestVerificationToken = "";

var HumanToRobot = "";

function App_GetParameterByName(ID, url) {

    if (!url) url = window.location.href;
    ID = location.pathname.split('/').pop();
    ID = ID.replace(/[\[\]]/g, "\\$&");
    return decodeURIComponent(ID.replace(/\+/g, " "));

}

var doAjax_params_default = {
    'url': null,
    'requestType': "POST",
    'contentType': 'application/json; charset=utf-8',
    'dataType': 'json',
    'data': {},
    'beforeSendCallbackFunction': null,
    'successCallbackFunction': null,
    'completeCallbackFunction': null,
    'errorCallBackFunction': null,
    'WebApiBaseUrl': null,
    'WebAppBaseUrl':null
};

APP_BaseUrlType = {

    APP_ApiUrl :1,
    App_WebUrl :2
}

function APP_CommonApiData(obj) {

    if(doesConnectionExist()){
        CommonMsg.ivm.toasterMsg(2,'No Internet',"Please check your internet connection.");
        return;
    }
    var UrlType = (typeof obj.UrlType == "undefined" || obj.UrlType == null) ? APP_BaseUrlType.APP_ApiUrl : obj.UrlType;
    var BaseUrl = (UrlType == APP_BaseUrlType.APP_ApiUrl) ? WebApiBaseUrl : WebAppBaseUrl;
    var url = (typeof obj.url == "undefined" || obj.url == null) ? "" : BaseUrl + obj.url;
    var requestType = (typeof obj.requestType == "undefined" || obj.requestType == null) ? doAjax_params_default.requestType : obj.requestType;
    var contentType = (typeof obj.contentType == "undefined" || obj.contentType == null) ? doAjax_params_default.contentType : obj.contentType ;
    var dataType = (typeof obj.dataType == "undefined" || obj.dataType == null) ? doAjax_params_default.dataType : obj.dataType ;
    var data = (typeof obj.data == "undefined" || obj.data == null) ? doAjax_params_default.data : JSON.stringify(obj.data);
    var successFuntion = (typeof obj.successFuntion == "undefined" || obj.successFuntion == null) ? "" : obj.successFuntion;
    var Authorization = "bearer ";
    var RequestVerificationToken = '@GetAntiXsrfRequestToken()';
    var errorFunction = (typeof obj.errorFunction == "undefined" || obj.errorFunction == null) ? "" : obj.errorFunction;

    $.ajax({
        url: url,
        crossDomain: true,
        type: requestType,
        headers: { "Authorization": Authorization },
        contentType: contentType,
        dataType: dataType,
        data: data,
        beforeSend: function (jqXHR, settings) {


        },
        success: function (data, textStatus, jqXHR) {

            successFuntion(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {

            errorFunction(jqXHR, textStatus, errorThrown);
        },
        complete: function (jqXHR, textStatus) {

        }
    });
}

function doesConnectionExist() {
    var xhr = new XMLHttpRequest();
    var file = BASE_URL+"assets/imgs/companyLogo0.png";
    var randomNum = Math.round(Math.random() * 10000);

    xhr.open('HEAD', file + "?rand=" + randomNum, true);
    xhr.send();

    xhr.addEventListener("readystatechange", processRequest, false);

    function processRequest(e) {
        if (xhr.readyState == 4) {
            if (xhr.status >= 200 && xhr.status < 304) {
                return true;
            } else {
                return false;
            }
        }
    }
}
