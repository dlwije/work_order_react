var App_Copyright = "&copy    " + new Date().getFullYear() + " - DLW.&nbsp;&nbsp;All rights reserved";
var App_PageSize = "10";
var App_DecimalPoint = "2";

NewMasterListName = {
    "customer": "Customer",
    "item": "Item",
    "nonInventory": "Non Inventory GRN ",
    "nonInventoryIssue": "Non Inventory Issue ",
    "unitofmesure": "Unit Of Mesure",
    "vehicle": "Vehicle",
    "vehicletype": "Vehicle Type",
    "vendor": "Supplier",
    "servicetype": "Service Package",
    "servicetask": "Service Task",
    "Dealer": "Dealer",
    "vwarranty": "Vehicle Warranty",
    "employee": "Employee",
    "customerjob": "Job Order",
    "joborder": "Job Order",
    "mjoborder": "Mechanical Job Order",
    "topenjob": "Opened Job",
    "tstartjob": "Started Job",
    "trequestjob": "Inventory Requested Job",
    "tcompletejob": "Job Order",
    "GRN": "Goods Receive Note",
    "GRNRTN": "Goods Return",
    "invoice": "Invoice",
    "spinvoice": "Spare Parts Invoice",
    "stockcard": "Job Card",
    "mjobcard": "Mechanical Job Card",
    "porder": "Purchase Order",
    "pnlreport": "Profit and Loss",
    "SDReport": "Stock Details",
    "CDReport": "Customer Details",
    "PNLHReport": "Profit & Loss Hierarchy",
    "CCReport": "Cash Collection",
    "disReport": "Daily Item Issue",
};

NewMasterListNo = {
    "customer": 1,
    "item": 2,
    "unitofmesure": 3,
    "vehicle": 4,
    "vehicletype": 5,
    "vendor": 6,
    "servicetype": 7,
    "servicetask": 8,
    "Dealer": 9,
    "vwarranty": 10,
    "employee": 11,
    "nonInventory": 12,
    "nonInventoryIssue": 13,
    "customerjob": 101,
    "joborder": 106,
    "mjoborder": 1007,
    "topenjob": 1011,
    "tstartjob": 1012,
    "trequestjob": 1013,
    "tcompletejob": 1014,
    "GRN": 102,
    "GRNRTN": 103,
    "invoice": 104,
    "stockcard": 105,
    "porder": 106,
    "spinvoice": 107,
    "mjobcard": 108,
    "pnlreport": 301,
    "SDReport": 302,
    "CDReport": 303,
    "disReport": 304,
    "CCReport": 305,
    "PNLHReport": 306,
};

function APP_pageBack(MasterFileId){

    if(MasterFileId == NewMasterListNo.item)
        window.location.href = WebApiBaseUrl+"itemList";
    else if(MasterFileId == NewMasterListNo.customer)
        window.location.href = WebApiBaseUrl+"Customer_con/paginListView/";
    else if(MasterFileId == NewMasterListNo.vehicletype)
        window.location.href = WebApiBaseUrl+"Common_con/paginListView/"+NewMasterListNo.vehicletype;
    else if(MasterFileId == NewMasterListNo.unitofmesure)
        window.location.href = WebApiBaseUrl+"uomList";
    else if(MasterFileId == NewMasterListNo.vehicle)
        window.location.href = WebApiBaseUrl+"vehicleList";
    else if(MasterFileId == NewMasterListNo.customerjob)
        window.location.href = WebApiBaseUrl+"TransactionForm/Job_con/paginListView/"+NewMasterListNo.customerjob;
    else if(MasterFileId == NewMasterListNo.GRN)
        window.location.href = WebApiBaseUrl+"Common_con/paginListView/"+NewMasterListNo.GRN;
    else if(MasterFileId == NewMasterListNo.GRNRTN)
        window.location.href = WebApiBaseUrl+"Common_con/paginListView/"+NewMasterListNo.GRNRTN;
    else if(MasterFileId == NewMasterListNo.vendor)
        window.location.href = WebApiBaseUrl+"MasterForm/Vendor_con/paginListView";
    else if(MasterFileId == NewMasterListNo.servicetype)
        window.location.href = WebApiBaseUrl+"Common_con/paginListView/"+NewMasterListNo.servicetype;
    else if(MasterFileId == NewMasterListNo.Dealer)
        window.location.href = WebApiBaseUrl+"MasterForm/Dealer_con/paginListView/";
    else if(MasterFileId == NewMasterListNo.vwarranty)
        window.location.href = WebApiBaseUrl+"MasterForm/Warranty_con/paginListView/";
    else if(MasterFileId == NewMasterListNo.invoice)
        window.location.href = WebApiBaseUrl+"Common_con/paginListView/"+NewMasterListNo.invoice;
    else if(MasterFileId == NewMasterListNo.stockcard)
        window.location.href = WebApiBaseUrl+"TransactionForm/Stock_card_con/paginListView/";
    else if(MasterFileId == NewMasterListNo.porder)
        window.location.href = WebApiBaseUrl+"TransactionForm/POrder_con/paginListView/";
    else if(MasterFileId == NewMasterListNo.joborder)
        window.location.href = WebApiBaseUrl+"TransactionForm/Job_con/paginListView";
    else if(MasterFileId == NewMasterListNo.tcompletejob)
        window.location.href = WebApiBaseUrl+"TransactionForm/Job_con/TechnicianCompleteJobList/"+NewMasterListNo.tcompletejob;
    else if(MasterFileId == NewMasterListNo.employee)
        window.location.href = WebApiBaseUrl+"employeeList";
    else if(MasterFileId == NewMasterListNo.nonInventory)
        window.location.href = WebApiBaseUrl+"TransactionForm/NonInvenGRN_con/paginListView";
    else if(MasterFileId == NewMasterListNo.nonInventoryIssue)
        window.location.href = WebApiBaseUrl+"TransactionForm/NonInvenGRN_con/paginListViewNIIssue";
    else if(MasterFileId == NewMasterListNo.spinvoice)
		window.location.href = WebApiBaseUrl+"TransactionForm/Invoice_con/partsInvoiceListView";
    else if(MasterFileId == NewMasterListNo.mjobcard)
        window.location.href = WebApiBaseUrl+"TransactionForm/Mechanical_job_card_con/paginListView";
    else
        window.location.href = WebApiBaseUrl+"Login_con/Home";
}

