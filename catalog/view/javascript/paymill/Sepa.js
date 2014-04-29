Sepa = function(mandateReferenceNumber)
{
    this.mandateReferenceNumber = mandateReferenceNumber;
    this.checkboxText = 'By signing this mandate form, you authorise PAYMILL to send instructions to your bank to debit your account and in accordance with the instructions from PAYMILL.';
    this.cancelText = 'Cancel';
    this.submitText = 'Submit';
    this.mandateReferenceLabel = 'Mandate reference';
};

Sepa.prototype.setMandateReferenceLabel = function(mandateReferenceLabel)
{
    this.mandateReferenceLabel = mandateReferenceLabel;
};

Sepa.prototype.setCheckboxText = function(checkboxText)
{
    this.checkboxText = checkboxText;
};

Sepa.prototype.setSubmitText = function(submitText)
{
    this.submitText = submitText;
};

Sepa.prototype.setCancelText = function(cancelText)
{
    this.cancelText = cancelText;
};

Sepa.prototype.initPopUp = function()
{
    this.popup = document.createElement('div');

    this.popup.id = 'paymill-sepa-container';
    this.popup.className = 'paymill-sepa-container';
    this.popup.style.cssText = "position: fixed; z-index: 2147483647;height 300px; width: 600px; margin: -150px 0 0 -300px; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; background-color: white; font-family: sans-serif; font-size: 14px; -webkit-box-shadow: rgba(0, 0, 0, 0.298039) 0px 0px 50px; box-shadow: rgba(0, 0, 0, 0.298039) 0px 0px 50px; left: 50%; top: 50%; background-position: initial initial; background-repeat: initial initial";
};

Sepa.prototype.initHeadline = function()
{
    this.headline = document.createElement('div');
    this.headline.innerHTML = 'Sepa';
    this.headline.id = 'paymill-sepa-headline';
    this.headline.className = 'paymill-sepa-headline';
    this.headline.style.cssText = "border-bottom: 1px solid #c0c0c0!important; -webkit-border-top-right-radius: 5px; -moz-border-radius-topright: 5px; border-top-right-radius: 5px; -webkit-border-bottom-right-radius: 0; -moz-border-radius-bottomright: 0; border-bottom-right-radius: 0; -webkit-border-bottom-left-radius: 0; -moz-border-radius-bottomleft: 0; border-bottom-left-radius: 0; -webkit-border-top-left-radius: 5px; -moz-border-radius-topleft: 5px; border-top-left-radius: 5px; background-color: #dcdcdc; background-image: -moz-linear-gradient(top, #ededed, #c3c3c3); background-image: -ms-linear-gradient(top, #ededed, #c3c3c3); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ededed), to(#c3c3c3)); background-image: -webkit-linear-gradient(top, #ededed, #c3c3c3); background-image: -o-linear-gradient(top, #ededed, #c3c3c3); background-image: linear-gradient(top, #ededed, #c3c3c3); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#c3c3c3', GradientType=0); *zoom: 1; padding: 10px 0 0 0; height: 26px; text-align: center;";

};

Sepa.prototype.initFooter = function()
{
    this.footer = document.createElement('div');
    this.footer.id = 'paymill-sepa-footer';
    this.footer.className = 'paymill-sepa-footer';
    this.footer.style.cssText = "padding: 14px 15px 15px; margin-bottom: 0; text-align: right; background-color: #f5f5f5; border-top: 1px solid #ddd; -webkit-border-radius: 0 0 6px 6px; -moz-border-radius: 0 0 6px 6px; border-radius: 0 0 6px 6px; -webkit-box-shadow: inset 0 1px 0 #ffffff; -moz-box-shadow: inset 0 1px 0 #ffffff; box-shadow: inset 0 1px 0 #ffffff; *zoom: 1;";
};

Sepa.prototype.initCancelButton = function(callback)
{
    this.cancelButton = document.createElement('input');
    this.cancelButton.id = 'paymill-sepa-footer-cancel';
    this.cancelButton.className = 'paymill-sepa-footer-cancel';
    this.cancelButton.style.cssText = "display: inline-block; *display: inline; *zoom: 1; padding: 4px 10px 4px; margin-bottom: 0; font-size: 13px; line-height: 20px; *line-height: 20px; color: #333333; text-align: center; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75); vertical-align: middle; cursor: pointer; background-color: #f5f5f5; background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6); background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6)); background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6); background-image: -o-linear-gradient(top, #ffffff, #e6e6e6); background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e6e6e6', GradientType=0); border-color: #e6e6e6 #e6e6e6 #bfbfbf; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); *background-color: #e6e6e6; filter: progid:DXImageTransform.Microsoft.gradient(enabled = false); border: 1px solid #cccccc; *border: 0; border-bottom-color: #b3b3b3; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; *margin-left: .3em; -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); position: inherit; top:inherit; left: inherit;";
    this.cancelButton.setAttribute('type', 'button');
    this.cancelButton.setAttribute('value', this.cancelText);
    this.cancelButton.setAttribute('onclick', 'document.getElementById("paymill-sepa-container").parentNode.removeChild(document.getElementById("paymill-sepa-container")); ' + callback + '(false);');

};

