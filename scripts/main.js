function collectionHas(a, b) { //helper function (see below)
    for(var i = 0, len = a.length; i < len; i ++) {
        if(a[i] == b) return true;
    }
    return false;
}
function findParentBySelector(elm, selector) {
    var all = document.querySelectorAll(selector);
    var cur = elm.parentNode;
    while(cur && !collectionHas(all, cur)) { //keep going up until you find a match
        cur = cur.parentNode; //go up
    }
    return cur; //will return null if not found
}

function bindElementBySelector(selector, event, func){
    var elements = document.querySelectorAll(selector);
    for(var i=0; i<elements.length; i++){
        var element = elements[i];
        element.addEventListener(event, function(e) {
            return func(e);
        });
    }
}
