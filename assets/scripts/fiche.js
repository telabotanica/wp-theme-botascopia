document.addEventListener('DOMContentLoaded', function() {
    getScroll();

});
    

window.addEventListener('scroll', function(e) {
    getScroll();
    
});

function getScroll(){
    var titres = [];
    var items = document.querySelectorAll(".toc-subitem a");
    
    for (var i = 0;i<25;i++){
        var elem = document.querySelector(".rubrique"+i);
        if (elem){
            titres.push(elem);
        }
    }
    
    var top_first = null;
    
    for (var i = 0;i<titres.length;i++){
        var elem = titres[i];
        var top = elem.getBoundingClientRect().top;
        var bottom = elem.getBoundingClientRect().bottom;
        var name = elem.className;
        var numb = name.match(/\d/g);
        numb = numb.join("");
        items[i].classList.add("rub"+numb);
        var num = 0;
        if(i===0){
            top_first = top;
            num=numb;
        }
        var max =0;
        
        if (i==titres.length-1){
            max=numb;
            
        }
        if (window.scrollY < window.scrollY + top_first -250 ){

            var item = document.querySelector(".rub1");
            if (item){
                treatment(items,item);
            }
            
        }
        
        if(window.scrollY > window.scrollY + top - 250 && window.scrollY < bottom + window.scrollY- 250){
            var item = document.querySelector(".rub"+numb);
            if (item){
                treatment(items,item);
            }
        }

        
        if (window.scrollY + window.innerHeight >= document.body.scrollHeight - 2){
            
            var item = document.querySelector(".rub"+max);
            if (item){
                treatment(items,item);
            }
        }
    }
}

function treatment(items,item){
    for (var j = 0;j<items.length;j++){
                
        items[j].style.webkitTextStrokeWidth = '0px';
        var classe = items[j].parentElement.getAttribute("class");
        if (classe.includes("is-active")){
            classe = classe.replace("is-active","is-unactive")
            items[j].parentElement.setAttribute("class",classe);
        }else{
            items[j].parentElement.classList.add("is-unactive");
        }
    }
    
    item.style.webkitTextStrokeWidth = '1px';
    var classe = item.parentElement.getAttribute("class");
    if (classe.includes("is-unactive")){
        classe = classe.replace("is-unactive","is-active")
        item.parentElement.setAttribute("class",classe);
    }
}