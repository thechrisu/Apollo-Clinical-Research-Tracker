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

/**
 * Skeleton for input objects
 *
 * @since 0.0.1
 */
abstract class InputField implements Renderable {

    protected id:number;
    protected callback:Function;
    protected parentNode:JQuery;

    constructor(id:number, callback:Function) {
        this.id = id;
        this.callback = callback;
    }

    public render(target:JQuery) {
        target.append(this.parentNode);
    }

}

/**
 * Input expecting text, i.e. <input type="text" ... />
 *
 * @since 0.0.1
 */
class InputText extends InputField {

    private attributes:Attributes;
    private timeout:number;
    private input:JQuery;

    public constructor(id:number, callback:Function, attributes:Attributes) {
        super(id, callback);
        this.attributes = <Attributes> Util.mergeObjects(attributes, {
            'data-id': this.id.toString(),
            'id':  'input-text-' + this.id,
            'class': 'form-control',
            'type': 'text'
        });
        this.prepareNode();
        this.setupCallback();
    }

    private prepareNode() {
        this.parentNode = $('<div class="apollo-input-container"></div>');
        this.input = Util.buildNode('input', this.attributes, null, true);
        this.parentNode.append(this.input);
    }

    private setupCallback() {
        var that = this;
        console.log('Setting up callback!');
        this.input.on({
            keyup: function () { that.callbackWrapper() }
        });
    }

    private callbackWrapper() {
        clearTimeout(this.timeout);
        var that = this;
        this.timeout = setTimeout(function() {
            that.callback.call(that.input, that.id, that.input.val());
        }, AJAX_DELAY);
    }
}

class InputDropdown extends InputField {

}