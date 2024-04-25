document.addEventListener('DOMContentLoaded', function() {
    
    prepareToMesFiches();
    
});

function prepareToMesFiches(){
    var fiches_comp_div = document.getElementById("fiches_compl");
    var pageNbr =1;
    var svg_full = document.querySelectorAll("#favoris .card .card-fiche-icon .icon-star")[0];
    var svg_empty = document.querySelectorAll("#div_comp .card .card-fiche-icon .icon-star-outline")[0];
    var parametres = new Parametres();
    var params_fav = new Parametres();
    var fiches_fav_div = document.getElementById("fiches_fav");
    var fiches_inval_div = document.getElementById("fiches_inval");
    var fiches_term_div = document.getElementById("fiches_term");
    var fiches_val_div = document.getElementById("fiches_val");
    var pageNbrFav = 1;
    var pageNbrInval = 1;
    var pageNbrTerm = 1;
    var pageNbrVal = 1;
    var params_inval = new Parametres();  
    var params_term = new Parametres();
    var params_val = new Parametres();   
    
    //fiches en complétion
    if (fiches_comp_div){
        parametres = getParams(fiches_comp_div,"nb_fiches_comp",parametres,pageNbr,svg_full,svg_empty,"div_comp","pagination_comp");
    }
    //fiches favorites
    if (fiches_fav_div){
        params_fav = getParams(fiches_fav_div,"nb_fiches_fav",params_fav,pageNbrFav,svg_full,svg_empty,"favoris","pagination_fav");
    }
    //Fiches en validation
    if(fiches_inval_div){
        params_inval = getParams(fiches_inval_div,"nb_fiches_inval",params_inval,pageNbrInval,svg_full,svg_empty,"div_fiches_inval","pagination_inval");
    }
    //fiches terminées
    if(fiches_term_div){
        params_term = getParams(fiches_term_div,"nb_fiches_term",params_term,pageNbrTerm,svg_full,svg_empty,"div_fiches_term","pagination_term");
    }
    //fiches publiées
    if(fiches_val_div){
        params_val = getParams(fiches_val_div,"nb_fiches_val",params_val,pageNbrVal,svg_full,svg_empty,"div_fiches_val","pagination_val");
    }
    
    if (parametres.max_div){
        parametres.max_div.addEventListener("change",function(){

            createCards(parametres,pageNbr);
           
       });
    }
    if(params_fav.max_div){
        params_fav.max_div.addEventListener("change",function(){

            createCards(params_fav,pageNbrFav);
           
       });
    }
    if(params_inval.max_div){
        params_inval.max_div.addEventListener("change",function(){

            createCards(params_inval,pageNbrInval);
           
       });
    }
    if(params_term.max_div){
        params_term.max_div.addEventListener("change",function(){

            createCards(params_term,pageNbrTerm);
           
        });
    }
    if(params_val.max_div){
        params_val.max_div.addEventListener("change",function(){

            createCards(params_val,pageNbrVal);
       
        });
    }
}

function getParams(div,id_div_max,params,pageNb,svg_full,svg_empty,container_id,pagination_id){
    
    var fiches=JSON.parse(div.value);
    var max_fiches = document.getElementById(id_div_max);
    var size = fiches.length;
    return params = new Parametres(max_fiches,fiches,svg_empty,svg_full,pageNb,size,container_id,pagination_id);
    
}
class Parametres {
    constructor(max_div,tableau,svg_empty,svg_full,pageNbr,size,container_id,pagination_id) {
        this.max_div=max_div;
        this.tableau=tableau;
        this.svg_empty=svg_empty;
        this.svg_full=svg_full;
        this.pageNbr=pageNbr;
        this.size=size;
        this.container_id = container_id;
        this.pagination_id = pagination_id
    }
  }
  

