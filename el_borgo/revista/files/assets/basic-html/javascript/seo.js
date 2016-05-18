var bookWidth = 0;
var bookMinWidth = 0;
var bookScale = 1.2
var topMenuWidth = 242;

function checkTopMenu() {

    var tmp1 = document.getElementById("topRightBG");
    var ww1 = document.getElementById("fullVersionLink").offsetWidth;
    if (ww1 > tmp1.offsetWidth) {
        tmp1.style.width = (ww1 + 10) + "px";
        document.getElementById("topLine").style.right = (ww1 + 10 + 4) + 'px';
        document.getElementById("topLeftBG").style.right = (ww1 + 10 + 4) + 'px';
        document.getElementById("topLeft").style.right = (ww1 + 10 + 4 + 117) + 'px';

    }

    var tmp2 = document.getElementById("topLeftBG");
    var ww2 = document.getElementById("tocLink").offsetWidth;
    if (ww2 > tmp2.offsetWidth) {
        tmp2.style.width = (ww2 + 10) + "px";
        document.getElementById("topLeft").style.right = (4 + ww1 + 10 + ww2 + 10) + 'px';
    }

    var tmp3 = document.getElementById("prewKnob");
    var ww3 = document.getElementById("prewKnobTxt").offsetWidth;
    if (ww3 > tmp3.offsetWidth) {
        tmp3.style.width = ww3 + 'px';
        tmp3.style.left = (-ww3 - 5) + 'px';
    }
}

function checkTocInit() {
    widthSet();
    // set TOC width
    var tocWidth  = bookMinWidth + 300;      

    checkTocWidth(tocWidth);
    checkTocHeight();

}

function checkTocHeight() {

    var listH   = document.getElementById("divTree").offsetHeight;
    var windowH = window.innerHeight;
    var pageH   = document.getElementById("divPage") ;
    
    
    // stretch  PAGE to window height
    if (pageH.offsetHeight < 1*windowH - 250) {        
        pageH.style.height = (1*windowH - 250) + 'px';
    }
    
    // correct Knobs position
    document.getElementById("nextKnob").style.top = (pageH.offsetHeight/2) + 'px';
    document.getElementById("prewKnob").style.top = (pageH.offsetHeight/2) + 'px';
    
    // stretch PAGE to LIST size  
    if (listH > pageH.offsetHeight - 150) {  // 150 bottom margin        
        pageH.style.height    = (1 * listH + 150) + 'px';
        document.getElementById("divBG").style.height   = (1 * listH) + 'px'; 
        
    } 
    
}


function checkTocWidth(ww) {

    document.getElementById("divTitleHeader").style.width = ww + 'px';
    document.getElementById("fontTitle").style.width = ww + 'px';
    document.getElementById("divPage").style.width = ww + 'px';
    document.getElementById("divMain").style.width = ww + 'px';
    document.getElementById("divTOCHeader").style.width = (ww - 120) + 'px';
    document.getElementById("divTree").style.width = (ww - 140) + 'px';

    document.getElementById("topRight").style.right = '0px';
    document.getElementById("topRightBG").style.right = '4px';
    document.getElementById("topLine").style.right = '121px';
    document.getElementById("topLeftBG").style.right = '121px';
    document.getElementById("topLeft").style.right = '238px';

    document.getElementById("nextKnob").style.left = (ww + 5) + 'px';

    if (document.getElementById("divCopy")) {
        document.getElementById("divCopy").style.right = '0px';
    }

    var www = document.getElementById("divTitleHeader").offsetWidth;
    if (www > ww - topMenuWidth) {
        document.getElementById("fontTitle").style.padding = "40px 0 40px 0";
        document.getElementById("fontTitle").style.width = (www) + "px";
    }
}

function checkPageInit() {
    widthSet();
    
    if (bookWidth < bookMinWidth) {

        bookScale = bookMinWidth / bookWidth;
        zoom(bookScale);
        bookWidth = bookMinWidth;


    } else if (bookWidth > window.innerWidth - 300) {
        if (bookMinWidth > window.innerWidth - 300) {
            bookScale = bookMinWidth / bookWidth;
            zoom(bookScale);
            bookWidth = bookMinWidth;

        } else {
            bookScale = (window.innerWidth - 300) / bookWidth;
            zoom(bookScale);
            bookWidth = window.innerWidth - 300;
        }

    } else {
        zoom(bookScale);
    }

    checkPageWidth();
    checkPageHeight();


}

function checkPageHeight() {           // TODO - доделать проверку белого фона БГ
    var hh = document.getElementById("divBookPage").offsetHeight;

    document.getElementById("divPage").style.height = (1 * hh) + 'px';
    document.getElementById("divMain").style.height = (1 * hh + 100) + 'px';
    document.getElementById("divBG").style.height = (1 * hh - 150) + 'px';
    document.getElementById("nextKnob").style.top = ((hh - 70) / 2) + 'px';
    document.getElementById("prewKnob").style.top = ((hh - 70) / 2) + 'px';
}

