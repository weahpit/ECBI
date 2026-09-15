/*
        DECLARATIONS
*/
let formGrille = document.querySelector('#formPdt');
let libelle_grille = document.querySelector('#libelle_grille');
let grille_code_produit = document.querySelector('#grille_code_produit');
let liste_grille = document.querySelector('#liste_grille');
let tarif = document.querySelector('#tarif');
let id_grille = 0;


function getGrilles(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_grilles = ''
    $.ajax({
        url : '/getGrilles',
        type : 'POST',
        success: function(response){
            let listeGrilles = JSON.parse(response);
            if (listeGrilles.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_grilles +='<form id="formGrille" class="p-2 m-2 bg-light" style="background-color: #fff7e7;border: 1px solid lightgrey;font-size: 14px;">\n' +
                        '                    <div class="row">\n' +
                        '                        <div class="col-3">\n' +
                        '                            <h6 class="mt-1 p-2" style="background-color: #303130;color:white;font-weight: lighter;">Enregistrer un tarif</h6><div class="text-center"><label class="w-75 mx-auto p-1 text-dark bg-warning fw-bold text-center" id="lbl_grille" style="border-radius: 25px;font-size: 12px;"></label></div>\n' +
                        '                            <div class="m-2"><div class="input-group input-group-sm mb-3">\n' +
                        '                                <select class="w-100"  id="grille_code_produit" aria-label="Code du produit" aria-describedby="code_produit">\n' +
                        '                                    <option value="0">Sélectionnez un produit</option>\n' +
                        '                                </select>\n' +
                        '                            </div>\n' +
                        '                            <div class="input-group input-group-sm mb-3" style="">\n' +
                        '                                <select class="w-100" id="liste_grille" aria-label="grille" aria-describedby="grille" disabled>\n' +
                        '                                    <option value="0">Sélectionnez une grille</option>\n' +
                        '                                </select>\n' +
                        '                            </div>\n' +
                        '                            <div class="input-group input-group-sm mb-3">\n';
                                                    contenu_grilles +=`<input class="w-100 text-center fw-bold h2 p-2" id="tarif" type="number" placeholder="Tarif"><hr>`;
                                                    contenu_grilles +='<button class="btn btn-success pt-1 mt-2 pb-1 w-100" type="button" id="btn-ajt-tarif">Ajouter le produit</button>\n' +
                        '                            </div></div>\n' +
                        '\n' +
                        '                        </div>\n' +
                        '                        <div class="col-9" style="background: #f2f2f2">\n' +
                        '                            <h6 class="mt-1 p-2" style="background-color: #303130;color:white;font-weight: lighter;">Grilles</h6>\n' +
                        '\n' +
                        '                            <div class="input-group input-group-sm mb-3 d-inline-flex">\n' +
                        '                                <input type="text" class="fw-bold w-75 mb-2" id="libelle_grille" aria-label="Nouvelle grille" aria-describedby="Nouvelle grille" placeholder="Nouvelle grille" style="text-transform: uppercase;height: 30px;">\n' +
                        '                                <a class="btn btn-outline-primary ms-2" id="btn-add-grille" href="#" style="height: 30px;"><i class="fa fa-save"></i></a>\n' +
                        '                            </div>\n' +
                        '                            <div class="row  p-2 m-2" style="background: #ececec;border-radius: 10px;border:1px solid lightgray;">\n' +
                        '                            <div class="col-6 pt-2">\n' +
                        '                            <div class="list-group pt-2" style="border-top: 3px solid #dbb001" id="list-tab" role="tablist">\n';

                                                        for (var i=0; i < listeGrilles.data.length ; i++){
                                                            if (i === 0){
                                                                contenu_grilles +='<button type="button" id="' + listeGrilles.data[i].id + '" class="list-group-item list-group-item-action a_elt_grille fw-bold mb-1 <!--active-->"\n' +
                                                                    '                                data-bs-toggle="tab" data-bs-target="#grille' + listeGrilles.data[i].id + '">\n' +
                                                                    '                            <span class="pb-1" style="border-bottom: 2px solid #c39e05;"><img src="assets/icons/camions.png" class="me-2" alt="camion-toupie"></span>' + listeGrilles.data[i].libelle_grille +'</button>';
                                                            } else {
                                                                contenu_grilles +='<button type="button" id="' + listeGrilles.data[i].id + '" class="list-group-item list-group-item-action a_elt_grille fw-bold mb-1"\n' +
                                                                    '                                data-bs-toggle="tab" data-bs-target="#grille' + listeGrilles.data[i].id + '">\n' +
                                                                    '                            <span class="pb-1" style="border-bottom: 2px solid #c39e05;"><img src="assets/icons/camions.png" class="me-2" alt="camion-toupie"></span>' + listeGrilles.data[i].libelle_grille +'</button>';
                                                           }
                                                        }
                    contenu_grilles +=
                        '                               </div>\n' +
                        '                            </div>\n' +
                                                    '<div class="col-6" style="height:400px;overflow-y: scroll;">\n' +
                                                    '<div class="text-center pt-3 div_selection"><h6 class="fw-bold mt-2">Sélectionnez une grille tarifaire</h6><img src="assets/images/money.png" height="128" alt="money"></div>\n' +

                                                    '    <div class="tab-content border-start p-2">';
                                                            for (var i=0; i < listeGrilles.data.length ; i++){
                                                                if (i === 0){
                                                                    contenu_grilles +='<div class="tab-pane fade <!--show active-->" id="grille'+ listeGrilles.data[i].id +'">' ;
                                                                } else {
                                                                    contenu_grilles +='<div class="tab-pane fade" id="grille'+ listeGrilles.data[i].id +'">' ;
                                                               }
                                                                   if ( listeGrilles.data[i].tarifs.length > 0){
                                                                        contenu_grilles +='<table class="table mt-2">'
                                                                        contenu_grilles +='<thead><tr class="sticky-top">'
                                                                        contenu_grilles +='<th class="bg-secondary text-light">Produit</th>'
                                                                        contenu_grilles +='<th class="bg-secondary text-light text-center">Prix</th>'
                                                                        contenu_grilles +='</tr></thead>'
                                                                        contenu_grilles +='<tbody>'
                                                                    // Enregistrement du contenu
                                                                        for (var j=0; j < listeGrilles.data[i].tarifs.length ; j++){
                                                                            contenu_grilles +='<tr style="font-size: 20px;">'
                                                                            contenu_grilles +='<td><span class="badge bg-light fw-bold text-dark border border-1 ">' + listeGrilles.data[i].tarifs[j].produit+ '</span></td>'
                                                                            contenu_grilles +='<td class="fw-bold text-danger text-center"><span>' + numStr(listeGrilles.data[i].tarifs[j].tarif, " ") + '</span></td>'
                                                                            contenu_grilles +='</tr>'
                                                                        }
                                                                    }

                                                                contenu_grilles +='</tbody></table></div>'
                                                            }
                                        contenu_grilles += `</div>`
                                    contenu_grilles += '</div>\n' +
                                                   '</div>'+
                                    '             </div>\n' +
                                    '          </form>'
                  } else { // le contrôle est un dropdown
                    contenu_grilles +='Liste des grilles'
                    contenu_grilles +='<option value="0">Grilles Tarifaires</option>'
                    for (var i=0; i < listeGrilles.data.length ; i++){
                        contenu_grilles +='<option value="'+ listeGrilles.data[i].id + '">' + listeGrilles.data[i].libelle_grille  + '</option>'
                    }
                }

                if (ctrl_name) { ctrl_name.innerHTML = contenu_grilles;}
                 formGrille = document.querySelector('#formPdt');
                 libelle_grille = document.querySelector('#libelle_grille');
                grille_code_produit = document.querySelector('#grille_code_produit');
                 tarif = document.querySelector('#tarif');
                 liste_grille = document.querySelector('#liste_grille');

                //$('#lbl_grille').text(document.querySelector(".active").text)

            } else {
                render_toast_value(listeGrilles.msg, listeGrilles.code)
            }
        }
    })
}

