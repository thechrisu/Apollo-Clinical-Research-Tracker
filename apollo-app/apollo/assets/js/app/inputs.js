///<reference path="jquery.d.ts"/>
///<reference path="scripts.ts"/>
/**
 * Typescript for inputs (and some other renderable elements)
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */
var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
/**
 * Skeleton for input objects
 *
 * @since 0.0.1
 */
var InputField = (function () {
    function InputField(id, callback) {
        this.id = id;
        this.callback = callback;
    }
    InputField.prototype.render = function (target) {
        target.append(this.parentNode);
    };
    return InputField;
})();
/**
 * Input expecting text, i.e. <input type="text" ... />
 *
 * @since 0.0.1
 */
var InputText = (function (_super) {
    __extends(InputText, _super);
    function InputText(id, callback, attributes) {
        _super.call(this, id, callback);
        this.attributes = Util.mergeObjects(attributes, {
            'data-id': this.id.toString(),
            'id': 'input-text-' + this.id,
            'class': 'form-control',
            'type': 'text'
        });
        this.prepareNode();
        this.setupCallback();
    }
    InputText.prototype.prepareNode = function () {
        this.parentNode = $('<div class="apollo-input-container"></div>');
        this.input = Util.buildNode('input', this.attributes, null, true);
        this.parentNode.append(this.input);
    };
    InputText.prototype.setupCallback = function () {
        var that = this;
        console.log('Setting up callback!');
        this.input.on({
            keyup: function () { that.callbackWrapper(); }
        });
    };
    InputText.prototype.callbackWrapper = function () {
        clearTimeout(this.timeout);
        var that = this;
        this.timeout = setTimeout(function () {
            that.callback.call(that.input, that.id, that.input.val());
        }, AJAX_DELAY);
    };
    return InputText;
})(InputField);
var InputDropdown = (function (_super) {
    __extends(InputDropdown, _super);
    function InputDropdown() {
        _super.apply(this, arguments);
    }
    return InputDropdown;
})(InputField);
