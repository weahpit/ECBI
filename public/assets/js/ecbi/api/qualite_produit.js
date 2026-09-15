
let formQPdt = document.querySelector('#formQPdt');
let libelle = document.querySelector('#libelle_qualite_produit');
let id_qualite_produit = 0;

function getQualiteProduits(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_qualite_produits = ''
    $.ajax({
        url : '/getQualiteProduits',
        type : 'POST',
        success: function(response){
            let listeQualiteProduits = JSON.parse(response);
            if (listeQualiteProduits.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_qualite_produits +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_qpdt" data-bs-toggle="modal"  id="ajt-pdt" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter une qualité</button>'
                    contenu_qualite_produits +='<table class="table table-hover table-striped mt-2" style="border: 1px solid lightgrey;">'
                    contenu_qualite_produits +='<thead><tr>'
                    contenu_qualite_produits +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;"> Libellé</th>'
                    contenu_qualite_produits +='</tr></thead>'
                    contenu_qualite_produits +='<tbody>'
                    for (var i=0; i < listeQualiteProduits.data.length ; i++){
                        contenu_qualite_produits +='<tr class="tr_qualite_produit" id="'+ listeQualiteProduits.data[i].id + '" style="cursor:pointer;">'
                        contenu_qualite_produits +='<td class="p-0">' + listeQualiteProduits.data[i].libelle + '</td>'
                        contenu_qualite_produits +='</tr>'
                    }
                    contenu_qualite_produits +='</tbody>'
                    contenu_qualite_produits +='</table>'
                  } else { // le contrôle est un dropdown
                    contenu_qualite_produits +='Liste des qualite_produits'
                    contenu_qualite_produits +='<option value="0">Liste des qualite_produits</option>'
                    for (var i=0; i < listeQualiteProduits.data.length ; i++){
                        contenu_qualite_produits +='<option value="'+ listeQualiteProduits.data[i].id + '">' + listeQualiteProduits.data[i].libelle  + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_qualite_produits;
            } else {
                render_toast_value(listeQualiteProduits.msg, listeQualiteProduits.code)
            }
        }
    })
}

function getSingleQualiteProduit(id_qualite_produit) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleQualiteProduit/' + id_qualite_produit,
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

function getTarifQualiteProduit(id_qualite_produit, id_grille) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getTarifQualiteProduit/' + id_qualite_produit + '/' + id_grille,
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

function saveQualiteProduit(formData, value_ctrl, ctrl_name){
    fetch("/saveQualiteProduit", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getQualiteProduits(value_ctrl, ctrl_name)
                resetForm(formQPdt)
                setTimeout(()=>{
                    code_qualite_produit.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}



/*
EVENTS
*/

$("body").on('click','.tr_qualite_produit', function (){
    id_qualite_produit = this.id;
    resetForm(formQPdt)
    $("#pdtLabel").html('<img class="me-2" src="assets/icons/qualite_produit.png" alt="produits"> ...')
    $("#modal_ajt_qpdt").modal('show')
    getSingleQualiteProduit(id_qualite_produit)
        .then(qualite_produit => {
            $("#pdtLabel").html('<img class="me-2" src="assets/icons/qualite_produit.png" alt="produits"> Editer la qualite_produit <span class="text-danger fw-bold">'+ qualite_produit.libelle + '</span>')
            libelle.value = qualite_produit.libelle;
        })
        .catch(err => {
            console.error("Erreur :", err);
        });
})

$("#btn-enregistrer-qpdt").on("click", function (){
    let formData = new FormData()

    formData.append("id_qualite_produit", id_qualite_produit)
    formData.append("libelle", libelle.value)

    saveQualiteProduit(formData, 1, div_body)
})
