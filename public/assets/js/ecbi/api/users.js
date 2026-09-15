
let formUsers = document.querySelector('#formUsers');
let nom = document.querySelector('#nom');
let prenoms = document.querySelector('#prenoms');
let email_user = document.querySelector('#email_user');
let mobile_user = document.querySelector('#mobile_user');
let serviceEcbi = document.querySelector('#serviceEcbi');
let poste = document.querySelector('#poste');
let titre = document.querySelector('#titre');
let mot_passe = document.querySelector('#mot_passe');
let confirme_mot_passe = document.querySelector('#confirme_mot_passe');
let div_mot_passe = document.querySelector('#div_mot_passe');
let div_confirme_mot_passe = document.querySelector('#div_confirme_mot_passe');
let id_user = 0;

function getUsers(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_users = ''
    $.ajax({
        url : '/getUsers',
        type : 'POST',
        success: function(response){
            let listeUsers = JSON.parse(response);
            if (listeUsers.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_users +='<div class="mb-2 text-center"><button type="button" id="ajt-user" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un user</button>'
                    contenu_users +='<table class="w-100 table-hover table-striped mt-2" id="table_users" style="border: 1px solid lightgrey;">'
                    contenu_users +='<thead class="w-100"><tr>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Nom & Prénoms</th>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Email</th>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Mobile</th>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Service</th>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Poste</th>'
                    contenu_users +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Action</th>'
                    contenu_users +='</tr></thead>'
                    contenu_users +='<tbody>'
                    for (var i=0; i < listeUsers.data.length ; i++){
                        contenu_users +='<tr class="tr_user" id="'+ listeUsers.data[i].id + '" style="cursor:pointer;font-size:12px;">'
                        contenu_users +='<td class="fw-bold text-dark p-0">' + listeUsers.data[i].nom_prenoms + '</td>'
                        contenu_users +='<td class="p-0">' + listeUsers.data[i].email + '</td>'
                        contenu_users +='<td class="p-0">' + listeUsers.data[i].mobile + '</td>'
                        contenu_users +='<td class="p-0">' + listeUsers.data[i].service + '</td>'
                        contenu_users +='<td class="p-0">' + listeUsers.data[i].poste + '</td>'
                        contenu_users +='<td class="p-0"><a class="changer_mdp"  id="'+ listeUsers.data[i].id + '" data-bs-toggle="modal" data-bs-target="#modal_mdp"><i class="fa fa-key"></i> </a></td>'
                        contenu_users +='</tr>'
                    }
                    contenu_users +='</tbody>'
                    contenu_users +='</table>'
                  } else { // le contrôle est un dropdown
                    contenu_users +='Liste des users'
                    contenu_users +='<option value="0">Liste des utilisateurs</option>'
                    for (var i=0; i < listeUsers.data.length ; i++){
                        contenu_users +='<option value="'+ listeUsers.data[i].id + '">' + listeUsers.data[i].nom_prenoms  +'</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_users;
                activerCouleurSurClic(document.getElementById("table_users"), "rgba(182,136,7,0.55)")
            } else {
                render_toast_value(listeUsers.msg, listeUsers.code)
            }
        }
    })
}

function getSingleUser(id_user) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSingleUser/' + id_user,
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
function saveUser(formData, value_ctrl, ctrl_name){
    fetch("/saveUser", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                id_user = 0;
                getUsers(value_ctrl, ctrl_name)
                resetForm(formUsers)
                setTimeout(()=>{
                    nom.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}

function changeMdp(formData, ){
    fetch("/changeMdp", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                    document.querySelector("#modal_mdp .btn-close").close()
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}
