
function dataTableSnvlt(name, title, taille){
    let table = new DataTable(name, {
        responsive: true,
        pageLength: taille,
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: title,
                        text: 'Exporter en excel'

                    }
                ]
            }
        },
        colReorder: true,
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }
    });
}

function dataTableSnvltNoButton(name, taille){
    let table = new DataTable(name, {
        responsive: true,
        pageLength: taille,
        colReorder: true,
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        }
    });
}
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);

        csv.push(row.join(";"));
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}

function json_to_csv(data, filename)
{


// Generate CSV string
    const headers = Object.keys(data[0]).join(';');
    const rows = data.map(obj => Object.values(obj).join(';')).join('\n');
    const csvContent = headers + '\n' + rows;

// Create a Blob and trigger download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);

}
function render_toast_value(valeur, type_valeur){
    let couleur = '#d95555'; // Erreur
    let couleur_texte = 'white'; // Erreur
    let icon = 'error';
    let iconColor = "white"
    valeur ='<span class="text-black" style="font-size: 16px;">' + valeur + '</span>'
    if(type_valeur === 1){ couleur_texte ='#2a7904'; couleur = '#cdfcb6'; icon = 'success';iconColor = '#2a7904'} // Succès
    else if (type_valeur === 2){couleur_texte ='#051000'; couleur = '#fada8c'; icon = 'warning'; iconColor = "#a87803"} // Avertissement
    else if (type_valeur === 3){couleur_texte ='#051000'; couleur = '#85dbc6'; icon = 'success'; iconColor = "#257d64"} // Infos
    Swal.fire({
        toast: true,
        background: couleur,
        color: couleur_texte,
        position: "top-end",
        modal : true,
        timer : 3000,
        icon: icon,
        iconColor: iconColor,
        title: valeur,
        showConfirmButton: false,
        fontWeight : "light"
    });
}
function getNbJours(date1, date2){

    const differenceInMilliseconds = date2.getTime() - date1.getTime(); // Conversion en millisecondes
    const differenceInDays = differenceInMilliseconds /  (1000 * 60 * 60 * 24); // Conversion en jours
        return differenceInDays;
}
function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);

        csv.push(row.join(";"));
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}
function json_to_csv(data, filename)
{


// Generate CSV string
    const headers = Object.keys(data[0]).join(';');
    const rows = data.map(obj => Object.values(obj).join(';')).join('\n');
    const csvContent = headers + '\n' + rows;

// Create a Blob and trigger download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename + '.csv';
    link.click();
    URL.revokeObjectURL(link.href);
    //
    // var data = document.getElementById(element_id);
    //
    // var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    //
    // XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    //
    // XLSX.writeFile(file, filename+'.' + type);
}
function exportJsonToExcel(jsonData, name) {
    // Create a new workbook
    const workbook = XLSX.utils.book_new();

    // Convert JSON data to a worksheet
    const worksheet = XLSX.utils.json_to_sheet(jsonData);

    // Append the worksheet to the workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, "données");

    // Export the workbook as an Excel file
    XLSX.writeFile(workbook, name + ".xlsx");
}
function resetForm(form) {
    form.reset();
}
function applyPhoneMask(input) {
    input.addEventListener("input", function(e) {
        // Supprimer tout sauf les chiffres
        let value = e.target.value.replace(/\D/g, "");

        // Limiter à 10 chiffres
        value = value.substring(0, 10);

        // Regrouper par 2 chiffres
        let formatted = value.match(/.{1,2}/g)?.join(" ") || "";

        // Mettre à jour le champ
        e.target.value = formatted;
    });
}
function applyInputHighlight(selector) {
    const elements = document.querySelectorAll(selector);

    function updateHighlight(el) {
        if (el.value.trim() !== "") {
            el.style.backgroundColor = "#ccffcc"; // vert clair adorable
        } else {
            el.style.backgroundColor = "";
        }
    }

    // Sur saisie utilisateur
    elements.forEach(el => {
        el.addEventListener("input", () => updateHighlight(el));
    });

    // Sur changement programmatique (ex: el.value = "...")
    const observer = new MutationObserver(() => {
        elements.forEach(el => updateHighlight(el));
    });

    elements.forEach(el => {
        observer.observe(el, { attributes: true, attributeFilter: ["value"] });
    });

    // Vérification initiale
    elements.forEach(el => updateHighlight(el));
}
function getDateJour(control){
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    control.value = `${yyyy}-${mm}-${dd}`;
}
function numStr(number, separator) {
    number = '' + number;
    separator = separator || ' ';
    var c = '',
        d = 0;
    while (number.match(/^0[0-9]/)) {
        number = number.substr(1);
    }
    for (var i = number.length-1; i >= 0; i--) {
        c = (d != 0 && d % 3 == 0) ? number[i] + separator + c : number[i] + c;
        d++;
    }
    return c ;
}
function enLettres(nombre) {
    let chiffres = [
        "zéro", "un", "deux", "trois", "quatre",
        "cinq", "six", "sept", "huit", "neuf"
    ];
    return nombre
        .toString()
        .split('')
        .map(c => chiffres[parseInt(c)])
        .join(' ');
}
function nombreEnLettres(n) {
    const unites = [
        "", "un", "deux", "trois", "quatre", "cinq",
        "six", "sept", "huit", "neuf", "dix", "onze",
        "douze", "treize", "quatorze", "quinze", "seize"
    ];

    const dizaines = [
        "", "", "vingt", "trente", "quarante",
        "cinquante", "soixante"
    ];

    function convertirMoinsDe1000(nb) {
        let resultat = "";

        const centaines = Math.floor(nb / 100);
        let reste = nb % 100;

        if (centaines > 0) {
            if (centaines === 1) {
                resultat += "cent ";
            } else {
                resultat += unites[centaines] + " cent ";
            }
        }

        if (reste < 17) {
            resultat += unites[reste];
        } else if (reste < 20) {
            resultat += "dix-" + unites[reste - 10];
        } else if (reste < 70) {
            const dizaine = Math.floor(reste / 10);
            const unite = reste % 10;

            resultat += dizaines[dizaine];

            if (unite === 1) {
                resultat += "-et-un";
            } else if (unite > 0) {
                resultat += "-" + unites[unite];
            }
        } else if (reste < 80) {
            resultat += "soixante-" + convertirMoinsDe1000(reste - 60);
        } else {
            resultat += "quatre-vingt";
            if (reste > 80) {
                resultat += "-" + convertirMoinsDe1000(reste - 80);
            }
        }

        return resultat.trim();
    }

    if (n === 0) return "zéro";

    const millions = Math.floor(n / 1000000);
    const milliers = Math.floor((n % 1000000) / 1000);
    const reste = n % 1000;

    let resultat = "";

    if (millions > 0) {
        resultat += millions === 1
            ? "un million "
            : convertirMoinsDe1000(millions) + " millions ";
    }

    if (milliers > 0) {
        resultat += milliers === 1
            ? "mille "
            : convertirMoinsDe1000(milliers) + " mille ";
    }

    if (reste > 0) {
        resultat += convertirMoinsDe1000(reste);
    }

    return resultat.trim();
}
function estEmail(valeur) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(valeur);
}
function activerCouleurSurClic(table, color) {
    // Sélectionner toutes les lignes de la table
    table.querySelectorAll("tr").forEach(function(row) {
        row.addEventListener("click", function() {
            // Réinitialiser toutes les lignes
            table.querySelectorAll("tr").forEach(r => r.style.backgroundColor = "");
            // Appliquer la couleur à la ligne cliquée
            this.style.backgroundColor = color;
            /*this.style.color = textColor;*/
        });
    });
}
function RechercherEtPaginerTable(table, rowsPerPage, pagination, searchInput) {
    const rows = Array.from(table.querySelectorAll("tbody tr"));
   /* const pagination = document.getElementById("pagination");*/
    let currentPage = 1;
    let filteredRows = rows;

    function afficherPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.forEach(r => r.style.display = "none");
        filteredRows.slice(start, end).forEach(r => r.style.display = "");
        [...pagination.children].forEach(btn => btn.classList.remove("active"));
        if (pagination.children[page - 1]) {
            pagination.children[page - 1].classList.add("active");
        }
    }

    function construirePagination() {
        pagination.innerHTML = "";
        const pageCount = Math.ceil(filteredRows.length / rowsPerPage);
        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.addEventListener("click", () => {
                currentPage = i;
                afficherPage(currentPage);
            });
            pagination.appendChild(btn);
        }
    }

    // Recherche dynamique
    searchInput.addEventListener("input", function() {
        const query = this.value.toLowerCase();
        filteredRows = rows.filter(r => r.textContent.toLowerCase().includes(query));
        currentPage = 1;
        construirePagination();
        afficherPage(currentPage);
    });

    // Initialisation
    construirePagination();
    afficherPage(currentPage);
}
function keypress_input(control_text, control_button){
    control_text.addEventListener('keypress', function (evt){
        if (evt.key === "Enter"){
            evt.preventDefault()
            control_button.click()
        }
    })
}

function getTaux(ctrl){
    let contenu = ''
    for (var i=0; i<71 ;i++){
        contenu +='<option value="' + i + '">' + i + '</option>'
    }
    ctrl.innerHTML = contenu
}
