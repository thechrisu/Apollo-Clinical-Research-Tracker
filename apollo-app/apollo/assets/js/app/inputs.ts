///<reference path="jquery.d.ts"/>
///<reference path="scripts.ts"/>
/**
 * Typescript for inputs (and some other renderable elements)
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.3
 */

/**
 * Skeleton for input objects
 *
 * @since 0.0.1
 */
abstract class InputField implements Renderable {

    protected id:number;
    protected callback:Function;
    protected parentNode:JQuery;
    protected timeout:number;

    constructor(id:number, callback:Function) {
        this.id = id;
        this.callback = callback;
        this.parentNode = $('<div class="apollo-input-container"></div>');
    }

    public render(target:JQuery) {
        target.append(this.parentNode);
    }

    protected callbackWrapper(callback:Function) {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(function () {
            callback();
        }, AJAX_DELAY);
    }

}

/**
 * Input expecting text, i.e. <input type="text" ... />
 *
 * @since 0.0.1
 */
class InputText extends InputField {

    private attributes:Attributes;
    private input:JQuery;

    public constructor(id:number, callback:Function, attributes:Attributes, value:string = null) {
        super(id, callback);
        this.attributes = <Attributes> Util.mergeObjects(attributes, {
            'data-id': this.id.toString(),
            'id': 'input-text-' + this.id,
            'class': 'form-control input-sm',
            'type': 'text',
            'value': (value == null ? '' : value)
        });
        this.prepareNode();
        this.setupCallback();
    }

    private prepareNode() {
        this.input = Util.buildNode('input', this.attributes, null, true);
        this.parentNode.append(this.input);
    }

    private setupCallback() {
        var that = this;
        this.input.on({
            keyup: function () {
                that.callbackWrapper(that.callback.bind(null, that.id, that.input.val()));
            }
        });
    }
}

/**
 * Text with an option to add new fields
 *
 * @since 0.0.3
 */
class InputTextMultiple extends InputField {

    private inputNodes: JQuery[];

    public constructor(id:number, callback:Function, attributes:Attributes, values:string[] = []) {
        super(id, callback);
        this.prepareNode();
    }

    private prepareNode() {

        //TODO Tim: complete this code
        for(;;) {

        }
    }

    private createInputNode() {

    }

}

/**
 * Bootstrap dropdown
 *
 * @since 0.0.2
 */
class InputDropdown extends InputField {

    private options:string[];
    private selected:number;
    private allowOther:boolean;
    private value:string;
    private multiple:boolean;
    private select:JQuery;
    private input:JQuery;

    public constructor(id:number, callback:Function, options:string[], selected:number = 0, allowOther:boolean = false, value:string = null, multiple:boolean = false) {
        super(id, callback);
        this.options = options;
        this.selected = selected;
        this.allowOther = allowOther;
        this.value = value;
        this.multiple = multiple && !allowOther;
        this.prepareNode();
        this.setupCallback();
    }

    private prepareNode() {
        var attributes:Attributes = {
            'data-id': this.id.toString(),
            'id': 'input-dropdown-' + this.id,
            'class': 'form-control input-sm'
        };
        if (this.multiple) {
            attributes = <Attributes> Util.mergeObjects(attributes, {
                multiple: 'multiple'
            });
        }
        this.select = Util.buildNode('select', attributes);
        for (var i = 0; i < this.options.length + (this.allowOther ? 1 : 0); i++) {
            var label = this.options[i];
            if (i == this.options.length) label = 'Other';
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
            if (this.selected == this.options.length) this.input.show();
        }
    }

    private setupCallback() {
        var that = this;
        this.select.on({
            change: function () {
                var value = that.select.val();
                if (value == that.options.length) {
                    that.input.show();
                    that.callbackWrapper(that.callback.bind(null, that.id, that.input.val()));
                } else {
                    if (that.allowOther) that.input.hide();
                    if (that.multiple) {
                        if (value == null) value = [];
                        for (var i = 0; i < value.length; i++) {
                            value[i] = parseInt(value[i]);
                        }
                    } else {
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
    }
}