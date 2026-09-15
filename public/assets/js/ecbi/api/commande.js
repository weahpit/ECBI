
function loadCommandesInterface(control){
            control.innerHTML = ` <h2 class="text-secondary mb-4 lbl_commande">Enregistrement de la commande N° <span class="fw-bold text-danger">${numero_commande}</span></h2>

                <!-- Barre de progression -->
                <div class="progress mb-4 ms-2">
                    <div id="progressBar" class="progress-bar bg-warning text-dark" role="progressbar" style="width: 50%">
                        Étape 1/3
                    </div>
                </div>

                <!-- Étape 1 : Informations Proforma -->
                <div id="step1" class="ms-2 bg-white p-2" style="border-top: 1px solid lightgrey;">
                    <h5 class="text-light fw-light  w-100 p-1 ps-4" style="background: #7d7c7c;">Informations</h5>
                    <form id="step1Commande">
                       <div class="row">
                           <div class="mb-3 col-md-12">
                               <label for="client" class="form-label">Client <span class="text-danger">*</span></label>
                               <select id="client" required></select>
                               <label for="proforma" class="form-label ms-4">Proforma <span class="text-danger">*</span></label>
                               <select id="proforma" required></select>
                               <div class="mb-3 ms-4 d-inline-flex">
                                   <label for="dateCommande" class="form-label">Date Commande <span class="text-danger">*</span></label>
                                   <input type="date" class="form-control fw-bold text-center ms-2" id="dateCommande" style="width:150px;padding : 2px;" required disabled>
                                </div>
                           </div>
                       </div>
                        <hr>
                        <div class="row bg-light m-2" style="min-height: 300px;border: 1px solid lightgrey;">
                                <div class="col-md-4" id="div_infos_client">

                                </div>
                                 <div class="col-md-8" id="div_proforma">

                                </div>
                        </div>

                        <hr>
                        <button type="button" class="btn btn-warning mt-2" onclick="goToStep(2)">Suivant ➡️</button>
                    </form>
                </div>

                <!-- Étape 2 : Edition Commande -->
                <div id="step2" class="ms-2 bg-white p-2" style="border-top: 1px solid lightgrey;display:none;">
                    <h5 class="text-light fw-light  w-100 p-1 ps-4" style="background: #7d7c7c;">Editer la commande depuis la proforma sélectionnée</h5>
                        <form id="step2Commande">
                            <div class="p-2 m-2 bg-light" style="border: 1px solid lightgrey;min-height: 300px;max-height: 500px;">
                                <div class="text-center p-1"><button id="btnAddRow" type="button" class="btn btn-primary">➕ Ajouter un produit</button></div>
                                <table class="w-100 table table-responsive" id="tblProforma">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 5%">N°</th>
                                            <th style="width: 35%">Produit</th>
                                            <th class="text-center" style="width: 15%">Quantité</th>
                                            <th class="text-center" style="width: 15%">Prix Unitaire</th>
                                            <th class="text-center" style="width: 15%">Prix Total</th>
                                            <th class="text-center" style="width: 15%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                    </tbody>
                               </table>
                               <hr>
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="mt-5" style="bottom: 10px" id="en_lettre"></h5>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mt-2 p-2" style="border: 1px solid lightgrey;">
                                            <div class="input-group">
                                                <label class="fw-bold" for="Total" style="width: 120px;">Total HT</label>
                                                <input type="number" id="total_ht" class="fw-bold" readonly style="width: 150px;text-align: right;">
                                            </div>
                                            <div class="input-group mt-1">
                                                <label class="fw-bold" for="remise" style="width: 120px;">Remise (%)</label>
                                                <select id="taux_remise" class="fw-bold text-center"  style="min-width: 20px;background: #fcf1d5;"></select>
                                                <input type="number" id="remise" class="fw-bold" readonly value="0" style="width: 110px;text-align: right;">
                                            </div>
                                            <hr>
                                            <div class="input-group">
                                                <label class="fw-bold" for="tva" style="width: 120px;">TVA ({{ tva }}%)</label>
                                                <input type="number" id="tva" class="fw-bold" readonly style="width: 150px;text-align: right;">
                                            </div>
                                            <div class="input-group">
                                                <label class="fw-bold" for="total_ttc" style="width: 120px;">Total TTC</label>
                                                <input type="number" id="total_ttc" class="fw-bold" readonly style="width: 150px;text-align: right;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<button class="btn btn-success mt-2" id="soumettre_proforma" type="button" style="float: right;">Soumettre</button>-->

                        <hr>
                        <button type="button" class="btn btn-secondary mt-2" onclick="goToStep(1)">⬅️ Précédent</button>
                       <button type="button" class="btn btn-warning mt-2" onclick="goToStep(3)">Suivant ➡️</button>
                       </form>
                </div>

                <!-- Étape 3 : Documentation -->
                <div id="step3" style="display:none;">
                    <h4></h4>
                    <h5 class="text-light fw-light  w-100 p-1 ps-4" style="background: #7d7c7c;">Documentation</h5>
                        <!-- Zone de drop -->
                            <div id="dropZone" class="drop-zone">
                                <p>Glissez vos fichiers ici ou cliquez pour sélectionner</p>
                                <input type="file" id="fileInput" multiple hidden>
                            </div>

                            <!-- Liste des fichiers -->
                            <div class="mt-1 mx-auto mb-2" id="fileList" style="width: 75%;"></div>
                            <hr>
                        <button type="button" class="btn btn-secondary mt-2" onclick="goToStep(2)">⬅️ Précédent</button>
                        <button type="button" id="sendBtn" class="btn btn-success mt-2">✅ Enregistrer</button>
                </div>`;
                document.getElementById("btnAddRow").addEventListener("click", function() {
                        const tableBody = document.querySelector("#tblProforma tbody");
                        // Créer une nouvelle ligne
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td><input class="text-center fw-bold" id="row_number" type="text"  style="width: 50px;padding: 0;" readonly></td>
                            <td ><select class="produit" style="width: 250px;"></select></td>
                            <td><input style="width:  75px;background: #fcf1d5;" type="number" class="qty text-center fw-bold" value="0"></td>
                            <td><input style="width:  100px;padding: 0;" type="number" readonly class="prixUnitaire text-center fw-bold" value="0"></td>
                            <td><input style="width:  150px;padding: 0;" type="number" readonly class="total text-center fw-bold" value="0"></td>
                            <td><button class="btn btn-danger text-white btn-sm removeRow">🗑️</button></td>
                        `;
                        row.style.backgroundColor = "lightgrey"
                        tableBody.appendChild(row);

                        getTaux(document.getElementById("taux_remise"));

                         document.querySelector("#taux_remise").addEventListener("change", ()=>{
                        let valeur = document.querySelector("#taux_remise").value;
                        //alert()
                        calcul_remise(
                            document.querySelector("#total_ht").value,
                            valeur,
                            document.querySelector("#remise")
                        )
                        calcul_montants()

                    })

        // recalculer le total quand quantité ou prix changent
        row.querySelector("#row_number").value = row.rowIndex

        row.addEventListener("keypress", (e)=>{

            if (e.key === "Enter"){
                e.preventDefault()
                document.getElementById("btnAddRow").click()
                row.querySelector(".produit").focus()
            }
        });

        row.querySelector(".qty").addEventListener("input", updateRowTotal);
        row.querySelector(".produit").addEventListener("change", updateRowTotal);
        row.querySelector(".prixUnitaire").addEventListener("input", updateRowTotal);
        row.querySelector(".produit").addEventListener("load", getOnlyProduitsFromProforma(id_proforma,  row.querySelector(".produit")));



        // supprimer la ligne
        row.querySelector(".removeRow").addEventListener("click", function() {
            row.remove();
        });

        setTimeout(function (){
            $(".produit").on("change", function (){
                getTarifFromProforma(this.value, id_proforma,  row.querySelector(".prixUnitaire"))
                $(".qty").focus()
                $(".qty").select()
                /*setInputsValue()*/
            });
        }, 500)
    });


    /*$("#soumettre_proforma").on("click", function (){
        let formData = new FormData()

        let lignes = [];
        $("#tblProforma tbody tr").each(function () {

            lignes.push({

                produit: $(this).find(".produit").val(),
                quantite: $(this).find(".qty").val(),
                prixUnitaire: $(this).find(".prixUnitaire").val(),
                total: $(this).find(".total").val()
            });
        });
        let array_lignes = JSON.stringify(lignes);

        formData.append("id_proforma", id_proforma)
        formData.append("client", client.value)
        formData.append("grille", grille.value)
        formData.append("date_proforma", dateProforma.value)
        formData.append("lignes", array_lignes)
        formData.append("total_ht", total_ht.value)
        formData.append("tva", tva.value)
        formData.append("total_ttc", total_ttc.value)
        formData.append("remise", remise.value)
        formData.append("taux_remise", taux_remise.value)
        formData.append("validiteProforma", validiteProforma.value)
        formData.append("en_lettre", en_lettre)



        saveProforma(formData, 1, document.getElementById("mes_proformas"))
    })*/


    $("body").on('click','.tr_Proforma', function (){
        id_produit = this.id;
        reinitialise()
        getSingleProforma(id_produit)
            .then(produit => {
                code_produit.value = produit.code_produit;
                libelle_produit.value = produit.libelle_produit;
                libelle_produit.value = produit.qualite_produit;
                description_produit.value = produit.description_produit;
            })
            .catch(err => {
                console.error("Erreur :", err);
            });
    })

    function ToutEnLettres(){
        if ($('#total_ttc').val() > 0) {
            $("#en_lettre").html('<span style="font-size: 14px;">Arrêtez le présent Bon de Commande à la somme de <br><span class="p-1" style="background-color: #d4d4d4;border: 1px solid lightgray;border-radius: 4px;"><b>' + nombreEnLettres($('#total_ttc').val()) + '</b> Francs CFA !</span></span>')
            en_lettre = nombreEnLettres($('#total_ttc').val());
        }
    }
    function calcul_montants(){
        $('#total_ht').val(setInputsValue())
        let net_commercial = $('#total_ht').val() - parseFloat($('#remise').val());
        $('#tva').val(Math.round(net_commercial * taux_tva * 0.01))
        $('#total_ttc').val(net_commercial + parseFloat($('#tva').val()));
        ToutEnLettres()
    }

    function updateRowTotal(e) {
        const row = e.target.closest("tr");
        const qty = parseFloat(row.querySelector(".qty").value) || 0;

        const price = parseFloat(row.querySelector(".prixUnitaire").value) || 0;
        const total = qty * price;
        row.querySelector(".total").value = (total.toFixed(0)).toLocaleString();
        calcul_montants()
    }
    function reinitialise(){
        resetForm(formProforma)
        $("#total_ht").val(0)
        $("#remise").val(0)
        $("#taux_remise").val(0)
        $("#tva").val(0)
        $("#total_ttc").val(0)
        $("#en_lettre").html("")
    }

    /*
    -----------------------------------------------------------------------------------------------------------------------------------
    -----------------------------------------------------------------------------------------------------------------------------------
    */

            // Mettre la date du jour par défaut
                getDateJour(document.getElementById("dateCommande"));
                getClients(2,document.getElementById("client"))
                const dropZone = document.getElementById("dropZone");
                const fileInput = document.getElementById("fileInput");
                const fileList = document.getElementById("fileList");
                const sendBtn = document.getElementById("sendBtn");

                let selectedFiles = [];

                dropZone.addEventListener("click", () => fileInput.click());

                dropZone.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    dropZone.classList.add("dragover");
                });
                dropZone.addEventListener("dragleave", () => dropZone.classList.remove("dragover"));

                dropZone.addEventListener("drop", (e) => {
                    e.preventDefault();
                    dropZone.classList.remove("dragover");
                    addFiles(e.dataTransfer.files);
                });

                fileInput.addEventListener("change", () => addFiles(fileInput.files));

                function addFiles(files) {
                    Array.from(files).forEach(file => {
                        selectedFiles.push(file);

                        const item = document.createElement("div");
                        item.className = "file-item";

                        const info = document.createElement("div");
                        info.className = "file-info";

                        let preview;
                        if (file.type.startsWith("image/")) {
                            preview = document.createElement("img");
                            preview.className = "file-preview";
                            preview.src = URL.createObjectURL(file);
                        }else if (file.type === "application/pdf") {
                            preview = document.createElement("canvas");
                            preview.className = "file-preview";

                            const reader = new FileReader();
                            reader.onload = function() {
                                const typedarray = new Uint8Array(this.result);
                                pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                                    pdf.getPage(1).then(page => {
                                        const viewport = page.getViewport({ scale: 0.2 });
                                        const context = preview.getContext("2d");
                                        preview.height = viewport.height;
                                        preview.width = viewport.width;
                                        page.render({ canvasContext: context, viewport: viewport });
                                    });
                                });
                            };
                            reader.readAsArrayBuffer(file);
                        } else {
                            preview = document.createElement("img");
                            preview.className = "file-preview";
                            preview.src = "/icons/file-icon.png"; // icône générique
                        }

                        const label = document.createElement("span");
                        label.textContent = file.name;

                        const deleteBtn = document.createElement("i");
                        deleteBtn.className = "fa fa-trash delete-btn fs-5";
                        deleteBtn.title = "Supprimer le fichier";
                        deleteBtn.addEventListener("click", () => {
                            item.remove();
                            selectedFiles = selectedFiles.filter(f => f !== file);
                        });

                        info.appendChild(preview);
                        info.appendChild(label);

                        item.appendChild(info);
                        item.appendChild(deleteBtn);
                        fileList.appendChild(item);
                    });
                }

            // Envoi groupé
                sendBtn.addEventListener("click", () => {
                    if (selectedFiles.length === 0) {
                        showToast("Aucun fichier sélectionné !", {
                            type:  'warning',
                            title: 'Enregistrement Proforma',
                            duration: 5000,
                            position: "bottom-right"
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append("client", document.getElementById("client").value);
                    formData.append("proforma",  document.getElementById("proforma").value);
                    formData.append("dateCommande",  document.getElementById("dateCommande").value);

                    selectedFiles.forEach(file => {
                        formData.append("files[]", file);
                    });

                    lignes = [];
                    $("#tblProforma tbody tr").each(function () {

                        lignes.push({

                            produit: $(this).find(".produit").val(),
                            quantite: $(this).find(".qty").val(),
                            prixUnitaire: $(this).find(".prixUnitaire").val(),
                            total: $(this).find(".total").val()
                        });
                    });
                    let array_lignes = JSON.stringify(lignes);

                    formData.append("date_proforma", dateProforma.value)
                    formData.append("lignes", array_lignes)
                    formData.append("total_ht", total_ht.value)
                    formData.append("tva", tva.value)
                    formData.append("total_ttc", total_ttc.value)
                    formData.append("remise", remise.value)
                    formData.append("taux_remise", taux_remise.value)
                    formData.append("validiteProforma", validiteProforma.value)
                    formData.append("en_lettre", en_lettre)



                    saveProforma(formData, 1, document.getElementById("mes_proformas"))

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "/saveCommande", true);
                    xhr.responseType = "json"; // très important
                    xhr.onload = () => {
                        if (xhr.status === 200) {
                            const data = xhr.response;
                            showToast("✅Commande N° '+data.nc+' enregistrée avec succès !", {
                                type:  'success',
                                title: 'Enregistrement Bon de commande Client',
                                duration: 5000,
                                position: "bottom-right"
                            });
                            document.querySelector(".lbl_commande").innerHTML = 'Enregistrement de la commande N° <span class="fw-bold text-danger">'+ data.nc +'</span>'
                            fileList.innerHTML = "";
                            selectedFiles = [];
                            goToStep(1)
                            resetForm(document.getElementById("step1Commande"))
                            document.getElementById("div_infos_client").innerHTML = ""
                            document.getElementById("div_proforma").innerHTML = ""
                            document.getElementById("proforma").innerHTML = ""
                        } else {
                            showToast("❌ Erreur lors de l'envoi !", {title: 'Enregistrement de BC Client', duration: 4000, type: 'error'});
                        }
                    };

                    xhr.send(formData);
                });

            }

            function goToStep(step) {
                let client= document.getElementById("client")
                let proforma= document.getElementById("proforma")

                if (
                    client.value === "0" || client.value === null ||
                    proforma.value === "0" || proforma.value === null
                ) {
                        showToast("Merci de sélectionner le client et  la proforma !",{
                            type : 'warning',
                            duration : 4000,
                            closable: true
                        })

                    }  else {
                    // Masquer toutes les étapes
                    document.getElementById("step1").style.display = "none";
                    document.getElementById("step2").style.display = "none";
                    document.getElementById("step3").style.display = "none";
                    /*    document.getElementById("step3").style.display = "none";*/

                    // Afficher l'étape choisie
                    document.getElementById("step" + step).style.display = "block";

                    // Mettre à jour la barre de progression
                    const progressBar = document.getElementById("progressBar");
                    if (step === 1) {
                        progressBar.style.width = "33%";
                        progressBar.textContent = "Étape 1/3";
                        progressBar.className = "progress-bar bg-warning";
                    } else if (step === 2) {
                        progressBar.style.width = "66%";
                        progressBar.textContent = "Étape 2/3";
                        progressBar.className = "progress-bar bg-info";
                    } else if (step === 3) {
                        progressBar.style.width = "100%";
                        progressBar.textContent = "Étape 3/3";
                        progressBar.className = "progress-bar bg-success";
                    }
                }
            }

            function getSingleCommande(id_commande) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '/getSingleCommande/' + id_commande,
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
function getOnlyProduitsFromProforma(value, ctrl_name){ // Si value_ctrl =1 alors le contrôle est une table sinon un dropdown. ctrl_name est le nom du controle
    let contenu_produits = ''
    $.ajax({
        url : '/getOnlyProduitsFromProforma/' + value,
        type : 'POST',
        success: function(response){
            let listeProduits = JSON.parse(response);
            if (listeProduits.code === "success"){
                contenu_produits +='Liste des produits'
                contenu_produits +='<option value="0">Liste des produits</option>'
                for (var i=0; i < listeProduits.data.length ; i++){
                    contenu_produits +='<option value="'+ listeProduits.data[i].id + '">' + listeProduits.data[i].libelle_produit  + ' (' + listeProduits.data[i].code_produit  + ')</option>'
                }
            } else {
                showToast(listeProduits.msg,{title: 'Produits', type: listeProduits.code, duration: 3000})
            }
            ctrl_name.innerHTML = contenu_produits;
        }
    })
}

function getTarifFromProforma(produit, id_proforma, ctrl){
    $.ajax({
        url: '/getTarifProduitFromProforma/' + produit + '/' + id_proforma,
        type: 'POST',
        success: function(response) {
            if (response.code === 'success') {
                ctrl.value = response.tarif
            }
        }
    });
}
