
let formPdt = document.querySelector('#formPdt');
let code_produit = document.querySelector('#code_produit');
let libelle_produit = document.querySelector('#libelle_produit');
let qualite_produit = document.querySelector('#qualite_produit');
let description_produit = document.querySelector('#description_produit');
let id_produit = 0;

function getProduits(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_produits = ''
    $.ajax({
        url : '/getProduits',
        type : 'POST',
        success: function(response){
            let listeProduits = JSON.parse(response);
            if (listeProduits.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_produits +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_pdt" data-bs-toggle="modal"  id="ajt-pdt" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un produit</button>'
                    contenu_produits +='<table class="table table-hover table-striped mt-2" style="border: 1px solid lightgrey;">'
                    contenu_produits +='<thead><tr>'
                    contenu_produits +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Code</th>'
                    contenu_produits +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;"> Libellé</th>'
                    contenu_produits +='</tr></thead>'
                    contenu_produits +='<tbody>'
                    for (var i=0; i < listeProduits.data.length ; i++){
                        contenu_produits +='<tr class="tr_produit" id="'+ listeProduits.data[i].id + '" style="cursor:pointer;">'
                        contenu_produits +='<td class="text-center fw-bold text-danger p-0">' + listeProduits.data[i].code_produit + '</td>'
                        contenu_produits +='<td class="p-0">' + listeProduits.data[i].libelle_produit + '</td>'
                        contenu_produits +='</tr>'
                    }
                    contenu_produits +='</tbody>'
                    contenu_produits +='</table>'
                  } else { // le contrôle est un dropdown
                    contenu_produits +='Liste des produits'
                    contenu_produits +='<option value="0">Liste des produits</option>'
                    for (var i=0; i < listeProduits.data.length ; i++){
                        contenu_produits +='<option value="'+ listeProduits.data[i].id + '">' + listeProduits.data[i].libelle_produit  + ' (' + listeProduits.data[i].code_produit  + ')</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_produits;
            } else {
                render_toast_value(listeProduits.msg, listeProduits.code)
            }
        }
    })
}

function getSingleProduit(id_produit) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleProduit/' + id_produit,
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

function saveProduit(formData, value_ctrl, ctrl_name){
    fetch("/saveProduit", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getProduits(value_ctrl, ctrl_name)
                resetForm(formPdt)
                setTimeout(()=>{
                    code_produit.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}


/*
EVENTS
*/

$("body").on('click','#ajt-pdt', function (){
    id_produit = 0;
    $("#pdtLabel").html('<img class="me-2" src="assets/icons/produit.png" alt="clients"> Enregistrer un produit')
    resetForm(formPdt)
    getQualiteProduits(2, qualite_produit)
    setTimeout(()=>{
        code_produit.focus()
    }, 500)
})

$("body").on('click','.tr_produit', function (){
    id_produit = this.id;
    resetForm(formPdt)
    $("#pdtLabel").html('<img class="me-2" src="assets/icons/produit.png" alt="clients"> ...')
    $("#modal_ajt_pdt").modal('show')
    getSingleProduit(id_produit)
        .then(produit => {
            $("#pdtLabel").html('<img class="me-2" src="assets/icons/produit.png" alt="clients"> Editer le produit <span class="text-danger fw-bold">'+ produit.code_produit + '</span>')
            code_produit.value = produit.code_produit;
            libelle_produit.value = produit.libelle_produit;
            qualite_produit.value = produit.qualite_produit;
            getQualiteProduits(2, qualite_produit)
            description_produit.value = produit.description_produit;

        })
        .catch(err => {
            console.error("Erreur :", err);
        });
})

$("#btn-enregistrer-pdt").on("click", function (){
    let formData = new FormData()

    formData.append("id_produit", id_produit)
    formData.append("code_produit", code_produit.value)
    formData.append("libelle_produit", libelle_produit.value)
    formData.append("qualite_produit", qualite_produit.value)
    formData.append("description_produit", description_produit.value)

    saveProduit(formData, 1, div_body)
})