function createCards(parametres,pageNb){
    var max = parseInt(parametres.max_div.value);
    var container_id = parametres.container_id;
    removeElementsByClass("#"+container_id+" .card");
    var container = document.getElementById(container_id);
    var tableau = parametres.tableau;
    var svg_full = parametres.svg_full;
    var svg_empty = parametres.svg_empty;
    var pageNbr = pageNb;
    var min = (pageNbr-1) * max;
    var size = parametres.size;
    var total = max * pageNbr;
    if (total > size){
        total=size;
    }
    for (var i=min;i<total;i++){

        var a = document.createElement('a');
        a.setAttribute("href",tableau[i].href);
        getAtrributes(tableau[i].extra_attributes,a);
        var image = document.createElement("img");
        image.setAttribute("class","card-fiche-image");
        image.setAttribute("src",tableau[i].image);

        var div=document.createElement("div");
        div.setAttribute("class","card-fiche-body");
        a.setAttribute("class","");
        a.appendChild(image); 
        var card = document.createElement("div");
        card.setAttribute("class","card-fiche card");
        var span1= document.createElement("span");
        span1.setAttribute("class","card-fiche-title");
        span1.innerHTML = tableau[i].name;
        var a2 = document.createElement("a");
        setSpan(a2,tableau[i].name,"card-fiche-title");
        setSpan(a2,tableau[i].species,"card-fiche-espece");
        div.appendChild(a2);
        card.appendChild(a);
        card.appendChild(div);
        var div2=document.createElement('div');
        div2.setAttribute("id",tableau[i].id);
        div2.setAttribute("class","card-fiche-icon");
        getAtrributes(tableau[i].extra_attributes,div2);
        var svg=document.createElementNS('http://www.w3.org/2000/svg','svg');
        if (tableau[i].icon.icon==='star'){
            svg=svg_full;
        }else{
            svg=svg_empty;
        }
        div2.appendChild(svg.cloneNode(true));
        card.appendChild(div2);
        container.appendChild(card);
    }

    createPagination(parametres,pageNbr);
}

function removeElementsByClass(className){
    document.querySelectorAll(className).forEach(function(a){
        a.remove();
    })
}

function getAtrributes(attributes,element){
    for (const [key, value] of Object.entries(attributes)) {
        element.setAttribute(key, value); 
    }
}

function setSpan(element,texte,classe){
    var span= document.createElement("span");
    span.setAttribute("class",classe);
    span.innerHTML = texte;
    element.appendChild(span);
}

function createPagination(parametres,pageNb){
    
    var max = parseInt(parametres.max_div.value);
    var size = parametres.size;
    var nb_pages = Math.ceil(size/max);
    var pageNbr = pageNb;
    var div_pagination = document.getElementById(parametres.pagination_id);
    if (div_pagination){
        div_pagination.remove();
    }
   
    if (nb_pages > 1 && max !== size){
        
    
        var div = document.createElement("div");
        div.setAttribute("class","selector")
        var div2 = document.createElement("div")
        div2.setAttribute('id',parametres.pagination_id);
        div2.setAttribute('class','pagination');
    
        for (var i=1;i<=nb_pages;i++){
            if (i===pageNbr){
                var span_current = document.createElement("span");
                span_current.setAttribute("class","page-numbers current");
                span_current.setAttribute("aria-current","page");
                var span = document.createElement('span');
                span.setAttribute("class","meta-nav screen-reader-text");
                span.innerHTML = "Page";
                span_current.appendChild(span);
                span_current.innerHTML +=pageNbr;
                div2.appendChild(span_current);
            }else{
                var a = document.createElement("a");
                a.setAttribute("class","page-numbers");
                var span = document.createElement("span");
                span.setAttribute("class","meta-nav screen-reader-text");
                var count=i;
                a.onclick = (function(parametres, count){
                    return function(){
                        createCards(parametres, count);
                    }
                 })(parametres,count);
                span.innerHTML="Page";
                a.appendChild(span);
                a.innerHTML+=i;
                div2.appendChild(a);
            }
        }

        if (pageNbr===1){
            createSpan("next",div2,parametres,pageNbr);

        }else if(pageNbr>1 && pageNbr<nb_pages){
            //current:pageNbr ; prev et next
            createSpan("prev",div2,parametres,pageNbr);
            createSpan("next",div2,parametres,pageNbr);
        }else if(pageNbr=nb_pages){
            //current:pageNbr : prev 
            createSpan("prev",div2,parametres,pageNbr);
        }

        div.appendChild(div2);
        var container = document.getElementById(parametres.container_id);
        container.appendChild(div);
    }
}

function createSpan(prev,div,parametres,pageNbr){
    var prev_a = document.createElement("a");
    prev_a.setAttribute("class",prev+" page-numbers");

    if (prev==="prev"){
        prev_a.innerHTML="Page précédente";
        prev_a.addEventListener("click",function(){createCards(parametres,pageNbr-1);});
    }else{
        prev_a.innerHTML="Page suivante";
        prev_a.addEventListener("click",function(){createCards(parametres,pageNbr+1);});
    }
    div.appendChild(prev_a);
    
}
