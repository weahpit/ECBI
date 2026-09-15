/*  API */
        function getProformas(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_Proformas = ''
    $.ajax({
        url : '/getProformas',
        type : 'POST',
        success: function(response){
            let listeProformas = JSON.parse(response);
            if (listeProformas.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_Proformas +='<div class="text-center">'
                    contenu_Proformas +='<table class="table table-hover table-striped ms-2 me-2" style="border: 1px solid lightgrey;">'
                    contenu_Proformas +='<thead><tr>'
                    contenu_Proformas +='<th class="fs-7 fw-light p-0" style="background-color: rgb(1,106,104);color:white;font-size: 12px;"></th>'
                    contenu_Proformas +='<th class="fs-7 fw-light p-0" style="background-color: rgb(1,106,104);color:white;font-size: 12px;">Référence</th>'
                    contenu_Proformas +='<th class="fs-7 fw-light p-0" style="background-color: rgb(1,106,104);color:white;font-size: 12px;"> Date</th>'
                    contenu_Proformas +='<th class="fs-7 fw-light p-0" style="background-color: rgb(1,106,104);color:white;font-size: 12px;"> Montant</th>'
                    contenu_Proformas +='<th class="fs-7 fw-light p-0  text-center" style="background-color: rgb(1,106,104);color:white;font-size: 12px;"> Client</th>'
                    contenu_Proformas +='</tr></thead>'
                    contenu_Proformas +='<tbody>'
                    for (var i=0; i < listeProformas.data.length ; i++){
                        contenu_Proformas +='<tr class="tr_Proforma" id="'+ listeProformas.data[i].id + '" style="cursor:pointer;">'
                        if (listeProformas.data[i].etat === 0){
                            contenu_Proformas +='<td class="text-center fw-bold p-0 text-dark" style=""><a class="p-0" href="/imprimerProforma/' + listeProformas.data[i].id + '" target="_blank"><i class="fas fa-file-pdf text-secondary"></i></a></td>'
                            contenu_Proformas +='<td class="text-center fw-bold text-dark text-dark" style="font-size: 12px;"><span class="mt-2">' + listeProformas.data[i].ref_Proforma + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 text-dark" style="font-size: 12px;"><span class="mt-2">' + listeProformas.data[i].dateProforma + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 fw-bold text-center text-dark" style="font-size: 12px;"><span class="mt-2">' + listeProformas.data[i].montant + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 text-dark" style="font-size: 12px;"><span class="mt-2">' + listeProformas.data[i].client + '</span></td>'
                        } else {
                            contenu_Proformas +='<td class="text-center fw-bold text-dark" style="background-color: #b0f2c2"><a class="text-white" title="Télécharger la proforma" style="border:1px solid lightgray;" href="/imprimerProforma/' + listeProformas.data[i].id + '" target="_blank"><i class="fas fa-file-pdf"></i></a></td>'
                            contenu_Proformas +='<td class="text-center fw-bold text-dark" style="font-size: 12px;background-color: #b0f2c2"><span class="mt-2">' + listeProformas.data[i].ref_Proforma + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 fw-bold text-dark" style="font-size: 12px;background-color: #b0f2c2"><span class="mt-2">' + listeProformas.data[i].dateProforma + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 fw-bold text-dark" style="font-size: 12px;background-color: #b0f2c2"><span class="mt-2">' + listeProformas.data[i].montant + '</span></td>'
                            contenu_Proformas +='<td class="pt-2 fw-bold text-dark" style="font-size: 12px;background-color: #b0f2c2"><span class="mt-2">' + listeProformas.data[i].client + '</span></td>'
                        }

                        contenu_Proformas +='</tr>'
                    }
                    contenu_Proformas +='</tbody>'
                    contenu_Proformas +='</table>'
                } else { // le contrôle est un dropdown
                    contenu_Proformas +='Liste des Proformas'
                    contenu_Proformas +='<option value="0" style="background-color: rgba(255,255,255,0.98);">Liste des Proformas</option>'
                    for (var i=0; i < listeProformas.data.length ; i++){
                        contenu_Proformas +='<option value="'+ listeProformas.data[i].id + '">' + listeProformas.data[i].ref_Proforma  + " / " + listeProformas.data[i].dateProforma  + ' (' + listeProformas.data[i].client  + ')</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_Proformas;
            } else {
                showToast(listeProformas.msg, {
                    type:  listeProformas.code,
                    title: 'Gestion des proformas',
                    duration: 6000,
                });
            }
        }
    })
}

        function getProformasByClient(id_client, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_Proformas = ''
    $.ajax({
        url : '/getProformasByClient/' + id_client,
        type : 'POST',
        success: function(response){
            let listeProformas = JSON.parse(response);
            if (listeProformas.code === 'success'){
                contenu_Proformas +='<option value="0" style="background-color: rgba(255,255,255,0.98);">Liste des Proformas</option>'
                for (var i=0; i < listeProformas.data.length ; i++){
                    contenu_Proformas +='<option value="'+ listeProformas.data[i].id + '">PF N° ' + listeProformas.data[i].ref_Proforma  + " du " + listeProformas.data[i].dateProforma  + '</option>'
                }

                ctrl_name.innerHTML = contenu_Proformas;
            } else {
                showToast(listeProformas.msg, {
                    type:  listeProformas.code,
                    title: 'Gestion des proformas',
                    duration: 6000,
                });
            }
        }
    })
}



        function getTarif(produit, id_grille, ctrl){
    getTarifProduit(produit.value, id_grille)
        .then(produit => {
            ctrl.value = produit.prix
        })
        .catch(err => {
            console.error("Erreur :", err);
        });
}
        function setInputsValue(){
    let totalHt = 0;
    let rowLength = document.querySelector("#tblProforma tbody").rows.length;
    for (var i=0; i< rowLength ; i++){
        totalHt += parseFloat(document.querySelector("#tblProforma tbody").rows[i].querySelector(".total").value)
    }
    return totalHt
}

        function SelectChange(){
            if (
                $("#client").val() !== "0" &&
                $("#client").val() !== null &&
                $("#grille").val() !== "0" &&
                $("#grille").val() !== null
            ){
                $("#btnAddRow").prop('disabled', false);
            } else {
                $("#btnAddRow").prop('disabled', true);
            }
        }
        function saveProforma(formData, value_ctrl, ctrl_name){
    fetch("/saveProforma", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            showToast(data.msg, {
                type:  data.code,
                title: 'Enregistrement Proforma',
                duration: 6000,
            });
            if (data.code === 'success') {
                getProformas(value_ctrl, ctrl_name)
                resetForm(formProforma)
                $("#btnAddRow").prop('disabled', true);
                document.querySelector("#tblProforma tbody").innerHTML = ""
                document.querySelector("#en_lettre").innerHTML = ""
                en_lettre = "";
                document.location.target("_blank")
                document.location.href = '/imprimerProforma/' + data.id
            }
        })
        .catch(err => showToast("❌ Erreur : " + err, {title : 'Enregistrement Proforma', duration: 4000 }));
}
        function getSingleProforma(id_produit) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/getSingleProforma/' + id_produit,
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


       function calcul_remise(total_ht, taux, input_remise){
            input_remise.value = parseInt(total_ht) * parseInt(taux )/ 100;
        }
/*  FIN API */

