///<reference path="jquery.d.ts"/>
///<reference path="scripts.ts"/>
/**
 * Typescript for inputs (and some other renderable elements)
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
 * TODO: Add documentation
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
        this.parentNode = $('<div class="apollo-input-container"></div>');
    }
    InputField.prototype.render = function (target) {
        target.append(this.parentNode);
    };
    InputField.prototype.callbackWrapper = function (callback) {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(function () {
            callback();
        }, AJAX_DELAY);
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
    function InputText(id, callback, attributes, value) {
        if (value === void 0) { value = null; }
        _super.call(this, id, callback);
        this.attributes = Util.mergeObjects(attributes, {
            'data-id': this.id.toString(),
            'id': 'input-text-' + this.id,
            'class': 'form-control input-sm',
            'type': 'text',
            'value': (value == null ? '' : value)
        });
        this.prepareNode();
        this.setupCallback();
    }
    InputText.prototype.prepareNode = function () {
        this.input = Util.buildNode('input', this.attributes, null, true);
        this.parentNode.append(this.input);
    };
    InputText.prototype.setupCallback = function () {
        var that = this;
        this.input.on({
            keyup: function () {
                that.callbackWrapper(that.callback.bind(null, that.id, that.input.val()));
            }
        });
    };
    return InputText;
})(InputField);
/**
 * Text with an option to add new fields
 *
 * @since 0.0.3
 */
var InputTextMultiple = (function (_super) {
    __extends(InputTextMultiple, _super);
    function InputTextMultiple(id, callback, attributes, values) {
        if (values === void 0) { values = []; }
        _super.call(this, id, callback);
        this.prepareNode();
    }
    InputTextMultiple.prototype.prepareNode = function () {
        //TODO Tim: complete this code
        for (;;) {
        }
    };
    InputTextMultiple.prototype.createInputNode = function () {
    };
    return InputTextMultiple;
})(InputField);
/**
 * Bootstrap dropdown
 *
 * @since 0.0.2
 */
var InputDropdown = (function (_super) {
    __extends(InputDropdown, _super);
    function InputDropdown(id, callback, options, selected, allowOther, value, multiple) {
        if (selected === void 0) { selected = 0; }
        if (allowOther === void 0) { allowOther = false; }
        if (value === void 0) { value = null; }
        if (multiple === void 0) { multiple = false; }
        _super.call(this, id, callback);
        this.options = options;
        this.selected = selected;
        this.allowOther = allowOther;
        this.value = value;
        this.multiple = multiple && !allowOther;
        this.prepareNode();
        this.setupCallback();
    }
    InputDropdown.prototype.prepareNode = function () {
        var attributes = {
            'data-id': this.id.toString(),
            'id': 'input-dropdown-' + this.id,
            'class': 'form-control input-sm'
        };
        if (this.multiple) {
            attributes = Util.mergeObjects(attributes, {
                multiple: 'multiple'
            });
        }
        this.select = Util.buildNode('select', attributes);
        for (var i = 0; i < this.options.length + (this.allowOther ? 1 : 0); i++) {
            var label = this.options[i];
            if (i == this.options.length)
                label = 'Other';
            this.select.append($('<option' + (i == this.selected ? ' selected' : '') + ' value="' + i + '">' + label + '</option>'));
        }
        this.parentNode.append(this.select);
        if (this.allowOther) {
            this.input = Util.buildNode('input', {
                'style': 'display:none;',
                'data-id': this.id.toString(),
                'id': 'input-dropdown-other-' + this.id,
                'class': 'form-control input-sm',
                'placeholder': 'Type here...',
                'type': 'text',
                'value': (this.value == null ? '' : this.value)
            }, null, true);
            this.parentNode.append(this.input);
            if (this.selected == this.options.length)
                this.input.show();
        }
    };
    InputDropdown.prototype.setupCallback = function () {
        var that = this;
        this.select.on({
            change: function () {
                var value = that.select.val();
                if (value == that.options.length) {
                    that.input.show();
                    that.callbackWrapper(that.callback.bind(null, that.id, that.input.val()));
                }
                else {
                    if (that.allowOther)
                        that.input.hide();
                    if (that.multiple) {
                        if (value == null)
                            value = [];
                        for (var i = 0; i < value.length; i++) {
                            value[i] = parseInt(value[i]);
                        }
                    }
                    else {
                        value = parseInt(value);
                    }
                    that.callbackWrapper(that.callback.bind(null, that.id, value));
                }
            }
        });
        if (this.allowOther) {
            this.input.on({
                keyup: function () {
                    that.callbackWrapper(that.callback.bind(null, that.id, that.input.val()));
                }
            });
        }
    };
    return InputDropdown;
})(InputField);