Sepa.prototype.initMandateReference = function()
{
    this.mandateReference = document.createElement('div');
    this.mandateReference.id = 'paymill-sepa-content-mandate-reference';
    this.mandateReference.className = 'paymill-sepa-content-mandate-reference';
    this.mandateReference.innerHTML = '<span style="font-weight: bold;">' + this.mandateReferenceLabel + '</span>: ' + this.mandateReferenceNumber;
    this.mandateReference.style.cssText = 'margin-bottom: 10px; line-height: 20px; color: rgb(51, 51, 51); padding-left: 25px;';

};

Sepa.prototype.initContentConfirmCheckboxWrapper = function()
{
    this.contentConfirmCheckboxWrapper = document.createElement('div');
    this.contentConfirmCheckboxWrapper.style.cssText = 'float: left; height: 50px; margin-top: 20px;';
    this.contentConfirmCheckboxWrapper.id = 'paymill-sepa-content-checkbox-wrapper';
    this.contentConfirmCheckboxWrapper.className = 'paymill-sepa-content-checkbox-wrapper';
};

Sepa.prototype.initContentConfirmCheckbox = function()
{
    this.contentConfirmCheckbox = document.createElement('input');
    this.contentConfirmCheckbox.style.cssText = 'margin-right: 10px; position: inherit; top:inherit; left: inherit;';
    this.contentConfirmCheckbox.setAttribute('type', 'checkbox');
    this.contentConfirmCheckbox.id = 'paymill-sepa-content-checkbox';
    this.contentConfirmCheckbox.className = 'paymill-sepa-content-checkbox';
};

Sepa.prototype.initContentText = function()
{
    this.contentText = document.createElement('div');
    this.contentText.id = 'paymill-sepa-content-text';
    this.contentText.className = 'paymill-sepa-content-text';
    this.contentText.innerHTML = this.checkboxText;
    this.contentText.style.cssText = 'height: 50px; line-height: 20px; color: rgb(51, 51, 51);';
};

Sepa.prototype.initSubmitButton = function(callback)
{
    this.submitButton = document.createElement('input');
    this.submitButton.id = 'paymill-sepa-content-submit';
    this.submitButton.className = 'paymill-sepa-content-submit';
    this.submitButton.style.cssText = "display: inline-block; *display: inline; *zoom: 1; padding: 4px 10px 4px; margin-bottom: 0; font-size: 13px; line-height: 20px; *line-height: 20px; color: #333333; text-align: center; text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75); vertical-align: middle; cursor: pointer; background-color: #f5f5f5; background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6); background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6)); background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6); background-image: -o-linear-gradient(top, #ffffff, #e6e6e6); background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e6e6e6', GradientType=0); border-color: #e6e6e6 #e6e6e6 #bfbfbf; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); *background-color: #e6e6e6; filter: progid:DXImageTransform.Microsoft.gradient(enabled = false); border: 1px solid #cccccc; *border: 0; border-bottom-color: #b3b3b3; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; *margin-left: .3em; -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05); position: inherit; top:inherit; left: inherit;";
    this.submitButton.setAttribute('type', 'button');
    this.submitButton.setAttribute('value', this.submitText);
    this.submitButton.setAttribute('onclick', callback + '(document.getElementById("paymill-sepa-content-checkbox").checked); document.getElementById("paymill-sepa-container").parentNode.removeChild(document.getElementById("paymill-sepa-container"));');
};

Sepa.prototype.initSubmitRow = function()
{
    this.submitRow = document.createElement('div');
    this.submitRow.style.cssText = 'margin-left: 25px; margin-top: 25px;';
    this.submitRow.id = 'paymill-sepa-content-submit-row';
    this.submitRow.className = 'paymill-sepa-content-submit-row';
};

Sepa.prototype.initContent = function()
{
    this.content = document.createElement('div');
    this.content.style.cssText = 'height: auto; padding: 30px;';
    this.content.id = 'paymill-sepa-footer-cancel';
    this.content.className = 'paymill-sepa-footer-cancel';
};

Sepa.prototype.init = function(callback)
{
    this.initPopUp();
    this.initHeadline();
    this.initFooter();
    this.initCancelButton(callback);
    this.initMandateReference();
    this.initContentConfirmCheckboxWrapper();
    this.initContentConfirmCheckbox();
    this.initContentText();
    this.initSubmitButton(callback);
    this.initSubmitRow();
    this.initContent();
};

Sepa.prototype.popUp = function(callback)
{
    this.init(callback);

    this.contentConfirmCheckboxWrapper.appendChild(this.contentConfirmCheckbox);

    this.content.appendChild(this.mandateReference);
    this.content.appendChild(this.contentConfirmCheckboxWrapper);
    this.content.appendChild(this.contentText);

    this.submitRow.appendChild(this.submitButton);

    this.content.appendChild(this.submitRow);

    this.footer.appendChild(this.cancelButton);

    this.popup.appendChild(this.headline);
    this.popup.appendChild(this.content);
    this.popup.appendChild(this.footer);

    document.getElementsByTagName('body')[0].appendChild(this.popup);
};
