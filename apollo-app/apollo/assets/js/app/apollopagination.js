///<reference path="../typings/jquery.d.ts"/>
var PAGINATION_ITEMS_DEFAULT_ID = 'paginationNumItems';
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
var ApolloPagination = (function () {
    function ApolloPagination(paginationWrapper, objectCreationArgs, currentPage) {
        this.paginationWrapper = paginationWrapper;
        this.paginationWrapper.pagination(objectCreationArgs);
        this.currentPage = currentPage;
    }
    ApolloPagination.prototype.displayNumItems = function (numItems) {
        var paginationNumItemsDiv = $('#' + PAGINATION_ITEMS_DEFAULT_ID);
        paginationNumItemsDiv.empty();
        var itemText;
        if (numItems == 1) {
            itemText = "1 item";
        }
        else {
            itemText = numItems + " items";
        }
        var textContainer = $("<span class=" + PAGINATION_ITEMS_DEFAULT_ID + "></span>");
        textContainer.text(itemText);
        paginationNumItemsDiv.append(textContainer);
    };
    ApolloPagination.prototype.updateNumPagesSmart = function (count) {
        if (count < (this.getCurrentPage() - 1) * 10) {
            this.selectPage(count / 10 - count % 10);
            return true;
        }
        else {
            this.displayNumItems(count);
            this.updateItems(count);
            return false;
        }
    };
    ApolloPagination.prototype.selectPage = function (pageNumber) {
        this.currentPage = pageNumber;
        this.paginationWrapper.pagination('selectPage', pageNumber);
    };
    ApolloPagination.prototype.prevPage = function () {
        this.paginationWrapper.pagination('prevPage');
    };
    ApolloPagination.prototype.nextPage = function () {
        this.paginationWrapper.pagination('nextPage');
    };
    ApolloPagination.prototype.getPagesCount = function () {
        return parseInt(this.paginationWrapper.pagination('getPagesCount').toString());
    };
    ApolloPagination.prototype.getCurrentPage = function () {
        return this.currentPage;
    };
    ApolloPagination.prototype.disable = function () {
        this.paginationWrapper.pagination('disable');
    };
    ApolloPagination.prototype.enable = function () {
        this.paginationWrapper.pagination('enable');
    };
    ApolloPagination.prototype.destroy = function () {
        this.paginationWrapper.pagination('destroy');
    };
    ApolloPagination.prototype.redraw = function () {
        this.paginationWrapper.pagination('redraw');
    };
    ApolloPagination.prototype.updateItems = function (numItemsToBeUpdated) {
        this.paginationWrapper.pagination('updateItems', numItemsToBeUpdated);
    };
    ApolloPagination.prototype.updateItemsOnPage = function (numItemsPerPage) {
        this.paginationWrapper.pagination('updateItemsOnPage', numItemsPerPage);
    };
    ApolloPagination.prototype.drawPage = function (pageNumToDraw) {
        this.paginationWrapper.pagination('drawPage', pageNumToDraw);
    };
    return ApolloPagination;
}());
