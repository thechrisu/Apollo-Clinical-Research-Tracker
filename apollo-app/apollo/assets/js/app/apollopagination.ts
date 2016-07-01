///<reference path="../typings/jquery.d.ts"/>
const PAGINATION_ITEMS_DEFAULT_ID = 'paginationNumItems';
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 *
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 * Wrapper class for simplepagination, used to extend the simplepagination plugin to our needs
 * ... and also to use methods like normal human beings (just see this here
 * @link http://flaviusmatis.github.io/simplePagination.js/
 * )
 * TODO documentation for the methods
 */

class ApolloPagination {
    private paginationWrapper:JQuery;
    private currentPage:number;

    constructor(paginationWrapper:JQuery, objectCreationArgs:Object, currentPage:number) {
        this.paginationWrapper = paginationWrapper;
        this.paginationWrapper.pagination(objectCreationArgs);
        this.currentPage = currentPage;
    }

    private displayNumItems(numItems:number) {
        var paginationNumItemsDiv = $('#' + PAGINATION_ITEMS_DEFAULT_ID);
        paginationNumItemsDiv.empty();
        var itemText:string;
        if(numItems == 1) {
            itemText = "1 item";
        } else {
            itemText = numItems + " items";
        }
        var textContainer = $("<span class=" + PAGINATION_ITEMS_DEFAULT_ID + "></span>");
        textContainer.text(itemText);
        paginationNumItemsDiv.append(textContainer);
    }
    
    public updateNumPagesSmart(count) {
        if(count < (this.getCurrentPage() - 1) * 10) {
            this.selectPage(count / 10 - count % 10);
            return true;
        } else {
            this.displayNumItems(count);
            this.updateItems(count);
            return false;
        }
    }

    public selectPage(pageNumber:number) {
        this.currentPage = pageNumber;
        this.paginationWrapper.pagination('selectPage', pageNumber);
    }

    public prevPage() {
        this.paginationWrapper.pagination('prevPage');
    }

    public nextPage() {
        this.paginationWrapper.pagination('nextPage');
    }

    public getPagesCount():number {
        return parseInt(this.paginationWrapper.pagination('getPagesCount').toString());
    }

    public getCurrentPage() {
        return this.currentPage;
    }

    public disable() {
        this.paginationWrapper.pagination('disable');
    }

    public enable() {
        this.paginationWrapper.pagination('enable');
    }

    public destroy() {
        this.paginationWrapper.pagination('destroy');
    }

    public redraw() {
        this.paginationWrapper.pagination('redraw');
    }

    public updateItems(numItemsToBeUpdated:number) {
        this.paginationWrapper.pagination('updateItems', numItemsToBeUpdated);
    }

    public updateItemsOnPage(numItemsPerPage:number) {
        this.paginationWrapper.pagination('updateItemsOnPage', numItemsPerPage);
    }

    public drawPage(pageNumToDraw:number) {
        this.paginationWrapper.pagination('drawPage', pageNumToDraw);
    }
}