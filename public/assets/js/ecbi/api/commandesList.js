
function loadListeCommandesInterface(control){
            control.innerHTML = ` <h2 class="text-secondary mb-4 lbl_commande">Mes commandes</h2>

                <!-- Barre de progression -->
                <div class="row m-2" style="border: 1px solid lightgrey">
                    <div id="listeCommandes" class="col-md-4 bg-white p-2" style="border-top: 1px solid lightgrey;">
                        <h5 class="text-light fw-light  w-100 p-1 ps-4" style="background: #7d7c7c;">Dernières commandes</h5>
                    </div>

                    <div id="details_commandes" class="col-md-8 p-2" style="">
                        <h5 class="text-light fw-light  w-100 p-1 ps-4" style="background: #7d7c7c;">Détails</h5>
                        <div class="" id="contenu_commande"></div>
                    </div>
             </div>`;

            getCommandes(1, document.getElementById("listeCommandes"))
            $("body").on('click', '.tr_commande', function(){
        let div_infos_commande = document.getElementById("contenu_commande")
        if (this.value === "0"){
            div_infos_commande.innerHTML = ""
        } else {
            getSingleCommande(this.id)
                .then(commande => {
                    div_infos_commande.innerHTML = `
                            <fieldset class="bg-white mb-2">
                                <legend class="fw-light h6"><u>Commande</u></legend>
                                <div class="row">
                                    <div class="ps-4 col-5" style="font-size:13px;">
                                        <label style="width:125px;color:darkred;"><span class="badge bg-secondary border border-1 border-white fw-bold">${commande.statut}</span></label><br>
                                        <label style="width:125px;">N° Commande</label>:<span class="fw-bold text-danger">${commande.numero_commande}</span><br>
                                        <label style="width:125px;">Proforma N° </label>:<span class="fw-bold">${commande.quotation} du ${commande.date_quotation}</span><br>
                                        <label style="width:125px;">Date</label>:<span class="fw-bold">${commande.date_commande}</span><br>
                                        <label style="width:125px;">Commercial</label>:<span class="fw-bold">${commande.user} </span><br>
                                        <label style="width:125px;">Conditions</label>:<span class="fw-bold">${commande.conditions}</span><br>
                                    </div>
                                    <div class="col-7">
                                        <table class="table-hover w-100" style="border: 1px solid #e5e5e5;width: 95%;">
                                               <thead>
                                                   <tr class="w-100">
                                                        <th style="background: linear-gradient(#e0e0e0, #cdcdcd)">Désignation</th>
                                                        <th style="background: linear-gradient(#e0e0e0, #cdcdcd)">PU</th>
                                                        <th style="background: linear-gradient(#e0e0e0, #cdcdcd)">Qte</th>
                                                        <th style="background: linear-gradient(#e0e0e0, #cdcdcd)">Total</th>
                                                    </tr>
                                               </thead>
                                               <tbody class="commande_tbody">
                                               </tbody>
                                        </table>
                                        <hr>
                                        <div class="" style="display: flex;justify-content: flex-end;">
                                            <div id="div_montant" style="float: right;margin-right: 20px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mb-0">
                                <div class="p-2 bg-light" style="background: #ccf0e6" id="div_fichiers">

                                </div>
                            </fieldset>
                        `
                    let contenu = "";
                    let contenu_fichier = "";
                    for(var i=0; i < commande.data.length;i++){
                        contenu +='<tr style="font-size: 13px;">'
                        contenu +='<td>'+ commande.data[i].libelle_produit+ '</td>'
                        contenu +='<td>'+ commande.data[i].pu+ '</td>'
                        contenu +='<td>'+ commande.data[i].qte+ '</td>'
                        contenu +='<td>'+ commande.data[i].net+ '</td>'
                        contenu +='</tr>'
                    }

                    contenu +='</tbody></table>'
                    contenu +='</tbody></table>'
                    document.querySelector(".commande_tbody").innerHTML = contenu;

                    contenu ='<label style="width:125px;">Montant HT</label>:<span class="fw-bold ms-1" style="font-size: 14px;">'+ numStr(commande.total_ht, " ")+ ' F CFA</span><br>'
                    contenu +='<label style="width:125px;">TVA</label>:<span class="fw-bold ms-1" style="font-size: 14px;">'+ numStr(commande.tva, " ")+ ' F CFA</span><br>'
                    contenu +='<label style="width:125px;">Remise</label>:<span class="fw-bold ms-1" style="font-size: 14px;">'+ numStr(commande.remise, " ")+ ' F CFA</span><br>'
                    contenu +='<label style="width:125px;">Montant TTC</label>:<span class="badge bg-danger text-white fw-bold" style="font-size: 14px;">'+ numStr(commande.total_ttc, " ")+ ' F CFA</span>'
                    document.querySelector("#div_montant").innerHTML = contenu;
                    contenu_fichier = '<h6 class="mb-2"><u>Fichiers associés</u></h6>'
                    for(var j=0; j< commande.data_fichiers.length;j++){
                        contenu_fichier +='<a class=" ms-2 p-2 mb-2 a_commande w-100 text-dark" target=" _blank" style="box-shadow: #555555;border-radius: 4px;border:  1px solid lightgrey;width:100%;background: #c9ebc9" href="docs/bc/'+ commande.data_fichiers[j].fichier+ '" >'+ commande.data_fichiers[j].fichier +'</a>'
                    }
                    document.querySelector("#div_fichiers").innerHTML = contenu_fichier;

                })
                .catch(err => {
                    console.error("Erreur :", err);
                });
        }

    } )
    }



