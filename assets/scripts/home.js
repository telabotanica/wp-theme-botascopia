document.addEventListener('DOMContentLoaded', function() {
    
   createHomeSwitchHome();
    
});

function createHomeSwitchHome(){
    var box= document.getElementsByClassName("search-box large");
    var path = document.getElementById("path-home");

    if (path){
        var pathHome = path.value+"/";
       
        if (window.location.href === pathHome){
            
            var label = document.createElement('label');
            label.setAttribute('class','switch');
            var input = document.createElement('input');
            input.setAttribute("type","checkbox");
            var div = document.createElement('div');
            div.setAttribute("class","slider round");
            var spanOn= document.createElement('span');
            spanOn.setAttribute("class","on");
            spanOn.innerHTML="FICHES";
            var spanOff = document.createElement("span");
            spanOff.setAttribute("class","off");
            spanOff.innerHTML="COLLECTIONS";
            div.appendChild(spanOn);
            div.appendChild(spanOff);
            label.appendChild(input);
            label.appendChild(div);
            box[0].appendChild(label);
            label.addEventListener("change",function(){
                var form = document.getElementById("search-home");
                var action =form.getAttribute("action");
                var formInput = document.getElementsByClassName("search-box-input");
                var placeholder = "";
                if(input.checked){
                    action = action.replace("collection","fiches");
                    console.log(action);
                    placeholder = "Rechercher une fiche...";
                }else{
                    action =action.replace("fiches","collection");
                    console.log(action);
                    placeholder = "Rechercher une collection...";
                }
                form.setAttribute("action",action);
                formInput[0].setAttribute("placeholder",placeholder);
            })
        }
    }        
}
