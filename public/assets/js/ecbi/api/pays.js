let formPays = document.querySelector('#formPays');
let id_pays = 0;
let libelle_pays = document.querySelector('#libelle_pays');

function getPays(value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_pays = ''
    $.ajax({
        url : '/getPays',
        type : 'POST',
        success: function(response){
            let listePays = JSON.parse(response);
            if (listePays.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                    contenu_pays +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_pays" data-bs-toggle="modal"  id="ajt-pays" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un pays</button>'
                    contenu_pays +='<table class="table table-hover table-striped mt-2" style="border: 1px solid lightgrey;">'
                    contenu_pays +='<thead><tr>'
                    contenu_pays +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Pays</th>'
                    contenu_pays +='</tr></thead>'
                    contenu_pays +='<tbody>'
                    for (var i=0; i < listePays.data.length ; i++){
                        contenu_pays +='<tr class="tr_pays" id="'+ listePays.data[i].id + '" style="cursor:pointer;">'
                        contenu_pays +='<td class="text-center fw-bold text-danger p-0">' + listePays.data[i].libelle + '</td>'
                        contenu_pays +='</tr>'
                    }
                    contenu_pays +='</tbody>'
                    contenu_pays +='</table>'
                } else { // le contrôle est un dropdown
                    contenu_pays +='Liste des pays'
                    contenu_pays +='<option value="0">Liste des pays</option>'
                    for (var i=0; i < listePays.data.length ; i++){
                        contenu_pays +='<option value="'+ listePays.data[i].id + '">' + listePays.data[i].libelle + '</option>'
                    }
                    console.log(contenu_pays)
                }

                ctrl_name.innerHTML = contenu_pays;
            } else {
                render_toast_value(listePays.msg, listePays.code)
            }
        }
    })
}

function getVillesByPays(id_pays,value_ctrl, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_pays = ''
    $.ajax({
        url : '/getVillesByPays/' + id_pays,
        type : 'POST',
        success: function(response){
            let listePays = JSON.parse(response);
            if (listePays.code === 'success'){
                if (value_ctrl === 1){ // le contrôle est une table
                   // contenu_pays +='<div class="mb-2 text-center"><button type="button" data-bs-target="#modal_ajt_ville" data-bs-toggle="modal"  id="ajt-ville" style="background-color: #047bbb;padding: 5px 10px;border-radius: 0;color: white;border: 0;">Ajouter un pays</button>'
                    contenu_pays +='<table class="table table-hover table-striped mt-2" style="border: 1px solid lightgrey;">'
                    contenu_pays +='<thead><tr>'
                    contenu_pays +='<th class="fs-7 fw-light p-0" style="background: linear-gradient(#cb9c03,#926903);color:white;">Ville</th>'
                    contenu_pays +='</tr></thead>'
                    contenu_pays +='<tbody>'
                    for (var i=0; i < listePays.data.length ; i++){
                        contenu_pays +='<tr class="tr_pays" id="'+ listePays.data[i].id + '" style="cursor:pointer;">'
                        contenu_pays +='<td class="text-center fw-bold text-danger p-0">' + listePays.data[i].libelle + '</td>'
                        contenu_pays +='</tr>'
                    }
                    contenu_pays +='</tbody>'
                    contenu_pays +='</table>'
                } else { // le contrôle est un dropdown
                    contenu_pays +='Liste des pays'
                    contenu_pays +='<option value="0">Sélectionnez la ville</option>'
                    for (var i=0; i < listePays.data.length ; i++){
                        contenu_pays +='<option value="'+ listePays.data[i].id + '">' + listePays.data[i].libelle + '</option>'
                    }
                }
                ctrl_name.innerHTML = contenu_pays;
            } else {
                render_toast_value(listePays.msg, listePays.code)
            }
        }
    })
}

function getSinglePays(id_pays) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/getSinglePays/' + id_pays,
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

function savePays(formData, value_ctrl, ctrl_name){
    fetch("/savePays", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getPays(value_ctrl, ctrl_name)
                resetForm(formPays)
                document.getElementById("div_villes").innerHTML = ''
                setTimeout(()=>{
                    libelle_pays.focus()
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}

function saveVille(formData, value_ctrl, ctrl_name){
    fetch("/saveVille", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            render_toast_value(data.msg, data.code)
            if (data.code === 'success') {
                getVillesByPays(id_pays, value_ctrl, ctrl_name)
                setTimeout(()=>{
                    if (document.getElementById("libelle_ville")){
                        document.getElementById("libelle_ville").focus()
                    }
                }, 500)
            }
        })
        .catch(err => render_toast_value("❌ Erreur : " + err, 0));
}
/*
EVENTS
*/

$("body").on('click','#ajt-pays', function (){
    id_pays = 0;
    resetForm(formPays)
    document.getElementById("div_villes").innerHTML = ''
        libelle_pays.focus()
})
$("body").on('click','.tr_pays', function (){
            id_pays = this.id;
            resetForm(formPays)
            $("#modal_ajt_pays").modal('show')
            getSinglePays(id_pays)
                .then(pays => {
                    libelle_pays.value = pays.libelle;
                    // Chargement et saisie de nouvelle villes
                    document.querySelector("#div_villes").innerHTML = `
                <p class="text-primary fw-bold p-0">Enregistrer une ville dans la grille ci-dessous</p>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text fw-bold">Ville</span>
                        <input type="text" class="form-control fw-bold" id="libelle_ville" required aria-label="Ville" aria-describedby="libelle_ville" style="text-transform: uppercase;border: 2px solid black;">
                        <button type="button" class="btn btn-primary p-0 ms-2" id="ajt-ville" style="width: 30px;"><i class="fa fa-solid fa-plus"></i></button>
                    </div>
                    <div class="table-striped"  id="liste_ville"></div>
                    `

                    $("#ajt-ville").on("click", function (e){
                        e.preventDefault()
                        let formData = new FormData()

                        formData.append("id_pays", id_pays)
                        formData.append("libelle_ville", document.querySelector("#libelle_ville").value)

                        saveVille(formData, 1, document.querySelector("#liste_ville"))
                    })

                    $('body').on('keypress', '#libelle_ville', function (e){
                        if (e.key === "Enter"){
                            e.preventDefault()
                            document.querySelector("#ajt-ville").click()
                            document.querySelector("#libelle_ville").value = ""
                            document.querySelector("#libelle_ville").focus()
                        }
                    })
                    getVillesByPays(id_pays, 1, document.querySelector("#liste_ville"))
                })
                .catch(err => {
                    console.error("Erreur :", err);
                });
        })
$('body').on('keypress', '#libelle_pays', function (e){
            if (e.key === "Enter"){
                e.preventDefault()
                document.querySelector("#btn-enregistrer-pays").click()
            }
        })
$("#btn-enregistrer-pays").on("click", function (){
    let formData = new FormData()

    formData.append("id_pays", id_pays)
    formData.append("libelle_pays", libelle_pays.value)

    savePays(formData, 1, div_body)
})