function getCommandes(value_ctrl, ctrl_name){
    let contenu_commandes = ''
    $.ajax({
        url : '/getCommandes',
        type : 'POST',
        success: function(response){
            let listeCommandes = JSON.parse(response);
            if (listeCommandes.code === "success"){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_commandes +='<input type="text" id="searchInput" placeholder="Rechercher...">'
                    contenu_commandes +='<table class="table-hover table-striped mt-2" id="table_commandes" style="border: 1px solid lightgrey;">'
                    contenu_commandes +='<thead><tr>'
                    contenu_commandes +='<th class="fs-7 fw-light p-1" style="background: linear-gradient(#cb9c03,#926903);color:white;">Réf</th>'
                    contenu_commandes +='<th class="fs-7 fw-light p-1" style="background: linear-gradient(#cb9c03,#926903);color:white;"> Client</th>'
                    contenu_commandes +='<th class="fs-7 fw-light p-1" style="background: linear-gradient(#cb9c03,#926903);color:white;"> Date</th>'
                    contenu_commandes +='<th class="fs-7 fw-light p-1" style="background: linear-gradient(#cb9c03,#926903);color:white;">Net à Payer</th>'
                    contenu_commandes +='</tr></thead>'
                    contenu_commandes +='<tbody>'
                    for (var i=0; i < listeCommandes.data.length ; i++){
                        contenu_commandes +='<tr class="tr_commande p-1" id="'+ listeCommandes.data[i].id + '" style="cursor:pointer;font-size: 12px;padding: 4px;">'
                        contenu_commandes +='<td class="text-center fw-bold" style="font-size: 12px;padding: 4px;">' + listeCommandes.data[i].numero_commande + '</td>'
                        contenu_commandes +='<td class="text-center " style="font-size: 12px;padding: 4px;">' + listeCommandes.data[i].client + '</td>'
                        contenu_commandes +='<td class="text-center" style="font-size: 12px;padding: 4px;">' + listeCommandes.data[i].date_commande + '</td>'
                        contenu_commandes +='<td class="text-center fw-bold" style="font-size: 12px;padding: 4px;">' + numStr(listeCommandes.data[i].montant, " ") + '</td>'
                        contenu_commandes +='</tr>'
                    }
                    contenu_commandes +='</tbody>'
                    contenu_commandes +='</table>'
                    contenu_commandes +='<div class="pagination" id="pagination" ></div>'
                 } else { // le contrôle est un dropdown
                    contenu_commandes +='Liste des commandes'
                    contenu_commandes +='<option value="0">Liste des commandes</option>'
                    for (var i=0; i < listeCommandes.data.length ; i++){
                        contenu_commandes +='<option value="'+ listeCommandes.data[i].id + '">' + listeCommandes.data[i].numero_commande  + ' (' + listeCommandes.data[i].client  + ')</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_commandes;
                activerCouleurSurClic(document.getElementById("table_commandes"), "rgba(182,136,7,0.55)")
                RechercherEtPaginerTable(
                    document.getElementById("table_commandes"),
                    10,
                    document.getElementById("pagination"),
                    document.getElementById("searchInput")
                    )
            } else {
                showToast(listeCommandes.msg, {
                    title: 'Commandes',
                    duration : 6000
                })
            }
        }
    })
}