function checkPageWidth() {
    var ww = document.getElementById("divBookPage").offsetWidth;

    document.getElementById("divTitleHeader").style.width = ww + 'px';
    document.getElementById("fontTitle").style.width = ww + 'px';
    document.getElementById("divPage").style.width = ww + 'px';
    document.getElementById("divMain").style.width = ww + 'px';

    document.getElementById("topRight").style.right = '0px';
    document.getElementById("topRightBG").style.right = '4px';
    document.getElementById("topLine").style.right = '121px';
    document.getElementById("topLeftBG").style.right = '121px';
    document.getElementById("topLeft").style.right = '238px';

    document.getElementById("nextKnob").style.left = (ww + 5) + 'px';


    if (document.getElementById("divCopy")) {
        document.getElementById("divCopy").style.right = '0px';
    }

    var www = document.getElementById("divTitleHeader").offsetWidth;
    if (www > ww - topMenuWidth) {
        document.getElementById("fontTitle").style.padding = "40px 0 40px 0";
        document.getElementById("fontTitle").style.width = (www) + "px";
    }
}

function widthSet() {
    bookWidth = document.getElementById("divMain").offsetWidth;
    var pW1 = document.getElementById("fullVersionLink").offsetWidth;
    var dW1 = document.getElementById("topRightBG").offsetWidth;
    var maxW1 = (pW1 > dW1) ? pW1 : dW1;

    var pW2 = document.getElementById("tocLink").offsetWidth;
    var dW2 = document.getElementById("topLeftBG").offsetWidth;
    var maxW2 = (pW2 > dW2) ? pW2 : dW2;

    topMenuWidth = maxW1 + maxW2;
    bookMinWidth = topMenuWidth + 50 + document.getElementById("divSeoVersion").offsetWidth;
    bookScale = 1.2;
}

function zoom(step) {

    // scale text divs  
    var item = document.getElementById('divBookPage');
    var elems = item.childNodes;

    for (var i = 0; i < elems.length; i++) {
        if (elems[i].tagName == "DIV") {
            elems[i].style.top = (1 * (elems[i].style.top.replace("px", "")) * step) + "px"
            elems[i].style.left = (1 * (elems[i].style.left.replace("px", "")) * step) + "px"

            elems[i].style.width = (Math.abs(elems[i].style.width.replace("px", "")) * step) + "px"
            elems[i].style.height = (Math.abs(elems[i].style.height.replace("px", "")) * step) + "px"
        }
    }
    //  scale font size
    elems = item.getElementsByTagName('span')

    for (var i = 0; i < elems.length; i++) {
        var computedStyle = elems[i].currentStyle || window.getComputedStyle(elems[i], null);
        var divCont = (elems[i].parentNode.tagName == "DIV") ? elems[i].parentNode : elems[i].parentNode.parentNode;


        if (elems[i].style.fontSize) {
            scale = (Math.abs(elems[i].style.fontSize.replace("px", "")) * step);
        } else {
            scale = (Math.abs(computedStyle.fontSize.replace("px", "")) * step);
        }

        elems[i].style.fontSize = scale + "px";

        while ((elems[i].offsetWidth > divCont.offsetWidth) && (scale > 1)) {
            scale = scale - 1;
            elems[i].style.fontSize = scale + "px";
        }

        var diff = ((divCont.offsetHeight - elems[i].offsetHeight) / 2);
        diff = diff.toFixed();
        if (diff > 0) { elems[i].style.padding = diff + "px 0px"; }
        elems[i].style.top = ((divCont.offsetHeight - elems[i].offsetHeight) / 2) + "px";
    }

    //  scale image size and place
    elems = item.getElementsByTagName('img');
    for (var i = 0; i < elems.length; i++) {
        var computedStyle = elems[i].currentStyle || window.getComputedStyle(elems[i], null);
        elems[i].style.top = (1 * elems[i].style.width.replace("px", "") * step) + "px";
        elems[i].style.left = (1 * elems[i].style.width.replace("px", "") * step) + "px";

        elems[i].style.width = (1 * elems[i].style.width.replace("px", "") * step) + "px";
        elems[i].style.height = (1 * elems[i].style.height.replace("px", "") * step) + "px";
    }

    // scale page size 
    computedStyle = item.currentStyle || window.getComputedStyle(item, null);
    if (item.style.width) {
        item.style.width = (1 * item.style.width.replace("px", "") * step) + "px";
    } else {
        item.style.width = (1 * computedStyle.width.replace("px", "") * step) + "px";
    }
    if (item.style.height) {
        item.style.height = (1 * item.style.height.replace("px", "") * step) + "px";
    } else {
        item.style.height = (1 * computedStyle.height.replace("px", "") * step) + "px";
    }

    checkPageWidth();
    checkPageHeight();
}

function checkPublCom() {
    if (document.domain.indexOf('publ.com') != -1) {
        var parts = document.URL.split('/');
        var prevpart = '';
        for (var i = 0; i < parts.length; i++) {
            if (prevpart.toLowerCase() == 'bookdata')
                return replaceBackLink(parts[i]);
            if (parts[i].toLowerCase() == 'seo' || parts[i].toLowerCase() == 'basic-html')
                return replaceBackLink(prevpart);
            prevpart = parts[i];
        }
    }
}

function replaceBackLink(id) {
    var link = document.getElementById('fullVersionLink');
    if (link) {
        var hash = link.href.indexOf('#');
        if (hash != -1)
            link.href = 'http://' + document.domain + '/' + id + link.href.substr(hash);
        else
            link.href = 'http://' + document.domain + '/' + id;
    }
}