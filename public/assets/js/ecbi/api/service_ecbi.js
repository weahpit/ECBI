
let formServ = document.querySelector('#formServ');
let libelle_service_ecbi = document.querySelector('#libelle_service');
let id_service_ecbi = 0;

function getServiceEcbis(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_service_ecbis = ''
    $.ajax({
        url : '/getServiceEcbis',
        type : 'POST',
        success: function(response){
            let listeServiceEcbis = JSON.parse(response);
            if (listeServiceEcbis.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_service_ecbis +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_service" data-bs-toggle="modal"  id="ajt_service" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un service</button>'
                    contenu_service_ecbis +='<table class="w-100 table-hover table-striped mt-2" id="table_services" style="border: 1px solid lightgrey;">'
                    contenu_service_ecbis +='<thead><tr>'
                    contenu_service_ecbis +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Nom du service</th>'
                    contenu_service_ecbis +='</tr></thead>'
                    contenu_service_ecbis +='<tbody>'
                    for (var i=0; i < listeServiceEcbis.data.length ; i++){
                        contenu_service_ecbis +='<tr class="tr_service_ecbi" id="'+ listeServiceEcbis.data[i].id + '" style="cursor:pointer;">'
                        contenu_service_ecbis +='<td class="p-0">' + listeServiceEcbis.data[i].libelle + '</td>'
                        contenu_service_ecbis +='</tr>'
                    }
                    contenu_service_ecbis +='</tbody>'
                    contenu_service_ecbis +='</table>'
                  } else { // le contrôle est un dropdown
                    contenu_service_ecbis +='Liste des services'
                    contenu_service_ecbis +='<option value="0">Liste des services</option>'
                    for (var i=0; i < listeServiceEcbis.data.length ; i++){
                        contenu_service_ecbis +='<option value="'+ listeServiceEcbis.data[i].id + '">' + listeServiceEcbis.data[i].libelle + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_service_ecbis;
                //activerCouleurSurClic(document.getElementById("table_services"), "rgba(182,136,7,0.55)")
            } else {
                render_toast_value(listeServiceEcbis.msg, listeServiceEcbis.code)
            }
        }
    })
}

function getSingleServiceEcbi(id_service_ecbi) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleServiceEcbi/' + id_service_ecbi,
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



function saveServiceEcbi(formData, value_ctrl, ctrl_name){
    fetch("/saveServiceEcbi", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getServiceEcbis(value_ctrl, ctrl_name)
                resetForm(formServ)
                setTimeout(()=>{
                    libelle_service_ecbi.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}


/*
EVENTS
*/

$("body").on('click','#ajt_service', function (){
    id_service_ecbi = 0;
    $("#servLabel").html('<img class="me-2" src="assets/icons/service_direction.png" alt="Services"> Enregistrer un service')
    resetForm(formServ)
    setTimeout(()=>{
        libelle_service_ecbi.focus()
    }, 500)
})

$("body").on('click','.tr_service_ecbi', function (){
    id_service_ecbi = this.id;
    resetForm(formServ)
    $("#servLabel").html('<img class="me-2" src="assets/icons/service_direction.png" alt="Services"> ...')
    $("#modal_ajt_service").modal('show')
    getSingleServiceEcbi(id_service_ecbi)
        .then(service_ecbi => {
            $("#servLabel").html('<img class="me-2" src="assets/icons/service_direction.png" alt="Services"> Editer le service <span class="text-danger fw-bold">'+ service_ecbi.libelle+ '</span>')
            libelle_service_ecbi.value = service_ecbi.libelle;
        })
        .catch(err => {
            console.error("Erreur :", err);
        });
})
keypress_input(document.getElementById("libelle_service"), document.getElementById('btn-enregistrer-service'))
$("#btn-enregistrer-service").on("click", function (e){
    e.preventDefault()
    let formData = new FormData()

    formData.append("id_service_ecbi", id_service_ecbi)
    formData.append("libelle_service_ecbi", libelle_service_ecbi.value)

    saveServiceEcbi(formData, 1, div_body)
})
