$(document).ready(function () {

    CommonMsg.init();

    $('#showToast').on('click', function () {
        CommonMsg.ivm.toasterMsg(4,1,1);
    });
});

CommonMsg = {

    ivm: null,
    init: function () {

        CommonMsg.ivm = new CommonMsg.CommonMsgViewModel();
    },
    CommonMsgViewModel: function () {

        var self = this;

        self.ConfirmFunc = "";
        self.ConfirmParam = "";

        self.isNumeric = function(value) {
            return (value==Number(value))?"number":"string"
        };
        self.getToastMessage = function (msgContentsId) {
            var msgContents = [
                'cannot be empty.',
                'Invalid EmailBody2'
            ];

            return msgContents[msgContentsId];
        };
        self.getToastMessageHeader = function (msgHeaderId) {
            var msgContents = [
                'Form Validation Error',
                'Form Submission Error'
            ];

            return msgContents[msgHeaderId];
        };
        self.toastMsgHead = {
            "FVE": "Form Validation Error",
            "FSE": "Form Submission Error",
            "confirm": "Confirm"
        }
        self.toastMsgContent = {
            "CBE": "cannot be empty",
            "confirm": "Are you sure you want to delete this record?",

        }
        self.ToastMsgType = {
            "1": "success",
            "2": "warning",
            "3": "error",
            "4": "Confirm"
        };
        self.toasterMsg = function (msgType,msgTitle,msgContent,ConFunc,ConParam) {

            self.msgType = "";
            self.msgTitle = "";
            self.msgContent = "";
            /*cheking parse value number or sring*/
            if(self.isNumeric(msgContent) == "number"){ self.msgContent =self.getToastMessage(msgContent); }else{ self.msgContent = msgContent; }
            if(self.isNumeric(msgTitle) == "number"){ self.msgTitle =self.getToastMessageHeader(msgTitle); }else{ self.msgTitle = msgTitle; }
            /*cheking parse value number or sring*/

            self.msgType = self.ToastMsgType[msgType];

            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                rtl: false,
                positionClass: 'toast-top-right',
                // positionClass: 'toast-top-full-width',
                // preventDuplicates: true,
                preventDuplicates: true,
                onclick: null
            };

            if(self.msgType == self.ToastMsgType[4]){

            	self.ConfirmFunc = ConFunc;
            	self.ConfirmParam = ConParam;
				self.msgType = self.ToastMsgType[3];

				toastr.options = {
					closeButton: false,
					allowHtml: true,
					onShown: function (event){
						$("#confirmationRevertYes").click(function(){
							self.ConfirmMsg();
						});
					},
					debug: false,
					newestOnTop: true,
					positionClass: 'toast-top-center'
				};
				toastr.options.tapToDismiss = false;
				toastr.options.timeOut = 0;
				toastr.options.extendedTimeOut = 0;

				self.msgContent += "<div class='row col-md-12'><br /><button type='button' id='confirmationRevertYes' class='btn btn-info pull-right'>Yes</button><button id='' class='btn btn-success clear pull-right' style='margin-right: 10px'>No</button></div> ";
			}

            if(msgType >4){
                self.msgType = self.ToastMsgType[3];
                self.msgTitle = "Massage Type Exceeded!";
                self.msgContent = "You can only use 1 to 3 types only; 1=Success, 2=Warning, 3=Error";
            }

            var $toast = toastr[self.msgType](self.msgContent, self.msgTitle); // Wire up an event handler to a button in the toast, if it exists

			if ($toast.find('.clear').length) {
				$toast.delegate('.clear', 'click', function () {
					toastr.clear($toast, { force: true });
				});
			}
        }

        self.sweetAlertMsg = function(msgH,msgB,msgType){

            Swal.fire(
                msgH,
                msgB,
                msgType
            );
        };

        self.ConfirmMsg = function () {

        	if(typeof self.ConfirmFunc == "undefined" || self.ConfirmFunc == null) return;
        	if(typeof self.ConfirmParam == "undefined" || self.ConfirmParam == null){

        		self.ConfirmFunc();
			}else{

        		self.ConfirmFunc(self.ConfirmParam)
			}
		}
    }
};
