
let formClt = document.querySelector('#formClt');
let code_client = document.querySelector('#code_client');
let rs_client = document.querySelector('#rs_client');
let type_client = document.querySelector('#type_client');
let sigle = document.querySelector('#sigle');
let email = document.querySelector('#email');
let adresse = document.querySelector('#adresse');
let bp = document.querySelector('#bp');
let personne_ressource = document.querySelector('#personne_ressource');
let tel = document.querySelector('#tel');
let mobile = document.querySelector('#mobile');
let rccim = document.querySelector('#rccim');
let cc = document.querySelector('#cc');
let pays = document.querySelector('#pays');
let ville = document.querySelector('#ville');
let id_client = 0;


function getClients(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_clients = ''
    $.ajax({
        url : '/getClients',
        type : 'POST',
        success: function(response){
            let listeClients = JSON.parse(response);
            if (listeClients.code === "success"){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_clients +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_clt" data-bs-toggle="modal"  id="ajt-clt" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un client</button>'
                    contenu_clients +='<table class="table table-hover table-striped mt-2" style="border: 1px solid lightgrey;">'
                    contenu_clients +='<thead><tr>'
                    contenu_clients +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Code</th>'
                    contenu_clients +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;"><!--<img class="me-2" src="assets/icons/clients.png" alt="clients" height="32">--> Raison sociale</th>'
                    contenu_clients +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Contact</th>'
                    contenu_clients +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Actions</th>'
                    contenu_clients +='</tr></thead>'
                    contenu_clients +='<tbody>'
                    for (var i=0; i < listeClients.data.length ; i++){
                        contenu_clients +='<tr class="tr_client" id="'+ listeClients.data[i].id + '" style="cursor:pointer;">'
                        contenu_clients +='<td class="text-center fw-bold text-danger p-0">' + listeClients.data[i].code + '</td>'
                        contenu_clients +='<td class="p-0">' + listeClients.data[i].rs + '</td>'
                        contenu_clients +='<td class="p-0">' + listeClients.data[i].contacts + '</td>'
                        contenu_clients +='<td class="p-0"> - </td>'
                        contenu_clients +='</tr>'
                    }
                    contenu_clients +='</tbody>'
                    contenu_clients +='</table>'
                  } else { // le contrôle est un dropdown
                    contenu_clients +='Liste des clients'
                    contenu_clients +='<option value="0">Liste des clients</option>'
                    for (var i=0; i < listeClients.data.length ; i++){
                        contenu_clients +='<option value="'+ listeClients.data[i].id + '">' + listeClients.data[i].rs + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_clients;
            } else {
                showToast(listeClients.msg, {
                    type : listeClients.code,
                    duration: 2000,
                    title: 'Ouveture du fichier Clients avec succès'
                })

            }
        }
    })
}

function getSingleClient(id_client) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleClient/' + id_client,
            type: 'POST',
            success: function(response) {
                if (response.code === "success") {
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


function getTypesClients(combo){
    let contenu_t_clients = ''
    combo.innerHTML = '<option>Chargement...</option>'
    $.ajax({
        url : '/getTypesClients',
        type : 'POST',
        success: function(response){
            let typesClients = JSON.parse(response);
            if (typesClients.code === "success"){
                contenu_t_clients +='<option value="0">Sélectionnez un type client</option>'
                    for (var i=0; i < typesClients.data.length ; i++){
                        contenu_t_clients +='<option value="'+ typesClients.data[i].id + '">' + typesClients.data[i].libelle + '</option>'
                    }
                combo.innerHTML = contenu_t_clients;
            } else {
                showToast(typesClients.msg, {
                    type : typesClients.code,
                    duration: 2000,
                    title: 'Ouveture du fichier Types Clients avec succès'
                })
            }
        }
    })
}
function saveClient(formData, value_ctrl, ctrl_name){
    fetch("/saveClient", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            showToast(data.msg, {
                type : data.code,
                duration: 4000,
                title: 'Fichier Clients'
            })
            if (data.code === "success") {
                getClients(value_ctrl, ctrl_name)
                resetForm(formClt)
                setTimeout(()=>{
                    rs_client.focus()
                }, 500)
            }
        })
        .catch(err => showToast("❌ Erreur : " + err, {title: 'Fichier Clients', duration: 4000}));
}



        /*
        EVENTS
        */

$("body").on('click','#ajt-clt', function (){
    id_client = 0;
    resetForm(formClt)
    getTypesClients(type_client)
    getPays(2, pays)
    $("#cltLabel").html('<img class="me-2" src="assets/icons/client_32.png" alt="clients"> Nouveau client')
    setTimeout(()=>{
        rs_client.focus()
    }, 500)
})
$("body").on('click','.tr_client', function (){
    id_client = this.id;
    resetForm(formClt)
    getTypesClients(type_client)
    getPays(2, pays)
    $("#modal_ajt_clt").modal('show')
    getSingleClient(id_client)
        .then(client => {
            $("#cltLabel").html('<img class="me-2" src="assets/icons/client_32.png" alt="clients"> <span class="fw-bold">' + client.sigle + '</span>')
            code_client.value = client.code_client;
            rs_client.value = client.rs;
            sigle.value = client.sigle;
            type_client.value = client.type_client;
            adresse.value = client.adresse;
            bp.value = client.bp;
            email.value = client.email;
            mobile.value = client.mobile;
            tel.value = client.tel;
            rccim.value = client.rccim;
            cc.value = client.cc;
            personne_ressource.value = client.personne_ressource;
            pays.value = client.pays;
            getVillesByPays( pays.value , 2, ville)
            setTimeout(()=>{
                ville.value = client.ville;
            }, 500);

        })
        .catch(err => {
            console.error("Erreur :", err);
        });
})

$("#pays").on('change', function (){
    id_pays = this.value;
    let ville = document.querySelector("#ville");
    getVillesByPays(id_pays, 2, ville)
})

$("#btn-enregistrer").on("click", function (){
    let formData = new FormData()

    formData.append("id_client", id_client)
    formData.append("code_client", code_client.value)
    formData.append("rs_client", rs_client.value)
    formData.append("sigle", sigle.value)
    formData.append("type_client", type_client.value)
    formData.append("email", email.value)
    formData.append("adresse", adresse.value)
    formData.append("bp", bp.value)
    formData.append("tel", tel.value)
    formData.append("mobile", mobile.value)
    formData.append("rccim", rccim.value)
    formData.append("cc", cc.value)
    formData.append("personne_ressource", personne_ressource.value)
    formData.append("pays", pays.value)
    formData.append("ville", ville.value)

    saveClient(formData, 1, div_body)
})