function getGrilleContent(value, ctrl){
    let contenu_grille = ''
    $.ajax({
        url: '/getGrilleContent/' + value,
        type: 'POST',
        success: function (response) {
            let listeContent = JSON.parse(response)
            if ( listeContent.data.length > 0){
                contenu_grille +='<table class="table mt-2">'
                contenu_grille +='<thead><tr class="sticky-top">'
                contenu_grille +='<th class="bg-secondary text-light">Produit</th>'
                contenu_grille +='<th class="bg-secondary text-light text-center">Prix</th>'
                contenu_grille +='</tr></thead>'
                contenu_grille +='<tbody>'
                // Enregistrement du contenu
                for (var j=0; j < listeContent.data.length ; j++){
                    contenu_grille +='<tr style="font-size: 20px;">'
                    contenu_grille +='<td><span class="badge bg-light fw-bold text-dark border border-1 ">' + listeContent.data[j].produit+ '</span></td>'
                    contenu_grille +='<td class="fw-bold text-danger text-center"><span>' + numStr(listeContent.data[j].tarif, " ") + '</span></td>'
                    contenu_grille +='</tr>'
                }
            }

            contenu_grille +='</tbody></table></div>'
            ctrl.innerHTML = contenu_grille;
        }
    });
}
function getSingleGrille(id_grille) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleGrille/' + id_grille,
            type: 'POST',
            success: function(response) {
                if (response.code === 'success') {
                    resolve(response); // renvoie l'objet au .then()
                } else {
                    reject(response.msg);
                }
            },
            error: function(err) {
                reject(err);
            }
        });
    });
}

