//Permet d'afficher les infos en 2 colonnes distinctes lors du cas fleurs_bi + fl_male/femelle    


document.addEventListener("DOMContentLoaded", function() { 
   changeDesign();
    
});

function changeDesign(){
	var fleur_male = document.querySelector("#fm_txt").value;
	console.log(fleur_male);
	var fleur_femelle = document.querySelector("#ff_txt").value;

	if (fleur_male=='1' || fleur_femelle=='1'){
		var tige = document.querySelector( "#stem" );
		var feuille = document.querySelector("#leaf");
		var inflo = document.querySelector("#inflo");
		var fruit = document.querySelector("#frutty");
		var fl_bi = document.querySelector("#fl_bi");
		var fl_male = document.querySelector("#fl_male");
		var fl_fem = document.querySelector("#fl_fem");
		console.log(fl_fem);
		var caracteristiques = document.querySelector("#caracteristiques");
		var css = document.querySelectorAll(".page-template-fiche_pdf p");
		for (i=0;i<css.length;i++){
			element = css[i];
			element.style.margin = '10px';
		}
		
		
		tige.remove();
		feuille.remove();
		inflo.remove();
		fruit.remove();
		fl_bi.remove();
		if (fl_male){
			fl_male.remove();
		}else if(fl_fem){
			fl_fem.remove();
		}

		var general = document.createElement('div');
		general.setAttribute("id", "general");
		caracteristiques.append(general);
		
		general.append(tige);
		general.append(feuille);
		general.append(inflo);
		general.append(fruit);
		
		var flowers = document.createElement('div');
		flowers.setAttribute("id", "flowers");
		caracteristiques.append(flowers);
		
		flowers.append(fl_bi);

		if (fleur_male =='1'){
			flowers.append(fl_male);
		}else{
			flowers.append(fl_fem);
		}
	}
}