function sweetAlertMsg(msgH,msgB,msgType){

    Swal.fire(msgH,msgB,msgType);
}

function ErrorValidationColor(ElementID,State){

    if(State==1)
        $('#'+ElementID).parent().parent().addClass('text-danger');
    else
        $('#'+ElementID).parent().parent().removeClass('text-danger');

}

function select2DropdownNormal(elementId, URL) {

    $("#"+elementId).select2({
        placeholder: 'Select...',
        // allowClear: true,
        ajax: {
            url: URL,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });
}

function APP_FormatNumber(ElementId) {
    $('#'+ElementId).val(accounting.formatNumber($('#'+ElementId).val(),2));
}

function APP_FormatNumberValue(number) {

    return accounting.formatNumber(number,2);
}

function select2Dropdown(elementId,URL,URLGET,ID,vendorID) {

    var element = "#"+elementId;
    $(element).select2({
        placeholder: 'Select...',
        // allowClear: true,
        ajax: {
            url:$.trim(URL),
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term || "",
                    page: params.page || 1,
                    resCount: 10,
                    vendor_id: vendorID
                }
            }
        }
    });
    $(".select2-container").tooltip({
        title: function() {
            return $(this).prev().attr("title");
        },
        placement: "auto",
        //container: 'body'
    });

    var elementSelect = $(element);

    if(typeof ID == "undefined" || ID == null) return;
    $.ajax({
        type: 'GET',
        url:$.trim(URLGET),
        data:{'id':ID}

    }).then(function (data) {
        var jsonVal = JSON.parse(data);
        // create the option and append to Select2
        var option = new Option(jsonVal[0].text, jsonVal[0].id, true, true);
        elementSelect.append(option).trigger('change');

        // manually trigger the `select2:select` event
        elementSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}

function select2DropdownForModel(elementId,modelID,URL,URLGET,ID,vendorID) {

    var element = "#"+elementId;
    $(element).select2({
        placeholder: 'Select...',
        // allowClear: true,
        // dropdownParent: $("#"+modelID),
        ajax: {
            url: URL,
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term || "",
                    page: params.page || 1,
                    resCount: 10,
                    vendor_id: vendorID
                }
            }
        }
    });
    $(".select2-container").tooltip({
        title: function () {
            return $(this).prev().attr("title");
        },
        placement: "auto",
    });
    var elementSelect = $(element);

    if(typeof ID == "undefined" || ID == null) return;
    $.ajax({
        type: 'GET',
        url: URLGET,
        data:{'id':ID}

    }).then(function (data) {
        var jsonVal = data;
        // console.log(jsonVal);
        // create the option and append to Select2
        var option = new Option(jsonVal[0].text, jsonVal[0].id, true, true);
        elementSelect.append(option).trigger('change');

        // manually trigger the `select2:select` event
        elementSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}

function select2DropdownForModelByClass(elementClass,modelID,URL,URLGET,ID,vendorID) {

    var element = "."+elementClass;
    $(element).select2({
        placeholder: 'Select...',
        // allowClear: true,
        // dropdownParent: $("#"+modelID),
        ajax: {
            url: URL,
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term || "",
                    page: params.page || 1,
                    resCount: 10,
                    vendor_id: vendorID
                }
            }
        }
    });
    $(".select2-container").tooltip({
        title: function () {
            return $(this).prev().attr("title");
        },
        placement: "auto",
    });
    var elementSelect = $(element);

    if(typeof ID == "undefined" || ID == null) return;
    $.ajax({
        type: 'GET',
        url: URLGET,
        data:{'id':ID}

    }).then(function (data) {
        var jsonVal = data;
        // console.log(jsonVal);
        // create the option and append to Select2
        var option = new Option(jsonVal[0].text, jsonVal[0].id, true, true);
        elementSelect.append(option).trigger('change');

        // manually trigger the `select2:select` event
        elementSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}
function getSelect2Val(ElementID) {
    if($("#"+ElementID).data("select2")!==undefined) {
        if ($('#' + ElementID).select2('val') != null) {
            return $('#' + ElementID).select2('data')[0].id;
        }
    }else if($("#"+ElementID).val()!==undefined){
        return $("#"+ElementID+" option:selected").val();
    }
}

function getSelect2Text(ElementID) {
    if($("#"+ElementID).data("select2")!==undefined) {
        if ($('#' + ElementID).select2('val') != null) {
            return $('#' + ElementID).select2('data')[0].text;
        }
    }else if($("#"+ElementID).val()!==undefined){
        return $("#"+ElementID+" option:selected").text();
    }
}

function APP_selectEmpty(element) {
    if($("#"+element).data("select2")!==undefined) {
        var elementSelect = $('#' + element);
        elementSelect.val(null).trigger('change');
    }
}

function APP_getBoolean(value){
    switch(value){
        case false:
        case "false":
        case "0":
            return false;
        default:
            return true;
    }
}

function uncheckIcheck(elemId) {

    $('#'+elemId).iCheck('uncheck');
}

function StopLoading () {

    $("#loadingDiv").fadeOut(500, function () {
        // fadeOut complete. Remove the loading div
        $("#loadingDiv").hide();
    })
}

function StartLoading () {

    $("#loadingDiv").fadeOut(500, function () {
        // fadeOut complete. Remove the loading div
        $("#loadingDiv").show();
    })
}

function checkEmail(emailVal) {

    var em = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return em.test(emailVal);
}

function APP_SubmitionError(jqXHR, textStatus, errorThrown) {

    try {
        CommonMsg.ivm.toasterMsg(3,1,"Error Code is "+jqXHR.status);
        console.log('errorThrown: '+errorThrown);
        console.log('errorMsg: '+jqXHR.responseText);
        console.warn(jqXHR.responseText)
    } catch (ex) {    }
}

function removeSeparators(value) {
    return value.replace(/[^0-9-.]/g, '');
}

/*
functions which took from Advance pos system
*/
function unitToBaseQty(qty, unitObj) {
    switch (unitObj.operator) {
        case '*':
            return parseFloat(qty) * parseFloat(unitObj.operation_value);
            break;
        case '/':
            return parseFloat(qty) / parseFloat(unitObj.operation_value);
            break;
        case '+':
            return parseFloat(qty) + parseFloat(unitObj.operation_value);
            break;
        case '-':
            return parseFloat(qty) - parseFloat(unitObj.operation_value);
            break;
        default:
            return parseFloat(qty);
    }
}

function formatDecimal(x, d) {
    if (!d) {
        d = site.settings.decimals;
    }
    return parseFloat(accounting.formatNumber(x, d, '', '.'));
}

function formatQuantity2(x) {
    return x != null ? formatQuantityNumber(x, site.settings.qty_decimals) : '';
}
function formatQuantityNumber(x, d) {
    if (!d) {
        d = site.settings.qty_decimals;
    }
    return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function formatMoney(x, symbol) {
    if (!symbol) {
        symbol = '';
    }
    if (site.settings.sac == 1) {
        return (
            (site.settings.display_symbol == 1 ? site.settings.symbol : '') +
            '' +
            formatSA(parseFloat(x).toFixed(site.settings.decimals)) +
            (site.settings.display_symbol == 2 ? site.settings.symbol : '')
        );
    }
    var fmoney = accounting.formatMoney(
        x,
        symbol,
        site.settings.decimals,
        site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep,
        site.settings.decimals_sep,
        '%s%v'
    );
    return (
        (site.settings.display_symbol == 1 ? site.settings.symbol : '') +
        fmoney +
        (site.settings.display_symbol == 2 ? site.settings.symbol : '')
    );
}
function formatSA(x) {
    x = x.toString();
    var afterPoint = '';
    if (x.indexOf('.') > 0) afterPoint = x.substring(x.indexOf('.'), x.length);
    x = Math.floor(x);
    x = x.toString();
    var lastThree = x.substring(x.length - 3);
    var otherNumbers = x.substring(0, x.length - 3);
    if (otherNumbers != '') lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + lastThree + afterPoint;

    return res;
}
function is_numeric(mixed_var) {
    var whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
    return (
        (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -1)) &&
        mixed_var !== '' &&
        !isNaN(mixed_var)
    );
}