function saveGrille(formData, value_ctrl, ctrl_name){
    fetch("/saveGrille", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getGrilles(value_ctrl, ctrl_name)
                resetForm(formPdt)
                setTimeout(()=>{
                    libelle_grille.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}

function saveTarif(formData, value_ctrl, ctrl_name){
    fetch("/saveTarif", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                let ctrl_name = document.getElementById("grille"+id_grille);
                 if (id_grille && ctrl_name){
                     getGrilleContent(id_grille, ctrl_name)
                     getOnlyProduitsOutofGrille(id_grille, grille_code_produit)
                 }
                tarif.value = 0;
                grille_code_produit.value="0"
                // liste_grille.value="0"
                tarif.focus()
                // resetForm(formPdt)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}



/*
EVENTS
*/


$("body").on('click','.tr_grille', function (){
    id_grille = this.id;
    resetForm(formPdt)
    $("#pdtLabel").html('<img class="me-2" src="assets/icons/grille.png" alt="clients"> ...')
    $("#modal_ajt_pdt").modal('show')
    getSingleGrille(id_grille)
        .then(grille => {
            $("#pdtLabel").html('<img class="me-2" src="assets/icons/grille.png" alt="clients"> Editer le grille <span class="text-danger fw-bold">'+ grille.code_grille + '</span>')
            code_grille.value = grille.code_grille;
            libelle_grille.value = grille.libelle_grille;
            // description_grille.value = grille.description_grille;
        })
        .catch(err => {
            console.error("Erreur :", err);
        });
})

$("body").on('click','.a_grille', function (){
    id_grille = this.id;
    $("#modal_grille").modal('show')
    setTimeout(()=>{
        $("#modal_ajt_pdt .btn-close").click()
        document.getElementById("grille_code_grille").value =  id_grille;
    }, 1000)

})

$("body").on("click", '#btn-add-grille', function (){
    let formData = new FormData()

    formData.append("id_grille", id_grille)
    formData.append("libelle_grille", libelle_grille.value)
    saveGrille(formData, 1, div_body)
})

$("body").on("click", '#btn-ajt-tarif', function (){
    let formData = new FormData()

    formData.append("grille_code_produit", document.querySelector('#grille_code_produit').value)
    formData.append("liste_grille", document.querySelector('#liste_grille').value)
    formData.append("tarif", document.querySelector('#tarif').value)
    saveTarif(formData, 1, div_body)
})

$("body").on("change", '#tarif', function (){
    numStr(this.value," ")
})

$('body').on('keypress', '#tarif', function (e){
    if (e.key === "Enter"){
        document.querySelector("#btn-ajt-tarif").click()
    }
})

$("body").on("click", '.a_elt_grille', function (){
    id_grille = this.id;
    if (document.querySelector("body .div_selection")){document.querySelector("body .div_selection").style.display = "none"}
    document.querySelector(".tab-content").style.borderStyle = "1px solid grey;"
    $('#lbl_grille').text(this.textContent)
     getGrilles(2, document.getElementById('liste_grille'))
    // getProduits(2, grille_code_produit)
    getOnlyProduitsOutofGrille(id_grille, grille_code_produit)
    setTimeout(()=>{
        $('#liste_grille').val(this.id)
    }, 1000)
})

function getOnlyProduitsFromGrille(value, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_produits = ''
    $.ajax({
        url : '/getOnlyProduitsFromGrille/' + value,
        type : 'POST',
        success: function(response){
            let listeProduits = JSON.parse(response);
            if (listeProduits.code === 'success'){
                    contenu_produits +='Liste des produits'
                    contenu_produits +='<option value="0">Liste des produits</option>'
                    for (var i=0; i < listeProduits.data.length ; i++){
                        contenu_produits +='<option value="'+ listeProduits.data[i].id + '">' + listeProduits.data[i].libelle_produit  + ' (' + listeProduits.data[i].code_produit  + ')</option>'
                    }
                } else {
                    render_toast_value(listeProduits.msg, listeProduits.code)
                }
                ctrl_name.innerHTML = contenu_produits;
            }
        })
    }

function getOnlyProduitsOutofGrille(value, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_produits = ''
    $.ajax({
        url : '/getOnlyProduitsOutofGrille/' + value,
        type : 'POST',
        success: function(response){
            let listeProduits = JSON.parse(response);
            if (listeProduits.code === 'success'){
                    contenu_produits +='Liste des produits'
                    contenu_produits +='<option value="0">Liste des produits</option>'
                    for (var i=0; i < listeProduits.data.length ; i++){
                        contenu_produits +='<option value="'+ listeProduits.data[i].id + '">' + listeProduits.data[i].libelle_produit  + ' (' + listeProduits.data[i].code_produit  + ')</option>'
                    }
                } else {
                    render_toast_value(listeProduits.msg, listeProduits.code)
                }
                ctrl_name.innerHTML = contenu_produits;
            }
        })
    }

function getTarifProduit(id_produit, id_grille) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getTarifProduit/' + id_produit + '/' + id_grille,
            type: 'POST',
            success: function(response) {
                if (response.code === 'success') {
                    resolve(response); // renvoie l'objet au .then()
                } else {
                    reject(response.msg);
                }
            },
            error: function(err) {
                reject(err);
            }
        });
    });
}
